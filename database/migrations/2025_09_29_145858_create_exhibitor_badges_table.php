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
        Schema::create('exhibitor_badges', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('exhibitor_id');
            $table->string('name');
            $table->unsignedBigInteger('badge_type_id');
            $table->boolean('is_printed')->default(false);
            $table->integer('printed_copies')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exhibitor_badges');
    }
};
