<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionRenewal extends Model
{
    protected $table = 'subscription_renewals';

    protected $guarded = [];

    protected $casts = [
        'due_date' => 'date:Y-m-d',
        'payment_date' => 'date:Y-m-d',
        'subscription_price' => 'decimal:2',
        'subscription_gst' => 'decimal:2',
        'penalty_principal' => 'decimal:2',
        'penalty_gst' => 'decimal:2',
        'total_payable' => 'decimal:2',
        'paid_at' => 'datetime',
        'before_due_notified_at' => 'datetime',
        'due_notified_at' => 'datetime',
        'deactivated_notified_at' => 'datetime',
    ];

    public function subscriber()
    {
        return $this->belongsTo(Subscriber::class, 'subscriber_id');
    }
}
