<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MentorMatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'mentee_profile_id', 'mentor_profile_id',
        'crime_domain', 'status', 'moderator_notes',
    ];
}
