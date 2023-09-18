<script setup>
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import { useForm } from "@inertiajs/vue3";
import FormSection from "@/Components/Forms/FormSection.vue";
import InputError from "@/Components/Forms/InputError.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import SubmitButton from "@/Components/Buttons/SubmitButton.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import { QuillEditor } from "@vueup/vue-quill";
import "@vueup/vue-quill/dist/vue-quill.snow.css";
import { ref, computed, onBeforeMount, watch } from "vue";
import { Switch } from "@headlessui/vue";
import NotificationsFixedBottom from "@/Components/Modals/NotificationsFixedBottom.vue";
import Tags from "@/Components/Forms/Tags.vue";
import MediaLibraryModal from "@/Components/Modals/MediaLibraryModal.vue";
import { useStore } from "vuex";
import slugify from "slugify";
import config from "@/utils/config";
import SearchUserModal from "@/Components/Search/SearchUserModal.vue";
import { router } from "@inertiajs/vue3";
import DynamicModal from "@/Components/Modals/DynamicModal.vue";
import {
    Listbox,
    ListboxButton,
    ListboxLabel,
    ListboxOption,
    ListboxOptions,
} from "@headlessui/vue";

import {
    CheckIcon,
    ChevronUpDownIcon,
    TrashIcon,
    XMarkIcon,
} from "@heroicons/vue/24/outline";

const props = defineProps({
    currentUserTeamRole: {
        required: false,
    },
    currentUserTeam: {
        required: true,
    },
    user: {
        required: true,
    },
    post: {
        default: null,
        required: false,
    },
    postAuthor: {
        default: null,
        required: false,
    },
});

const modalShowClearForm = ref(false);

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

// Check if the user has a role other than 'reader' for this team
const userTeamsWithoutReaderRole = props.user.all_teams.filter((team) => {
    if (team.membership !== undefined && team.membership.role === "reader") {
        return team.membership.role !== "reader";
    }

    return team;
});

// store
const store = useStore();

const getCurrentImage = computed(() => {
    return store.getters["mediaLibrary/getCurrentImage"];
});
const getCurrentAttachedUsers = computed(() => {
    return store.getters["attachedUsers/getCurrentAttachedUsers"];
});

const formType = ref("create");
const pathLocalStorage = `post-form-${
    props.currentUserTeam ? props.currentUserTeam.reference_id : null
}`;

// use media library
const showMediaLibraryModal = ref(false);
// modal content
const titleMedia = ref("");
const descriptionMedia = ref("");
const firstButtonMedia = ref("");
const secondButtonMedia = ref(null);
const thirdButtonMedia = ref(null);
// set dynamic modal handle functions
const firstMediaButtonFunction = ref(null);
const secondMediaButtonFunction = ref(null);
const thirdMediaButtonFunction = ref(null);

const handleUploadCoverImage = function () {
    // handle show media library modal
    showMediaLibraryModal.value = true;

    // set media library modal standards
    titleMedia.value = "Media Library";
    descriptionMedia.value = null;
    firstButtonMedia.value = "Close";
    secondButtonMedia.value = "Select image";
    thirdButtonMedia.value = null;
    // handle click
    firstMediaButtonFunction.value = function () {
        // handle show media library modal
        showMediaLibraryModal.value = false;
    };
    //
    // handle click
    secondMediaButtonFunction.value = function () {
        postForm.cover_image_original =
            getCurrentImage.value.currentImage?.mediaLibrary?.path;
        postForm.cover_image_thumbnail =
            getCurrentImage.value.currentImage?.mediaLibrary?.thumbnail_path;
        postForm.cover_image_medium =
            getCurrentImage.value.currentImage?.mediaLibrary?.medium_path;
        postForm.cover_image_large =
            getCurrentImage.value.currentImage?.mediaLibrary?.large_path;

        // handle show media library modal
        showMediaLibraryModal.value = false;
    };
    // end modal
};
const handleRemoveCoverImage = function () {
    postForm.cover_image_original = null;
    postForm.cover_image_thumbnail = null;
    postForm.cover_image_medium = null;
    postForm.cover_image_large = null;
};

const modalShowAddAuthor = ref(false);

// modal content
const titleModalSearchAuthor = ref("");
const descriptionModalSearchAuthor = ref("");
const firstButtonModalSearchAuthor = ref("");
const secondButtonModalSearchAuthor = ref(null);
// set dynamic modal handle functions
const firstModalButtonSearchAuthorFunction = ref(null);
const secondModalButtonSearchAuthorFunction = ref(null);

