<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pincode extends Model
{
    use HasFactory;
    protected $table = 'pincode';
    
    protected $fillable =[
        'state',
        'district',
        'city',
        'taluk',
        'pincode',
        'usedBy'
    ];

    /**
     * Pincode IDs already assigned to an active, unblocked subscriber.
     */
    public static function unavailableForNewSubscriberIds(?int $exceptSubscriberId = null): array
    {
        return Subscriber::query()
            ->where('activestatus', 1)
            ->where('blockedstatus', 1)
            ->whereNotNull('pincode')
            ->when($exceptSubscriberId, fn ($query) => $query->whereKeyNot($exceptSubscriberId))
            ->pluck('pincode')
            ->flatMap(function ($pincodes) {
                $decoded = json_decode((string) $pincodes, true);

                return is_array($decoded) ? $decoded : [];
            })
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    public function scopeAvailableForNewSubscriber(Builder $query): Builder
    {
        return $query->whereNotIn('id', static::unavailableForNewSubscriberIds());
    }

    /**
     * Keep a subscriber's current pincodes visible while excluding pincodes
     * owned by every other active, unblocked subscriber.
     */
    public function scopeAvailableForSubscriber(Builder $query, Subscriber $subscriber): Builder
    {
        $currentPincodeIds = collect(json_decode((string) $subscriber->pincode, true))
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->all();

        $unavailableIds = array_diff(
            static::unavailableForNewSubscriberIds($subscriber->id),
            $currentPincodeIds
        );

        return $query->whereNotIn('id', $unavailableIds);
    }

    public function booking(): HasMany
    {
        return $this->hasMany(Booking::class, 'pincode', 'pincode');
    }

    public function subscriber(): HasMany
    {
        return $this->hasMany(Subscriber::class,'pincode','[pincode]');
    }

    public function pincodebasedcategory(): HasMany
    {
        return $this->hasMany(Pincodebasedcategory::class);
    }
}

