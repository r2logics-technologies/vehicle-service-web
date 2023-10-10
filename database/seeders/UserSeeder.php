<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::where('email', 'admin@admin.com')->where('user_type', 'admin')->first();
        if (!$admin) {
            User::create([
                'name' => 'Administrator',
                'mobile' => '9123456789',
                'email' => 'admin@admin.com',
                'country_code' => '+91',
                'avatar' => null,
                'password' => Hash::make('Admin@12345'),
                'user_type' => 'admin',
                'status' => 'activated',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }
    }
}
