<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentTemplate extends Model {
    use HasFactory;

    protected $fillable = [
        'name', 'content', 'category', 'description', 'variables', 'is_active', 'created_by'
    ];

    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean',
    ];

    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function extractVariables() {
        preg_match_all('/\{\{(\w+)\}\}/', $this->content, $matches);
        return array_unique($matches[1]);
    }
    
}