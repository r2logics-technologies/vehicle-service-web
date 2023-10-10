<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'title',
        'name',
        'mobile',
        'alternate_mobile',
        'address_line1',
        'address_line2',
        'locality',
        'landmark',
        'pincode',
        'city',
        'state',
        'country',
        'details',
        'status',
        'latitude',
        'longitude',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'activated');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
