{{--
    Partial: admin/posts/_form.blade.php
    Variables: $post (null saat create), $categories, $action, $method
--}}

<form
    action="{{ $action }}"
    method="POST"
    enctype="multipart/form-data"
    x-data="{
        charCount: {{ strlen(old('meta_description', $post->meta_description ?? '')) }},
        titleCount: {{ strlen(old('meta_title', $post->meta_title ?? '')) }},
    }"
>
    @csrf
    @if ($method === 'PUT') @method('PUT') @endif

    <div class="grid lg:grid-cols-[1fr_300px] gap-6 items-start">

        {{-- ─── Panel Kiri ──────────────────────────────────────── --}}
        <div class="space-y-5">

            {{-- Judul --}}
            <div class="card p-5">
                <label class="form-label" for="title">Judul Artikel <span class="text-red-400">*</span></label>
                <input
                    type="text"
                    id="title"
                    name="title"
                    value="{{ old('title', $post->title ?? '') }}"
                    placeholder="Tulis judul yang menarik..."
                    class="form-input text-lg font-display @error('title') border-red-300 @enderror"
                    required
                >
                @error('title') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            {{-- Konten --}}
            <div class="card p-5">
                <div class="flex items-start justify-between mb-1.5">
                    <label class="form-label mb-0" for="content">
                        Konten <span class="text-red-400">*</span>
                    </label>
                    <span class="text-xs text-sage-400 font-sans">Mendukung HTML</span>
                </div>
                <textarea
                    id="content"
                    name="content"
                    rows="20"
                    placeholder="Tulis konten artikel di sini...

