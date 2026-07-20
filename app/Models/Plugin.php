<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plugin extends Model
{
    protected $fillable = [
        'title',
        'price',
        'description',
        'image',
        'link',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];
}
