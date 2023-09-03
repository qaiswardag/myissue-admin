<script setup>
import { computed, ref } from "vue";
import UploadImagesForm from "@/Components/Forms/UploadImagesForm.vue";
import MediaLibraryGalleryList from "@/Components/GalleryList/MediaLibraryGalleryList.vue";
import {
    Dialog,
    DialogOverlay,
    DialogTitle,
    TransitionChild,
    TransitionRoot,
} from "@headlessui/vue";
import { Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";

// new version
import { useForm } from "@inertiajs/vue3";
import SubmitButton from "@/Components/Buttons/SubmitButton.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import { useStore } from "vuex";
import DynamicModal from "@/Components/Modals/DynamicModal.vue";

// store
const store = useStore();

const modalShowDeleteImage = ref(false);

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

const getCurrentImage = computed(() => {
    return store.getters["mediaLibrary/getCurrentImage"];
});

const getCurrentMedia = computed(() => {
    return store.getters["mediaLibrary/getCurrentMedia"];
});

const getCurrentPreviewImage = computed(() => {
    return store.getters["mediaLibrary/getCurrentPreviewImage"];
});

const search_query = ref("");

const selected = ref("Media library");

const tabs = ref([
    {
        name: "Upload",
        current: true,
    },
    {
        name: "Media library",
        current: false,
    },
]);

const props = defineProps({
    team: {
        required: true,
    },
    user: {
        required: true,
    },
    title: {
        type: String,
        required: true,
    },
    description: {
        required: true,
    },
    firstButtonText: {
        type: String,
    },
    secondButtonText: {
        type: String,
    },
    thirdButtonText: {
        type: String,
    },
    open: {
        required: true,
    },
});

const emit = defineEmits([
    "firstMediaButtonFunction",
    "secondMediaButtonFunction",
    "thirdMediaButtonFunction",
]);

// first button function
const firstButton = function () {
    emit("firstMediaButtonFunction");
};

// second button  function
const secondButton = function () {
    emit("secondMediaButtonFunction");
};

// third button function
const thirdButton = function () {
    emit("thirdMediaButtonFunction");
};
//
//
const changeSelectedMenuTab = function (clicked) {
    selected.value = clicked;
};
//
const uploadOnSuccess = function () {
    selected.value = "Media library";
};
//
//
// form
const formDeleteImage = useForm({
    image_id: null,
});
// form
const form = useForm({
    name: "",
    image_id: null,
});
//
//
const handleImageUpdate = function (imageId) {
    // search query
    search_query.value = getCurrentMedia.value?.fetchedMedia?.search_query;

    //  page = getCurrentMedia.value.fetchedMedia.current_clicked_page;
    let currentClickedPage =
        getCurrentMedia.value?.fetchedMedia?.current_clicked_page;

    // set image id
    form.image_id = imageId;

    form.post(route("media.update", [props.team.id]), {
        preserveScroll: true,
        onSuccess: () => {
            // reset form
            form.reset();
            // dispatch
            store.dispatch("mediaLibrary/loadImage", {
                mediaLibraryId: imageId,
                teamId: props.team.id,
            });

            //
            // dispatch
            store.dispatch("mediaLibrary/loadMedia", {
                team: props.team,
                page: currentClickedPage,
                search_query: search_query.value,
            });
        },
        onError: (err) => {},
        onFinish: () => {},
    });
};

const handleDeleteImage = function (imageId) {
    modalShowDeleteImage.value = true;
    // set modal standards
    typeModal.value = "delete";
    gridColumnModal.value = 3;
    titleModal.value = `Delete Image`;
    descriptionModal.value = `Are you sure you want to delete this image?`;
    firstButtonModal.value = "Close";
    secondButtonModal.value = null;
    thirdButtonModal.value = "Delete Image";

    // handle click
    firstModalButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowDeleteImage.value = false;
    };

    // handle click
    thirdModalButtonFunction.value = function () {
        modalShowDeleteImage.value = false;

        deleteImage(imageId);
    };
};

