<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'title'       => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'location'    => 'nullable|string|max:500',
            'media'       => 'nullable|array|max:5',
            'media.*'     => [
                'file',
                'mimes:jpg,jpeg,png,webp,mp4,mov',
                'max:20480', // 20MB max per file
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'media.max'       => 'Maksimal 5 file media.',
            'media.*.max'     => 'Ukuran file maksimal 20MB.',
            'media.*.mimes'   => 'Format file harus jpg, jpeg, png, webp, mp4, atau mov.',
            'title.max'       => 'Judul maksimal 255 karakter.',
            'description.max' => 'Deskripsi maksimal 5000 karakter.',
        ];
    }
}
