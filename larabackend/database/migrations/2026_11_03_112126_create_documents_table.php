<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('file_path');
            $table->string('file_name');
            $table->string('file_type'); 
            $table->unsignedBigInteger('file_size'); 
            
            $table->string('category')->default('general'); 
            $table->text('description')->nullable();
            
            $table->foreignId('case_id')->nullable()->constrained('cases')->nullOnDelete();
            $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            $table->index(['case_id', 'client_id', 'category']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('documents');
    }
};