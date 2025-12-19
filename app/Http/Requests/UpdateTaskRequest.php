<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
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
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'sometimes|in:TODO,IN_PROGRESS,DONE,OVERDUE',
            'priority' => 'sometimes|in:LOW,MEDIUM,HIGH',
            'due_date' => 'sometimes|required|date',
            'project_id' => 'sometimes|exists:projects,id',
            'assigned_to' => 'sometimes|exists:users,id',
        ];
    }
}
