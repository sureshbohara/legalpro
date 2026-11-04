<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity; 
use Laravel\Sanctum\HasApiTokens;

class Client extends Model {
    use HasFactory, LogsActivity, HasApiTokens;

    protected $fillable = [
        'name', 'type', 'email', 'password', 'phone', 
        'registration_number', 'date_of_birth', 'website', 
        'address', 'city', 'postcode', 'country', 'notes', 'status'
    ];

    protected $hidden = [
        'password', 'remember_token', 
    ];

    protected $casts = [
        'status' => 'boolean',
        'date_of_birth' => 'date', 
        'password' => 'hashed',
    ];

    public function scopeActive($query) {
        return $query->where('status', 1);
    }
}