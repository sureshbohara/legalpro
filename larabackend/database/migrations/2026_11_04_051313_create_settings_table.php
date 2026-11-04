<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            // General Info
            $table->string('system_name', 191)->nullable();
            $table->string('email', 191)->nullable();
            $table->string('extra_email', 191)->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('extra_phone', 50)->nullable();
            $table->text('address')->nullable();
            $table->string('opening_hr', 100)->nullable();
            $table->string('work_hours', 100)->nullable();
            
            // Social
            $table->string('facebook', 191)->nullable();
            $table->string('twitter', 191)->nullable();
            $table->string('linkedin', 191)->nullable();
            $table->string('instagram', 191)->nullable();
            $table->string('youtube', 191)->nullable();


            $table->string('open_api_key')->nullable();

            
            // Media
            $table->string('logo', 191)->nullable();
            $table->string('favicon', 191)->nullable();
            
            // Info
            $table->text('info1')->nullable();
            $table->text('info2')->nullable();

            
            // SMTP
            $table->string('mail_transport', 50)->nullable();
            $table->string('mail_host', 191)->nullable();
            $table->integer('mail_port')->nullable();
            $table->string('mail_username', 191)->nullable();
            $table->string('mail_password', 191)->nullable();
            $table->string('mail_encryption', 10)->nullable();
            $table->string('mail_from', 191)->nullable();
            $table->string('mail_from_name', 191)->nullable();
            $table->boolean('smtp_check')->default(0);

            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};