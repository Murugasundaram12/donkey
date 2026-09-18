<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

class Subscriber extends Authenticatable
{
    use HasFactory, HasRoles, HasApiTokens;

    /**
     * Created By accessor - "Self" if no admin created it
     */
    public function getCreatedByAttribute()
    {
        $createdBy = $this->getAttributeFromArray('created_by');
        if (empty($createdBy) || $createdBy == 0) {
            return "Self";
        }
        $admin = \App\Models\Admin::where('id', $createdBy)->first();
        return $admin ? $admin->emp_id : "Self";
    }

    /**
     * Joined Date accessor with fallback to created_at
     */
    public function getJoinedDateAttribute()
    {
        $joinedDate = $this->getAttributeFromArray('joined_date');
        if (!empty($joinedDate)) {
            return \Carbon\Carbon::parse($joinedDate)->format('d-m-Y');
        }

        return $this->created_at?->format('d-m-Y');
    }

    /**
     * Subscription price accessor/mutator strictly bound to subscriber.subscription_price column
     */
    public function getSubscriptionPriceAttribute()
    {
        return $this->attributes['subscription_price'] ?? $this->getAttributeFromArray('subscription_price') ?? null;
    }

    public function setSubscriptionPriceAttribute($value)
    {
        $this->attributes['subscription_price'] = $value;
    }

    /**
     * Backward compatibility accessor/mutator for legacy activestatus field
     */
    public function getActivestatusAttribute()
    {
        return $this->attributes['status'] ?? $this->getAttributeFromArray('status') ?? 1;
    }

    public function setActivestatusAttribute($value)
    {
        $this->attributes['status'] = $value;
    }

    /**
     * Check if a subscriber account is active based on blockedstatus and subscription expiry date.
     * Expiry date is the source of truth for expiration.
     */
    public static function isSubscriberActive($subscriber): bool
    {
        if (!$subscriber) {
            return false;
        }

        $blockedstatus = is_object($subscriber) ? ($subscriber->blockedstatus ?? 1) : 1;
        if ((int) $blockedstatus === 0) {
            return false;
        }

        $expiryDate = is_object($subscriber) ? ($subscriber->expiryDate ?? null) : null;
        if (!empty($expiryDate)) {
            try {
                $expiry = \Carbon\Carbon::parse($expiryDate)->endOfDay();
                return !$expiry->isPast();
            } catch (\Throwable $e) {
                // fallthrough to status check
            }
        }

        $status = is_object($subscriber) ? ($subscriber->status ?? $subscriber->activestatus ?? 1) : 1;
        return (int) $status === 1;
    }

    /**
     * Calculate standard subscription renewal pricing breakdown.
     */
    public static function calculateSubscriptionPricing($subscriber): array
    {
        $rawPrice = is_object($subscriber) ? ($subscriber->subscription_price ?? null) : null;
        $price = is_numeric($rawPrice) && (float) $rawPrice > 0 ? (float) $rawPrice : 2.0;

        $gstPercentage = 18;
        $gstAmount = round(($price * $gstPercentage) / 100, 2);
        $exactTotalPayable = round($price + $gstAmount, 2);
        $totalPayable = round($exactTotalPayable, 0, PHP_ROUND_HALF_UP);
        $totalPayableInPaise = (int) round($totalPayable * 100);

        return [
            'price' => $price,
            'gst_percentage' => $gstPercentage,
            'gst_amount' => $gstAmount,
            'exact_total_payable' => $exactTotalPayable,
            'total_payable' => $totalPayable,
            'total_payable_in_paise' => $totalPayableInPaise,
        ];
    }

    /**
     * Pincode attribute accessor with fallback to pincodebasedcategories table
     */
    public function getPincodeAttribute()
    {
        $value = $this->getAttributeFromArray('pincode');
        if (!empty($value) && $value !== 'null') {
            return $value;
        }

        $pincodeIds = \App\Models\Pincodebasedcategory::where('subscriber_id', $this->id)
            ->pluck('pincode_id')
            ->unique()
            ->values()
            ->toArray();

        return !empty($pincodeIds) ? json_encode($pincodeIds) : null;
    }
    protected $table = 'subscriber';
    protected $guarded = [];
    protected $dateFormat = 'Y-m-d';
    public function subUnblock(): BelongsTo
    {
        return $this->belongsTo(SubUnblock::class, 'id', 'unblockBy');
    }

    public function subBlock(): BelongsTo
    {
        return $this->belongsTo(SubBlock::class, 'id', 'blockedBy');
    }

    public function pincode(): BelongsTo
    {
        return $this->belongsTo(Pincode::class, 'pincode', 'pincode');
    }

    public function blocklist(): BelongsTo
    {
        return $this->belongsTo(Blocklist::class, 'id', 'blockedId');
    }

    public function driver(): HasMany
    {
        return $this->hasMany(Driver::class, 'subscriberId', 'id');
    }

    protected $casts = [
        'subscriptionDate' => 'datetime',
        'expiryDate' => 'datetime'
    ];
}
