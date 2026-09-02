<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pushnotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'subscriber_id',
        'category',
        'title',
        'content',
        'image',
        'read_at',
        'data',
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'data' => 'array',
    ];
}