const deleteImage = function (imageId) {
    // search query
    search_query.value = getCurrentMedia.value?.fetchedMedia?.search_query;
    let currentClickedPage =
        getCurrentMedia.value?.fetchedMedia?.current_clicked_page;
    // set image id
    formDeleteImage.image_id = imageId;

    formDeleteImage.post(route("media.destroy", [props.team.id]), {
        preserveScroll: true,
        onSuccess: () => {
            // commit
            store.commit("mediaLibrary/setCurrentImage", null);
            // dispatch
            store.dispatch("mediaLibrary/loadMedia", {
                team: props.team,
                page: currentClickedPage,
                search_query: search_query.value,
            });
        },
        onError: (err) => {},
        onFinish: () => {},
    });
};
</script>

<template>
    <teleport to="body">
        <TransitionRoot :show="open" as="template">
            <Dialog
                as="div"
                class="fixed z-30 inset-0 overflow-y-auto sm:px-4 py-6"
                @close="firstButton"
            >
                <div
                    class="flex items-end justify-center pb-20 text-center sm:block sm:p-0 p-4"
                >
                    <TransitionChild
                        as="template"
                        enter="ease-out duration-300"
                        enter-from="opacity-0"
                        enter-to="opacity-100"
                        leave="ease-in duration-200"
                        leave-from="opacity-100"
                        leave-to="opacity-0"
                    >
                        <DialogOverlay
                            class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                        />
                    </TransitionChild>

                    <!-- This element is to trick the browser into centering the modal contents. -->
                    <span
                        aria-hidden="true"
                        class="hidden sm:inline-block sm:align-middle sm:h-screen"
                        >&#8203;</span
                    >
                    <TransitionChild
                        as="template"
                        enter="ease-out duration-300"
                        enter-from="opacity-0 scale-95"
                        enter-to="opacity-100 scale-100"
                        leave="ease-in duration-200"
                        leave-from="opacity-100 scale-100"
                        leave-to="opacity-0 scale-95"
                    >
                        <div
                            class="relative w-full min-h-[61rem] max-h-[61rem] inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:align-middle sm:max-w-7xl sm:w-full sm:p-6"
                        >
                            <div
                                class="flex gap-2 justify-between items-center border-b border-gray-200 pb-2 mb-2"
                            >
                                <DynamicModal
                                    :show="modalShowDeleteImage"
                                    :type="typeModal"
                                    :disabled="formDeleteImage.processing"
                                    disabledWhichButton="thirdButton"
                                    :gridColumnAmount="gridColumnModal"
                                    :title="titleModal"
                                    :description="descriptionModal"
                                    :firstButtonText="firstButtonModal"
                                    :secondButtonText="secondButtonModal"
                                    :thirdButtonText="thirdButtonModal"
                                    @firstModalButtonFunction="
                                        firstModalButtonFunction
                                    "
                                    @secondModalButtonFunction="
                                        secondModalButtonFunction
                                    "
                                    @thirdModalButtonFunction="
                                        thirdModalButtonFunction
                                    "
                                >
                                    <header></header>
                                    <main></main>
                                </DynamicModal>
                                <DialogTitle
                                    as="h3"
                                    class="tertiaryHeader my-0 py-0"
                                >
                                    {{ title }}
                                    {{ team.name }}
                                </DialogTitle>

                                <div class="flex-end">
                                    <span
                                        class="h-6 w-6 text-gray-700 self-center cursor-pointer"
                                        @click="firstButton"
                                        ><svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.5"
                                            stroke="currentColor"
                                            class="w-6 h-6"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M6 18L18 6M6 6l12 12"
                                            />
                                        </svg>
                                    </span>
                                </div>
                            </div>

                            <div class="flex items-center pb-2 mb-2">
                                <div class="flex-1">
                                    <!--content media library - start-->
                                    <div
                                        class="h-full flex md:flex-row flex-col myPrimaryGap mt-2 p-2 overflow-y-scroll"
                                    >
                                        <!-- Main content - start-->
                                        <main class="flex-1 relativ">
                                            <div class="pb-4 max-w-7xl mx-auto">
                                                <!-- Tabs -->
                                                <div class="mb-2">
                                                    <!-- Tabs Mobile -->
                                                    <div class="sm:hidden">
                                                        <label
                                                            for="tabs"
                                                            class="sr-only"
                                                            >Select a tab</label
                                                        >
                                                        <!-- Use an "onChange" listener to redirect the user to the selected tab URL. -->

                                                        <select
                                                            v-model="selected"
                                                            id="tabs"
                                                            class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-myPrimaryLinkColor focus:border-myPrimaryLinkColor sm:text-sm rounded-md"
                                                        >
                                                            <option>
                                                                Upload
                                                            </option>
                                                            <option>
                                                                Media library
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div
                                                        class="hidden sm:block"
                                                    >
                                                        <div
                                                            class="flex items-center border border-gray-200 py-1 px-2 rounded"
                                                        >
                                                            <nav
                                                                class="flex-1 -mb-px flex space-x-6 xl:space-x-8"
                                                                aria-label="Tabs"
                                                            >
                                                                <div
                                                                    @click="
                                                                        changeSelectedMenuTab(
                                                                            tab.name
                                                                        )
                                                                    "
                                                                    v-for="tab in tabs"
                                                                    :key="
                                                                        tab.name
                                                                    "
                                                                    :aria-current="
                                                                        tab.current
                                                                            ? 'page'
                                                                            : undefined
                                                                    "
                                                                    class="py-2 px-3 my-1 text-xs cursor-pointer"
                                                                    :class="[
                                                                        tab.name ===
                                                                        selected
                                                                            ? 'myPrimaryButton'
                                                                            : 'text-gray-500 hover:text-gray-700 ',
                                                                        'whitespace-nowrap',
                                                                    ]"
                                                                >
                                                                    {{
                                                                        tab.name
                                                                    }}
                                                                </div>
                                                            </nav>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div
                                                    v-if="selected === 'Upload'"
                                                >
                                                    <!-- image upload - start -->
                                                    <UploadImagesForm
                                                        :team="team"
                                                        :user="user"
                                                        @uploadOnSuccess="
                                                            uploadOnSuccess
                                                        "
                                                    ></UploadImagesForm>
                                                    <!-- image upload - end -->
                                                </div>
                                                <div
                                                    v-if="
                                                        selected ===
                                                        'Media library'
                                                    "
                                                >
                                                    <!-- image gallary - start -->
                                                    <MediaLibraryGalleryList
                                                        :team="team"
                                                    ></MediaLibraryGalleryList>
                                                    <!-- image gallary - end -->
                                                </div>
                                            </div>
                                        </main>
                                        <!-- Main content - end-->

                                        <!-- Details sidebar - media library start-->
                                        <aside
                                            v-if="selected === 'Media library'"
                                            aria-label="sidebar"
                                            class="md:w-72 relative md:min-h-[55rem] md:max-h-[55rem] min-h-[22rem] max-h-[22rem]"
                                        >
                                            <div
                                                class="md:w-72 md:min-h-[50.8rem] md:max-h-[50.8rem] min-h-[15rem] max-h-[15rem] overflow-y-scroll bg-white border border-gray-200 rounded"
                                            >
                                                <div
                                                    v-if="
                                                        (getCurrentImage &&
                                                            Object.keys(
                                                                getCurrentImage
                                                            ).length === 0) ||
                                                        getCurrentImage === null
                                                    "
                                                    class="pb-6 space-y-6 md:px-3 px-2 pt-2"
                                                >
                                                    <p
                                                        class="myPrimaryParagraph text-xs p-2"
                                                    >
                                                        No image selected
                                                    </p>
                                                </div>
                                                <div
                                                    v-if="
                                                        getCurrentImage &&
                                                        getCurrentImage.isSuccess ===
                                                            false &&
                                                        getCurrentImage.isLoading ===
                                                            false &&
                                                        getCurrentImage.isError ===
                                                            true
                                                    "
                                                    class="myPrimaryParagraphError"
                                                >
                                                    {{ getCurrentImage.error }}
                                                </div>

                                                <!--  -->
                                                <div
                                                    v-if="
                                                        getCurrentImage &&
                                                        getCurrentImage.isLoading ===
                                                            true &&
                                                        getCurrentImage.isError ===
                                                            false
                                                    "
                                                    class="mx-auto block w-full rounded-sm object-cover object-center cursor-pointer"
                                                >
                                                    <div
                                                        class="flex items-center justify-center pt-12"
                                                    >
                                                        <div
                                                            class="inline-block h-8 w-8 animate-spin rounded-full border-4 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"
                                                            role="status"
                                                        >
                                                            <span
                                                                class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]"
                                                                >Loading...</span
                                                            >
                                                        </div>
                                                    </div>
                                                </div>

                                                <div
                                                    v-if="
                                                        getCurrentImage &&
                                                        getCurrentImage.currentImage &&
                                                        getCurrentImage
                                                            .currentImage
                                                            .mediaLibrary &&
                                                        getCurrentImage.isSuccess ===
                                                            true &&
                                                        getCurrentImage.isLoading ===
                                                            false &&
                                                        getCurrentImage.isError ===
                                                            false
                                                    "
                                                    class="pb-6 space-y-6"
                                                >
                                                    <a
                                                        :href="`/storage/uploads/${getCurrentImage.currentImage.mediaLibrary.path.toLowerCase()}`"
                                                        target="_blank"
                                                    >
                                                        <img
                                                            class="mx-auto block w-full rounded-t-sm object-cover object-center cursor-pointer"
                                                            :src="`/storage/uploads/${getCurrentImage.currentImage.mediaLibrary.medium_path}`"
                                                            alt="image"
                                                        />
                                                    </a>

                                                    <div class="md:px-3 px-2">
                                                        <div>
                                                            <h2
                                                                class="mySecondaryHeader py-2 break-words"
                                                            >
                                                                {{
                                                                    getCurrentImage
                                                                        .currentImage
                                                                        .mediaLibrary
                                                                        .name
                                                                        ? getCurrentImage
                                                                              .currentImage
                                                                              .mediaLibrary
                                                                              .name
                                                                        : "–"
                                                                }}
                                                            </h2>

                                                            <form
                                                                @submit.prevent="
                                                                    handleImageUpdate(
                                                                        getCurrentImage
                                                                            .currentImage
                                                                            .mediaLibrary
                                                                            .id
                                                                    )
                                                                "
                                                            >
                                                                <div
                                                                    class="myInputsOrganization mt-2 mb-8 pt-4 pr-0 pb-4 pl-0 border-b border-myPrimaryMediumGrayColor rounded-none"
                                                                >
                                                                    <InputLabel
                                                                        for="name"
                                                                        value="Image name"
                                                                    />
                                                                    <div
                                                                        class="flex gap-2 justify-center flex-col"
                                                                    >
                                                                        <div
                                                                            class="flex gap-2 items-center"
                                                                        >
                                                                            <div
                                                                                class="mt-1 relative flex items-center w-full border myPrimaryInput py-0 p-0"
                                                                            >
                                                                                <input
                                                                                    placeholder="Image name.."
                                                                                    id="title"
                                                                                    v-model="
                                                                                        form.name
                                                                                    "
                                                                                    type="text"
                                                                                    autofocus
                                                                                    autocomplete="off"
                                                                                    class="myPrimaryInput border-none rounded-r-none ml-0 w-full"
                                                                                    @keydown.enter.tab.prevent="
                                                                                        handleImageUpdate(
                                                                                            getCurrentImage
                                                                                                .currentImage
                                                                                                .mediaLibrary
                                                                                                .id
                                                                                        )
                                                                                    "
                                                                                />
                                                                                <div
                                                                                    class="border border-gray-200 border-none rounded flex items-center justify-center h-full w-8"
                                                                                >
                                                                                    <kbd
                                                                                        class="text-xs font-sans font-normal text-gray-400 border-none"
                                                                                    >
                                                                                        ⏎
                                                                                    </kbd>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <p
                                                                            v-if="
                                                                                form.errors
                                                                            "
                                                                            class="myPrimaryInputError"
                                                                        >
                                                                            {{
                                                                                form
                                                                                    .errors
                                                                                    .name
                                                                            }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <div>
                                                            <h3
                                                                class="font-normal text-gray-900"
                                                            >
                                                                Information
                                                            </h3>
                                                            <dl
                                                                class="mt-2 border-t border-b border-gray-200 divide-y divide-gray-200"
                                                            >
                                                                <div
                                                                    class="py-3 flex justify-between text-sm font-normal items-center"
                                                                >
                                                                    <dt
                                                                        class="text-gray-500"
                                                                    >
                                                                        Dimensions
                                                                    </dt>
                                                                    <dd
                                                                        class="text-gray-900"
                                                                    >
                                                                        {{
                                                                            getCurrentImage
                                                                                .currentImage
                                                                                .mediaLibrary
                                                                                .width
                                                                        }}
                                                                        x
                                                                        {{
                                                                            getCurrentImage
                                                                                .currentImage
                                                                                .mediaLibrary
                                                                                .height
                                                                        }}
                                                                    </dd>
                                                                </div>

                                                                <div
                                                                    class="py-3 flex justify-between text-sm font-normal items-center"
                                                                >
                                                                    <dt
                                                                        class="text-gray-500"
                                                                    >
                                                                        Size
                                                                    </dt>
                                                                    <dd
                                                                        class="text-gray-900"
                                                                    >
                                                                        {{
                                                                            getCurrentImage
                                                                                .currentImage
                                                                                .mediaLibrary
                                                                                .size
                                                                        }}
                                                                        KB
                                                                    </dd>
                                                                </div>
                                                                <div
                                                                    class="py-3 flex justify-between text-sm font-normal items-center"
                                                                >
                                                                    <dt
                                                                        class="text-gray-500"
                                                                    >
                                                                        Extension
                                                                    </dt>
                                                                    <dd
                                                                        class="text-gray-900"
                                                                    >
                                                                        {{
                                                                            getCurrentImage
                                                                                .currentImage
                                                                                .mediaLibrary
                                                                                .extension
                                                                        }}
                                                                    </dd>
                                                                </div>
                                                                <div
                                                                    class="py-3 flex justify-between text-sm font-normal items-center"
                                                                >
                                                                    <dt
                                                                        class="text-gray-500"
                                                                    >
                                                                        Image Id
                                                                    </dt>
                                                                    <dd
                                                                        class="text-gray-900"
                                                                    >
                                                                        {{
                                                                            getCurrentImage
                                                                                .currentImage
                                                                                .mediaLibrary
                                                                                .id
                                                                        }}
                                                                    </dd>
                                                                </div>
                                                                <div
                                                                    class="py-3 flex justify-between text-sm font-normal items-center"
                                                                >
                                                                    <dt
                                                                        class="text-gray-500"
                                                                    >
                                                                        Uploaded
                                                                        by
                                                                    </dt>
                                                                    <dd
                                                                        class="text-gray-900"
                                                                    >
                                                                        {{
                                                                            getCurrentImage
                                                                                .currentImage
                                                                                .uploadedBy
                                                                                .firstName
                                                                        }}
                                                                        {{
                                                                            getCurrentImage
                                                                                .currentImage
                                                                                .uploadedBy
                                                                                .lastName
                                                                        }}
                                                                    </dd>
                                                                </div>
                                                                <div
                                                                    class="py-3 flex justify-between text-sm font-normal items-center"
                                                                >
                                                                    <dt
                                                                        class="text-myPrimaryErrorColor"
                                                                    >
                                                                        Delete
                                                                    </dt>
                                                                    <dd
                                                                        class="text-gray-900"
                                                                    >
                                                                        <form
                                                                            @submit.prevent="
                                                                                handleDeleteImage(
                                                                                    getCurrentImage
                                                                                        .currentImage
                                                                                        .mediaLibrary
                                                                                        .id
                                                                                )
                                                                            "
                                                                        >
                                                                            <SubmitButton
                                                                                :ButtonStyleDelete="
                                                                                    true
                                                                                "
                                                                                :TableStyle="
                                                                                    true
                                                                                "
                                                                                :disabled="
                                                                                    false
                                                                                "
                                                                                buttonText=""
                                                                                type="button"
                                                                                @firstButtonClick="
                                                                                    handleDeleteImage(
                                                                                        getCurrentImage
                                                                                            .currentImage
                                                                                            .mediaLibrary
                                                                                            .id
                                                                                    )
                                                                                "
                                                                            >
                                                                                <svg
                                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                                    fill="none"
                                                                                    viewBox="0 0 24 24"
                                                                                    stroke-width="1.5"
                                                                                    stroke="currentColor"
                                                                                    class="w-5 h-5"
                                                                                >
                                                                                    <path
                                                                                        stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"
                                                                                    />
                                                                                </svg>
                                                                            </SubmitButton>
                                                                        </form>
                                                                    </dd>
                                                                </div>
                                                            </dl>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                v-if="
                                                    getCurrentImage &&
                                                    getCurrentImage.currentImage &&
                                                    getCurrentImage.currentImage
                                                        .mediaLibrary &&
                                                    getCurrentImage.isLoading ===
                                                        false &&
                                                    (getCurrentImage.isError ===
                                                        null ||
                                                        getCurrentImage.isError ===
                                                            false)
                                                "
                                                class="absolute bottom-0 right-0 left-0 px-2 my-2 flex gap-2 justify-end"
                                            >
                                                <div v-if="firstButtonText">
                                                    <button
                                                        ref="firstButtonRef"
                                                        class="mySecondaryButton"
                                                        type="button"
                                                        @click="firstButton"
                                                    >
                                                        {{ firstButtonText }}
                                                    </button>
                                                </div>

                                                <div v-if="secondButtonText">
                                                    <button
                                                        class="myPrimaryButton"
                                                        type="button"
                                                        @click="secondButton"
                                                    >
                                                        {{ secondButtonText }}
                                                    </button>
                                                </div>

                                                <div
                                                    v-if="thirdButtonText"
                                                    class="w-full"
                                                >
                                                    <button
                                                        class="myPrimaryDeleteButton"
                                                        type="button"
                                                        @click="thirdButton"
                                                    >
                                                        {{ thirdButtonText }}
                                                    </button>
                                                </div>
                                            </div>
                                        </aside>
                                        <!-- Details sidebar - media library end-->
                                        <!-- Details sidebar - upload start-->
                                        <aside
                                            v-if="selected === 'Upload'"
                                            aria-label="sidebar"
                                            class="md:w-72 md:min-h-[50.6rem] md:max-h-[50.6rem] min-h-[15rem] max-h-[15rem] overflow-y-scroll bg-white border border-gray-200 rounded"
                                        >
                                            <div
                                                v-if="
                                                    getCurrentPreviewImage ===
                                                    null
                                                "
                                                class="pb-6 space-y-6 md:px-3 px-2 pt-2"
                                            >
                                                <p
                                                    class="myPrimaryParagraph text-xs p-2"
                                                >
                                                    No image selected
                                                </p>
                                            </div>
                                            <div
                                                v-if="
                                                    getCurrentPreviewImage !==
                                                    null
                                                "
                                                class="pb-6 space-y-6"
                                            >
                                                <img
                                                    :src="
                                                        getCurrentPreviewImage.preview_url
                                                    "
                                                    class="mx-auto block h-72 w-full rounded-sm object-cover object-center cursor-pointer hover:shadow-sm"
                                                    alt="image"
                                                />

                                                <div class="md:px-3 px-2">
                                                    <div>
                                                        <h2
                                                            class="mySecondaryHeader py-2 break-words"
                                                        >
                                                            {{
                                                                getCurrentPreviewImage.file_name
                                                            }}
                                                        </h2>
                                                    </div>

                                                    <div>
                                                        <h3
                                                            class="font-normal text-gray-900"
                                                        >
                                                            Information
                                                        </h3>
                                                        <dl
                                                            class="mt-2 border-t border-b border-gray-200 divide-y divide-gray-200"
                                                        >
                                                            <div
                                                                class="py-3 flex justify-between text-sm font-normal items-center"
                                                            >
                                                                <dt
                                                                    class="text-gray-500"
                                                                >
                                                                    Size
                                                                </dt>
                                                                <dd
                                                                    class="text-gray-900"
                                                                >
                                                                    {{
                                                                        Number(
                                                                            getCurrentPreviewImage.file_size
                                                                        ).toFixed(
                                                                            0
                                                                        )
                                                                    }}
                                                                    KB
                                                                </dd>
                                                            </div>
                                                        </dl>
                                                    </div>
                                                </div>
                                            </div>
                                        </aside>
                                        <!-- Details sidebar end-->
                                    </div>
                                    <!--content media library - end-->
                                </div>
                            </div>

                            <div v-if="description">
                                <div
                                    v-html="description"
                                    class="text-sm text-gray-600 mt-4 mb-10"
                                ></div>
                                <header>
                                    <slot name="header"></slot>
                                </header>
                                <main>
                                    <slot></slot>
                                </main>
                            </div>
                        </div>
                    </TransitionChild>
                </div>
            </Dialog>
        </TransitionRoot>
    </teleport>
</template>
