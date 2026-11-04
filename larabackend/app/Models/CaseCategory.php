<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class CaseCategory extends Model {
    use HasFactory, LogsActivity;

    protected $fillable = ['name', 'color', 'description', 'status'];
    protected $casts = ['status' => 'boolean'];

    public function cases() {
        return $this->hasMany(CaseModel::class, 'category_id');
    }
    
}