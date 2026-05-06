<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Traits\HasRoles;

class Subscriber extends Authenticatable
{
    use HasFactory, HasRoles;

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
        return $admin ? $admin->emp_id : "Unknown";
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
    protected $table = 'subscriber';
    protected $guard = [];
    protected $fillable = [
        'notify',
        'platform_fee',
        'need_to_pay',
        // Service base prices
        'biketaxi_price',
        'pickup_price',
        'buy_price',
        'auto_price',
        'cab_price',
        // Bike Taxi distance prices
        'bt_price1',
        'bt_price2',
        'bt_price3',
        'bt_price4',
        // Pickup distance prices
        'pk_price1',
        'pk_price2',
        'pk_price3',
        'pk_price4',
        // Buy & Delivery distance prices
        'bd_price1',
        'bd_price2',
        'bd_price3',
        'bd_price4',
        // Auto distance prices
        'at_price1',
        'at_price2',
        'at_price3',
        'at_price4',
        // Cab distance prices
        'cab_price1',
        'cab_price2',
        'cab_price3',
        'cab_price4'
    ];
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
