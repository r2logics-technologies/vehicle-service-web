<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankDetail extends Model
{
    use HasFactory;
    protected  $fillable = ['user_id','holder_name','account_number','confirm_account_number','name','branch','ifsc','upi','details',];
}
