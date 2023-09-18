<script setup>
import { useForm } from "@inertiajs/vue3";
import FormSection from "@/Components/Forms/FormSection.vue";
import InputError from "@/Components/Forms/InputError.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import SubmitButton from "@/Components/Buttons/SubmitButton.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import DynamicModal from "@/Components/Modals/DynamicModal.vue";
import AvatarCardCenterSmall from "@/Components/Avatars/AvatarCardCenterSmall.vue";
import ActionMessage from "@/Components/Actions/ActionMessage.vue";
import { Switch } from "@headlessui/vue";
import { ref } from "@vue/reactivity";

const form = useForm({
    name: "",
    public: true,
});

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

const modalShowCreateTeam = ref(false);
const handleCreateTeam = function () {
    // handle show modal for unique content
    modalShowCreateTeam.value = true;
    // set modal standards
    typeModal.value = "success";
    gridColumnModal.value = 2;
    titleModal.value = `Create a new team ${form.name}`;
    descriptionModal.value = `Following team is being created ${form.name}.`;
    firstButtonModal.value = "Close";
    secondButtonModal.value = null;
    thirdButtonModal.value = "Create Team";
    // handle click
    firstModalButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowCreateTeam.value = false;
    };
    // handle click
    thirdModalButtonFunction.value = function () {
        createTeam();
        // handle show modal for unique content
        modalShowCreateTeam.value = false;
    };
    // end modal
};

const createTeam = () => {
    form.post(route("teams.store"), {
        // error bag validation
        errorBag: "createTeam",
        preserveScroll: true,
        onSuccess: (log) => {
            form.reset();
        },
        onError: (err) => {
            form.reset();
        },
        onFinish: () => {},
    });
};
</script>

