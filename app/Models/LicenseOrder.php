<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LicenseOrder extends Model
{
    protected $fillable = ['order_ref', 'customer_name', 'customer_email', 'customer_phone', 'domain', 'amount', 'status'];
}
