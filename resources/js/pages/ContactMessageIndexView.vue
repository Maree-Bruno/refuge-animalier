<script setup>
import ContactMessagesIndex from "@/pages/contactmessages/ContactMessagesIndex.vue";

const props = defineProps({
    messages: Object,
    filters: { type: Object, default: () => ({}) },
});
</script>

<template>
    <ContactMessagesIndex
        :messages="messages"
        :filters="filters"
    />
</template>
