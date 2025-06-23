<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJobSeekerProfileRequest extends FormRequest
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
        return [
            'name' => 'nullable|string|max:255',            
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'skills' => 'nullable|string',
            'bio' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:15',
            'location' => 'nullable|string|max:255',
            'desired_job_title' => 'nullable|string|max:255',
            'expected_salary' => 'nullable|numeric',
            'employment_type' => 'nullable|string|max:50',
            'available_from' => 'nullable|date',
            'portfolio_link' => 'nullable|url|max:255',
            'linkedin' => 'nullable|url|max:255',
            'github' => 'nullable|url|max:255',
            'certifications' => 'nullable|string|max:500',
            'languages' => 'nullable|string',

            'experiences' => 'nullable|json',
            'educations' => 'nullable|json',

            // Optionally, reject any unexpected fields
            '*' => function ($attribute, $value, $fail) {
                $allowedFields = [
                    'name',
                    'resume',
                    'skills',
                    'bio',
                    'phone',
                    'location',
                    'desired_job_title',
                    'expected_salary',
                    'employment_type',
                    'available_from',
                    'portfolio_link',
                    'linkedin',
                    'github',
                    'certifications',
                    'languages',
                    'experiences',
                    'educations'
                ];
                
                if (!in_array($attribute, $allowedFields)) {
                    $fail("The field '$attribute' is not allowed.");
                }
            }
        ];
    }

    public function passedValidation() {
        $this->merge([
            'experiences' => $this->input('experiences') ? json_decode($this->input('experiences'), true) : [],
            'educations' => $this->input('educations') ? json_decode($this->input('educations'), true) : [],       
        ]);
    }
}
