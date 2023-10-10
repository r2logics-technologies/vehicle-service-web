<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scanner extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function scopeAllowed($query)
    {
     return $query->where('status', '!=' ,'deleted');
    }
}
