<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceCentersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_centers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->text('logo')->nullable();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('mobile', 15)->nullable();
            $table->string('owner_name')->nullable();
            $table->string('alter_email')->nullable();
            $table->string('alter_mobile')->nullable();
            $table->enum('delivery_type', ['delivery', 'take-way', 'both'])->default('delivery');
            $table->tinyInteger('recommend')->default(1);
            $table->tinyInteger('sportlight')->default(1);
            $table->tinyInteger('popular')->default(1);
            $table->tinyInteger('featured')->default(1);
            $table->text('password')->default('Center@12345');
            $table->text('description')->nullable();
            $table->text('address_line_1')->nullable();
            $table->text('address_line_2')->nullable();
            $table->string('location')->nullable();
            $table->string('landmark')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('postcode')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->enum('activity_status', ['online', 'offline'])->default('online');
            $table->dateTime('change_activity_time')->nullable();
            $table->enum('status', ['pending', 'activated', 'deactivated', 'block', 'deleted'])->default('activated');
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
        Schema::dropIfExists('service_centers');
    }
}
