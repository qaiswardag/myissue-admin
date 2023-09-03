<script setup>
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import PrimaryButton from "@/Components/Buttons/PrimaryButton.vue";
import Pagination from "@/Components/Pagination/Pagination.vue";
import CardHeadings from "@/Components/Actions/CardHeadings.vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import DynamicModal from "@/Components/Modals/DynamicModal.vue";
import SubmitButton from "@/Components/Buttons/SubmitButton.vue";
import { onMounted, ref } from "vue";
import Breadcrumbs from "@/Components/Breadcrumbs/Breadcrumbs.vue";
import { parseISO, format } from "date-fns";
import { TrashIcon } from "@heroicons/vue/24/outline";

const props = defineProps({
    posts: {
        required: true,
    },
    currentUserTeam: {
        required: true,
    },
    oldInput: {
        search_query: {
            required: false,
        },
    },
});

const breadcrumbsLinks = [
    {
        label: "All Posts",
        route: {
            name: "team.posts.index",
            parameters: [props.currentUserTeam.reference_id],
        },
    },
];

const routesArray = [
    {
        label: "All Posts",
        route: {
            name: "team.posts.index",
            parameters: [props.currentUserTeam.reference_id],
        },
    },
    {
        label: "Create Post",
        route: {
            name: "team.posts.create",
            parameters: [props.currentUserTeam.reference_id],
        },
    },
];

const modalShowDeletePost = ref(false);

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

// handle action
const handleDelete = function (postId, post) {
    modalShowDeletePost.value = true;

    // set modal standards
    typeModal.value = "delete";
    gridColumnModal.value = 3;
    titleModal.value = `Delete ${post.title}?`;
    descriptionModal.value = `Are you sure you want to delete post with title ${post.title}?`;
    firstButtonModal.value = "Close";
    secondButtonModal.value = null;
    thirdButtonModal.value = "Delete Post";

    // handle click
    firstModalButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowDeletePost.value = false;
    };
    // handle click
    // secondModalButtonFunction.value = function () {
    //     // handle show modal for unique content
    //     modalShowDeletePost.value = false;
    // };
    // handle click
    thirdModalButtonFunction.value = function () {
        deletePost(postId);
    };
    // end modal
};
// form
const deletePostForm = useForm({});

// form action
const deletePost = (postId) => {
    deletePostForm.delete(
        route("team.posts.post.destroy", [postId, props.currentUserTeam.id]),
        {
            preserveScroll: true,
            onSuccess: () => (modalShowDeletePost.value = false),
            onError: (err) => {},
            onFinish: (log) => {
                modalShowDeletePost.value = false;
            },
        }
    );
};

// handle action
const handleEdit = function (postId) {
    router.get(
        route("team.posts.post.edit", [
            props.currentUserTeam.reference_id,
            postId,
        ])
    );
};

// form
const searchForm = useForm({
    search_query: "",
});

const handleSearch = function () {
    searchForm.get(
        route("team.posts.index", [props.currentUserTeam.reference_id]),
        {
            preserveScroll: true,
            onSuccess: () => {},
            onError: (err) => {},
            onFinish: () => {},
        }
    );
};

onMounted(() => {
    if (props.oldInput?.search_query) {
        searchForm.search_query = props.oldInput.search_query;
    }
});
</script>