const handleAddAuthor = function () {
    // handle show modal for unique content
    modalShowAddAuthor.value = true;
    // set modal standards
    titleModalSearchAuthor.value = "Add author";
    descriptionModalSearchAuthor.value = "Add Post author";
    firstButtonModalSearchAuthor.value = "Close";
    secondButtonModalSearchAuthor.value = "Save";
    // handle click
    firstModalButtonSearchAuthorFunction.value = function () {
        // handle show modal for unique content
        modalShowAddAuthor.value = false;
    };
    // handle click
    secondModalButtonSearchAuthorFunction.value = function () {
        // reasons why using the spread operator to create a non-reactive copy of an
        // array is generally considered better than using JSON.stringify
        // and JSON.parse to achieve the same result:

        // 1. Performance:
        // The spread operator is generally faster than using JSON.stringify and JSON.parse
        // to create a non-reactive copy of an array.
        // This is because the spread operator is a built-in JavaScript feature,
        // whereas JSON.stringify and JSON.parse require parsing and serializing data,
        // which can be computationally expensive for large arrays.

        // 2. Maintainability:
        // Using the spread operator to create a non-reactive copy of an array is more
        // maintainable than using JSON.stringify and JSON.parse because it is more
        // concise and easier to read. The spread operator clearly indicates that a new array
        // is being created, whereas the JSON.stringify and JSON.parse approach requires
        // multiple function calls and string manipulations,
        // which can be harder to read and understand.

        // 3. Compatibility: The spread operator is a standard JavaScript feature that is
        // supported by all modern browsers and Node.js, whereas JSON.stringify and JSON.parse
        // may not be available in older browsers or
        // environments that do not support ECMAScript 5.
        const currentAttachedUsers = [...getCurrentAttachedUsers.value];
        // Set post form author to the non-reactive copy
        postForm.author = currentAttachedUsers;

        // handle show modal for unique content
        modalShowAddAuthor.value = false;
    };

    // end modal
};

const filteredUsers = ref([]);
const handleRemoveAttachedUser = function (userId) {
    // filter the array to exclude user with matching ID
    postForm.author = postForm.author.filter((user) => user.id !== userId);
};

const showErrorNotifications = ref(false);

const notificationsModalButton = function () {
    showErrorNotifications.value = false;
};

// start Quill Editor
// define options
const globalOptions = {
    // debug: "info",
    modules: {
        toolbar: ["bold", "italic", "underline"],
    },
    placeholder: "Compose an epic...",
    readOnly: false,
    theme: "snow",

    //
    //
    toolbar: [
        [{ header: [1, 2, 3, 4, 5, 6, false] }],
        ["bold", "italic", "underline"], // toggled buttons

        [{ list: "ordered" }, { list: "bullet" }],

        [{ align: [] }],

        ["clean"], // remove formatting button
    ],
};
// end Quill Editor

const isSlugEditable = ref(false);
const slugValueTitle = ref("");
const slugValueCustom = ref("");

const postForm = useForm({
    title: "",
    slug: "",
    content: "",
    published: true,
    team: props.currentUserTeam,
    user_id: props.user.id,

    cover_image_original: "",
    cover_image_thumbnail: "",
    cover_image_medium: "",
    cover_image_large: "",

    tags: "",
    show_author: true,
    author: [],
});

// The above code uses the watch function from Vue 3 to watch for changes to the
// slugValueCustom property and update the postForm.slug field with the new value

const watchSlugInputChanges = function () {
    watch(
        () => slugValueCustom.value,
        (newValue) => {
            if (isSlugEditable.value === true) {
                postForm.slug = slugify(
                    slugValueCustom.value,
                    config.slugifyOptions
                );
            }
        },
        { immediate: true }
    );
    watch(
        () => postForm.title,
        (newValue) => {
            if (isSlugEditable.value === false) {
                postForm.slug = slugify(postForm.title, config.slugifyOptions);
                slugValueTitle.value = slugify(
                    postForm.title,
                    config.slugifyOptions
                );
            }
        },
        { immediate: true }
    );
};

watch(
    () => isSlugEditable.value,
    (newValue) => {
        watchSlugInputChanges();
    },
    { immediate: true }
);

