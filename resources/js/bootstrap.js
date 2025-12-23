import axios from "axios";
import Alpine from "alpinejs";

window.axios = axios;
window.Alpine = Alpine;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

// Lazy load Lucide icons - only initialize when DOM is ready
// This reduces initial JavaScript execution time
const initIcons = async () => {
    const { createIcons, icons } = await import("lucide");
    window.lucide = { createIcons, icons };
    createIcons({ icons });
};

// Initialize icons after DOM content is loaded
if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initIcons);
} else {
    // DOM already loaded, initialize immediately
    initIcons();
}

Alpine.start();
