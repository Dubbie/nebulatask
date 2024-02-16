<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreIssueRequest extends FormRequest
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
            'title' => 'required|max:255',
            'description' => 'nullable',
            'due_date' => 'nullable|date|after_or_equal:today',
            'board_section_id' => 'required|exists:board_sections,id',
            'user_id' => 'nullable|exists:users,id',
            'issue_status_id' => 'nullable|exists:issue_statuses,id',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'user_id' => $this->user()->id,
        ]);
    }
}
