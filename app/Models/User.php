<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'country_code',
        'mobile',
        'avatar',
        'user_type',
        'fcm_topics',
        'reward_points',
        'referral_id',
        'reference_id',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function scopeCustomers($query)
    {
        return $query->where('user_type', '=', 'customer');
    }

    public function scopeAllowed($query)
    {
        return $query->where('status', '!=', 'deleted');
    }
    public function scopeInactive($query)
    {
        return $query->where('status', '!=', 'activated');
    }

    public function scopeRole($query, $type)
    {
        return $query->where('user_type', $type);
    }

    public function deviceLogs()
    {
        return $this->hasMany(DeviceLog::class);
    }

    public function scopeMobileUser($query)
    {
        return $query->where('user_type', 'customer')->orWhere('user_type', 'service_center')->orWhere('user_type', 'driver');
    }

    public function scopeDriver($query)
    {
        return $query->where('user_type', 'driver');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'activated');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function scopeActiveCustomer($query)
    {
        return $query->where('user_type', 'customer')->where('status', 'activated');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function activePackages()
    {
        return $this->hasMany(ActivePackage::class);
    }
}
