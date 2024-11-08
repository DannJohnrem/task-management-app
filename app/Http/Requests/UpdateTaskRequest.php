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
            'title' => 'required|string|max:100|unique:tasks,title,' . $this->task->id,
            'content' => 'nullable|string',
            'status' => 'required|in:to-do,in-progress,done',
            'is_published' => 'boolean',
            'image' => 'nullable|image|max:4096',
        ];
    }
}
