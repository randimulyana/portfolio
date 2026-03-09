<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Enums\ProjectStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class StoreProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Middleware 'auth' sudah melindungi route ini,
        // tapi kita tetap set true agar eksplisit.
        return true;
    }

    public function rules(): array
    {
        return [
            'title'             => ['required', 'string', 'max:255'],
            'category_id'       => ['nullable', 'integer', Rule::exists('categories', 'id')],
            'short_description' => ['required', 'string', 'max:500'],
            'long_description'  => ['nullable', 'string'],
            'tech_stack'        => ['nullable', 'array'],
            'tech_stack.*'      => ['string', 'max:50'],
            'live_url'          => ['nullable', 'url', 'max:255'],
            'github_url'        => ['nullable', 'url', 'max:255'],
            'is_featured'       => ['boolean'],
            'sort_order'        => ['nullable', 'integer', 'min:0', 'max:9999'],
            'status'            => ['required', new Enum(ProjectStatus::class)],
            'thumbnail'         => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'tags'              => ['nullable', 'array'],
            'tags.*'            => ['string', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'             => 'Judul proyek wajib diisi.',
            'short_description.required' => 'Deskripsi singkat wajib diisi.',
            'short_description.max'      => 'Deskripsi singkat maksimal 500 karakter.',
            'status.required'            => 'Status wajib dipilih.',
            'thumbnail.image'            => 'File thumbnail harus berupa gambar.',
            'thumbnail.max'              => 'Ukuran thumbnail maksimal 2MB.',
            'live_url.url'               => 'Format URL live tidak valid.',
            'github_url.url'             => 'Format URL GitHub tidak valid.',
        ];
    }

    /**
     * Normalisasi input sebelum validasi dijalankan.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            // Checkbox HTML mengirim '1' atau tidak dikirim sama sekali
            'is_featured' => $this->boolean('is_featured'),
            'sort_order'  => $this->input('sort_order', 0),
        ]);
    }
}
