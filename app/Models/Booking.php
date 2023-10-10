<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function user_address()
    {
        return $this->belongsTo(UserAddress::class);
    }
    public function package()
    {
        return $this->belongsTo(Package::class);
    }
    public function center()
    {
        return $this->belongsTo(ServiceCenter::class, 'service_center_id', 'id');
    }
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }
    public function scopePicked($query)
    {
        return $query->where('status', 'picked');
    }
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
    public function scopeProccessed($query)
    {
        return $query->where('status', 'proccessed');
    }
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }
    public function scopeCash($query)
    {
        return $query->where('payment_method', 'cash');
    }
    public function scopeOnline($query)
    {
        return $query->where('payment_method', 'online');
    }
    public function scopeTwowheel($query)
    {
        return $query->where('vehicle_type', 'two_wheeller');
    }
    public function scopeFourwheel($query)
    {
        return $query->where('vehicle_type', 'four_wheeller');
    }

    public function scopeDropAtVendor($query)
    {
        return $query->where('status', 'dropped_at_vendor');
    }
    public function scopeAllowed($query)
    {
        return $query->where('status', '!=', 'deleted');
    }

    public function scopeOnlyAssignServiceCenter($query, $centerId)
    {
        return $query->where('status', 'picked')->where('status', '!=', 'dropped_at_vendor')->where('service_center_id', $centerId);
    }
       
    public function serviceReject()
    {
        return $this->hasMany(ServiceReject::class);
    }

    public function activePackage()
    {
        return $this->belongsTo(ActivePackage::class,'active_package_id');
    }
   
}
