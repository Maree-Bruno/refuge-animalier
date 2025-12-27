<script setup lang="ts">
import {onBeforeUnmount, watch} from "vue";
import {router} from "@inertiajs/vue3";

const props = defineProps<{
    modelValue: boolean
    routeKey?: string
    routeValue?: string | number | null
}>();

const emit = defineEmits<{
    'update:modelValue': [value: boolean]
}>();

const close = () => {
    emit('update:modelValue', false)
}

const handleEscape = (e: KeyboardEvent) => {
    if (e.key === 'Escape' && props.modelValue) {
        close()
    }
}
watch(
    () => props.modelValue,
    (isOpen) => {
        if (isOpen) {
            document.addEventListener('keydown', handleEscape)
            document.body.style.overflow = 'hidden'
            document.body.tabIndex = -1;
        } else {
            document.removeEventListener('keydown', handleEscape)
            document.body.style.overflow = ''
            document.body.tabIndex = 0;
        }
        if (!props.routeKey) return

        const url = new URL(window.location.href)

        if (isOpen && props.routeValue != null) {
            url.searchParams.set(props.routeKey, String(props.routeValue))
        } else {
            url.searchParams.delete(props.routeKey)
        }

        router.replace({
            url: url.pathname + url.search,
            preserveState: true,
            preserveScroll: true,
        })
    }
)

onBeforeUnmount(() => {
    document.removeEventListener('keydown', handleEscape)
    document.body.style.overflow = ''
})
</script>

<template>
    <Teleport to="body">
        <Transition
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            enter-active-class="transition-opacity duration-300"
            leave-active-class="transition-opacity duration-200"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-show="modelValue"
                class="bg-gray-700/30 backdrop-blur-sm fixed inset-0 z-50"
                @click.self="close"
            >
            </div>
        </Transition>

        <Transition
            enter-from-class="translate-x-full"
            enter-to-class="translate-x-0"
            enter-active-class="transition-transform duration-300 ease-out"
            leave-active-class="transition-transform duration-200 ease-in"
            leave-from-class="translate-x-0"
            leave-to-class="translate-x-full"
        >
            <div
                v-show="modelValue"
                class="fixed right-0 top-0 h-full w-full md:w-2/5 bg-white shadow-2xl z-50 overflow-y-auto rounded-tl-3xl rounded-bl-3xl "
            >
                <div class="p-6">
                    <div class="flex justify-between">
                        <slot name="header"/>
                        <button class="text-3xl" @click="close">&times;</button>
                    </div>
                    <slot/>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>
