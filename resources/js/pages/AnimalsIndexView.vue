<script setup>

  import AnimalIndex from "@/pages/animals/AnimalIndex.vue";
  import {usePage} from "@inertiajs/vue3";
  import {computed} from "vue";

  const page = usePage();
  const animals = computed(() => page.props.animals);
  const filters = computed(() => page.props.filters || {});
  const species = computed(()=>page.props.species);
  const races = computed(()=>page.props.races);
  const coats = computed(()=>page.props.coats);
  const vaccines = computed(()=>page.props.vaccines);
</script>

<template>
    <AnimalIndex
        :animals="animals"
        :filters="filters"
        :species="species"
        :races="races"
        :coats="coats"
        :vaccines = "vaccines"
    />
</template>
