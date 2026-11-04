<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@example.com')->first();
        
        if (!$admin) {
            $this->command->error('Admin user not found. Please run RolePermissionSeeder first.');
            return;
        }

        $notifications = [
            [
                'user_id' => $admin->id,
                'title' => 'Upcoming Hearing Tomorrow',
                'message' => 'Sharma vs. Thapa property dispute is scheduled for tomorrow at 10:00 AM at Kathmandu District Court.',
                'type' => 'hearing',
                'link' => '/hearings',
                'is_read' => false,
                'created_at' => Carbon::now()->subHours(2),
            ],
            [
                'user_id' => $admin->id,
                'title' => 'Task Overdue',
                'message' => '"Prepare bail application documents" is past its deadline. Please review immediately.',
                'type' => 'task',
                'link' => '/tasks',
                'is_read' => false,
                'created_at' => Carbon::now()->subHours(5),
            ],
            [
                'user_id' => $admin->id,
                'title' => 'Invoice Paid',
                'message' => 'Invoice #INV-2024-003 from Ram Sharma has been successfully marked as paid.',
                'type' => 'invoice',
                'link' => '/invoices',
                'is_read' => true,
                'created_at' => Carbon::now()->subDay(),
            ],
            [
                'user_id' => $admin->id,
                'title' => 'New Case Assigned',
                'message' => 'You have been assigned to a new case: Everest Tech Employment Claim.',
                'type' => 'case',
                'link' => '/cases',
                'is_read' => false,
                'created_at' => Carbon::now()->subHours(8),
            ],
            [
                'user_id' => $admin->id,
                'title' => 'Client Meeting Reminder',
                'message' => 'Meeting with Sita Thapa scheduled for today at 3:00 PM.',
                'type' => 'system',
                'link' => '/calendar',
                'is_read' => true,
                'created_at' => Carbon::now()->subDays(2),
            ],
        ];

        foreach ($notifications as $notif) {
            Notification::create($notif);
        }

        $this->command->info('✅ ' . count($notifications) . ' Notifications seeded successfully!');
    }
}