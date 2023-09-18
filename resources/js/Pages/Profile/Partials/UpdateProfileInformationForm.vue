<script setup>
import { ref, watch } from "vue";
import { Link, router, useForm } from "@inertiajs/vue3";
import ActionMessage from "@/Components/Actions/ActionMessage.vue";
import FormSection from "@/Components/Forms/FormSection.vue";
import InputError from "@/Components/Forms/InputError.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import PrimaryButton from "@/Components/Buttons/PrimaryButton.vue";
import SecondaryButton from "@/Components/Buttons/SecondaryButton.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import SubmitButton from "@/Components/Buttons/SubmitButton.vue";
import { Switch } from "@headlessui/vue";
import DynamicModal from "@/Components/Modals/DynamicModal.vue";
import SectionBorder from "@/Components/Sections/SectionBorder.vue";

const props = defineProps({
    user: Object,
});

const form = useForm({
    _method: "PUT",
    first_name: props.user.first_name,
    last_name: props.user.last_name,
    username: props.user.username,
    email: props.user.email,
    public: props.user.public ? true : false,
    photo: null,
});

const modalShowDeletePhoto = ref(false);

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

const verificationLinkSent = ref(null);
const photoPreview = ref(null);
const photoInput = ref(null);

const profilePhotoIsDirty = ref(false);

const updateProfileInformation = () => {
    if (photoInput.value) {
        form.photo = photoInput.value.files[0];
    }
    form.post(route("user-profile-information.update"), {
        errorBag: "updateProfileInformation",
        preserveScroll: true,
        onSuccess: () => {
            clearPhotoFileInput();
            profilePhotoIsDirty.value = false;
        },
        onError: (err) => {},
        onFinish: () => {},
    });
};

const sendEmailVerification = () => {
    verificationLinkSent.value = true;
};

const selectNewPhoto = () => {
    photoInput.value.click();
};

const updatePhotoPreview = () => {
    const photo = photoInput.value.files[0];

    if (!photo) return;

    const reader = new FileReader();

    reader.onload = (e) => {
        photoPreview.value = e.target.result;
    };

    reader.readAsDataURL(photo);
};

const deletePhoto = () => {
    router.delete(route("current-user-photo.destroy"), {
        preserveScroll: true,
        onSuccess: () => {
            photoPreview.value = null;
            clearPhotoFileInput();
        },
    });
};

const handleDeletePhoto = function () {
    // handle show modal for unique content
    modalShowDeletePhoto.value = true;
    // set modal standards
    typeModal.value = "delete";
    gridColumnModal.value = 2;
    titleModal.value = "Delete Photo";
    descriptionModal.value = "Are you sure you want to delete your photo?";
    firstButtonModal.value = "Close";
    secondButtonModal.value = null;
    thirdButtonModal.value = "Delete";
    // handle click
    firstModalButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowDeletePhoto.value = false;
    };
    // handle click
    thirdModalButtonFunction.value = function () {
        deletePhoto();
        // handle show modal for unique content
        modalShowDeletePhoto.value = false;
    };
    // end modal
};

const clearPhotoFileInput = () => {
    if (photoInput.value?.value) {
        photoInput.value.value = null;
    }
};

watch(photoPreview, (newValue) => {
    profilePhotoIsDirty.value = true;
});
</script>

