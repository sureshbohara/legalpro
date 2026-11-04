<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Hearing;
use App\Models\Task;
use App\Models\Invoice;
use Illuminate\Http\Request;

class CalendarController extends Controller {
    
    public function getEvents(Request $request) {
        $start = $request->query('start');
        $end = $request->query('end');
        
        $events = [];

        $hearings = Hearing::whereBetween('hearing_date', [$start, $end])
            ->where('is_active', true)
            ->with('case:id,case_number,title')
            ->get();
            
        foreach ($hearings as $h) {
            $events[] = [
                'id' => "hearing_{$h->id}",
                'title' => "⚖️ {$h->case?->case_number} - {$h->court_name}",
                'start' => $h->hearing_date->toDateString(),
                'type' => 'hearing',
                'color' => '#f59e0b',
                'url' => "/hearings/{$h->id}"
            ];
        }


        $tasks = Task::whereBetween('deadline', [$start, $end])
            ->where('is_active', true)
            ->where('status', '!=', 'done')
            ->get();
            
        foreach ($tasks as $t) {
            $events[] = [
                'id' => "task_{$t->id}",
                'title' => "✅ {$t->title}",
                'start' => $t->deadline->toDateString(),
                'type' => 'task',
                'color' => '#3b82f6', 
                'url' => "/tasks/{$t->id}"
            ];
        }

 
        $invoices = Invoice::whereBetween('due_date', [$start, $end])
            ->whereNotIn('status', ['paid', 'cancelled'])
            ->get();
            
        foreach ($invoices as $inv) {
            $events[] = [
                'id' => "invoice_{$inv->id}",
                'title' => "💰 {$inv->invoice_number} Due",
                'start' => $inv->due_date->toDateString(),
                'type' => 'invoice',
                'color' => '#10b981', 
                'url' => "/invoices/{$inv->id}"
            ];
        }

        return response()->json(['success' => true, 'data' => $events]);
    }
}