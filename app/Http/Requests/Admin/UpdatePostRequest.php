<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Enums\PostStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpdatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'            => ['required', 'string', 'max:255'],
            'category_id'      => ['nullable', 'integer', Rule::exists('categories', 'id')],
            'content'          => ['required', 'string', 'min:50'],
            'excerpt'          => ['nullable', 'string', 'max:300'],
            'status'           => ['required', new Enum(PostStatus::class)],
            'published_at'     => ['nullable', 'date'],
            'meta_title'       => ['nullable', 'string', 'max:60'],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'thumbnail'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'tags'             => ['nullable', 'array'],
            'tags.*'           => ['string', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'   => 'Judul artikel wajib diisi.',
            'content.required' => 'Konten artikel wajib diisi.',
            'content.min'      => 'Konten minimal 50 karakter.',
            'status.required'  => 'Status wajib dipilih.',
            'thumbnail.image'  => 'File thumbnail harus berupa gambar.',
            'thumbnail.max'    => 'Ukuran thumbnail maksimal 2MB.',
        ];
    }
}
