<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

use App\Models\User;
use App\Mail\VerifyEmail;

use App\Http\Requests\Api\RegisterUser;
use App\Http\Requests\Api\LoginUser;
use App\Http\Requests\Api\ResendVerification;
use App\Http\Requests\Api\ChangePasswordUser;
use App\RealWorld\Transformers\UserTransformer;


class AuthController extends ApiController
{
     /**
     * AuthController constructor.
     *
     * @param UserTransformer $transformer
     */
    public function __construct(UserTransformer $transformer)
    {
        $this->transformer = $transformer;
    }


    protected function sendVerificationEmail($user)
        {
            Mail::to($user->email)->send(new VerifyEmail($user));
        }

      /**
     * Register a new user and return the user if successful.
     *
     * @param RegisterUser $request
     * @return \Illuminate\Http\JsonResponse
     */

     public function registerUser(RegisterUser $request)
     {
         try {
             // Attempt to create a new user
             $user = User::create([
                 'first_name' => $request->input('first_name'),
                 'last_name' => $request->input('last_name'),
                 'email' => $request->input('email'),
                 'phone_number' => $request->input('phone_number'),
                 'status' => 'active',
                 'reg_courses' => 0,
                 'user_type' => 'user',
                 'verify_email' => false,
                 'password' => bcrypt($request->input('password')),
             ]);

              // Send custom verification email
               $this->sendVerificationEmail($user);
     
             // Return the created user with the transformer
             return $this->respondWithTransformer($user, 201);
     
         } catch (\Exception $e) {
             // Log the error for debugging purposes
             \Log::error('User registration failed: ' . $e->getMessage());
             return $this->respondInternalError($e->getMessage());
         }
     }


     public function resendVerificationEmail(ResendVerification $request){
        $email = $request->input('email');
        $user = User::where('email', $email)->first();

        // Send custom verification email
        $this->sendVerificationEmail($user);

        $data = [
            'message' => 'Verification has been sent your email !'
         ];
         
         return  $this->successResponse(200, $data);
     }
     

     public function verifyEmail($id)
     {
         // Fetch the user by ID
         $user = User::where('id', $id)->first();
     
         // Check if the user exists
         if (!$user) {
             return $this->respondError('No user found with this ID', 404);
         }
     
         // Update the 'verified' field to true 
         $user->verified = true; 

         $user->save(); 
     
         // Return a success response
         return $this->respondSuccess('User email verified successfully');
     }
     

     public function login(LoginUser $request)
     {
         $credentials = $request->only('email', 'password');
     
         // Attempt to authenticate the user
         if (!Auth::attempt($credentials)) {
             return $this->respondFailedLogin();
         }
     
         // Get the authenticated user
         $data = Auth::user();
     
         // Generate a personal access token
         $token = $data->createToken('API Token')->plainTextToken;
     
         // Prepare login data
         $loginData = [
             'id' => $data->id,
             'first_name' => $data->first_name,
             'last_name' => $data->last_name,
             'email' => $data->email,
             'phone_number' => $data->phone_number,
             'status' => $data->status,
             'reg_courses' => $data->reg_courses,
             'user_type' => $data->user_type,
             'token' => $token,
         ];
     
         // Return a successful response with the login data
         return $this->respond($loginData, 200);
     }


     //////////////Change password///////////////////
     public function changePassword(ChangePasswordUser $request)
     {
         $user = Auth::user();
 
         // Check if the current password matches
         if (!Hash::check($request->current_password, $user->password)) {
             return $this->respondError('Current password does not match', 400);
         }
 
         // Update the password
         $user->password = Hash::make($request->new_password);
         $user->save();
 
         //revoke tokens if you're using Laravel Sanctum or Passport
          $user->tokens()->delete();

          $data = [
             'message' => 'Password changed successfully'
          ];
          
          return  $this->successResponse(200, $data);
     }
 

     ///////////////////////Fetch user profile///////////
     public function profile()
     {
         $user = Auth::user();
         if (!$user) {
            return $this->respondUnauthorized($user, 200);
         }
         
         return $this->respondWithTransformer($user, 200);
     }
     
     
}
