<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class banner extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'image',
        'images',
        'link',
    ];

    public function getImageAttribute()
    {
        return $this->attributes['images'] ?? $this->attributes['image'] ?? null;
    }
}
