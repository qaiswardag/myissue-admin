<script setup>
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import SectionBorder from "@/Components/Sections/SectionBorder.vue";
import TeamMemberManager from "@/Pages/Teams/Partials/TeamMemberManager.vue";
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
        label: "Team Members",
    },
];
</script>

<template>
    <LoggedInLayout>
        <Head title="Team Memebers" />
        <template #header>
            <h2 class="myPrimaryMainPageHeader">Team Members</h2>
        </template>
        <template #breadcrumbs>
            <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
        </template>

        <div v-if="team.owner !== null && team.owner !== undefined">
            <TeamMemberManager
                class="mt-10 sm:mt-0"
                :team="team"
                :available-roles="availableRoles"
                :user-permissions="permissions"
            />
        </div>
    </LoggedInLayout>
</template>
