<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingLocation extends Model
{
    use HasFactory;
    protected $table = 'booking_locations';

    protected $fillable = [
        'booking_id',
        'location_id',
        'type',
        'address1',
        'address2',
        'address3',
        'city',
        'state',
        'country',
        'postal_code',
        'lat',
        'long',
        'landmark'
    ];
}
