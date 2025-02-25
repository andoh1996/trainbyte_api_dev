<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Course;


use App\Http\Requests\Api\CreateCourseMain;
use App\RealWorld\Transformers\CourseTransformer;

class CoursesController extends ApiController
{
      /**
     * AuthController constructor.
     *
     * @param UserTransformer $transformer
     */
        public function __construct(CourseTransformer $transformer)
        {
            $this->transformer = $transformer;
        }


    public function createCourse(CreateCourseMain $request){

     try {
        $course = Course::create([
            'course_name' => $request->input('first_name'),
            'cost' => $request->input('cost'),
            'duration' => $request->input('duration'),
            'short_description' => $request->input('short_description'),
            'description_url' => $request->input('description_url'),
            'students_number' => 0,
            'number_of_videos' => $request->input('number_of_videos'),
            'required_hours' => $request->input('required_hours'),
        ]);
  
        // Return the created user with the transformer
        return $this->respondWithTransformer($user, 201);
     } catch (\Throwable $e) {
        \Log::error('Course creation failed: ' . $e->getMessage());
        return $this->respondInternalError($e->getMessage());
     }
    }


    public function getCourses()
    {
        try {
            $courses = Course::all(); 
            return $this->respondWithTransformer($courses, 200); 
        } catch (\Throwable $e) {
            \Log::error('Fetching courses failed: ' . $e->getMessage());
            return $this->respondInternalError('Failed to retrieve courses.');
        }
    }


    public function getOneCourse($id)
    {
        try {
             $course = Course::find($id);

             if(!course){
                return $this->respondNoContent();
             }

             return $this->respondWithTransformer($course, 200); 
        } catch (\Throwable $e) {
            \Log::error('Fetching courses failed: ' . $e->getMessage());
            return $this->respondInternalError('Failed to retrieve courses.');
        }
    }


    public function deleteCourse($id) 
    {
        try {
            $deleted = Course::where('id', $id)->delete();

            if(!$deleted){
                return $this->respondError('Item not found', 404);
            }

            $data = [
                'message' => 'Item deleted successfully'
             ];
             
             return  $this->successResponse(200, $data);
        } catch (\Throwable $e) {
            \Log::error('Fetching courses failed: ' . $e->getMessage());
            return $this->respondInternalError('Failed to retrieve courses.');
        }
    }
   

    public function updateCourse(){
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'duration' => 'nullable|integer|min:1'
            ]);
        
            $course = Course::find($id);
        
            if (!$course) {
                return response()->json(['error' => 'Course not found'], 404);
            }
        
            $course->update($validatedData);
            
            $data = [
                'message' => 'Item deleted successfully'
             ];
            return  $this->successResponse(200, $data);
        } catch (\Throwable $e) {
            \Log::error('Fetching courses failed: ' . $e->getMessage());
            return $this->respondInternalError('Failed to retrieve courses.');
        }
    }


}
