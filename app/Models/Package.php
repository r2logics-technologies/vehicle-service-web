<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class   Package extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'duration', 'price', 'benefits', 'status', 'details','package_type'
    ];

    public function scopeAllowed($query)
    {
        return $query->where('status', '!=', 'deleted');
    }
    public function scopeActive($query)
    {
        return $query->where('status', '!=', 'deleted')->where('status', '!=', 'deactivated');
    }
 
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
