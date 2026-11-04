<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\CaseModel;
use App\Models\User;
use Carbon\Carbon;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $cases = CaseModel::all();
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->error('⚠️ Please seed Users first!');
            return;
        }

        $tasks = [
            [
                'title' => 'Prepare bail application documents',
                'description' => 'Draft bail application for the accused. Include all supporting documents and case references.',
                'priority' => 'high',
                'status' => 'in_progress',
                'deadline' => Carbon::now()->addDays(2)->toDateString(),
                'is_active' => true,
            ],
            [
                'title' => 'Research case law on property disputes',
                'description' => 'Find relevant Supreme Court judgments related to land boundary disputes in Kathmandu.',
                'priority' => 'medium',
                'status' => 'to_do',
                'deadline' => Carbon::now()->addDays(5)->toDateString(),
                'is_active' => true,
            ],
            [
                'title' => 'Client meeting - Everest Technology',
                'description' => 'Discuss employment claim strategy and gather additional evidence from the client.',
                'priority' => 'high',
                'status' => 'to_do',
                'deadline' => Carbon::now()->addDays(1)->toDateString(),
                'is_active' => true,
            ],
            [
                'title' => 'File court response for contract breach',
                'description' => 'Submit formal response to the plaintiff\'s complaint regarding Himalayan Traders case.',
                'priority' => 'high',
                'status' => 'under_review',
                'deadline' => Carbon::now()->subDays(1)->toDateString(), // OVERDUE for testing
                'is_active' => true,
            ],
            [
                'title' => 'Review custody settlement agreement',
                'description' => 'Review the draft settlement agreement prepared by the opposing counsel.',
                'priority' => 'medium',
                'status' => 'done',
                'deadline' => Carbon::now()->subDays(3)->toDateString(),
                'is_active' => true,
            ],
            [
                'title' => 'Prepare invoice for ABC Construction',
                'description' => 'Generate monthly invoice for legal services rendered in labor rights case.',
                'priority' => 'low',
                'status' => 'to_do',
                'deadline' => Carbon::now()->addDays(7)->toDateString(),
                'is_active' => true,
            ],
            [
                'title' => 'Update case file documentation',
                'description' => 'Organize and digitize all physical documents for the Karki vs Municipality case.',
                'priority' => 'low',
                'status' => 'in_progress',
                'deadline' => Carbon::now()->addDays(10)->toDateString(),
                'is_active' => true,
            ],
            [
                'title' => 'Send legal notice to opposing party',
                'description' => 'Draft and send formal legal notice before proceeding with litigation.',
                'priority' => 'medium',
                'status' => 'cancelled',
                'deadline' => Carbon::now()->addDays(3)->toDateString(),
                'is_active' => false,
            ],
        ];

        foreach ($tasks as $index => $taskData) {
            $case = $cases->isNotEmpty() ? $cases[$index % $cases->count()] : null;
            $user = $users[$index % $users->count()];
            $creator = $users->random();
            Task::updateOrCreate(
                ['title' => $taskData['title']],
                array_merge($taskData, [
                    'case_id' => $case?->id,
                    'assigned_to' => $user->id,
                    'created_by' => $creator->id,
                ])
            );
        }

        $this->command->info('✅ 8 Tasks seeded successfully!');
    }
}