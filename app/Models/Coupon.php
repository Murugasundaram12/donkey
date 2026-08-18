<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;
use App\Models\Subscriber;
use App\Models\Pincode;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    use HasFactory;

    protected $table = 'coupons';

    protected $fillable = [
        "title",
        "image",
        "type",
        "is_multiple",
        "pincode_id",
        "code",
        "limit",
        "start_date",
        "expiry_date",
        "discount_type",
        "amount",
        "percentage",
        "status",
        "created_by",
        "role"
    ];

    protected $dates = [
        'start_date',
        'expiry_date',
    ];

    public function getStartDateAttribute($value)
    {
        $raw = filled($value) ? $value : ($this->attributes['valid_from'] ?? null);
        return filled($raw) ? Carbon::parse($raw) : null;
    }

    public function getExpiryDateAttribute($value)
    {
        $raw = filled($value) ? $value : ($this->attributes['valid_to'] ?? null);
        return filled($raw) ? Carbon::parse($raw) : null;
    }

    public function getTitleAttribute($value)
    {
        return filled($value) ? $value : ($this->attributes['name'] ?? null);
    }

    public function getLimitAttribute($value)
    {
        return filled($value) ? $value : ($this->attributes['user_limit'] ?? 0);
    }

    public function pincode(): BelongsTo
    {
        return $this->belongsTo(Pincode::class);
    }

    public function usedcoupon(): HasMany
    {
        return $this->hasMany(Usedcoupon::class);
    }
}
