<script setup>
import {Input} from '@/components/ui/input/index.js';
import InputError from "@/components/InputError.vue";
import {Label} from '@/components/ui/label';

defineProps({
    nameId: String,
    type: String,
    placeholder: String,
    message: String,
    modelValue: String,
    required: Boolean,
    inputClass: String,
    maxlength: String
});

const emit = defineEmits(['update:modelValue']);
</script>

<template>
    <div class="flex flex-col gap-1 w-full">
        <Label :for="nameId" class="text-black font-semibold sm:text-lg leading-9">
            <slot/>
            <small class="xsmalltext" v-if="required === true">*Requis</small>
        </Label>

        <Input
            :id="nameId"
            :name="nameId"
            :type="type"
            :input-class="inputClass"
            :placeholder="placeholder"
            :model-value="modelValue"
            @update:model-value="emit('update:modelValue', $event)"
            :required="required ? 'required' : ''"
            :maxlength="maxlength"
        />
        <InputError :message="message"/>
    </div>
</template>
