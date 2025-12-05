<script setup lang="ts">
import type {HTMLAttributes} from "vue";

defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
}

defineProps<Props>();
</script>

<template>
    <svg xmlns="http://www.w3.org/2000/svg"
         viewBox="0 0 24 24"
         :class="className"
         v-bind="$attrs"
         fill="none"
    >
        <path d="M10 5.17187C10 3.78187 8.423 2.67887 6.5 2.99987C3.677 3.46987 2.387 9.00587 2.5 9.99987C2.58 10.7029 4.225 11.7219 6.156 10.9999C7.417 10.5279 8.116 9.54987 8.5 8.49987" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M14.267 5.17187C14.267 3.78187 15.844 2.67887 17.767 2.99987C20.59 3.46987 21.88 9.00587 21.767 9.99987C21.687 10.7029 20.042 11.7219 18.111 10.9999C16.85 10.5279 16.256 9.54987 15.872 8.49987" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M8 14V14.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M16 14V14.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M11.25 16.25H12.75L12 17L11.25 16.25Z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M4.42 11.2471C4.14013 12.3278 3.99899 13.4397 4 14.5561C4 18.7281 7.582 21.0001 12 21.0001C16.418 21.0001 20 18.7281 20 14.5561C20 13.4951 19.838 12.3561 19.507 11.2471M10.264 5.16509C10.8358 5.05275 11.4173 4.99748 12 5.00009C12.78 5.00009 13.5 5.10809 14.161 5.30609" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
</template>

<style scoped>

</style>
