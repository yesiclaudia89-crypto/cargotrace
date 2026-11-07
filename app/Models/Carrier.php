<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carrier extends Model
{
    protected $fillable = [
        'name',
        'code',
        'contact_email',
        'contact_phone',
        'transport_mode',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
