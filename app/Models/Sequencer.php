<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sequencer extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'price',
        'description',
        'video_path',
        'video_url',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];
}
