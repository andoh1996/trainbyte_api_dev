<?php

namespace App\Http\Requests\Api;

class ChangePasswordUser extends ApiRequest
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
            'email' => 'required|email|max:255',
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ];
    }
}
