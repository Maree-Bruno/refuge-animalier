import { ref, computed } from 'vue';

// Shared state across all instances
const isPinned = ref(localStorage.getItem('sidebar_pinned') === '1');
const isHovered = ref(false);

export function useSidebar() {
    const isCollapsed = computed(() => !isPinned.value && !isHovered.value);

    const togglePin = () => {
        isPinned.value = !isPinned.value;
        localStorage.setItem('sidebar_pinned', isPinned.value ? '1' : '0');
    };

    const handleMouseEnter = () => {
        isHovered.value = true;
    };

    const handleMouseLeave = () => {
        isHovered.value = false;
    };

    return {
        isPinned,
        isHovered,
        isCollapsed,
        togglePin,
        handleMouseEnter,
        handleMouseLeave
    };
}
