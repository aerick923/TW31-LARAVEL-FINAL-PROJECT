<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MembershipLevel;

class MembershipLevelSeeder extends Seeder
{
    public function run(): void
    {
        $levels = [
            [
                'name' => 'Basic',
                'fee' => 500.00,
                'validity_months' => 3,
                'benefits' => 'Basic membership access',
            ],
            [
                'name' => 'Silver',
                'fee' => 1000.00,
                'validity_months' => 6,
                'benefits' => 'Basic access, 5% discount',
            ],
            [
                'name' => 'Gold',
                'fee' => 2000.00,
                'validity_months' => 12,
                'benefits' => 'Priority services, 10% discount',
            ],
            [
                'name' => 'Platinum',
                'fee' => 3500.00,
                'validity_months' => 24,
                'benefits' => 'VIP services, 15% discount',
            ],
        ];

        foreach ($levels as $level) {
            MembershipLevel::updateOrCreate(
                ['name' => $level['name']],
                [
                    'fee' => $level['fee'],
                    'validity_months' => $level['validity_months'],
                    'benefits' => $level['benefits'],
                ]
            );
        }
    }
}
