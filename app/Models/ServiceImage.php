<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceImage extends Model
{
    protected $fillable = ['name', 'filename', 'path', 'is_active', 'order'];
    
    public function serviceMappings()
    {
        return $this->hasMany(ServiceMapping::class);
    }
    
    public function getUrlAttribute()
    {
        // Return URL to the image. Assuming storage link is set up, or we serve from public/images/services
        // For now let's assume valid storage link or public path
        return asset('storage/service_images/' . $this->filename);
    }
}