const handleCloseLock = function () {
    isSlugEditable.value = false;
};
const handleOpenLock = function () {
    isSlugEditable.value = true;
};
const handleTags = function (tags) {
    postForm.tags = tags;
};
const handleCreatePost = function () {
    // try to store post
    createPost();
};

const createPost = () => {
    if (formType.value === "create") {
        postForm.post(route("team.posts.store"), {
            preserveScroll: true,
            onSuccess: () => {
                clearForm();
            },
            onError: () => {},
            onFinish: () => {},
        });
    }
    if (formType.value === "update") {
        postForm.post(route("team.posts.update", props.post.id), {
            preserveScroll: true,
            onSuccess: () => {},
            onError: () => {},
            onFinish: () => {},
        });
    }
};

const handleClearForm = function () {
    // handle show modal for unique content
    modalShowClearForm.value = true;
    // set modal standards
    typeModal.value = "warning";
    gridColumnModal.value = 2;
    titleModal.value = `Are you certain that you want to clear the form?`;
    descriptionModal.value =
        "Are you certain that you want to clear the form? The form will be cleared.";
    firstButtonModal.value = "Close";
    secondButtonModal.value = null;
    thirdButtonModal.value = "Clear form";
    // handle click
    firstModalButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowClearForm.value = false;
    };
    // handle click
    thirdModalButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowClearForm.value = false;
        clearForm();
    };
};
// end modal

const clearTags = ref(0);
// clear form
const clearForm = function () {
    postForm.title = "";
    // slug
    postForm.slug = "";
    isSlugEditable.value = false;
    slugValueTitle.value = "";
    slugValueCustom.value = "";
    postForm.content = "";
    postForm.published = true;
    postForm.team = props.currentUserTeam;
    postForm.user_id = props.user.id;

    postForm.cover_image_original = "";
    postForm.cover_image_thumbnail = "";
    postForm.cover_image_medium = "";
    postForm.cover_image_large = "";

    //
    //
    postForm.tags = "";
    clearTags.value++;
    //
    //
    //
    postForm.show_author = true;
    postForm.author = [];

    localStorage.removeItem(pathLocalStorage);
};

// is form dirty? returns true or false
const formIsDirty = computed(() => {
    return postForm.isDirty;
});

const storeDirtyFormInLocalStorage = function () {
    if (formIsDirty.value === true && formType.value === "create") {
        // Convert the form data to a JSON string
        postForm.isSlugEditable = isSlugEditable.value;
        const formDataJson = JSON.stringify(postForm);
        // Save the form data to local storage using the form ID as the key
        localStorage.setItem(pathLocalStorage, formDataJson);
    }
};
// Will be executed when the user switch current route
router.on = () => {
    storeDirtyFormInLocalStorage();
};
// This function will be executed when the user clicks refresh or closes the tab/window
window.addEventListener("beforeunload", function () {
    storeDirtyFormInLocalStorage();
});

// get unique post if needs to be updated
onBeforeMount(() => {
    // User is creating a new Resource from scratch, rather than editing an existing one
    // Check local storage
    if (props.post === null) {
        if (localStorage.getItem(pathLocalStorage) !== null) {
            // Get the saved form data from local storage using the form ID as the key
            const formDataJson = localStorage.getItem(pathLocalStorage);
            let formLocalStorage = JSON.parse(formDataJson);
            //
            isSlugEditable.value = formLocalStorage.isSlugEditable;
            //
            if (formLocalStorage.isSlugEditable === false) {
                slugValueTitle.slug = formLocalStorage.title;
            }
            //
            if (formLocalStorage.isSlugEditable === true) {
                slugValueCustom.value = formLocalStorage.slug;
            }
            //
            postForm.title = formLocalStorage.title;
            postForm.content = formLocalStorage.content;
            postForm.published = formLocalStorage.published;
            postForm.show_author = formLocalStorage.show_author;
            postForm.cover_image_original =
                formLocalStorage.cover_image_original;
            postForm.cover_image_thumbnail =
                formLocalStorage.cover_image_thumbnail;
            postForm.cover_image_medium = formLocalStorage.cover_image_medium;
            postForm.cover_image_large = formLocalStorage.cover_image_large;
            postForm.tags = formLocalStorage.tags;

            if (
                formLocalStorage.author === undefined ||
                formLocalStorage.author === null
            ) {
                postForm.author = [];
            }
            if (
                formLocalStorage.author !== undefined ||
                formLocalStorage.author !== null
            ) {
                postForm.author = formLocalStorage.author;
            }
        }
    }

    // User is editing an existing Resource, rather than creating a new one from scratch.
    if (props.post !== null) {
        formType.value = "update";

        postForm.title = props.post.title;
        // slug logic
        // slug is editable when editing an existing post
        isSlugEditable.value = true;
        slugValueCustom.value = props.post.slug;

        postForm.content = props.post.content;
        postForm.published = props.post.published === 1 ? true : false;
        postForm.show_author = props.post.show_author === 1 ? true : false;

        postForm.cover_image_original = props.post.cover_image_original;
        postForm.cover_image_thumbnail = props.post.cover_image_thumbnail;
        postForm.cover_image_medium = props.post.cover_image_medium;
        postForm.cover_image_large = props.post.cover_image_large;

        postForm.tags = props.post.tags;

        // check if the post author is available and should be displayed
        if (props.post.show_author === 1 && props.postAuthor !== null) {
            // add the author to the postForm
            postForm.author = props.postAuthor;
        }

        // check if the post author is not available or should not be displayed
        if (props.post.show_author === 1 && props.postAuthor === null) {
            // clear the postForm author field
            postForm.author = [];
        }
    }
});
</script>

