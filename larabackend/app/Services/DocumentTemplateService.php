<?php

namespace App\Services;

use App\Models\DocumentTemplate;
use App\Models\Client;
use App\Models\CaseModel;

class DocumentTemplateService {

    public function getTemplates(array $filters = []) {
        $query = DocumentTemplate::query()->with('creator');

        if (!empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        return $query->orderBy('name')->get();
    }

    public function createTemplate(array $data): DocumentTemplate {
        $data['created_by'] = auth()->id();
        $data['variables'] = $this->extractVariables($data['content']);

        return DocumentTemplate::create($data);
    }

    public function updateTemplate(DocumentTemplate $template, array $data): DocumentTemplate {
        $data['variables'] = $this->extractVariables($data['content']);
        
        $template->update($data);
        
        return $template->fresh();
    }

    public function deleteTemplate(DocumentTemplate $template): bool {
        return $template->delete();
    }

    public function fillTemplate(DocumentTemplate $template, int $clientId, ?int $caseId = null): array {
        $client = Client::findOrFail($clientId);
        $case = $caseId ? CaseModel::with('category')->find($caseId) : null;

        $content = $template->content;

 
        $replacements = [
            '{{client_name}}' => $client->name,
            '{{client_email}}' => $client->email,
            '{{client_phone}}' => $client->phone ?? '',
            '{{client_address}}' => $client->address ?? '',
            '{{client_city}}' => $client->city ?? '',
        ];


        if ($case) {
            $caseReplacements = [
                '{{case_number}}' => $case->case_number,
                '{{case_title}}' => $case->title,
                '{{case_category}}' => $case->category->name ?? '',
                '{{opening_date}}' => $case->opening_date ? $case->opening_date->format('Y-m-d') : '',
            ];
            $replacements = array_merge($replacements, $caseReplacements);
        }

        $filledContent = str_replace(
            array_keys($replacements),
            array_values($replacements),
            $content
        );

        return [
            'filled_content' => $filledContent,
            'template_name' => $template->name
        ];
    }

    private function extractVariables(string $content): array {
        preg_match_all('/\{\{(\w+)\}\}/', $content, $matches);
        return array_unique($matches[1]);
    }
}