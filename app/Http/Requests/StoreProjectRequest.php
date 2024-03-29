<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreProjectRequest extends FormRequest
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
            'name' => ['required', 'max:255', Rule::unique('projects')->where(function ($query) {
                return $query->where('user_id', Auth::id());
            })],
            'code' => ['required', 'max:8', Rule::unique('projects')->where(function ($query) {
                return $query->where('user_id', Auth::id());
            })],
            'description' => 'nullable|max:255',
        ];
    }
}
