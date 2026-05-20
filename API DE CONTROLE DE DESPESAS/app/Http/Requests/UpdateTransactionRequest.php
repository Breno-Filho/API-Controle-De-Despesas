<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'description' => ['sometimes', 'string', 'max:255'],
            'amount' => ['sometimes', 'numeric', 'min:0.01'],
            'type' => ['sometimes', 'string', Rule::in(['income', 'expense'])],
            'date' => ['sometimes', 'date'],
            'category_id' => ['sometimes', 'exists:categories,id'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($this->category_id) {
                $category = \App\Models\Category::find($this->category_id);
                if ($category && $category->user_id !== $this->user()->id) {
                    $validator->errors()->add('category_id', 'A categoria informada não pertence ao usuário.');
                }
            }
        });
    }
}
