<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BadgeType;

class CreateBadgeTypes extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $badge_types = [
            'Visitor',
            'Exhibitor',
            'Exhibitor Comp',
            'Attendant',
            'Speaker',
            'Press',
            'Staff',
            'Delegate',
            'VIP',
            'Sponsor',
            'Judge',
            'Service Provider',
        ];

        foreach ($badge_types as $badge_type) {
            BadgeType::create([
                'name' => $badge_type,
            ]);
        }
    }
}
