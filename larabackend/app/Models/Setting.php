<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
class Setting extends Model
{
   
   use HasFactory;
    protected $table = 'settings';

   
    protected $fillable = [
        // General Information
        'system_name',
        'email',
        'extra_email',
        'phone',
        'extra_phone',
        'address',
        'opening_hr',
        'work_hours',

        // Social Media Links
        'facebook',
        'twitter',
        'linkedin',
        'instagram',
        'youtube',

        'open_api_key',


        // Media & Images
        'logo',
        'favicon',

        'info1',
        'info2',
        'info7',

        // SMTP / Mail Configuration
        'mail_transport',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'mail_encryption',
        'mail_from',
        'mail_from_name',
        'smtp_check',



  
    ];
   
    public static function getInstance(): self
    {
        return static::firstOrCreate(
            ['id' => 1],
            ['system_name' => config('app.name')]
        );
    }
   

    protected $appends = [
        'logo_url',
        'favicon_url',
    ];


    public function getLogoUrlAttribute(): string {
        if ($this->logo) {
            return Storage::disk('public')->url($this->logo);
        }
        return asset('default/noimage.png');
    }


     public function getFaviconUrlAttribute(): string {
        if ($this->favicon) {
            return Storage::disk('public')->url($this->favicon);
        }
        return asset('default/noimage.png');
    }
    


 
}