<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {


        Schema::create('time_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_id')->nullable()->constrained('cases')->nullOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('task_id')->nullable()->constrained('tasks')->nullOnDelete();
            
            $table->string('description');
            $table->date('work_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->unsignedInteger('duration_minutes')->default(0); 
            
            $table->decimal('billable_rate', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->boolean('is_billable')->default(true);
            
            $table->enum('status', ['draft', 'submitted', 'approved', 'invoiced'])->default('draft');
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            $table->index(['user_id', 'case_id', 'work_date', 'status']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('time_entries');
    }
};