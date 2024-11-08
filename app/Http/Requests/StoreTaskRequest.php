<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
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
            'title' => 'required|max:100|unique:tasks,title,NULL,id,user_id,' . auth()->id(), // Unique per user
            'content' => 'required',
            'status' => 'required|in:to-do,in-progress,done',
            'attachment' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096'
        ];
    }
}
