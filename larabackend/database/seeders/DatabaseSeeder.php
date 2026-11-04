<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SettingTableSeeder::class,      
            RolePermissionSeeder::class,     
            ClientSeeder::class,         
            CaseCategorySeeder::class,    
            CaseStatusSeeder::class, 
            CaseSeeder::class,            
            HearingSeeder::class,        
            TaskSeeder::class,          
            TimeEntrySeeder::class,       
            DocumentTemplateSeeder::class,   
            InvoiceSeeder::class,
            NotificationSeeder::class,           
         
        ]);
    }
}