Gunakan HTML untuk format:
<h2>Subjudul</h2>
<p>Paragraf</p>
<code>kode inline</code>
<pre><code>blok kode</code></pre>"
                    class="form-input font-mono text-sm leading-relaxed @error('content') border-red-300 @enderror"
                    required
                >{{ old('content', $post->content ?? '') }}</textarea>
                @error('content') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            {{-- Excerpt --}}
            <div class="card p-5">
                <label class="form-label" for="excerpt">Excerpt (Kutipan)</label>
                <p class="form-hint -mt-1 mb-2">Tampil di daftar blog. Dikosongkan = otomatis dari konten.</p>
                <textarea
                    id="excerpt"
                    name="excerpt"
                    rows="3"
                    maxlength="300"
                    placeholder="Ringkasan singkat artikel (maks 300 karakter)..."
                    class="form-input @error('excerpt') border-red-300 @enderror"
                >{{ old('excerpt', $post->excerpt ?? '') }}</textarea>
                @error('excerpt') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            {{-- SEO Meta --}}
            <div class="card p-5">
                <h3 class="text-sm font-semibold text-forest-700 mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-sage-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    SEO Meta
                </h3>

                <div class="space-y-4">
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="form-label mb-0" for="meta_title">Meta Title</label>
                            <span class="text-xs font-mono"
                                  :class="titleCount > 60 ? 'text-red-400' : titleCount > 50 ? 'text-amber-500' : 'text-sage-400'"
                                  x-text="titleCount + '/60'">
                            </span>
                        </div>
                        <input
                            type="text" id="meta_title" name="meta_title"
                            value="{{ old('meta_title', $post->meta_title ?? '') }}"
                            placeholder="Kosongkan = pakai judul artikel"
                            maxlength="60"
                            @input="titleCount = $event.target.value.length"
                            class="form-input @error('meta_title') border-red-300 @enderror"
                        >
                        @error('meta_title') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="form-label mb-0" for="meta_description">Meta Description</label>
                            <span class="text-xs font-mono"
                                  :class="charCount > 160 ? 'text-red-400' : charCount > 140 ? 'text-amber-500' : 'text-sage-400'"
                                  x-text="charCount + '/160'">
                            </span>
                        </div>
                        <textarea
                            id="meta_description" name="meta_description"
                            rows="3" maxlength="160"
                            placeholder="Deskripsi yang tampil di Google hasil pencarian..."
                            @input="charCount = $event.target.value.length"
                            class="form-input @error('meta_description') border-red-300 @enderror"
                        >{{ old('meta_description', $post->meta_description ?? '') }}</textarea>
                        @error('meta_description') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- ─── Panel Kanan ─────────────────────────────────────── --}}
        <div class="space-y-4">

            {{-- Aksi --}}
            <div class="card p-4 flex gap-2">
                <button type="submit" class="btn-primary flex-1 justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan
                </button>
                <a href="{{ route('admin.posts.index') }}" class="btn-secondary px-4">Batal</a>
            </div>

            {{-- Thumbnail --}}
            <div class="card p-4"
                 x-data="{ preview: '{{ $post?->thumbnail_url ?? '' }}' }">
                <label class="form-label">Thumbnail</label>
                <div
                    class="relative border-2 border-dashed border-sage-300 rounded-xl overflow-hidden transition-colors hover:border-forest-400 cursor-pointer"
                    @click="$refs.thumbInput.click()"
                >
                    <img x-show="preview" :src="preview" class="w-full h-40 object-cover" x-cloak>
                    <div x-show="!preview" class="h-40 flex flex-col items-center justify-center gap-2 text-sage-400">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-xs">Klik untuk upload</span>
                        <span class="text-xs text-sage-300">JPG, PNG, WebP — max 2MB</span>
                        <span class="text-xs text-sage-300">Ideal: 1200×630 (og:image)</span>
                    </div>
                </div>
                <input type="file" name="thumbnail" accept="image/*"
                       class="hidden" x-ref="thumbInput"
                       @change="preview = URL.createObjectURL($event.target.files[0])">
                @error('thumbnail') <p class="form-error mt-2">{{ $message }}</p> @enderror
            </div>

            {{-- Status --}}
            <div class="card p-4">
                <label class="form-label">Status</label>
                <div class="flex gap-2">
                    @foreach (\App\Enums\PostStatus::cases() as $status)
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="status" value="{{ $status->value }}"
                                   class="sr-only peer"
                                   {{ old('status', $post->status->value ?? 'draft') === $status->value ? 'checked' : '' }}>
                            <span class="block text-center text-xs font-medium py-2 px-2 rounded-lg border border-mist-200
                                         peer-checked:bg-forest-500 peer-checked:text-white peer-checked:border-forest-500
                                         hover:border-forest-300 transition-all cursor-pointer leading-tight">
                                {{ $status->label() }}
                            </span>
                        </label>
                    @endforeach
                </div>
                @error('status') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            {{-- Publish Date --}}
            <div class="card p-4">
                <label class="form-label" for="published_at">Tanggal Publish</label>
                <input
                    type="datetime-local"
                    id="published_at"
                    name="published_at"
                    value="{{ old('published_at', $post?->published_at?->format('Y-m-d\TH:i') ?? '') }}"
                    class="form-input"
                >
                <p class="form-hint">Kosongkan = otomatis saat di-publish</p>
                @error('published_at') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            {{-- Kategori --}}
            <div class="card p-4">
                <label class="form-label" for="category_id">Kategori</label>
                <select id="category_id" name="category_id" class="form-input">
                    <option value="">— Tanpa Kategori —</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}"
                            {{ old('category_id', $post->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Tags (text input, dipisah koma) --}}
            <div class="card p-4"
                 x-data="{
                     tags: {{ isset($post) ? json_encode($post->tags->pluck('name')->toArray()) : '[]' }},
                     newTag: '',
                     addTag() {
                         const v = this.newTag.trim().toLowerCase();
                         if (v && !this.tags.includes(v)) this.tags.push(v);
                         this.newTag = '';
                     },
                     removeTag(i) { this.tags.splice(i, 1); }
                 }"
            >
                <label class="form-label">Tags</label>
                <div class="flex flex-wrap gap-1.5 mb-2 min-h-[28px]">
                    <template x-for="(tag, i) in tags" :key="i">
                        <span class="inline-flex items-center gap-1 badge-rain text-xs py-1 px-2">
                            <span x-text="'#' + tag"></span>
                            <button type="button" @click="removeTag(i)"
                                    class="hover:opacity-70 transition-opacity ml-0.5">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </span>
                    </template>
                </div>
                <div class="flex gap-2">
                    <input type="text" x-model="newTag"
                           @keydown.enter.prevent="addTag()"
                           @keydown.comma.prevent="addTag()"
                           placeholder="Ketik tag, Enter..."
                           class="form-input flex-1 text-xs">
                    <button type="button" @click="addTag()" class="btn-secondary px-3 py-1.5">+</button>
                </div>
                <template x-for="(tag, i) in tags" :key="i">
                    <input type="hidden" :name="`tags[${i}]`" :value="tag">
                </template>
            </div>
        </div>
    </div>
</form>
