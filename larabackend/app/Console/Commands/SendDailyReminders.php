<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Hearing;
use App\Models\Invoice;
use App\Mail\HearingReminderMail;
use App\Mail\InvoiceReminderMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendDailyReminders extends Command
{
    protected $signature = 'reminders:send-daily';
    protected $description = 'Send daily reminders for tomorrow hearings and overdue invoices';

    public function handle()
    {
        $this->info('Starting daily reminders...');

        $this->sendHearingReminders();

        $this->sendInvoiceReminders();

        $this->info('Daily reminders completed successfully!');
        
        return Command::SUCCESS;
    }

    private function sendHearingReminders()
    {
        $tomorrow = Carbon::tomorrow();
        
        $hearings = Hearing::with(['case.client', 'case.lawyer'])
            ->where('hearing_date', $tomorrow->toDateString())
            ->where('status', 'scheduled')
            ->where('is_active', true)
            ->get();

        $count = 0;
        foreach ($hearings as $hearing) {
   
            if ($hearing->case && $hearing->case->client && $hearing->case->client->email) {
                try {
                    Mail::to($hearing->case->client->email)->send(new HearingReminderMail($hearing));
                    $count++;
                } catch (\Exception $e) {
                    $this->error("Failed to send hearing reminder to client: " . $e->getMessage());
                }
            }

            if ($hearing->case && $hearing->case->lawyer && $hearing->case->lawyer->email) {
                try {
                    Mail::to($hearing->case->lawyer->email)->send(new HearingReminderMail($hearing));
                    $count++;
                } catch (\Exception $e) {
                    $this->error("Failed to send hearing reminder to lawyer: " . $e->getMessage());
                }
            }
        }

        $this->info("Sent {$count} hearing reminders for tomorrow.");
    }

    private function sendInvoiceReminders(){
        $overdueInvoices = Invoice::with('client')
            ->whereIn('status', ['sent', 'partial'])
            ->where('due_date', '<', Carbon::today())
            ->get();

        $count = 0;
        foreach ($overdueInvoices as $invoice) {
            if ($invoice->client && $invoice->client->email) {
                try {
                    Mail::to($invoice->client->email)->send(new InvoiceReminderMail($invoice));
                    $count++;
                } catch (\Exception $e) {
                    $this->error("Failed to send invoice reminder: " . $e->getMessage());
                }
            }
        }

        $this->info("Sent {$count} overdue invoice reminders.");
    }
}