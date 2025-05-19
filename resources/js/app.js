import "./bootstrap";

import Alpine from "alpinejs";
import collapse from "@alpinejs/collapse";
import PerfectScrollbar from "perfect-scrollbar";

window.PerfectScrollbar = PerfectScrollbar;

// Debounce function to limit how often a function can be called
const debounce = (func, wait) => {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
};

document.addEventListener("alpine:init", () => {
    Alpine.data("mainState", () => {
        let lastScrollTop = 0;

        const init = function () {
            // Use passive event listener for better scroll performance
            window.addEventListener(
                "scroll",
                debounce(() => {
                    const st =
                        window.pageYOffset ||
                        document.documentElement.scrollTop;
                    if (st > lastScrollTop) {
                        this.scrollingDown = true;
                        this.scrollingUp = false;
                    } else {
                        this.scrollingDown = false;
                        this.scrollingUp = true;
                        if (st === 0) {
                            this.scrollingDown = false;
                            this.scrollingUp = false;
                        }
                    }
                    lastScrollTop = st <= 0 ? 0 : st;
                }, 10),
                { passive: true }
            );
        };

        const getTheme = () => {
            const storedTheme = window.localStorage.getItem("dark");
            if (storedTheme !== null) {
                return JSON.parse(storedTheme);
            }
            return (
                window.matchMedia?.("(prefers-color-scheme: dark)").matches ??
                false
            );
        };

        const setTheme = (value) => {
            window.localStorage.setItem("dark", value);
        };

        return {
            init,
            isDarkMode: getTheme(),
            toggleTheme() {
                this.isDarkMode = !this.isDarkMode;
                setTheme(this.isDarkMode);
            },
            isSidebarOpen: window.innerWidth > 1024,
            isSidebarHovered: false,
            handleSidebarHover(value) {
                if (window.innerWidth < 1024) return;
                this.isSidebarHovered = value;
            },
            handleWindowResize: debounce(function () {
                this.isSidebarOpen = window.innerWidth > 1024;
            }, 100),
            scrollingDown: false,
            scrollingUp: false,
        };
    });
});

Alpine.plugin(collapse);
Alpine.start();
