<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'company_code',
        'name',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'country',
        'pincode',
        'gst_number',
        'website',
        'logo',
        'status',
        'contact_person',
        'contact_person_phone',
        'api_key',
    ];

    protected $casts = [
        'status' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($company) {
            if (empty($company->company_code)) {
                $company->company_code = self::generateUniqueCompanyCode();
            }
            if (empty($company->company_id)) {
                $company->company_id = self::generateUniqueCompanyId();
            }
            if (empty($company->api_key)) {
                $company->api_key = self::generateApiKey();
            }
        });
    }

    private static function generateUniqueCompanyCode(): string
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        do {
            $companyCode = strtoupper(substr(str_shuffle($chars), 0, 8));
        } while (static::where('company_code', $companyCode)->exists());

        return $companyCode;
    }

    private static function generateUniqueCompanyId(): string
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        do {
            $companyId = strtoupper(substr(str_shuffle($chars), 0, 8));
        } while (static::where('company_id', $companyId)->exists());

        return $companyId;
    }

    public static function generateApiKey(): string
    {
        return hash('sha256', uniqid('dk_live_', true) . random_bytes(32));
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'company_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function scopeSearch($query, $term)
    {
        if (empty($term)) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
                ->orWhere('email', 'like', "%{$term}%")
                ->orWhere('phone', 'like', "%{$term}%")
                ->orWhere('company_code', 'like', "%{$term}%")
                ->orWhere('company_id', 'like', "%{$term}%")
                ->orWhere('gst_number', 'like', "%{$term}%")
                ->orWhere('contact_person', 'like', "%{$term}%");
        });
    }

    public function getLogoUrlAttribute(): ?string
    {
        if ($this->logo && Storage::disk('public')->exists($this->logo)) {
            return Storage::url($this->logo);
        }
        return null;
    }

    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->state,
            $this->country,
            $this->pincode,
        ]);
        return implode(', ', $parts);
    }
}
