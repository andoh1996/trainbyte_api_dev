<?php
namespace App\Http\Requests\Api;

class CreateCourseMain extends ApiRequest
{
   /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    public function validationData()
    {
        return $this->all();
    }

   
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'course_name' => 'required|string|max:255',
            'cost' => 'required|float',
            'duration' => 'required|integer',
            'short_description' => 'required|string|min:10|max:500', 
            'description_url' => 'required|string|min:10|max:500', 
            'number_of_videos' => 'required|integer',
            'required_hours' => 'required|integer',
            'ratings' => 'required|float',
            'image_url' => 'required|string|min:10|max:500',
            
        ];
        
    }
}