<template>
    <FormSection @submitted="updateProfileInformation">
        <template #title> Profile Details </template>

        <template #description>
            Update your account's profile information.
        </template>

        <template #main>
            <div class="myInputsOrganization">
                <div class="myPrimaryFormOrganizationHeaderDescriptionSection">
                    <div class="myPrimaryFormOrganizationHeader">
                        Title & description
                    </div>
                    <p class="myPrimaryParagraph">Update name and email.</p>
                </div>
                <div class="myInputGroup">
                    <InputLabel for="user_name" value="Username" />
                    <div class="relative flex items-center">
                        <TextInput
                            id="user_name"
                            v-model="form.username"
                            :value="user.username"
                            type="text"
                            autocomplete="off"
                            readonly
                            class="myPrimaryInputReadonly"
                        />
                        <div
                            class="absolute inset-y-0 right-0 pr-1.5 flex items-center cursor-pointer"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="w-5 h-5 text-myPrimaryErrorColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"
                                />
                            </svg>
                        </div>
                    </div>
                    <p class="myPrimaryParagraph">
                        Your username must be between 4-15 characters in length
                        and can only contain letters (A-Z) numbers (0-9) and
                        underscores. Special characters and spaces are not
                        allowed.
                    </p>
                    <InputError :message="form.errors.username" />
                </div>

                <div class="myInputGroup">
                    <InputLabel for="first_name" value="First name" />
                    <TextInput
                        id="first_name"
                        v-model="form.first_name"
                        type="text"
                        autocomplete="first_name"
                    />
                    <InputError :message="form.errors.first_name" />
                </div>

                <div class="myInputGroup">
                    <InputLabel for="last_name" value="Last name" />
                    <TextInput
                        id="last_name"
                        v-model="form.last_name"
                        type="text"
                        autocomplete="last_name"
                    />
                    <InputError :message="form.errors.last_name" />
                </div>

                <!-- Email -->
                <div class="myInputGroup">
                    <InputLabel for="email" value="Email" />
                    <TextInput id="email" v-model="form.email" type="email" />
                    <InputError :message="form.errors.email" />

                    <div
                        v-if="
                            $page.props.jetstream.hasEmailVerification &&
                            user.email_verified_at === null
                        "
                    >
                        <p class="text-sm mt-2">
                            Your email address is unverified.

                            <Link
                                :href="route('verification.send')"
                                method="post"
                                as="button"
                                class="underline text-myPrimaryDarkGrayColor hover:text-myPrimaryDarkGrayColor"
                                @click.prevent="sendEmailVerification"
                            >
                                Click here to re-send the verification email.
                            </Link>
                        </p>

                        <div
                            v-show="verificationLinkSent"
                            class="mt-2 font-normal text-sm text-myPrimaryLinkColor"
                        >
                            A new verification link has been sent to your email
                            address.
                        </div>
                    </div>
                </div>
            </div>
            <div class="myInputsOrganization">
                <div class="myPrimaryFormOrganizationHeaderDescriptionSection">
                    <div class="myPrimaryFormOrganizationHeader">
                        Your Profile photo
                    </div>
                </div>
                <div class="myInputGroup">
                    <InputLabel for="email" value="Profile Image" />
                    <input
                        ref="photoInput"
                        type="file"
                        class="hidden"
                        @change="updatePhotoPreview"
                    />
                    <div
                        class="flex-col items-center gap-2 mb-8 flex justify-center rounded-md border-2 border-dashed border-gray-300 px-6 pt-5 pb-6"
                    >
                        <div
                            v-if="
                                !photoPreview &&
                                user.profile_photo_path !== null
                            "
                            @click.prevent="selectNewPhoto"
                            class="mt-2 cursor-pointer"
                        >
                            <img
                                class="object-cover w-16 h-16 rounded-full"
                                :src="`/storage/${user.profile_photo_path}`"
                                :alt="user.first_name + user.last_name"
                            />
                        </div>

                        <div v-if="photoPreview !== null">
                            <span
                                class="block rounded-full w-16 h-16 bg-cover bg-no-repeat bg-center"
                                :style="
                                    'background-image: url(\'' +
                                    photoPreview +
                                    '\');'
                                "
                            />
                        </div>

                        <div
                            v-if="
                                user.profile_photo_path === null &&
                                !photoPreview
                            "
                            @click.prevent="selectNewPhoto"
                            class="cursor-pointer w-16 h-16 rounded-full bg-myPrimaryBrandColor flex justify-center items-center text-xs font-normal text-white"
                        >
                            {{ user.first_name.charAt(0).toUpperCase() }}
                            {{ user.last_name.charAt(0).toUpperCase() }}
                        </div>
                        <span
                            class="flex flex-col items-center gap-1 myPrimaryParagraph"
                        >
                            <span>
                                {{ user.first_name }}
                                {{ user.last_name }}
                            </span>
                            <span>
                                {{ $page.props.user.email }}
                            </span>
                        </span>
                    </div>

                    <div class="flex justify-center items-center myPrimaryGap">
                        <button
                            class="myPrimaryButton"
                            type="button"
                            @click.prevent="selectNewPhoto"
                        >
                            Upload New
                        </button>

                        <button
                            v-if="
                                photoPreview || user.profile_photo_path !== null
                            "
                            type="button"
                            class="myPrimaryDeleteButton"
                            @click.prevent="handleDeletePhoto"
                        >
                            Delete Photo
                        </button>
                    </div>

                    <br />
                    <InputError :message="form.errors.photo" />
                    <div
                        v-if="profilePhotoIsDirty === true"
                        class="rounded-md bg-red-50 p-4 shadow-lg"
                    >
                        <div class="flex items-center">
                            <div class="ml-3">
                                <p
                                    class="text-sm font-normal text-red-800 pr-4"
                                >
                                    Image and form not saved.
                                    <span
                                        @click="updateProfileInformation"
                                        class="cursor-pointer myPrimaryLink font-medium italic"
                                    >
                                        Save image.
                                    </span>
                                </p>
                            </div>
                            <div class="ml-auto pl-3">
                                <div class="-mx-1.5 -my-1.5">
                                    <button
                                        type="button"
                                        class="inline-flex rounded-md bg-red-50 p-1.5 text-red-500 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-2 focus:ring-offset-green-50"
                                    ></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
        <template #sidebar>
            <div class="myInputsOrganization">
                <div class="myPrimaryFormOrganizationHeaderDescriptionSection">
                    <div class="myPrimaryFormOrganizationHeader">Status</div>
                    <p class="myPrimaryParagraph">Specify Your status.</p>
                </div>
                <div
                    class="myInputGroup flex myPrimaryGap flex-row-reverse justify-end"
                >
                    <InputLabel
                        :value="form.public ? 'Public' : 'Private'"
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
            <DynamicModal
                :show="modalShowDeletePhoto"
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
        </template>
        <template #actions>
            <SubmitButton :disabled="form.processing" buttonText="Update">
            </SubmitButton>
            <ActionMessage :on="form.recentlySuccessful" type="success">
                Successfully updated your Profile Information.
            </ActionMessage>
        </template>
    </FormSection>
</template>
