import "./bootstrap";
import { createIcons, icons } from 'lucide';

// Expose lucide globally and initialize icons on DOM ready so any template using data-lucide renders icons
if (typeof window !== 'undefined') {
    window.lucide = { createIcons, icons };
    document.addEventListener('DOMContentLoaded', () => {
        try { createIcons({ icons }); } catch (e) { console.warn('lucide init error', e); }
    });
}
