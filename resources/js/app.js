import './bootstrap';
import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';

// ─── Alpine.js Setup ────────────────────────────────────────────────
// Plugin focus: trap focus di modal/dialog untuk aksesibilitas
Alpine.plugin(focus);

// Daftarkan Alpine ke window — dibutuhkan oleh Livewire
window.Alpine = Alpine;
Alpine.start();

// ─── Custom Pagination Style ─────────────────────────────────────────
// Daftarkan view pagination Tailwind agar konsisten dengan desain kita
// (dilakukan di AppServiceProvider, lihat catatan di bawah)

// ─── Global utilities ────────────────────────────────────────────────

/**
 * Copy ke clipboard — dipakai di halaman kode/tutorial (opsional)
 */
window.copyToClipboard = async (text) => {
    try {
        await navigator.clipboard.writeText(text);
        return true;
    } catch {
        return false;
    }
};

/**
 * Format angka dengan pemisah ribuan (ID locale)
 */
window.formatNumber = (n) =>
    new Intl.NumberFormat('id-ID').format(n);