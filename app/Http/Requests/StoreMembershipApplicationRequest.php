<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMembershipApplicationRequest extends FormRequest
{
    // Only verified regular users can submit applications.
    public function authorize(): bool
    {
        $user = $this->user();

        return $user !== null
            && $user->role === 'user'
            && $user->hasVerifiedEmail();
    }

    public function rules(): array
    {
        return [
            // Membership category must exist and be active.
            'membership_category_id' => [
                'required',
                Rule::exists('membership_categories', 'id')
                    ->where('is_active', 1),
            ],

            // Membership level is selected independently.
            'membership_level_id' => [
                'required',
                'integer',
                Rule::exists('membership_levels', 'id'),
            ],

            'full_name' => [
                'required',
                'string',
                'max:150',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::in([$this->user()?->email]),
            ],

            'phone' => [
                'required',
                'string',
                'regex:/^09[0-9]{9}$/',
            ],

            'address' => [
                'required',
                'string',
                'max:255',
            ],

            'latitude' => [
                'required',
                'numeric',
                'between:-90,90',
            ],

            'longitude' => [
                'required',
                'numeric',
                'between:-180,180',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'membership_category_id.required' =>
                'Please select a membership category.',

            'membership_category_id.exists' =>
                'The selected membership category is unavailable.',

            'membership_level_id.required' =>
                'Please select a membership level.',

            'membership_level_id.integer' =>
                'The selected membership level is invalid.',

            'membership_level_id.exists' =>
                'The selected membership level is unavailable.',

            'phone.required' =>
                'Please enter your mobile phone number.',

            'phone.regex' =>
                'The phone number must contain exactly 11 digits and start with 09.',
        ];
    }
}
