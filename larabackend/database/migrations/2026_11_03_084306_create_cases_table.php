<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        
        Schema::create('cases', function (Blueprint $table) {
            $table->id();
            $table->string('case_number')->unique(); 
            $table->string('title');
            $table->text('description')->nullable();
            

            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('case_categories')->cascadeOnDelete();
            $table->foreignId('status_id')->constrained('case_statuses')->cascadeOnDelete();
            $table->foreignId('assigned_lawyer_id')->nullable()->constrained('users')->nullOnDelete();
            
            $table->date('opening_date')->nullable();
            $table->date('closing_date')->nullable();
            
            $table->boolean('status')->default(true); 
            $table->timestamps();
            
            $table->index(['case_number', 'status']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('cases');
    }
};