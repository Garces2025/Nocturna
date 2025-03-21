<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255|unique:categories,title,' . $this->category->id
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'El título es requerido',
            'title.string' => 'El título debe ser texto',
            'title.max' => 'El título no puede tener más de 255 caracteres',
            'title.unique' => 'Ya existe una categoría con este título'
        ];
    }
} 