<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {

        Schema::create('hearings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_id')->constrained('cases')->cascadeOnDelete();
            
            $table->date('hearing_date');
            $table->time('hearing_time')->nullable();
            $table->string('court_name');
            $table->string('judge_name')->nullable();
            $table->string('hearing_type')->default('Regular'); 
            $table->text('purpose')->nullable();
            $table->text('outcome')->nullable(); 
            $table->date('next_hearing_date')->nullable();
            
            $table->enum('status', ['scheduled', 'completed', 'adjourned', 'cancelled'])->default('scheduled');
            $table->boolean('is_active')->default(true); 
            
            $table->timestamps();
            $table->index(['case_id', 'hearing_date', 'status']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('hearings');
    }
};