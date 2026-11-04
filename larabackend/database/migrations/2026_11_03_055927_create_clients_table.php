<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {

        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
            $table->enum('type', ['individual', 'corporate'])->default('individual');
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->string('phone')->nullable();
            $table->string('registration_number')->nullable(); 
            $table->date('date_of_birth')->nullable();  
            $table->string('website')->nullable(); 
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('postcode')->nullable();  
            $table->string('country')->default('Nepal'); 
            $table->text('notes')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
            
            $table->index(['status', 'type']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('clients');
    }
};