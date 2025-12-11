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
        <path d="M12 19V5M12 5L5 12M12 5L19 12" stroke-width="2" stroke-linecap="round"
              stroke-linejoin="round"/>
    </svg>
</template>

<style scoped>

</style>
