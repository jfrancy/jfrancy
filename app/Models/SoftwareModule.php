<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoftwareModule extends Model
{
    protected $fillable = ['software_product_id', 'name', 'code', 'description', 'price', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];
}
