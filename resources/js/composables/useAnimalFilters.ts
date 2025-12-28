import { ref, watch } from "vue";
import { router } from "@inertiajs/vue3";

export function useAnimalFilters(filters: Record<string, any> | undefined) {
    const search = ref(filters?.animal_search ?? "");
    const activeTab = ref(filters?.status ?? "all");

    let timeout: number | undefined;

    watch(search, value => {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            updateRoute();
        }, 300);
    });

    const updateRoute = (extra = {}) => {
        router.get(
            window.location.pathname,
            {
                animal_search: search.value,
                status: activeTab.value,
                ...extra
            },
            {
                preserveState: true,
                preserveScroll: true,
                replace: true
            }
        );
    };

    const switchTab = (tab) => {
        activeTab.value = tab;
        updateRoute();
    };

    return { search, activeTab, switchTab, updateRoute };
}