<template>
    <FormSection @submitted="handleCreatePost">
        <template #title> Post details</template>
        <template #description> Create a new Post. </template>
        <template #main>
            <div class="myInputsOrganization">
                <div class="myPrimaryFormOrganizationHeaderDescriptionSection">
                    <div class="myPrimaryFormOrganizationHeader">
                        Title & description
                    </div>
                    <p class="myPrimaryParagraph">
                        Specify title & description.
                    </p>
                </div>
                <!-- post title start -->
                <div class="myInputGroup">
                    <InputLabel for="title" value="Your Post title" />
                    <TextInput
                        placeholder="Enter your title.."
                        id="title"
                        v-model="postForm.title"
                        type="text"
                        class="block w-full mt-1"
                        autofocus
                        autocomplete="off"
                    />
                    <InputError :message="postForm.errors.title" />
                </div>
                <!-- post title end -->
                <!-- post slug start -->
                <div class="myInputGroup">
                    <div v-show="isSlugEditable === false">
                        <InputLabel for="slug" value="Slug" />
                        <div class="relative flex items-center">
                            <TextInput
                                placeholder="Post slug"
                                id="slug"
                                v-model="slugValueTitle"
                                type="text"
                                class="block w-full mt-1 myPrimaryInputReadonly"
                                readonly
                                autocomplete="off"
                            />
                            <div
                                @click="handleOpenLock"
                                class="cursor-pointer absolute inset-y-0 right-0 pr-1.5 flex items-center"
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
                                    ></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div v-show="isSlugEditable === true">
                        <InputLabel for="slug" value="Slug" />
                        <div class="relative flex items-center">
                            <TextInput
                                placeholder="Post slug"
                                id="slug"
                                v-model="slugValueCustom"
                                type="text"
                                class="block w-full mt-1"
                                autocomplete="off"
                            />
                            <div
                                @click="handleCloseLock"
                                class="cursor-pointer absolute inset-y-0 right-0 pr-1.5 flex items-center"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                    class="w-5 h-5 text-myPrimaryLinkColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M13.5 10.5V6.75a4.5 4.5 0 119 0v3.75M3.75 21.75h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H3.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"
                                    />
                                </svg>
                            </div>
                        </div>
                        <p
                            class="myPrimaryParagraph italic py-2 px-2 bg-gray-100 text-xs my-1 rounded-md"
                        >
                            Slug: {{ postForm.slug }}
                        </p>
                    </div>
                    <InputError :message="postForm.errors.slug" />
                </div>
                <!-- post slug end -->

                <!-- post content start -->
                <div class="myInputGroup">
                    <InputLabel
                        for="content"
                        value="Post description"
                        class="mb-1"
                    />
                    <QuillEditor
                        id="content"
                        v-model:content="postForm.content"
                        contentType="html"
                        :options="globalOptions"
                        class="rounded-b-md bg-white"
                    >
                    </QuillEditor>
                    <InputError :message="postForm.errors.content" />
                </div>
                <!-- post content end -->
            </div>
        </template>

        <template #sidebar>
            <!-- post status - start -->
            <div class="myInputsOrganization">
                <div class="myPrimaryFormOrganizationHeaderDescriptionSection">
                    <div class="myPrimaryFormOrganizationHeader">Status</div>
                    <p class="myPrimaryParagraph">Specify post status.</p>
                </div>
                <div
                    class="myInputGroup flex myPrimaryGap flex-row-reverse justify-end"
                >
                    <InputLabel
                        :value="
                            postForm.published ? 'Published' : 'Unpublished'
                        "
                        :class="{
                            'text-myPrimaryLinkColor': postForm.published,
                            'text-myPrimaryErrorColor': !postForm.published,
                        }"
                    />
                    <Switch
                        v-model="postForm.published"
                        :class="[
                            postForm.published
                                ? 'bg-myPrimaryLinkColor'
                                : 'bg-gray-200',
                            'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-myPrimaryLinkColor focus:ring-offset-2',
                        ]"
                    >
                        <span class="sr-only">Use setting</span>
                        <span
                            :class="[
                                postForm.published
                                    ? 'translate-x-5'
                                    : 'translate-x-0',
                                'pointer-events-none relative inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out',
                            ]"
                        >
                            <span
                                :class="[
                                    postForm.published
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
                                    postForm.published
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
                <InputError :message="postForm.errors.published" />
            </div>
            <!-- post status - end -->
            <!-- post show author - start -->
            <div class="myInputsOrganization">
                <div class="myPrimaryFormOrganizationHeaderDescriptionSection">
                    <div class="myPrimaryFormOrganizationHeader">
                        Show Authors
                    </div>
                </div>
                <div
                    class="myInputGroup flex myPrimaryGap flex-row-reverse justify-end"
                >
                    <InputLabel
                        :value="postForm.show_author ? 'Show' : 'Hide'"
                        :class="{
                            'text-myPrimaryLinkColor': postForm.show_author,
                            'text-myPrimaryErrorColor': !postForm.show_author,
                        }"
                    />
                    <Switch
                        v-model="postForm.show_author"
                        :class="[
                            postForm.show_author
                                ? 'bg-myPrimaryLinkColor'
                                : 'bg-gray-200',
                            'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-myPrimaryLinkColor focus:ring-offset-2',
                        ]"
                    >
                        <span class="sr-only">Use setting</span>
                        <span
                            :class="[
                                postForm.show_author
                                    ? 'translate-x-5'
                                    : 'translate-x-0',
                                'pointer-events-none relative inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out',
                            ]"
                        >
                            <span
                                :class="[
                                    postForm.show_author
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
                                    postForm.show_author
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
                <InputError :message="postForm.errors.show_author" />

                <div v-if="postForm.show_author === true">
                    <div
                        class="mt-2 flex items-center justify-between border-t border-myPrimaryLightGrayColor pt-4"
                    >
                        <button
                            @click="handleAddAuthor"
                            type="button"
                            class="myPrimaryButton gap-2 items-center"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="w-4 h-4"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z"
                                />
                            </svg>

                            Add Author
                        </button>
                    </div>

                    <div
                        class="p-2 mt-4"
                        :class="
                            postForm.author.length === 0
                                ? 'bg-white'
                                : 'bg-gray-50'
                        "
                    >
                        <p
                            v-if="postForm.author.length !== 0"
                            class="myPrimaryParagraph italic text-xs py-4"
                        >
                            Added {{ postForm.author.length }} author(s)
                        </p>

                        <div
                            v-if="postForm.author.length !== 0"
                            class="p-2 rounded-md min-h-[4rem] max-h-[18rem] flex flex-col w-full overflow-y-scroll border border-myPrimaryLightGrayColor divide-y divide-gray-200"
                        >
                            <div
                                v-for="user in postForm.author"
                                :key="user.id"
                                class="hover:bg-white px-2"
                            >
                                <div
                                    class="flex justify-between items-center rounded"
                                >
                                    <div class="flex items-center gap-2 my-4">
                                        <!-- start photo -->
                                        <div
                                            class="flex-shrink-0"
                                            v-if="
                                                user &&
                                                user.profile_photo_path !== null
                                            "
                                        >
                                            <img
                                                class="object-cover w-12 h-12 rounded-full"
                                                :src="`/storage/${user.profile_photo_path}`"
                                                :alt="
                                                    user.first_name +
                                                    user.last_name
                                                "
                                            />
                                        </div>

                                        <div
                                            v-if="
                                                user &&
                                                user.profile_photo_path === null
                                            "
                                            class="flex-shrink-0 myPrimaryParagraph w-12 h-12 gap-0.5 rounded-full bg-myPrimaryBrandColor flex justify-center items-center text-xs font-normal text-white"
                                        >
                                            <span>
                                                {{
                                                    user.first_name
                                                        .charAt(0)
                                                        .toUpperCase()
                                                }}
                                            </span>
                                            <span>
                                                {{
                                                    user.last_name
                                                        .charAt(0)
                                                        .toUpperCase()
                                                }}
                                            </span>
                                        </div>

                                        <!-- end photo -->
                                        <span
                                            class="flex flex-col items-left gap-0.5 myPrimaryParagraph text-xs"
                                        >
                                            <span class="font-medium">
                                                {{ user.first_name }}
                                                {{ user.last_name }}
                                            </span>
                                        </span>
                                    </div>
                                    <div
                                        @click="
                                            handleRemoveAttachedUser(user.id)
                                        "
                                    >
                                        <TrashIcon
                                            class="w-4 h-4 text-myPrimaryErrorColor stroke-2 cursor-pointer"
                                        ></TrashIcon>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="postForm.author.length === 0" class="space-y-6">
                        <p class="myPrimaryParagraph">No author selected.</p>
                    </div>
                    <InputError :message="postForm.errors.author" />
                    <p
                        v-if="postForm.author.length >= 6"
                        class="myPrimaryParagraphError"
                    >
                        Maximum 5 author is allowed.
                    </p>
                </div>
            </div>
            <!-- post show author - end -->
            <!-- cover image - start -->
            <div class="myInputsOrganization">
                <div class="myPrimaryFormOrganizationHeaderDescriptionSection">
                    <div class="myPrimaryFormOrganizationHeader">
                        Cover image
                    </div>
                    <p class="myPrimaryParagraph">
                        Uplaod or select a post cover image.
                    </p>
                </div>

                <img
                    v-if="
                        postForm.cover_image_medium &&
                        postForm.cover_image_medium.length !== 0
                    "
                    @click="handleUploadCoverImage"
                    class="myPrimarythumbnailInsertPreview"
                    alt="cover image"
                    :src="`/storage/uploads/${postForm.cover_image_medium}`"
                />

                <div
                    class="myInputGroup flex items-center justify-between border-t border-myPrimaryLightGrayColor pt-4"
                >
                    <button
                        @click="handleUploadCoverImage"
                        type="button"
                        class="myPrimaryButton gap-2 items-center"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="w-4 h-4"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"
                            />
                        </svg>

                        Cover image
                    </button>

                    <div v-if="postForm && postForm.cover_image_medium">
                        <svg
                            @click="handleRemoveCoverImage"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="w-5 h-5 text-myPrimaryErrorColor cursor-pointer"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"
                            />
                        </svg>
                    </div>
                </div>
                <InputError :message="postForm.errors.cover_image_original" />
            </div>
            <!-- cover image - end -->
            <!-- tags - start -->
            <div class="myInputsOrganization">
                <div class="myPrimaryFormOrganizationHeaderDescriptionSection">
                    <div class="myPrimaryFormOrganizationHeader">Tags</div>
                    <p class="myPrimaryParagraph">Enter tags for the post.</p>
                </div>
                <div class="myInputGroup">
                    <Tags
                        :clearTags="clearTags"
                        :tagsOnLoad="postForm.tags"
                        @handleTags="handleTags"
                    ></Tags>
                    <InputError :message="postForm.errors.tags" />
                </div>
            </div>
            <!-- tags - end -->

            <div class="myInputsOrganization">
                <div class="myPrimaryFormOrganizationHeaderDescriptionSection">
                    <div class="myPrimaryFormOrganizationHeader">
                        Post belongs to Team
                    </div>
                    <p class="myPrimaryParagraph">
                        Update a Team which this post belongs to.
                    </p>
                </div>
                <div class="myInputGroup">
                    <Listbox as="div" v-model="postForm.team">
                        <ListboxLabel class="myPrimaryInputLabel"
                            >Post belongs to Team</ListboxLabel
                        >
                        <div class="relative mt-1">
                            <ListboxButton class="myPrimarySelect">
                                <span class="block truncate">
                                    {{ postForm.team.name }}
                                </span>
                                <span
                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2"
                                >
                                    <ChevronUpDownIcon
                                        class="h-5 w-5 text-gray-400"
                                        aria-hidden="true"
                                    />
                                </span>
                            </ListboxButton>

                            <transition
                                leave-active-class="transition ease-in duration-100"
                                leave-from-class="opacity-100"
                                leave-to-class="opacity-0"
                            >
                                <ListboxOptions
                                    class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
                                >
                                    <ListboxOption
                                        as="template"
                                        v-for="team in userTeamsWithoutReaderRole"
                                        :key="team.id"
                                        :value="team"
                                    >
                                        <li
                                            class="hover:text-white hover:bg-myPrimaryDarkGrayColor"
                                            :class="[
                                                team.id === postForm.team.id
                                                    ? ''
                                                    : '',
                                                'relative cursor-default select-none py-2 pl-3 pr-9',
                                            ]"
                                        >
                                            <span
                                                :class="[
                                                    team.id === postForm.team.id
                                                        ? 'font-normal'
                                                        : 'font-normal',
                                                    'block truncate',
                                                ]"
                                                >{{ team.name }}</span
                                            >
                                            <span
                                                v-if="
                                                    team.id === postForm.team.id
                                                "
                                                :class="[
                                                    team.id === postForm.team.id
                                                        ? ''
                                                        : '',
                                                    'absolute inset-y-0 right-0 flex items-center pr-4',
                                                ]"
                                            >
                                                <CheckIcon
                                                    :class="[
                                                        team.id ===
                                                        postForm.team.id
                                                            ? ''
                                                            : 'text-white',
                                                    ]"
                                                    class="h-5 w-5"
                                                    aria-hidden="true"
                                                />
                                            </span>
                                        </li>
                                    </ListboxOption>
                                </ListboxOptions>
                            </transition>
                        </div>
                    </Listbox>
                    <InputError :message="postForm.errors.team" />
                </div>
            </div>
        </template>

        <template #actions>
            <div class="flex myPrimaryGap justify-end items-center">
                <template v-if="formType === 'create'">
                    <button
                        type="button"
                        @click="handleClearForm"
                        class="myPrimaryDeleteButton"
                    >
                        Clear
                    </button>
                </template>
                <SubmitButton :disabled="postForm.processing" buttonText="Save">
                </SubmitButton>
            </div>
            <div
                class="flex justify-end mt-4"
                v-if="Object.values(postForm.errors).length !== 0"
            >
                <div
                    @click="showErrorNotifications = true"
                    class="w-fit py-1 flex items-center gap-2 rounded-md px-2 cursor-pointer italic"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="w-4 h-4 text-myPrimaryErrorColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"
                        />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                        />
                    </svg>
                    <p
                        class="myPrimaryParagraph text-xs text-myPrimaryErrorColor py-0 my-0"
                    >
                        Show
                        {{ Object.values(postForm.errors).length }}
                        errors
                    </p>
                </div>
            </div>
            <DynamicModal
                :show="modalShowClearForm"
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
            <MediaLibraryModal
                :user="user"
                :team="postForm.team"
                :open="showMediaLibraryModal"
                :title="titleMedia"
                :description="descriptionMedia"
                :firstButtonText="firstButtonMedia"
                :secondButtonText="secondButtonMedia"
                :thirdButtonText="thirdButtonMedia"
                @firstMediaButtonFunction="firstMediaButtonFunction"
                @secondMediaButtonFunction="secondMediaButtonFunction"
                @thirdMediaButtonFunction="thirdMediaButtonFunction"
            >
            </MediaLibraryModal>
            <SearchUserModal
                :user="user"
                :team="postForm.team"
                :show="modalShowAddAuthor"
                :title="titleModalSearchAuthor"
                :description="descriptionModalSearchAuthor"
                :firstButtonText="firstButtonModalSearchAuthor"
                :secondButtonText="secondButtonModalSearchAuthor"
                @firstModalButtonSearchAuthorFunction="
                    firstModalButtonSearchAuthorFunction
                "
                @secondModalButtonSearchAuthorFunction="
                    secondModalButtonSearchAuthorFunction
                "
            >
            </SearchUserModal>
            <NotificationsFixedBottom
                :listOfMessages="Object.values(postForm.errors)"
                :show="showErrorNotifications"
                @notificationsModalButton="notificationsModalButton"
            >
                <div class="flex items-center justify-start gap-2">
                    <p class="myPrimaryParagraphError">
                        {{ Object.values(postForm.errors).length }}
                        errors
                    </p>
                </div>
            </NotificationsFixedBottom>
        </template>
    </FormSection>
</template>
