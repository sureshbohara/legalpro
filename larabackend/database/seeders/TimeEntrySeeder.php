<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TimeEntry;
use App\Models\CaseModel;
use App\Models\User;
use Carbon\Carbon;

class TimeEntrySeeder extends Seeder {
    public function run(): void {
        $cases = CaseModel::all();
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->error('Please seed Users first!');
            return;
        }

        $entries = [
            [
                'description' => 'Drafted initial client consultation notes and case strategy',
                'duration_minutes' => 90,
                'billable_rate' => 2500,
                'is_billable' => true,
                'status' => 'approved',
                'work_date' => Carbon::now()->subDays(5)->toDateString(),
                'start_time' => '09:00:00',
                'end_time' => '10:30:00',
            ],
            [
                'description' => 'Reviewed property documents and prepared evidence bundle',
                'duration_minutes' => 120,
                'billable_rate' => 2500,
                'is_billable' => true,
                'status' => 'submitted',
                'work_date' => Carbon::now()->subDays(3)->toDateString(),
                'start_time' => '14:00:00',
                'end_time' => '16:00:00',
            ],
            [
                'description' => 'Team meeting to discuss case progress',
                'duration_minutes' => 60,
                'billable_rate' => 0,
                'is_billable' => false,
                'status' => 'approved',
                'work_date' => Carbon::now()->subDays(2)->toDateString(),
                'start_time' => '11:00:00',
                'end_time' => '12:00:00',
            ],
            [
                'description' => 'Court appearance and hearing representation',
                'duration_minutes' => 180,
                'billable_rate' => 3000,
                'is_billable' => true,
                'status' => 'invoiced',
                'work_date' => Carbon::now()->subDays(1)->toDateString(),
                'start_time' => '10:00:00',
                'end_time' => '13:00:00',
            ],
            [
                'description' => 'Research on recent Supreme Court judgments',
                'duration_minutes' => 150,
                'billable_rate' => 2000,
                'is_billable' => true,
                'status' => 'draft',
                'work_date' => Carbon::today()->toDateString(),
                'start_time' => '09:30:00',
                'end_time' => '12:00:00',
            ],
        ];

        foreach ($entries as $index => $entryData) {
            $case = $cases->isNotEmpty() ? $cases[$index % $cases->count()] : null;
            $user = $users[$index % $users->count()];
            
            $hours = $entryData['duration_minutes'] / 60;
            $entryData['total_amount'] = $hours * $entryData['billable_rate'];

            TimeEntry::updateOrCreate(
                ['description' => $entryData['description'], 'work_date' => $entryData['work_date']],
                array_merge($entryData, [
                    'case_id' => $case?->id,
                    'user_id' => $user->id,
                ])
            );
        }

        $this->command->info('✅ 5 Time Entries seeded successfully!');
    }
}