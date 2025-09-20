import axios from "axios";
import { createIcons, icons } from "lucide";

window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

createIcons({ icons });
