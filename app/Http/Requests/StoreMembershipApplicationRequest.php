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

            // The selected category must exist and be active.
            'membership_category_id' => [
                'required',
                Rule::exists('membership_categories', 'id')
                    ->where('is_active', 1),
            ],

            // Applicant information.
            'full_name' => [
                'required',
                'string',
                'max:150',
            ],

            // Must match the registered account's email.
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::in([$this->user()?->email]),
            ],

            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'address' => [
                'nullable',
                'string',
                'max:255',
            ],

            // Google Maps coordinates.
            'latitude' => [
                'nullable',
                'numeric',
                'between:-90,90',
            ],

            'longitude' => [
                'nullable',
                'numeric',
                'between:-180,180',
            ],
        ];
    }
}
