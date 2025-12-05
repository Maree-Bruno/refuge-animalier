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
        <path d="M11 19C15.4183 19 19 15.4183 19 11C19 6.58172 15.4183 3 11 3C6.58172 3 3 6.58172 3 11C3 15.4183 6.58172 19 11 19Z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M20.9999 20.9999L16.6499 16.6499" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
</template>

<style scoped>

</style>
