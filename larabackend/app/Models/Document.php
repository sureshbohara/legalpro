<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Document extends Model {
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name', 'file_path', 'file_name', 'file_type', 'file_size',
        'category', 'description',
        'case_id', 'client_id', 'uploaded_by',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'file_size' => 'integer',
    ];

    public function case() {
        return $this->belongsTo(CaseModel::class, 'case_id');
    }

    public function client() {
        return $this->belongsTo(Client::class);
    }

    public function uploader() {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

 
    public function getFileSizeHumanAttribute() {
        $bytes = $this->file_size;
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    
}