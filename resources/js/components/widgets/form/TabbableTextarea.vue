<script setup>
import InputError from "@/components/InputError.vue";

defineProps({
    nameId: String,
    type: String,
    placeholder: String,
    message: String,
    modelValue: String,
    required: Boolean,
    inputClass:String,
    maxlength:String,
    classTextarea:String,

});
let emit = defineEmits(['update:modelValue'])
function onTabPress(e) {
  let textarea = e.target

  let val = textarea.value,
    start = textarea.selectionStart,
    end = textarea.selectionEnd

  textarea.value = val.substring(0, start) + '\t' + val.substring(end)

  textarea.selectionStart = textarea.selectionEnd = start + 1
}

function update(e) {
  emit('update:modelValue', e.target.value)
}
</script>

<template>
    <div class="flex flex-col gap-1 w-full">
        <Label :for="nameId" class="text-black font-semibold sm:text-lg leading-9">
            <slot/>
            <small class="xsmalltext" v-if="required === true">*Requis</small>
        </Label>
  <textarea
    @keydown.tab.prevent="onTabPress"
    @keyup="update"
    v-text="modelValue"
    class="'file:text-foreground placeholder:text-muted-foreground selection:bg-primary selection:text-primary-foreground dark:bg-input/30 border-input flex h-9 w-full min-w-0 rounded-md border bg-transparent px-3 py-1 text-base shadow-xs transition-[color,box-shadow] outline-none file:inline-flex file:h-7 file:border-0 file:bg-transparent file:text-sm file:font-medium disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50 md:text-sm',
      'focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px]',
      'aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive',
"
    :class="classTextarea"

  />
        <InputError :message="message"/>
    </div>
</template>
