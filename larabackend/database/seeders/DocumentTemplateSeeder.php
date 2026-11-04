<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DocumentTemplate;

class DocumentTemplateSeeder extends Seeder
{
    public function run(): void
    {
      
        DocumentTemplate::updateOrCreate(
            ['name' => 'Vakalatnama (English)'],
            [
                'category' => 'Court Filing',
                'description' => 'Standard Vakalatnama format for district court',
                'content' => "To the Honorable District Court, {{case_category}}.\n\nSubject: Regarding Vakalatnama.\n\nWith reference to the above subject, I hereby submit this Vakalatnama on behalf of the plaintiff, {{client_name}} (Address: {{client_address}}, {{client_city}}), in case number {{case_number}} (Case Title: {{case_title}}).\n\nApplicant / Advocate: _________________\nDate: {{opening_date}}",
                'is_active' => true,
                'created_by' => 1,
            ]
        );

   
        DocumentTemplate::updateOrCreate(
            ['name' => 'वकालतनामा (Nepali)'],
            [
                'category' => 'Court Filing',
                'description' => 'जिल्ला अदालतको लागि मानक वकालतनामा ढाँचा',
                'content' => "श्रीमान् जिल्ला अदालत, {{case_category}}।\n\nविषय: वकालतनामा सम्बन्धमा।\n\nउपरोक्त सम्बन्धमा वादी {{client_name}} (ठेगाना: {{client_address}}, {{client_city}}) को तर्फबाट मुद्दा नं. {{case_number}} (शीर्षक: {{case_title}}) मा वकालतनामा पेश गरेको छु।\n\nनिवेदक/वकिल: _________________\nमिति: {{opening_date}}",
                'is_active' => true,
                'created_by' => 1,
            ]
        );

        $this->command->info('Templates seeded successfully!');
    }
}