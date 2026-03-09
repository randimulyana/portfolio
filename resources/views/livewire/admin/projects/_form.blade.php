{{--
    Partial: _form.blade.php
    Dipakai oleh create.blade.php dan edit.blade.php
    Variable: $project (bisa null saat create), $categories, $action (route url), $method
--}}

<form
    action="{{ $action }}"
    method="POST"
    enctype="multipart/form-data"
    x-data="{
        techStack: {{ isset($project) ? json_encode($project->tech_stack ?? []) : '[]' }},
        newTech: '',
        addTech() {
            const v = this.newTech.trim();
            if (v && !this.techStack.includes(v)) this.techStack.push(v);
            this.newTech = '';
        },
        removeTech(i) { this.techStack.splice(i, 1); }
    }"
>
    @csrf
    @if ($method === 'PUT') @method('PUT') @endif

    <div class="grid lg:grid-cols-[1fr_280px] gap-6 items-start">

        {{-- ─── Panel Kiri: Konten Utama ────────────────────────── --}}
        <div class="space-y-5">

            {{-- Judul --}}
            <div class="card p-5">
                <label class="form-label" for="title">Judul Proyek <span class="text-red-400">*</span></label>
                <input
                    type="text"
                    id="title"
                    name="title"
                    value="{{ old('title', $project->title ?? '') }}"
                    placeholder="Nama proyeknya..."
                    class="form-input text-base @error('title') border-red-300 @enderror"
                    required
                >
                @error('title') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            {{-- Deskripsi Singkat --}}
            <div class="card p-5">
                <label class="form-label" for="short_description">Deskripsi Singkat <span class="text-red-400">*</span></label>
                <textarea
                    id="short_description"
                    name="short_description"
                    rows="3"
                    placeholder="Ringkasan singkat proyek (tampil di card)..."
                    class="form-input @error('short_description') border-red-300 @enderror"
                    required
                >{{ old('short_description', $project->short_description ?? '') }}</textarea>
                @error('short_description') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            {{-- Deskripsi Panjang (Rich Text) --}}
            <div class="card p-5">
                <label class="form-label" for="long_description">Deskripsi Lengkap</label>
                <p class="form-hint -mt-1 mb-2">Tampil di halaman detail proyek. Mendukung HTML.</p>
                <textarea
                    id="long_description"
                    name="long_description"
                    rows="10"
                    placeholder="Ceritakan proyek ini secara detail..."
                    class="form-input font-mono text-sm @error('long_description') border-red-300 @enderror"
                >{{ old('long_description', $project->long_description ?? '') }}</textarea>
                @error('long_description') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            {{-- Tech Stack (Alpine tag input) --}}
            <div class="card p-5">
                <label class="form-label">Tech Stack</label>
                <p class="form-hint -mt-1 mb-3">Ketik nama teknologi lalu tekan Enter atau tombol +</p>

                {{-- Display tags --}}
                <div class="flex flex-wrap gap-2 mb-3 min-h-[32px]">
                    <template x-for="(tech, i) in techStack" :key="i">
                        <span class="inline-flex items-center gap-1 badge-forest text-sm py-1 px-2.5">
                            <span x-text="tech"></span>
                            <button type="button" @click="removeTech(i)"
                                    class="w-3.5 h-3.5 rounded-full hover:bg-forest-300 flex items-center justify-center ml-0.5 -mr-1 transition-colors">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </span>
                    </template>
                </div>

                {{-- Input baru --}}
                <div class="flex gap-2">
                    <input
                        type="text"
                        x-model="newTech"
                        @keydown.enter.prevent="addTech()"
                        placeholder="Laravel, MySQL, dll..."
                        class="form-input flex-1"
                    >
                    <button type="button" @click="addTech()" class="btn-secondary px-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </button>
                </div>

                {{-- Hidden inputs untuk submit --}}
                <template x-for="(tech, i) in techStack" :key="i">
                    <input type="hidden" :name="`tech_stack[${i}]`" :value="tech">
                </template>
            </div>

            {{-- URLs --}}
            <div class="card p-5">
                <h3 class="text-sm font-semibold text-forest-700 mb-4">Links</h3>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label" for="live_url">URL Demo Live</label>
                        <input type="url" id="live_url" name="live_url"
                               value="{{ old('live_url', $project->live_url ?? '') }}"
                               placeholder="https://..."
                               class="form-input @error('live_url') border-red-300 @enderror">
                        @error('live_url') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label" for="github_url">URL GitHub</label>
                        <input type="url" id="github_url" name="github_url"
                               value="{{ old('github_url', $project->github_url ?? '') }}"
                               placeholder="https://github.com/..."
                               class="form-input @error('github_url') border-red-300 @enderror">
                        @error('github_url') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- ─── Panel Kanan: Meta & Pengaturan ──────────────────── --}}
        <div class="space-y-4">

            {{-- Aksi --}}
            <div class="card p-4 flex gap-2">
                <button type="submit" class="btn-primary flex-1 justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan
                </button>
                <a href="{{ route('admin.projects.index') }}" class="btn-secondary px-4">Batal</a>
            </div>

            {{-- Thumbnail Upload --}}
            <div class="card p-4"
                 x-data="{ preview: '{{ $project?->thumbnail_url ?? '' }}' }">
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
                    </div>
                </div>
                <input
                    type="file" name="thumbnail" accept="image/*"
                    class="hidden" x-ref="thumbInput"
                    @change="preview = URL.createObjectURL($event.target.files[0])"
                >
                @error('thumbnail') <p class="form-error mt-2">{{ $message }}</p> @enderror
            </div>

            {{-- Status --}}
            <div class="card p-4">
                <label class="form-label">Status</label>
                <div class="flex gap-2">
                    @foreach (\App\Enums\ProjectStatus::cases() as $status)
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="status" value="{{ $status->value }}"
                                   class="sr-only peer"
                                   {{ old('status', $project->status->value ?? 'draft') === $status->value ? 'checked' : '' }}>
                            <span class="block text-center text-xs font-medium py-2 px-3 rounded-lg border border-mist-200
                                         peer-checked:bg-forest-500 peer-checked:text-white peer-checked:border-forest-500
                                         hover:border-forest-300 transition-all cursor-pointer">
                                {{ $status->label() }}
                            </span>
                        </label>
                    @endforeach
                </div>
                @error('status') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            {{-- Kategori --}}
            <div class="card p-4">
                <label class="form-label" for="category_id">Kategori</label>
                <select id="category_id" name="category_id" class="form-input">
                    <option value="">— Tanpa Kategori —</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}"
                            {{ old('category_id', $project->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Featured + Sort --}}
            <div class="card p-4 space-y-4">
                <label class="flex items-center justify-between cursor-pointer">
                    <div>
                        <span class="form-label mb-0">Featured</span>
                        <span class="text-xs text-sage-400 block">Tampilkan di halaman Home</span>
                    </div>
                    <div x-data="{ on: {{ old('is_featured', $project->is_featured ?? false) ? 'true' : 'false' }} }"
                         @click="on = !on" class="relative w-10 h-6">
                        <input type="hidden" name="is_featured" :value="on ? '1' : '0'">
                        <div class="w-10 h-6 rounded-full transition-colors" :class="on ? 'bg-forest-500' : 'bg-sage-200'"></div>
                        <div class="absolute top-1 left-1 w-4 h-4 rounded-full bg-white shadow transition-transform" :class="on ? 'translate-x-4' : ''"></div>
                    </div>
                </label>

                <div>
                    <label class="form-label" for="sort_order">Urutan Tampil</label>
                    <input type="number" id="sort_order" name="sort_order" min="0" max="9999"
                           value="{{ old('sort_order', $project->sort_order ?? 0) }}"
                           class="form-input">
                    <p class="form-hint">Angka lebih kecil tampil lebih dulu</p>
                </div>
            </div>
        </div>
    </div>
</form>
