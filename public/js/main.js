import { initLogout } from "./global.js";
import { initVideos } from "./video.js";

document.addEventListener("DOMContentLoaded", () => {
    initVideos();
    initLogout();
})