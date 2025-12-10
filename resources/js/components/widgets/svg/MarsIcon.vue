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
         fill="none">
        <path d="M16 3h5v5"/>
        <path d="m21 3-6.75 6.75"/>
        <circle cx="10" cy="14" r="6"/>
    </svg>
</template>

<style scoped>

</style>
