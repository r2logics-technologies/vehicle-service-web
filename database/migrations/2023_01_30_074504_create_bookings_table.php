<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('user_address_id');
            $table->unsignedBigInteger('package_id');
            $table->unsignedBigInteger('active_package_id')->nullable();
            $table->unsignedBigInteger('service_center_id')->nullable();
            $table->unsignedBigInteger('driver_id')->nullable();
            $table->string('booking_number')->unique();
            $table->dateTime('booking_date')->nullable();
            $table->date('booked_date')->nullable()->comment('user select service booking date');
            $table->string('booked_time', 12)->nullable()->comment('user select service booking time');
            $table->string('vehicle_name')->nullable()->comment('user vehicle name');
            $table->string('vehicle_number')->nullable();
            $table->enum('vehicle_type', ['two_wheeler', 'four_wheeler'])->nullable();
            $table->text('instructions')->nullable()->comment('user instructions');
            $table->dateTime('accepted_at')->nullable();
            $table->dateTime('rejected_at')->nullable();
            $table->dateTime('cancelled_at')->nullable();
            $table->dateTime('picked_at')->nullable();
            $table->dateTime('dropped_at_vendor_at')->nullable(); 
            $table->dateTime('proccessed_at')->nullable();
            $table->dateTime('service_completed_at')->nullable();
            $table->dateTime('picked_at_vendor_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->enum('payment_method', ['cash', 'online'])->default('cash');
            $table->json('payment_detail')->nullable();
            $table->enum('payment_status', ['paid', 'due'])->nullable();
            $table->string('review')->nullable();
            $table->string('rating')->nullable();
            $table->text('url')->nullable();
            $table->boolean('is_request_vehicle')->default(false);
            $table->enum('status', ['pending', 'accepted', 'picked', 'dropped_at_vendor', 'proccessed','service_completed', 'picked_at_vendor', 'completed', 'rejected', 'cancelled',])->default('pending');
            $table->json('detail')->nullable();
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
        Schema::dropIfExists('bookings');
    }
}
