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
        $id = $this->get('id', null);
        $ignore = ($id) ? ',' . $id : '';
        return [
            'name' => 'required|min:2|max:256',
            'position_id' => 'required|integer',
            'salary' => 'required|integer|max:500000',
            'phone' => 'required|string|unique:employees,phone' . $ignore,
            'email' => 'required|email',
            'file' => 'nullable|mimes:jpeg,png|max:5000|dimensions:min_width=300,min_height=300'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name can not be empty',
            'name.max' => 'Name may not be greater than 256 characters',
            'position_id.required' => 'Position can not be empty',
            'position_id.integer' => 'Position incorrect',
            'salary.required' => 'Salary can not be empty',
            'salary.integer' => 'Salary can be integer',
            'Phone.required' => 'Phone can not be empty',
            'email.required' => 'Email can not be empty',
            'file.mimes' => 'Format file jpg,png Up 5MB, the minimum size of 300x300px',
            'file.max' => 'Format file jpg,png Up 5MB, the minimum size of 300x300px',
            'file.dimensions' => 'Format file jpg,png Up 5MB, the minimum size of 300x300px',
        ];
    }
}
