<?php

namespace App\RealWorld\Transformers;

class CourseTransformer extends Transformer
{
    protected $resourceName = 'courses';

    public function transform($data)
    {
        return [
            'id' => $data->id,
            'course_name' => $data->course_name,
            'duration' => $data->duration,
            'required_hours' => $data->required_hours,
            'short_description' => $data->short_description,
            'description_url' => $data->description_url,
            'students_number' => $data->students_number,
            'number_of_videos' => $data->number_of_videos,
        ];
    }
}