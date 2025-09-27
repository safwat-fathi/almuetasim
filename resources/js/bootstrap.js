import axios from "axios";
import { createIcons, icons } from "lucide";
import Alpine from 'alpinejs';

window.axios = axios;
window.Alpine = Alpine;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

createIcons({ icons });
Alpine.start();
