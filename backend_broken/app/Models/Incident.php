<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    use HasFactory;

    protected $fillable = [
        'category', 'country', 'age_group',
        'description', 'health_impact_level', 'financial_loss_estimate',
    ];

    protected $casts = [
        'financial_loss_estimate' => 'float',
    ];
}
