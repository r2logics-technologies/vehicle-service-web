<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceCenter extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'logo', 'name', 'email', 'mobile', 'owner_name', "alter_email", "alter_mobile", "delivery_type", "recommend", 'sportlight', 'popular',
        'featured', 'password', 'description', 'address_line_1', 'address_line_2', 'location', 'latitude', 'longitude', 'landmark', 'city', 'state', 'country', 'postcode',
        'activity_status', 'change_activity_status', 'status', 'detail',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'activated');
    }

    public function scopeAllowed($query)
    {
        return $query->where('status', '!=', 'deleted');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'deactivated');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_service_center', 'service_center_id', 'category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function scopeIsActive($query)
    {
        return $query->where('status', 'activated')->where('activity_status', 'online');
    }
}
