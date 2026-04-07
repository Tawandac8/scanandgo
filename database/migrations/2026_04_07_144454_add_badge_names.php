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
        Schema::table('badge_types', function (Blueprint $table) {
            
        });

        $badges = ['Official','Guest','Attendant Complimentary','BAS Official',
                    'BAS Media','BAS Judge','BAS Life Member','BAS Annual Member','BAS Junior',
                    'ZSPA'];

        foreach($badges as $badge){
            BadgeType::create([
                'name' => $badge,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('badge_types', function (Blueprint $table) {
            //
        });
    }
};
