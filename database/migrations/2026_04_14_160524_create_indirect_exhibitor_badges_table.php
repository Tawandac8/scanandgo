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
        Schema::create('indirect_exhibitor_badges', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->foreignId('badge_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('indirect_exhibitor_id')->constrained()->onDelete('cascade');
            $table->string('batch_number');
            $table->boolean('is_printed')->default(0);
            $table->integer('printed_count')->default(0);
            $table->timestamp('printed_at')->nullable();
            $table->string('printed_by')->nullable();
            $table->string('serial_number')->nullable();
            $table->boolean('printed_in_bulawayo')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indirect_exhibitor_badges');
    }
};
