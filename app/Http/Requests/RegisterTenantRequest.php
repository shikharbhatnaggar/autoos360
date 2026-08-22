<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterTenantRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['nullable', 'string', 'email', 'max:255', 'unique:tenants,email'],
            'phone'      => ['nullable', 'string', 'max:20'],
            'address'    => ['nullable', 'string'],
            'city'       => ['required', 'string', 'max:255'],
            'state'      => ['required', 'string', 'max:255'],
            'country'    => ['required', 'string', 'max:255'],
            'website'    => ['nullable', 'url', 'max:255'],
            'gst_number' => ['nullable', 'string', 'max:50'],
            'subscription_plan_id' => ['nullable', 'integer', 'exists:subscription_plans,id'],
        ];
    }
}
