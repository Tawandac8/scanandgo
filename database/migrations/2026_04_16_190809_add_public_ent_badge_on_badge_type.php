<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\BadgeType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        BadgeType::create([
            'name' => 'Public Entertainment'
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
