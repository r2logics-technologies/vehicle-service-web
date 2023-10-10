<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile', 20);
            $table->string('country_code', 10)->default('+91');
            $table->text('avatar')->nullable();
            $table->enum('user_type', ['admin', 'employee', 'customer', 'super_admin', 'service_center', 'driver'])->default('customer');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->integer('reward_points')->nullable();
            $table->string('referral_id')->nullable();
            $table->string('reference_id')->nullable();
            $table->rememberToken();
            $table->text('fcm_topics')->nullable()->comment('Comma separated values');
            $table->enum('status', ['activated', 'deactivated', 'deleted'])->default('activated');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
