<script setup>
import ActionSection from "@/Components/Actions/ActionSection.vue";
import DynamicModal from "@/Components/Modals/DynamicModal.vue";
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import { useStore } from "vuex";
import { CheckIcon, PencilIcon } from "@heroicons/vue/24/outline";

// store
const store = useStore();

const switchTeamForm = useForm({});

const modalShowSwitchTeam = ref(false);

// modal content
const typeModal = ref("");
const gridColumnModal = ref(Number(1));
const titleModal = ref("");
const descriptionModal = ref("");
const firstButtonModal = ref("");
const secondButtonModal = ref(null);
const thirdButtonModal = ref(null);
// set dynamic modal handle functions
const firstModalButtonFunction = ref(null);
const secondModalButtonFunction = ref(null);
const thirdModalButtonFunction = ref(null);

const handleSwitchToTeam = (team, currentTeamId) => {
    if (team.id === currentTeamId) {
        return;
    }

    modalShowSwitchTeam.value = true;

    // set modal standards
    typeModal.value = "success";
    gridColumnModal.value = 2;
    titleModal.value = `Switch team to ${team.name}`;
    descriptionModal.value = `Are you sure you want to switch  team to ${team.name}`;
    firstButtonModal.value = "Close";
    secondButtonModal.value = null;
    thirdButtonModal.value = "Switch Team";

    // handle click
    firstModalButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowSwitchTeam.value = false;
    };
    // handle click
    secondModalButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowSwitchTeam.value = false;
    };
    // handle click
    thirdModalButtonFunction.value = function () {
        // commit
        store.commit("mediaLibrary/setCurrentImage", null);
        store.commit("mediaLibrary/setCurrentPreviewImage", null);
        store.commit("attachedUsers/setRemoveAttachedUser", []);

        switchTeam(team);
    };
    // end modal
};
//
//
const switchTeam = function (team) {
    switchTeamForm.put(route("current-team.update", { team_id: team.id }), {
        preserveScroll: true,
        onSuccess: () => {
            modalShowSwitchTeam.value = false;
        },
        onError: (err) => {},
    });
};
</script>

