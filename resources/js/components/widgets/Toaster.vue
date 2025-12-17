<template>
    <Teleport to="body">
        <Transition name="toast">
            <div v-if="toastStore.toasts.length" class="toaster__wrapper">
                <TransitionGroup name="toast" tag="ul">
                    <li
                        v-for="toast in toastStore.toasts"
                        :class="['toaster__inner', toastClassMap[toast.status]]"
                        :key="toast.id"
                    >
            <span class="toaster__inner-text">
              {{ toast.text }}
            </span>
                    </li>
                </TransitionGroup>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup lang="ts">
import { useToasterStore, TToastStatus } from "@/stores/useToasterStore";

const toastStore = useToasterStore();

const toastClassMap: Record<TToastStatus, string> = {
    success: "success",
    warning: "warning",
    error: "error",
};
</script>

<style scoped>
.toast-enter-from,
.toast-leave-to {
    transform: translateX(100%);
    opacity: 0;
}

.toast-enter-active,
.toast-leave-active {
    transition: 0.25s ease all;
}

.toaster__wrapper {
    position: fixed;
    top: 3%;
    right: 5%;
    z-index: 100;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.toaster__inner {
    --color: black;
    display: flex;
    align-items: center;
    gap: 1rem;
    border-radius: 6px;
    border: 1px solid var(--color);
    background: var(--color);
    padding: 12px 16px;
    color: white;
}

.toaster__inner.success {
    --color: green;
}

.toaster__inner.warning {
    --color: orange;
}

.toaster__inner.error {
    --color: red;
}
</style>
