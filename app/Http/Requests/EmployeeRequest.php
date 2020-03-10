<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
//            'name' => 'required|max:256',
//            'position_id' => 'required',
//            'salary' => 'required|min:1000000',
//            'phone' => 'required',
//            'email' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name can not be empty',
            'name.max' => 'Name may not be greater than 256 characters',
            'position_id.required' => 'Position can not be empty',
            'salary.required' => 'Salary can not be empty',
            'Phone.required' => 'Phone can not be empty',
            'email.required' => 'Email can not be empty',
        ];
    }
}
