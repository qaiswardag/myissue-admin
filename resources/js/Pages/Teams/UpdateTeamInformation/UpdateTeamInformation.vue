<script setup>
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import UpdateTeamNameForm from "@/Pages/Teams/Partials/UpdateTeamNameForm.vue";
import { router } from "@inertiajs/vue3";
import Breadcrumbs from "@/Components/Breadcrumbs/Breadcrumbs.vue";

const props = defineProps({
    team: Object,
    availableRoles: Array,
    permissions: Object,
});

const breadcrumbsLinks = [
    {
        label: "Team Settings",
        route: {
            name: "teams.show",
            parameters: [props.team.reference_id],
        },
    },
    {
        label: "Team Information",
    },
];
</script>

<template>
    <LoggedInLayout>
        <Head title="Team Information" />
        <template #header>
            <h2 class="myPrimaryMainPageHeader">Team Information</h2>
        </template>
        <template #breadcrumbs>
            <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
        </template>

        <div v-if="team.owner !== null && team.owner !== undefined">
            <UpdateTeamNameForm :team="team"> </UpdateTeamNameForm>
        </div>
    </LoggedInLayout>
</template>
