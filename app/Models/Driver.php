<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;
    protected  $fillable = ['user_id', 'avatar', 'name', 'email', 'mobile', 'password', 'license_front', 'license_back', 'aadhar_front', 'aadhar_back', 'address_line_1', 'address_line_2','location','landmark','postcode','city','state','country', 'activity_status', 'status', 'details',];

    public function scopeAllowed($query)
    {
        return $query->where('status', '!=', 'deleted');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
