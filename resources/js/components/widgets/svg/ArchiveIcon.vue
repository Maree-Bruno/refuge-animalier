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
        <g clip-path="url(#clip0_161_7538)">
            <path d="M21 8V21H3V8M10 12H14M1 3H23V8H1V3Z" stroke-width="2" stroke-linecap="round"
                  stroke-linejoin="round"/>
        </g>
        <defs>
            <clipPath id="clip0_161_7538">
                <rect width="24" height="24" fill="white"/>
            </clipPath>
        </defs>
    </svg>
</template>

<style scoped>

</style>
