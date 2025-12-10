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
         stro
    >
        <path d="M12 15v7"/>
        <path d="M9 19h6"/>
        <circle cx="12" cy="9" r="6"/>
    </svg>
</template>

<style scoped>

</style>
