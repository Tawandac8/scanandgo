<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('badges', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            //events
            $table->bigInteger('event_id')->nullable();
            $table->bigInteger('sub_event_id')->nullable();
            //qr code
            $table->string('reg_code')->nullable();
            //badge information
            $table->string('title')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            //company information
            $table->string('company_name')->nullable();
            $table->string('position')->nullable();
            //contact information
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            //badge type
            $table->bigInteger('badge_type_id')->nullable();
            //badge image
            $table->string('profile')->nullable();
            $table->string('background')->nullable();
            //nationality
            $table->bigInteger('city_id')->nullable();
            $table->bigInteger('country_id')->nullable();
            //online registration
            $table->boolean('is_online_registration')->default(0);
            //printing
            $table->boolean('is_printed')->default(0);
            $table->bigInteger('printed_copies')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('badges');
    }
};