<template>
    <div>
        <ActionSection :sidebarArea="false" :actionsArea="false">
            <template #title>Switch Teams</template>

            <template #description>
                <template
                    v-if="
                        $page.props.user.all_teams.length > 0 &&
                        !$page.props.user.current_team &&
                        $page.props.jetstream.hasTeamFeatures
                    "
                >
                    No Team selected. Select a Team.
                    <br />
                </template>

                <template
                    v-if="
                        $page.props.user.all_teams.length > 0 &&
                        $page.props.user.current_team &&
                        $page.props.jetstream.hasTeamFeatures
                    "
                >
                    Switch to another Team.
                </template>
            </template>

            <template #main>
                <div class="myInputsOrganization">
                    <div class="myInputGroup">
                        <div class="myTableContainer">
                            <div class="myTableSubContainer">
                                <table class="myPrimaryTable">
                                    <caption class="myPrimaryTableCaption">
                                        <p class="myPrimaryParagraph my-4">
                                            Switch Team
                                        </p>
                                    </caption>
                                    <thead class="myPrimaryTableTHead">
                                        <tr class="myPrimaryTableTr">
                                            <th
                                                scope="col"
                                                class="myPrimaryTableTh"
                                            >
                                                Cover image
                                            </th>
                                            <th
                                                scope="col"
                                                class="myPrimaryTableTh"
                                            >
                                                Team Name
                                            </th>

                                            <th
                                                scope="col"
                                                class="myPrimaryTableTh"
                                            >
                                                Status
                                            </th>
                                            <th
                                                scope="col"
                                                class="myPrimaryTableTh"
                                            >
                                                Your Role
                                            </th>
                                            <th
                                                scope="col"
                                                class="myPrimaryTableTh"
                                            >
                                                Switch Team
                                            </th>
                                            <th
                                                scope="col"
                                                class="myPrimaryTableTh"
                                            >
                                                Edit
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody class="myPrimaryTableTBody">
                                        <tr
                                            v-for="team in $page.props.user
                                                .all_teams"
                                            :key="team.id"
                                            class="myPrimaryTableTBodyTr"
                                        >
                                            <td class="myPrimaryTableTBodyTd">
                                                <form
                                                    @submit.prevent="
                                                        handleSwitchToTeam(
                                                            team,
                                                            $page.props.user
                                                                .current_team_id
                                                        )
                                                    "
                                                >
                                                    <div
                                                        class="flex items-center myPrimaryGap justify-start"
                                                        v-if="
                                                            $page.props.user
                                                                .all_teams
                                                                .length > 0
                                                        "
                                                    >
                                                        <div
                                                            v-if="
                                                                team.logo_medium
                                                            "
                                                            class="h-16 w-16 flex-shrink-0"
                                                        >
                                                            <img
                                                                @click="
                                                                    handleSwitchToTeam(
                                                                        team,
                                                                        $page
                                                                            .props
                                                                            .user
                                                                            .current_team_id
                                                                    )
                                                                "
                                                                class="w-16 h-16 rounded-full object-cover"
                                                                :class="[
                                                                    team.id ===
                                                                    $page.props
                                                                        .user
                                                                        .current_team_id
                                                                        ? 'cursor-default'
                                                                        : 'cursor-pointer',
                                                                ]"
                                                                :src="`/storage/uploads/${team.logo_medium}`"
                                                                alt="logo"
                                                            />
                                                        </div>
                                                    </div>
                                                </form>
                                            </td>
                                            <td class="myPrimaryTableTBodyTd">
                                                <form
                                                    @submit.prevent="
                                                        handleSwitchToTeam(
                                                            team,
                                                            $page.props.user
                                                                .current_team_id
                                                        )
                                                    "
                                                >
                                                    <div
                                                        class="flex items-center myPrimaryGap justify-start"
                                                        v-if="
                                                            $page.props.user
                                                                .all_teams
                                                                .length > 0
                                                        "
                                                    >
                                                        <button
                                                            class="myPrimaryButtonNoBackground text-myPrimaryDarkGrayColor flex items-center gap-2"
                                                            :class="{
                                                                'text-myPrimaryBrandColor cursor-default':
                                                                    team.id ===
                                                                    $page.props
                                                                        .user
                                                                        .current_team_id,
                                                            }"
                                                        >
                                                            <div>
                                                                {{ team.name }}
                                                            </div>
                                                            <div
                                                                v-if="
                                                                    team.id ===
                                                                    $page.props
                                                                        .user
                                                                        .current_team_id
                                                                "
                                                            >
                                                                <CheckIcon
                                                                    class="h-5 w-5"
                                                                ></CheckIcon>
                                                            </div>
                                                        </button>
                                                    </div>
                                                </form>
                                            </td>

                                            <td class="myPrimaryTableTBodyTd">
                                                <span
                                                    :class="
                                                        team.public
                                                            ? 'bg-green-100'
                                                            : 'bg-red-100 text-myPrimaryErrorColor'
                                                    "
                                                    class="inline-flex rounded-full px-2 myPrimaryParagraph font-medium text-green-800"
                                                    >{{
                                                        team.public
                                                            ? "Public"
                                                            : "Private"
                                                    }}</span
                                                >
                                            </td>

                                            <td class="myPrimaryTableTBodyTd">
                                                {{
                                                    team.membership
                                                        ? team.membership.role
                                                              .charAt(0)
                                                              .toUpperCase() +
                                                          team.membership.role.slice(
                                                              1
                                                          )
                                                        : "Owner"
                                                }}
                                            </td>
                                            <td class="myPrimaryTableTBodyTd">
                                                <form
                                                    @submit.prevent="
                                                        handleSwitchToTeam(
                                                            team,
                                                            $page.props.user
                                                                .current_team_id
                                                        )
                                                    "
                                                >
                                                    <div
                                                        class="flex items-center myPrimaryGap"
                                                        v-if="
                                                            $page.props.user
                                                                .all_teams
                                                                .length > 0
                                                        "
                                                    >
                                                        <span
                                                            class="myPrimaryParagraph text-xs"
                                                            v-if="
                                                                team.id ===
                                                                $page.props.user
                                                                    .current_team_id
                                                            "
                                                            >Current Team
                                                        </span>
                                                        <span
                                                            class="myPrimaryParagraph text-xs"
                                                            v-if="
                                                                team.id !==
                                                                $page.props.user
                                                                    .current_team_id
                                                            "
                                                            >Switch to
                                                        </span>
                                                        <button
                                                            class="myPrimaryButton"
                                                            :class="{
                                                                'mySecondaryButton flex items-center myPrimaryGap py-2 cursor-no-drop focus:ring-0':
                                                                    team.id ===
                                                                    $page.props
                                                                        .user
                                                                        .current_team_id,
                                                            }"
                                                        >
                                                            <div>
                                                                {{ team.name }}
                                                            </div>
                                                            <div
                                                                v-if="
                                                                    team.id ===
                                                                    $page.props
                                                                        .user
                                                                        .current_team_id
                                                                "
                                                            >
                                                                <CheckIcon
                                                                    class="w-5 h-5"
                                                                ></CheckIcon>
                                                            </div>
                                                        </button>
                                                    </div>
                                                </form>
                                            </td>
                                            <td class="myPrimaryTableTBodyTd">
                                                <form
                                                    v-if="
                                                        $page.props.user
                                                            .all_teams.length >
                                                            0 &&
                                                        team.id !==
                                                            $page.props.user
                                                                .current_team_id
                                                    "
                                                    @submit.prevent="
                                                        handleSwitchToTeam(
                                                            team,
                                                            $page.props.user
                                                                .current_team_id
                                                        )
                                                    "
                                                >
                                                    <div
                                                        class="flex items-center myPrimaryGap justify-start"
                                                    >
                                                        <button
                                                            class="myPrimaryButtonNoBackground text-myPrimaryDarkGrayColor flex items-center gap-2"
                                                        >
                                                            <div>
                                                                Switch to Team
                                                                for Edit
                                                            </div>
                                                        </button>
                                                    </div>
                                                </form>
                                                <Link
                                                    v-if="
                                                        $page.props.user
                                                            .all_teams.length >
                                                            0 &&
                                                        team.id ===
                                                            $page.props.user
                                                                .current_team_id
                                                    "
                                                    :href="
                                                        route(
                                                            'team.update.information',
                                                            team.reference_id
                                                        )
                                                    "
                                                    class="myPrimaryButtonNoBackground"
                                                >
                                                    <PencilIcon
                                                        class="w-5 h-5"
                                                    ></PencilIcon>
                                                </Link>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <DynamicModal
                            :show="modalShowSwitchTeam"
                            :type="typeModal"
                            :disabled="switchTeamForm.processing"
                            disabledWhichButton="thirdButton"
                            :gridColumnAmount="gridColumnModal"
                            :title="titleModal"
                            :description="descriptionModal"
                            :firstButtonText="firstButtonModal"
                            :secondButtonText="secondButtonModal"
                            :thirdButtonText="thirdButtonModal"
                            @firstModalButtonFunction="firstModalButtonFunction"
                            @secondModalButtonFunction="
                                secondModalButtonFunction
                            "
                            @thirdModalButtonFunction="thirdModalButtonFunction"
                        >
                            <header></header>
                            <main></main>
                        </DynamicModal>
                    </div>
                </div>
            </template>
        </ActionSection>
    </div>
</template>
