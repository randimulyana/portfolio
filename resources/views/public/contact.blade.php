<x-app-layout
    title="Contact — {{ config('app.name') }}"
    description="Hubungi saya untuk kolaborasi atau diskusi."
>

    <div class="container-main py-16">

        <div class="max-w-xl mx-auto">
            <div class="mb-10 text-center">
                <p class="text-xs uppercase tracking-widest text-sage-400 font-medium mb-1">Kontak</p>
                <h1 class="font-display text-4xl text-forest-800 mb-3">Ngobrol Yuk</h1>
                <p class="text-sage-600 text-sm leading-relaxed">
                    Punya proyek menarik? Ingin diskusi soal Laravel?<br>
                    Atau sekadar menyapa — saya selalu senang bertemu orang baru.
                </p>
            </div>

            {{-- Form Kontak Sederhana (mailto fallback — bisa diganti dengan Livewire form nanti) --}}
            <div class="card p-6 space-y-4">

                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label" for="contact_name">Nama</label>
                        <input type="text" id="contact_name" placeholder="Nama kamu"
                               class="form-input">
                    </div>
                    <div>
                        <label class="form-label" for="contact_email">Email</label>
                        <input type="email" id="contact_email" placeholder="email@contoh.com"
                               class="form-input">
                    </div>
                </div>

                <div>
                    <label class="form-label" for="contact_subject">Subjek</label>
                    <input type="text" id="contact_subject" placeholder="Kolaborasi / Pertanyaan / dll"
                           class="form-input">
                </div>

                <div>
                    <label class="form-label" for="contact_message">Pesan</label>
                    <textarea id="contact_message" rows="5"
                              placeholder="Ceritakan apa yang ingin kamu sampaikan..."
                              class="form-input"></textarea>
                </div>

                {{-- TODO: Ganti dengan Livewire contact form + mail notification --}}
                <div class="bg-mist-50 border border-mist-200 rounded-xl px-4 py-3 text-xs text-sage-500">
                    📌 Form ini belum aktif. Untuk sekarang, kirim email langsung ke
                    <a href="mailto:hello@example.com" class="text-forest-500 hover:underline">hello@example.com</a>
                </div>

                <button type="button" class="btn-primary w-full justify-center opacity-50 cursor-not-allowed" disabled>
                    Kirim Pesan
                </button>
            </div>

            {{-- Alternatif kontak --}}
            <div class="mt-8 flex items-center justify-center gap-6">
                <a href="https://github.com" target="_blank" rel="noopener"
                   class="text-sm text-sage-500 hover:text-forest-700 transition-colors flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/>
                    </svg>
                    GitHub
                </a>
                <span class="text-mist-300">·</span>
                <a href="https://linkedin.com" target="_blank" rel="noopener"
                   class="text-sm text-sage-500 hover:text-forest-700 transition-colors flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                    </svg>
                    LinkedIn
                </a>
            </div>
        </div>
    </div>

</x-app-layout>
