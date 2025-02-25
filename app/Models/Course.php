<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_name',
        'cost',
        'duration',
        'short_description',
        'description_url',
        'students_number',
        'image_url',
        'number_of_videos',
        'required_hours',
    ];
}
