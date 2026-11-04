<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClientPortalInviteMail extends Mailable {
    use Queueable, SerializesModels;

    public $clientName;
    public $email;
    public $tempPassword;
    public $portalUrl;

    public function __construct($clientName, $email, $tempPassword) {
        $this->clientName = $clientName;
        $this->email = $email;
        $this->tempPassword = $tempPassword;
        $this->portalUrl = env('FRONTEND_URL', 'http://localhost:3000') . '/client/login';
    }

    public function build() {
        return $this->subject('🔐 Welcome to LegalPro Client Portal - Your Login Credentials')
                    ->view('emails.client_invite');
    }
}