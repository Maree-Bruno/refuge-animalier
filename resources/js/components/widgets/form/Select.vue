<script setup>
const props = defineProps({
    label: String,
    multiple: String,
    modelValue: String

});
const emit = defineEmits(['update:modelValue'])

</script>

<template>
    <div class="flex flex-col gap-1 w-full">
        <Label for="nameId" class="text-black font-semibold sm:text-lg leading-9">
            {{ label }}
        </Label>
        <select name="nameId" id="nameId" class="'file:text-foreground placeholder:text-muted-foreground selection:bg-primary selection:text-primary-foreground dark:bg-input/30 border-input flex h-9 w-full min-w-0 rounded-md border bg-transparent px-3 py-1 text-base shadow-xs transition-[color,box-shadow] outline-none file:inline-flex file:h-7 file:border-0 file:bg-transparent file:text-sm file:font-medium disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50 md:text-sm',
      'focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px]',
      'aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive'"
                :multiple="multiple"
                @change="emit('update:modelValue', $event.target.value)"
                :value="modelValue"
        >
            <slot/>
        </select>
    </div>
</template>

<style scoped>

</style>
