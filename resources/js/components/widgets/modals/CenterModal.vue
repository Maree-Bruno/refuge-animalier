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
        } else {
            document.removeEventListener('keydown', handleEscape)
            document.body.style.overflow = ''
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
            enter-from-class="opacity-0 scale-125"
            enter-to-class="opacity-100 scale-100"
            enter-active-class="transition duration-300"
            leave-active-class="transition duration-200"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-125"
        >
            <div
                v-show="modelValue"
                class="bg-gray-700/30 backdrop-blur-xs fixed inset-0 z-50 flex items-center justify-center"
                @click.self="close"
                @keydown.esc="close"
                tabindex="0"
            >
                <div class="bg-white p-6 rounded-3xl shadow-xl max-w-5xl w-full z-50">
                    <slot />
                </div>
            </div>
        </Transition>
    </Teleport>
</template>