<template>
    <DynamicModal
        :show="modalShowCreateTeam"
        :type="typeModal"
        :gridColumnAmount="gridColumnModal"
        :title="titleModal"
        :description="descriptionModal"
        :firstButtonText="firstButtonModal"
        :secondButtonText="secondButtonModal"
        :thirdButtonText="thirdButtonModal"
        @firstModalButtonFunction="firstModalButtonFunction"
        @secondModalButtonFunction="secondModalButtonFunction"
        @thirdModalButtonFunction="thirdModalButtonFunction"
    >
        <header></header>
        <main></main>
    </DynamicModal>
    <FormSection
        @submitted="handleCreateTeam"
        :sidebarArea="true"
        :actionsArea="true"
    >
        <template #title> Create Team </template>

        <template #description>
            Create a new team to collaborate with others on projects.
        </template>

        <template #main>
            <div class="myInputsOrganization">
                <div class="myPrimaryFormOrganizationHeaderDescriptionSection">
                    <div class="myPrimaryFormOrganizationHeader">Team Name</div>
                    <p class="myPrimaryParagraph">
                        Please specify a name for the team.
                    </p>
                </div>
                <div class="myInputGroup">
                    <InputLabel for="name" value="Team Name" />
                    <TextInput
                        id="name"
                        v-model="form.name"
                        type="text"
                        autofocus
                        autocomplete="off"
                    />
                    <InputError :message="form.errors.name" />
                </div>
            </div>
        </template>
        <template #sidebar>
            <!-- post status - start -->

            <div class="myInputsOrganization">
                <div class="myPrimaryFormOrganizationHeaderDescriptionSection">
                    <div class="myPrimaryFormOrganizationHeader">Status</div>
                    <p class="myPrimaryParagraph">Specify Team status.</p>
                </div>
                <div
                    class="myInputGroup flex myPrimaryGap flex-row-reverse justify-end"
                >
                    <InputLabel
                        :value="form.public ? 'Published' : 'Unpublished'"
                        :class="{
                            'text-myPrimaryLinkColor': form.public,
                            'text-myPrimaryErrorColor': !form.public,
                        }"
                    />
                    <Switch
                        v-model="form.public"
                        :class="[
                            form.public
                                ? 'bg-myPrimaryLinkColor'
                                : 'bg-gray-200',
                            'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-myPrimaryLinkColor focus:ring-offset-2',
                        ]"
                    >
                        <span class="sr-only">Use setting</span>
                        <span
                            :class="[
                                form.public ? 'translate-x-5' : 'translate-x-0',
                                'pointer-events-none relative inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out',
                            ]"
                        >
                            <span
                                :class="[
                                    form.public
                                        ? 'opacity-0 ease-out duration-100'
                                        : 'opacity-100 ease-in duration-200',
                                    'absolute inset-0 flex h-full w-full items-center justify-center transition-opacity',
                                ]"
                                aria-hidden="true"
                            >
                                <svg
                                    class="h-3 w-3 text-gray-400"
                                    fill="none"
                                    viewBox="0 0 12 12"
                                >
                                    <path
                                        d="M4 8l2-2m0 0l2-2M6 6L4 4m2 2l2 2"
                                        stroke="currentColor"
                                        stroke-width="1.5"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    />
                                </svg>
                            </span>
                            <span
                                :class="[
                                    form.public
                                        ? 'opacity-100 ease-in duration-200'
                                        : 'opacity-0 ease-out duration-100',
                                    'absolute inset-0 flex h-full w-full items-center justify-center transition-opacity',
                                ]"
                                aria-hidden="true"
                            >
                                <svg
                                    class="h-3 w-3 text-myPrimaryLinkColor"
                                    fill="currentColor"
                                    viewBox="0 0 12 12"
                                >
                                    <path
                                        d="M3.707 5.293a1 1 0 00-1.414 1.414l1.414-1.414zM5 8l-.707.707a1 1 0 001.414 0L5 8zm4.707-3.293a1 1 0 00-1.414-1.414l1.414 1.414zm-7.414 2l2 2 1.414-1.414-2-2-1.414 1.414zm3.414 2l4-4-1.414-1.414-4 4 1.414 1.414z"
                                    />
                                </svg>
                            </span>
                        </span>
                    </Switch>
                </div>
                <InputError :message="form.errors.public" />
            </div>
            <div class="myInputsOrganization">
                <div class="myPrimaryFormOrganizationHeaderDescriptionSection">
                    <div class="myPrimaryFormOrganizationHeader">
                        Team Owner
                    </div>
                    <p class="myPrimaryParagraph">
                        You will become Owner of this Team.
                    </p>
                </div>
                <div
                    class="p-2 rounded-md min-h-[4rem] max-h-[18rem] flex flex-col w-full border border-myPrimaryLightGrayColor divide-y divide-gray-200"
                >
                    <div class="hover:bg-white px-2">
                        <div class="rounded">
                            <!-- start photo -->
                            <div class="flex items-center gap-2 my-4">
                                <div
                                    v-if="
                                        $page.props.user &&
                                        $page.props.user.profile_photo_path !==
                                            null
                                    "
                                    class="flex-shrink-0"
                                >
                                    <img
                                        class="object-cover w-12 h-12 rounded-full"
                                        :src="`/storage/${$page.props.user.profile_photo_path}`"
                                        alt="User Image
                                            
                                        "
                                    />
                                </div>

                                <div
                                    v-if="
                                        $page.props.user &&
                                        $page.props.user.profile_photo_path ===
                                            null
                                    "
                                    class="flex-shrink-0 w-12 h-12 rounded-full bg-myPrimaryBrandColor flex justify-center items-center text-xs font-normal text-white"
                                >
                                    {{
                                        $page.props.user.first_name
                                            .charAt(0)
                                            .toUpperCase()
                                    }}
                                    {{
                                        $page.props.user.last_name
                                            .charAt(0)
                                            .toUpperCase()
                                    }}
                                </div>
                                <span
                                    class="flex flex-col items-left gap-0.5 myPrimaryParagraph text-xs"
                                >
                                    <span>
                                        {{ $page.props.user.first_name }}
                                        {{ $page.props.user.last_name }}
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- post status - end -->
        </template>
        <template #actions>
            <SubmitButton :disabled="form.processing" buttonText="Create Team">
            </SubmitButton>
            <ActionMessage :on="form.recentlySuccessful" type="success">
                Successfully Created your Team.
            </ActionMessage>
        </template>
    </FormSection>
</template>
