<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {

        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            
            $table->enum('priority', ['high', 'medium', 'low'])->default('medium');
            $table->enum('status', ['to_do', 'in_progress', 'under_review', 'done', 'cancelled'])->default('to_do');
            
            $table->foreignId('case_id')->nullable()->constrained('cases')->nullOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            
            $table->date('deadline')->nullable();
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            $table->index(['status', 'priority', 'deadline', 'assigned_to']);
        });
        
    }

    public function down(): void {
        Schema::dropIfExists('tasks');
    }
};