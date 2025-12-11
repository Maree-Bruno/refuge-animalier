<script setup>
import {usePage} from "@inertiajs/vue3";
import {ref, computed} from "vue";
import Stats from "@/components/widgets/dashboard/Stats.vue";
import GenericTable from "@/components/widgets/table/GenericTable.vue";
import ArchiveIcon from "@/components/widgets/svg/ArchiveIcon.vue";
import ExternalIcon from "@/components/widgets/svg/ExternalIcon.vue";
import AnimalIndex from "@/pages/animals/AnimalIndex.vue";

const page = usePage();
const animals = computed(() => page.props.animals);
const filters = computed(() => page.props.filters || {});

const adoptionColumns = [
    {key: 'name', label: 'Nom'},
    {key: 'email', label: 'Email'},
    {key: 'phone', label: 'Téléphone'},
    {key: 'animal', label: 'Animal'},
    {key: 'date', label: 'Date'}
];

const adoptionActions = [
    {
        label: 'Ouvrir',
        icon: ExternalIcon,
        handler: (row) => {

        },
    },
    {
        label: 'Archiver',
        icon: ArchiveIcon,
        handler: (row) => {

        },
    }
];

const adoptionRequests = ref([
    {
        id: 1,
        name: 'Billy Doe',
        email: 'email@example.com',
        phone: '0499999999',
        animal: 'Billy',
        date: '23/12/2026'
    }
]);

const emailColumns = [
    {key: 'name', label: 'Nom'},
    {key: 'email', label: 'Email'},
    {key: 'phone', label: 'Téléphone'},
    {key: 'subject', label: 'Sujet'},
    {key: 'date', label: 'Date'}
];

const emailActions = [
    {
        label: 'Ouvrir',
        icon: ExternalIcon,
        handler: (row) => {
        },
    },
    {
        label: 'Archiver',
        icon: ArchiveIcon,
        handler: (row) => {

        },
    }
];

const emails = ref([
    {
        id: 1,
        name: 'Billy Doe',
        email: 'email@example.com',
        phone: '0499999999',
        subject: 'Billy',
        date: '23/12/2026'
    }
]);


const handleAdoptionRowSelect = (selected) => {

};

const handleEmailRowSelect = (selected) => {
    console.log('Emails sélectionnés:', selected);
};

</script>

<template>
    <section class="flex flex-col gap-5">
        <h3 class="sr-only">Statistiques</h3>
        <div class="gap-10 self-stretch flex justify-start items-start flex-wrap">
            <Stats title="Animaux adoptés" number="43" className="bg-lighthoneyyellow border-honeyyellow"/>
            <Stats title="Animaux Recueillis" number="22" className="border-greenmint bg-lightgreenmint"/>
            <Stats title="Animaux en cours d'adoption" number="5" className="border-sweetorange bg-lightsweetorange"/>
            <Stats title="Animaux dans le refuge" number="64" className="border-gray-500 bg-lightgray"/>
        </div>
    </section>

    <AnimalIndex
        :animals="animals"
        :filters="filters"
        :showTitle="true"
    />

    <section class="flex flex-col gap-5">
        <h3 class="subsubtitle">Demande d'adoption</h3>
        <GenericTable
            :columns="adoptionColumns"
            :data="adoptionRequests"
            :actions="adoptionActions"
            :selectable="true"
            empty-message="Aucune demande d'adoption"
            @row-select="handleAdoptionRowSelect"
        />
    </section>

    <section class="flex flex-col gap-5">
        <h3 class="subsubtitle">Emails</h3>
        <GenericTable
            :columns="emailColumns"
            :data="emails"
            :actions="emailActions"
            :selectable="true"
            empty-message="Aucun email"
            @row-select="handleEmailRowSelect"
        />
    </section>


</template>
