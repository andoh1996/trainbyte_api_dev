<?php
namespace App\Http\Requests\Api;

class RegisterUser extends ApiRequest
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'phone_number' => 'required|string|min:10|max:15',
            'password' => 'required|min:8|confirmed',
        ];
    }
}
