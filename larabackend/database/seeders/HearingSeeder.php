<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hearing;
use App\Models\CaseModel;
use Carbon\Carbon;

class HearingSeeder extends Seeder
{
    public function run(): void
    {
        $cases = CaseModel::all();

        if ($cases->isEmpty()) {
            $this->command->error('⚠️ Please seed Cases first before seeding Hearings!');
            return;
        }

        $hearings = [
            [
                'hearing_date' => Carbon::now()->subDays(15)->toDateString(),
                'hearing_time' => '10:00:00',
                'court_name' => 'Kathmandu District Court',
                'judge_name' => 'Hon. Ram Bahadur Thapa',
                'hearing_type' => 'Bail',
                'purpose' => 'Bail application hearing for the accused.',
                'outcome' => 'Bail granted with conditions.',
                'next_hearing_date' => Carbon::now()->addDays(10)->toDateString(),
                'status' => 'completed',
                'is_active' => true,
            ],
            [
                'hearing_date' => Carbon::now()->addDays(5)->toDateString(),
                'hearing_time' => '11:30:00',
                'court_name' => 'Patan High Court',
                'judge_name' => 'Hon. Sita Sharma',
                'hearing_type' => 'Evidence',
                'purpose' => 'Recording of witness statements.',
                'outcome' => null,
                'next_hearing_date' => null,
                'status' => 'scheduled',
                'is_active' => true,
            ],
            [
                'hearing_date' => Carbon::now()->subDays(30)->toDateString(),
                'hearing_time' => '09:00:00',
                'court_name' => 'Supreme Court of Nepal',
                'judge_name' => 'Hon. Chief Justice',
                'hearing_type' => 'Final Hearing',
                'purpose' => 'Final arguments on the constitutional writ.',
                'outcome' => 'Order reserved. Adjourned for final verdict.',
                'next_hearing_date' => Carbon::now()->addDays(20)->toDateString(),
                'status' => 'adjourned',
                'is_active' => true,
            ],
            [
                'hearing_date' => Carbon::now()->addDays(12)->toDateString(),
                'hearing_time' => '14:00:00',
                'court_name' => 'Kathmandu District Court',
                'judge_name' => 'Hon. Hari Prasad',
                'hearing_type' => 'Regular',
                'purpose' => 'Case management and scheduling.',
                'outcome' => null,
                'next_hearing_date' => null,
                'status' => 'scheduled',
                'is_active' => true,
            ],
            [
                'hearing_date' => Carbon::now()->subDays(5)->toDateString(),
                'hearing_time' => '10:30:00',
                'court_name' => 'Chitwan District Court',
                'judge_name' => 'Hon. Anita Gurung',
                'hearing_type' => 'Regular',
                'purpose' => 'Submission of documentary evidence.',
                'outcome' => 'Plaintiff failed to appear. Case dismissed for default.',
                'next_hearing_date' => null,
                'status' => 'completed',
                'is_active' => true,
            ],
            [
                'hearing_date' => Carbon::now()->addDays(25)->toDateString(),
                'hearing_time' => '11:00:00',
                'court_name' => 'Pokhara High Court',
                'judge_name' => 'Hon. Bikash Karki',
                'hearing_type' => 'Miscellaneous',
                'purpose' => 'Application for extension of time.',
                'outcome' => null,
                'next_hearing_date' => null,
                'status' => 'scheduled',
                'is_active' => true,
            ],
        ];

        foreach ($hearings as $index => $hearingData) {
            $case = $cases[$index % $cases->count()];
            Hearing::updateOrCreate(
                [
                    'case_id' => $case->id,
                    'hearing_date' => $hearingData['hearing_date'],
                    'court_name' => $hearingData['court_name'],
                ],
                array_merge($hearingData, ['case_id' => $case->id])
            );
        }

        $this->command->info('✅ 6 Hearings seeded successfully!');
    }
}