<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage; 

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'image', 'role', 'bio_info', 'status'
    ];

    protected $hidden = ['password', 'remember_token'];
    
    protected $appends = ['image_url'];
    
    protected function casts(): array {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status' => 'boolean', 
        ];
    }

 
     public function getImageUrlAttribute(): string {
        if ($this->image) {
            return Storage::disk('public')->url($this->image);
        }
        return asset('default/noimage.png');
    }
    

    public function scopeActive($query) {
        return $query->where('status', 1);
    }

    public function roles() {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    public function permissions() {
        return $this->roles()->with('permissions')->get()->pluck('permissions')->flatten()->unique('id');
    }

    public function hasPermission(string $permissionName): bool {
        return $this->permissions()->contains('name', $permissionName);
    }

    public function hasRole(string $roleName): bool {
        return $this->roles()->where('name', $roleName)->exists();
    }
}