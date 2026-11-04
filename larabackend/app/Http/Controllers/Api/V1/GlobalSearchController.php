<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\CaseModel;
use App\Models\Invoice;
use App\Models\Hearing;
use Illuminate\Http\Request;


class GlobalSearchController extends Controller {
    
    public function search(Request $request) {
        $query = $request->query('q', '');

        if (strlen($query) < 2) {
            return response()->json(['success' => true, 'data' => []]);
        }

        $results = [];

        $results['clients'] = Client::where('name', 'like', "%{$query}%")
            ->where('status', true)
            ->limit(5)
            ->get(['id', 'name', 'type'])
            ->map(fn($c) => [
                'id' => $c->id,
                'title' => $c->name,
                'subtitle' => ucfirst($c->type) . ' Client',
                'type' => 'client'
            ]);

   
        $results['cases'] = CaseModel::where('case_number', 'like', "%{$query}%")
            ->orWhere('title', 'like', "%{$query}%")
            ->where('status', true)
            ->limit(5)
            ->get(['id', 'case_number', 'title'])
            ->map(fn($c) => [
                'id' => $c->id,
                'title' => $c->case_number,
                'subtitle' => $c->title,
                'type' => 'case'
            ]);

  
        $results['invoices'] = Invoice::where('invoice_number', 'like', "%{$query}%")
            ->limit(5)
            ->get(['id', 'invoice_number', 'total_amount'])
            ->map(fn($i) => [
                'id' => $i->id,
                'title' => $i->invoice_number,
                'subtitle' => 'Amount: NPR ' . number_format($i->total_amount, 2),
                'type' => 'invoice'
            ]);

    
        $results['hearings'] = Hearing::whereHas('case', function($q) use ($query) {
                $q->where('case_number', 'like', "%{$query}%");
            })
            ->where('is_active', true)
            ->limit(5)
            ->with('case:id,case_number,title')
            ->get(['id', 'hearing_date', 'case_id'])
            ->map(fn($h) => [
                'id' => $h->id,
                'title' => $h->case?->case_number ?? 'Unknown Case',
                'subtitle' => 'Hearing on: ' . $h->hearing_date->format('d M Y'),
                'type' => 'hearing'
            ]);


        $results = array_filter($results, fn($arr) => count($arr) > 0);

        return response()->json(['success' => true, 'data' => $results]);
    }




}