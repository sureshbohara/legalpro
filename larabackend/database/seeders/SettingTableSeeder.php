<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingTableSeeder extends Seeder
{
    public function run(): void
    {
        Setting::updateOrCreate(
            ['id' => 1],
            [
           
                'system_name' => 'SP Legal Associates', 
                'email' => 'info@splegal.com',
                'extra_email' => 'booking@splegal.com',
                'phone' => '+977-1-4XXXXXX',
                'extra_phone' => '+977-98XXXXXXXX', 
                'address' => 'New Baneshwor, Kathmandu, Nepal',
                'opening_hr' => 'Sun - Fri: 9AM - 6PM',
                'work_hours' => 'Your trusted partner for professional legal services.',
            
             
                'facebook' => 'https://facebook.com/splegal',
                'twitter'  => 'https://twitter.com/splegal',
                'linkedin' => 'https://linkedin.com/company/splegal',
                'instagram'=> 'https://instagram.com/splegal',
                'youtube'  => null,

             
                'open_api_key' => env('OPENAI_API_KEY', ''),

            
                'favicon' => null,
                'logo'    => null,
  
              
                'info1' => 'Your Gateway to Professional Legal Services in Nepal',
                'info2' => 'Founded in Kathmandu, we are dedicated to providing expert legal representation, consultation, and genuine support for all your legal needs.',

            
                'mail_transport' => 'smtp',
                'mail_host' => 'smtp.gmail.com',
                'mail_port' => 587, 
                'mail_username' => env('MAIL_USERNAME', 'your-email@gmail.com'),
                'mail_password' => env('MAIL_PASSWORD', 'your-app-password'), 
                'mail_encryption' => 'tls',
                'mail_from' => 'noreply@splegal.com',
                'mail_from_name' => 'SP Legal Associates',
                'smtp_check' => true,
            ]
        );

        $this->command->info('⚙️ Default Settings seeded successfully!');
    }
}