<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CaseCategory;

class CaseCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Civil',
                'color' => '#3b82f6', 
                'description' => 'Civil disputes including contracts, property, and family matters.',
                'status' => true,
            ],
            [
                'name' => 'Criminal',
                'color' => '#ef4444', 
                'description' => 'Criminal defense and prosecution cases.',
                'status' => true,
            ],
            [
                'name' => 'Family',
                'color' => '#8b5cf6', 
                'description' => 'Divorce, child custody, alimony, and adoption cases.',
                'status' => true,
            ],
            [
                'name' => 'Corporate',
                'color' => '#10b981', 
                'description' => 'Business disputes, mergers, acquisitions, and compliance.',
                'status' => true,
            ],
            [
                'name' => 'Property / Real Estate',
                'color' => '#f59e0b', 
                'description' => 'Land disputes, property transfers, and tenant issues.',
                'status' => true,
            ],
            [
                'name' => 'Labor & Employment',
                'color' => '#6366f1', 
                'description' => 'Workplace disputes, wrongful termination, and labor rights.',
                'status' => true,
            ],
        ];

        foreach ($categories as $category) {
            CaseCategory::updateOrCreate(
                ['name' => $category['name']],
                $category
            );
        }

        $this->command->info('6 Case Categories seeded successfully!');
    }
}