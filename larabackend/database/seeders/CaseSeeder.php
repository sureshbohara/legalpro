<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CaseModel; 
use App\Models\Client;
use App\Models\CaseCategory;
use App\Models\CaseStatus;
use App\Models\User;
use Carbon\Carbon;

class CaseSeeder extends Seeder
{
    public function run(): void
    {
        $clients = Client::all();
        $categories = CaseCategory::all();
        $statuses = CaseStatus::all();
        $users = User::all(); 

        if ($clients->isEmpty() || $categories->isEmpty() || $statuses->isEmpty()) {
            $this->command->error('Please seed Clients, Case Categories, and Case Statuses first!');
            return;
        }

        $cases = [
            [
                'case_number' => 'CASE-2024-001',
                'title' => 'Sharma vs. Thapa Property Dispute',
                'description' => 'Dispute over land boundary and ownership in Kathmandu.',
                'opening_date' => Carbon::now()->subMonths(2)->toDateString(),
                'closing_date' => null,
                'status' => true,
            ],
            [
                'case_number' => 'CASE-2024-002',
                'title' => 'Everest Tech Employment Claim',
                'description' => 'Wrongful termination claim by former employee.',
                'opening_date' => Carbon::now()->subMonths(1)->toDateString(),
                'closing_date' => null,
                'status' => true,
            ],
            [
                'case_number' => 'CASE-2024-003',
                'title' => 'Himalayan Traders Contract Breach',
                'description' => 'Breach of supply contract regarding delivery delays.',
                'opening_date' => Carbon::now()->subDays(15)->toDateString(),
                'closing_date' => null,
                'status' => true,
            ],
            [
                'case_number' => 'CASE-2023-045',
                'title' => 'Gurung Family Custody Battle',
                'description' => 'Child custody and alimony settlement.',
                'opening_date' => Carbon::now()->subMonths(6)->toDateString(),
                'closing_date' => Carbon::now()->subDays(10)->toDateString(),
                'status' => true,
            ],
            [
                'case_number' => 'CASE-2024-004',
                'title' => 'ABC Construction Labor Rights',
                'description' => 'Unpaid wages and safety violation claims.',
                'opening_date' => Carbon::now()->subDays(5)->toDateString(),
                'closing_date' => null,
                'status' => true,
            ],
            [
                'case_number' => 'CASE-2024-005',
                'title' => 'Karki vs. Local Municipality',
                'description' => 'Tax assessment dispute and property valuation.',
                'opening_date' => Carbon::now()->subDays(2)->toDateString(),
                'closing_date' => null,
                'status' => true,
            ],
        ];

        foreach ($cases as $index => $caseData) {

            $client = $clients[$index % $clients->count()];
            $category = $categories[$index % $categories->count()];
            
            $statusName = $caseData['closing_date'] ? 'Resolved / Closed' : 'In Progress';
            $status = $statuses->firstWhere('name', $statusName) ?: $statuses->first();

            $lawyerId = $users->isNotEmpty() ? $users->random()->id : null;

            CaseModel::updateOrCreate(
                ['case_number' => $caseData['case_number']],
                array_merge($caseData, [
                    'client_id' => $client->id,
                    'category_id' => $category->id,
                    'status_id' => $status->id,
                    'assigned_lawyer_id' => $lawyerId,
                ])
            );
        }

        $this->command->info(count($cases) . ' Cases seeded successfully!');
    }
}