<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CaseStatus;

class CaseStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            [
                'name' => 'New',
                'color' => '#3b82f6', 
                'is_default' => true, 
                'status' => true,
            ],
            [
                'name' => 'Under Review',
                'color' => '#f59e0b', 
                'is_default' => false,
                'status' => true,
            ],
            [
                'name' => 'In Progress',
                'color' => '#10b981',
                'is_default' => false,
                'status' => true,
            ],
            [
                'name' => 'Pending Hearing',
                'color' => '#8b5cf6', 
                'is_default' => false,
                'status' => true,
            ],
            [
                'name' => 'Resolved / Closed',
                'color' => '#6b7280', 
                'is_default' => false,
                'status' => true,
            ],
            [
                'name' => 'Archived',
                'color' => '#9ca3af', 
                'is_default' => false,
                'status' => false,
            ],
        ];

        foreach ($statuses as $status) {
            CaseStatus::updateOrCreate(
                ['name' => $status['name']],
                $status
            );
        }

        $this->command->info('6 Case Statuses seeded successfully!');
    }
}