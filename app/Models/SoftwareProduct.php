<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoftwareProduct extends Model
{
    protected $fillable = ['name', 'slug', 'sku', 'description', 'price', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function modules()
    {
        return $this->hasMany(SoftwareModule::class);
    }
}
