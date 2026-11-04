<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class CaseStatus extends Model {
    use HasFactory, LogsActivity;

    protected $fillable = ['name', 'color', 'is_default', 'status'];
    protected $casts = [
        'status' => 'boolean',
        'is_default' => 'boolean',
    ];

     public function cases() {
        return $this->hasMany(CaseModel::class, 'status_id');
    }
    
}