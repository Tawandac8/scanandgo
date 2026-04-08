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
        $badge_types = [
            'BAS Attendant', 'Local Exhibitor'
            ];

        foreach($badge_types as $badge_type){
            BadgeType::create([
                'name' => $badge_type,
            ]);
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
