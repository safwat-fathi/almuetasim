import "./bootstrap";
import * as lucide from 'lucide';

// Expose lucide globally and initialize icons on DOM ready so any template using data-lucide renders icons
if (typeof window !== 'undefined') {
	window.lucide = lucide;
	document.addEventListener('DOMContentLoaded', () => {
		try { lucide.createIcons(); } catch (e) { console.warn('lucide init error', e); }
	});
}

