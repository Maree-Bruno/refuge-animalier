<script setup lang="ts">
defineProps({
    title: String,
    number: Number,
    className: String,
})
</script>

<template>
    <div
        class="flex-1 self-stretch rounded-2xl border-4 p-4 max-w-[300px] gap-10 flex flex-col justify-between"
        :class="className">
        <p class="font-bold text-2xl">{{ title }}</p>
        <p class="text-4xl font-bold place-self-center">{{ number }}</p>
    </div>
</template>

