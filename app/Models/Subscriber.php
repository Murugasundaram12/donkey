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
     * Backward compatibility accessor/mutator for legacy subscription_price field
     */
    public function getSubscriptionPriceAttribute()
    {
        return $this->attributes['platform_fee'] ?? $this->getAttributeFromArray('platform_fee') ?? '0';
    }

    public function setSubscriptionPriceAttribute($value)
    {
        $this->attributes['platform_fee'] = $value;
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
