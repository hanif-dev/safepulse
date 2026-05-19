<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LegalAidContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization', 'parent_network', 'province',
        'address', 'contact_channels', 'case_types_accepted', 'pro_bono',
    ];

    protected $casts = [
        'address'             => 'array',
        'contact_channels'    => 'array',
        'case_types_accepted' => 'array',
        'pro_bono'            => 'boolean',
    ];
}
