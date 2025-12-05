import { usePage } from "@inertiajs/vue3";

export function useActive() {
    const isActive = (href) => {
        const page = usePage();
        const current = page.url.split('?')[0];
        const target = new URL(href, window.location.origin).pathname;

        return current === target;
    };

}
