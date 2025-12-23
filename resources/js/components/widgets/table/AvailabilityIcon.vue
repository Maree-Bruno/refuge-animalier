<script setup>
defineProps({
    value: Boolean
})
</script>
<template>
    <div class="flex items-center justify-center">
        <div
            v-if="value"
            class="flex items-center justify-center w-8 h-8 bg-green-100 rounded-full"
        >
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <div
            v-else
            class="flex items-center justify-center w-8 h-8 bg-red-100 rounded-full"
        >
            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </div>
    </div>
</template>