<template>
    <LoggedInLayout>
        <Head title="Posts" />
        <DynamicModal
            :show="modalShowDeletePost"
            :type="typeModal"
            :disabled="deletePostForm.processing"
            disabledWhichButton="thirdButton"
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
        <template #header>
            <h2 class="myPrimaryMainPageHeader">
                Posts for
                {{ $page.props.user && $page.props.currentUserTeam.name }}
            </h2>
        </template>
        <template #breadcrumbs>
            <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
        </template>

        <CardHeadings :routesArray="routesArray">
            <template #title
                >Posts for
                {{ $page.props.user && $page.props.user.current_team.name }}
            </template>
            <template #subTitle
                >Lorem ipsum dolor sit amet consectetur adipisicing elit quam
                corrupti consectetur.
            </template>
            <template #buttons>
                <Link
                    class="myPrimaryButton"
                    type="button"
                    :href="
                        route('team.posts.create', currentUserTeam.reference_id)
                    "
                >
                    Create Post
                </Link>
            </template>
        </CardHeadings>

        <form @submit.prevent="handleSearch">
            <div class="myPrimarySection">
                <div class="mysearchBarWithOptions">
                    <div class="relative w-full">
                        <div
                            class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none"
                        >
                            <svg
                                aria-hidden="true"
                                class="w-5 h-5 text-gray-500 dark:text-gray-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.5"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                ></path>
                            </svg>
                        </div>
                        <input
                            v-model="searchForm.search_query"
                            type="search"
                            id="search_query"
                            class="myPrimarySearchInput"
                            autocomplete="off"
                            placeholder="Search..."
                        />
                    </div>

                    <button
                        @click="handleSearch"
                        type="button"
                        class="myPrimaryButton"
                    >
                        Search
                    </button>
                </div>
            </div>
        </form>

        <template v-if="posts && posts.data.length <= 0">
            <h1 class="myPrimaryHeaderMessage">No Posts</h1>
            <p class="myPrimaryParagraph">Looks like there are no posts!</p>
        </template>

        <!-- table start -->
        <div v-if="posts && posts.data.length >= 1" class="myTableContainer">
            <div class="myTableSubContainer">
                <table class="myPrimaryTable">
                    <caption class="myPrimaryTableCaption">
                        {{
                            $page.props.user &&
                            $page.props.user.current_team.name
                        }}
                    </caption>
                    <thead class="myPrimaryTableTHead">
                        <tr class="myPrimaryTableTr">
                            <th scope="col" class="myPrimaryTableTh">
                                Thumbnail
                            </th>
                            <th scope="col" class="myPrimaryTableTh">Title</th>
                            <th scope="col" class="myPrimaryTableTh">Status</th>
                            <th scope="col" class="myPrimaryTableTh">
                                Show Authors
                            </th>
                            <th scope="col" class="myPrimaryTableTh">
                                Post ID
                            </th>
                            <th scope="col" class="myPrimaryTableTh">Tags</th>

                            <th scope="col" class="myPrimaryTableTh">
                                Updated By
                            </th>

                            <th scope="col" class="myPrimaryTableTh">
                                Team Name
                            </th>
                            <th scope="col" class="myPrimaryTableTh">
                                Updated Date
                            </th>

                            <th scope="col" class="myPrimaryTableTh">
                                Created Date
                            </th>
                            <th scope="col" class="myPrimaryTableTh">Edit</th>
                            <th scope="col" class="myPrimaryTableTh">Delete</th>
                        </tr>
                    </thead>
                    <tbody class="myPrimaryTableTBody">
                        <TransitionGroup name="table">
                            <tr
                                class="myPrimaryTableTBodyTr"
                                v-for="post in posts && posts.data"
                                :key="post.id"
                            >
                                <td class="myPrimaryTableTBodyTd">
                                    <div
                                        class="flex items-center justify-start myPrimaryGap"
                                    >
                                        <Link
                                            :href="
                                                route('team.posts.post.show', [
                                                    $page.props.user
                                                        .current_team
                                                        .reference_id,
                                                    post.id,
                                                    post.slug,
                                                ])
                                            "
                                        >
                                            <div
                                                v-if="post.cover_image_medium"
                                                class="h-16 w-16 flex-shrink-0"
                                            >
                                                <img
                                                    class="h-16 w-16 rounded-full object-cover"
                                                    :src="`/storage/uploads/${post.cover_image_medium}`"
                                                    alt=""
                                                />
                                            </div>
                                        </Link>
                                    </div>
                                </td>

                                <td class="myPrimaryTableTBodyTd">
                                    <Link
                                        :href="
                                            route('team.posts.post.show', [
                                                $page.props.user.current_team
                                                    .reference_id,
                                                post.id,
                                                post.slug,
                                            ])
                                        "
                                    >
                                        <span
                                            class="text-myPrimaryLinkColor font-medium"
                                        >
                                            {{ post.title }}
                                        </span>
                                    </Link>
                                </td>

                                <td class="myPrimaryTableTBodyTd">
                                    <span
                                        :class="
                                            post.published
                                                ? 'bg-green-50 text-myPrimaryLinkColor'
                                                : 'bg-red-100 text-myPrimaryErrorColor'
                                        "
                                        class="inline-flex rounded-full px-2 font-medium leading-5 text-green-800"
                                        >{{
                                            post.published
                                                ? "Published"
                                                : "Private"
                                        }}</span
                                    >
                                </td>
                                <td class="myPrimaryTableTBodyTd">
                                    <span
                                        :class="
                                            post.show_author
                                                ? 'bg-green-50 text-myPrimaryLinkColor'
                                                : 'bg-red-100 text-myPrimaryErrorColor'
                                        "
                                        class="inline-flex rounded-full px-2 font-medium leading-5 text-green-800"
                                        >{{
                                            post.show_author ? "Show" : "Hide"
                                        }}</span
                                    >
                                </td>

                                <td class="myPrimaryTableTBodyTd">
                                    {{ post.id }}
                                </td>
                                <td
                                    class="myPrimaryTableTBodyTd flex flex-wrap justify-start items-center gap-2"
                                >
                                    <p
                                        v-for="tag in post.tags &&
                                        post.tags.split(',')"
                                        :key="tag"
                                        class="text-xs rounded-full bg-myPrimaryLightGrayColor py-1 px-2"
                                    >
                                        {{ tag }}
                                    </p>
                                </td>

                                <td class="myPrimaryTableTBodyTd">
                                    <div
                                        v-if="post.updatedBy !== null"
                                        class="flex items-center gap-2"
                                    >
                                        <!-- user photo - start -->

                                        <div
                                            v-if="
                                                post.updatedBy
                                                    .profile_photo_path !== null
                                            "
                                        >
                                            <div class="h-8 w-8 flex-shrink-0">
                                                <img
                                                    class="object-cover w-8 h-8 rounded-full"
                                                    :src="`/storage/${post.updatedBy.profile_photo_path}`"
                                                    alt="avatar"
                                                />
                                            </div>
                                        </div>

                                        <div
                                            v-if="
                                                post.updatedBy
                                                    .profile_photo_path === null
                                            "
                                            class="flex-shrink-0 h-8 w-8 rounded-full bg-myPrimaryBrandColor flex justify-center items-center font-normal text-white text-xs"
                                        >
                                            {{
                                                post.updatedBy.first_name
                                                    .charAt(0)
                                                    .toUpperCase()
                                            }}
                                            {{
                                                post.updatedBy.last_name
                                                    .charAt(0)
                                                    .toUpperCase()
                                            }}
                                        </div>
                                        <span
                                            class="flex flex-col items-left gap-1 myPrimaryParagraph"
                                        >
                                            <span>
                                                {{ post.updatedBy.first_name }}
                                                {{ post.updatedBy.last_name }}
                                            </span>
                                        </span>
                                    </div>
                                    <!-- user photo - end -->
                                    <span v-if="post.updatedBy === null">
                                        Unknown
                                    </span>
                                </td>

                                <td class="myPrimaryTableTBodyTd">
                                    {{
                                        $page.props.user &&
                                        $page.props.user.current_team.name
                                    }}
                                </td>

                                <td class="myPrimaryTableTBodyTd">
                                    {{
                                        format(
                                            parseISO(post.updated_at),
                                            "dd/MM/yyyy"
                                        )
                                    }}
                                </td>
                                <td class="myPrimaryTableTBodyTd">
                                    {{
                                        format(
                                            parseISO(post.created_at),
                                            "dd/MM/yyyy"
                                        )
                                    }}
                                </td>

                                <td class="myPrimaryTableTBodyTd">
                                    <button
                                        @click="handleEdit(post.id)"
                                        type="button"
                                        class="myPrimaryButtonNoBackground"
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
                                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"
                                            />
                                        </svg>
                                    </button>
                                </td>
                                <td class="myPrimaryTableTBodyTd">
                                    <SubmitButton
                                        :ButtonStyleDelete="true"
                                        :TableStyle="true"
                                        :disabled="false"
                                        @firstButtonClick="
                                            handleDelete(post.id, post)
                                        "
                                        buttonText=""
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
                                </td>
                            </tr>
                        </TransitionGroup>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- table end -->
        <Pagination :links="posts?.links ? posts.links : []"></Pagination>
    </LoggedInLayout>
</template>
