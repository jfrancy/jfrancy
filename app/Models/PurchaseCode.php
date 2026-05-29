<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseCode extends Model
{
    protected $fillable = [
        'license_order_id', 'software_product_id', 'code', 'activation_secret', 'max_activations',
        'activations_count', 'expires_at', 'last_verified_at', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'expires_at' => 'datetime',
        'last_verified_at' => 'datetime',
    ];
}
