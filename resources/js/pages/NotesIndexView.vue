<script setup lang="ts">
 import NotesIndex from "@/pages/notes/NotesIndex.vue";

 const props = defineProps({
     notes: Object,
     filters: {
         type: Object,
         default: () => ({})
     },
     animals:Array,
     adoptionRequests:Array,
 })
</script>
<template>
<NotesIndex
:notes="notes"
:filters="filters"
:animals="animals"
:adoptionRequests="adoptionRequests"
/>
</template>
