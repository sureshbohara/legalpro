<?php
namespace App\Mail;

use App\Models\Hearing;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class HearingReminderMail extends Mailable {
    use Queueable, SerializesModels;

    public $hearing;

    public function __construct(Hearing $hearing) {
        $this->hearing = $hearing->load(['case.client', 'case.category']);
    }

    public function build() {
        return $this->subject('⚖️ Court Hearing Reminder: ' . $this->hearing->case->case_number)
                    ->view('emails.hearing_reminder');
    }
}