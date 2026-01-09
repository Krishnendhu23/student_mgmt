<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:100|min:3',
            'gender' => 'required|in:Male,Female',
            'age' => 'required|integer|min:5|max:15',
            'mark' => 'required|integer|min:0|max:100',
        ];
        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'Please enter name',
            'name.string' => 'Name must be a string',
            'name.min' => 'Name must be at least 3 characters',
            'name.max' => 'Name cannot exceed 100 characters',

            'gender.required' => 'Please select gender',
            'gender.in' => 'Gender must be either Male or Female',

            'age.required' => 'Please enter age',
            'age.integer' => 'Age must be an integer',
            'age.min' => 'Age must be at least 5',
            'age.max' => 'Age cannot exceed 15',

            'mark.required' => 'Please enter mark',
            'mark.integer' => 'Mark must be a valid number',
            'mark.min' => 'Mark cannot be less than 0',
            'mark.max' => 'Mark cannot be more than 100'
        ];
    }
}
