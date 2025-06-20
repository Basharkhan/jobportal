<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployerProfileRequest extends FormRequest
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
            'name' => 'sometimes|required|string|max:255',
            'company_name' => 'required|string|max:255',
            'company_description' => 'nullable|string|max:1000',
            'website' => [
                'nullable', 
                'url', 
                'max:255',
                'regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/'
            ],
            'phone' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^\+?[0-9\s\-\(\)]{7,20}$/'
            ],
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'logo' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,svg,webp',
                'max:2048',
                'dimensions:max_width=2000,max_height=2000'
            ],
            'founded_year' => 'nullable|integer|min:1800|max:'.(date('Y')+1),
            'company_size' => 'nullable|string|in:1-10,11-50,51-200,201-500,501-1000,1001+',
            'linkedin' => 'nullable|url|regex:/linkedin\.com/',
            'industry_type' => 'nullable|string|max:255',
            'trade_license_number' => 'nullable|string|max:100',
            'is_verified' => 'nullable|boolean',
            'status' => 'nullable|string|in:active,inactive,pending'
        ];
    }
}
