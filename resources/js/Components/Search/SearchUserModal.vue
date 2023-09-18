<script setup>
import Modal from "@/Components/Modals/Modal.vue";
import { ref, computed, onMounted } from "vue";
import { TailwindPagination } from "laravel-vue-pagination";
import { useStore } from "vuex";

const props = defineProps({
    team: {
        required: true,
    },
    user: {
        required: true,
    },
    show: {
        type: Boolean,
        default: false,
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
});

// store
const store = useStore();

const frontEndError = ref(null);

const getCurrentUsers = computed(() => {
    return store.getters["attachedUsers/getCurrentUsers"];
});
const getCurrentAttachedUsers = computed(() => {
    return store.getters["attachedUsers/getCurrentAttachedUsers"];
});
//
const filteredUsers = ref([]);
//
const search_query = ref("");
// handle search
const handleSearch = function (page) {
    // dispatch
    store.dispatch("attachedUsers/loadUsers", {
        teamId: props.team.id,
        page: page,
        search_query: search_query.value,
    });
};

// get result for "laravel pagination" package
const getResultsForPage = (page = 1) => {
    handleSearch(page);
};

const emit = defineEmits([
    "firstModalButtonSearchAuthorFunction",
    "secondModalButtonSearchAuthorFunction",
]);

// first button function
const firstButton = function () {
    emit("firstModalButtonSearchAuthorFunction");
};
// second button  function
const secondButton = function () {
    emit("secondModalButtonSearchAuthorFunction");
};

const isUserAlreadyAdded = function (user) {
    const isUserExists = getCurrentAttachedUsers.value.some(
        (u) => u.id === user.id
    );

    // User with matching ID already exists
    if (isUserExists === true) {
        return true;
    }
    // User with matching ID do not exists
    if (isUserExists === false) {
        return false;
    }
};

const handleAttachUser = function (user) {
    const isUserExists = getCurrentAttachedUsers.value.some(
        (u) => u.id === user.id
    );

    // User with matching ID already exists
    if (isUserExists === true) {
        frontEndError.value = {
            id: user.id,
            error: "User aldready added.",
        };
    }
    // User with matching ID do not exists
    if (isUserExists === false) {
        frontEndError.value = null;
        store.commit("attachedUsers/setCurrentAttachedUsers", user);
    }
};
const handleRemoveAttachedUser = function (userId) {
    // filter the array to exclude user with matching ID
    filteredUsers.value = getCurrentAttachedUsers.value.filter(
        (user) => user.id !== userId
    );
    store.commit("attachedUsers/setRemoveAttachedUser", filteredUsers.value);
};

onMounted(() => {
    handleSearch(1);
});
</script>

<template>
    <Modal
        :show="show"
        @close="firstButton"
        maxWidth="5xl"
        minHeight="min-h-[50rem]"
        maxHeight="max-h-[50rem]"
    >
        <div
            class="px-4 w-full relative inline-block align-bottom text-left overflow-hidden transform transition-all sm:align-middle"
        >
            <div
                class="flex items-center border-b border-gray-200 pb-2 mb-2 justify-between"
            >
                <h3 class="tertiaryHeader my-0 py-0">
                    {{ title }}
                </h3>
                <div @click="firstButton" class="flex-end">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        aria-hidden="true"
                        class="h-6 w-6 text-myPrimaryDarkGrayColor self-center cursor-pointer"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M6 18L18 6M6 6l12 12"
                        ></path>
                    </svg>
                </div>
            </div>

            <!-- content start -->
            <div
                class="h-full flex md:flex-row flex-col myPrimaryGap mt-2 p-2 overflow-y-scroll"
            >
                <main class="overflow-y-auto relativ w-full">
                    <div>
                        <form @submit.prevent="handleSearch(1)">
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
                                        type="search"
                                        id="search_query"
                                        v-model="search_query"
                                        class="myPrimarySearchInput"
                                        autocomplete="off"
                                        placeholder="Search..."
                                    />
                                </div>

                                <button
                                    @click="handleSearch(1)"
                                    type="button"
                                    class="myPrimaryButton"
                                >
                                    Search
                                </button>
                            </div>
                        </form>

                        <div
                            v-if="
                                getCurrentUsers &&
                                getCurrentUsers.isError === false &&
                                getCurrentUsers.fetchedData &&
                                getCurrentUsers.fetchedData.users &&
                                getCurrentUsers.fetchedData.users.data &&
                                getCurrentUsers.fetchedData.total_results !== 0
                            "
                            class="flex justify-start items-center"
                        >
                            <p class="myPrimaryTag">
                                Total
                                {{ getCurrentUsers.fetchedData.count }}
                                users
                            </p>
                        </div>

                        <div
                            v-if="
                                getCurrentUsers &&
                                getCurrentUsers.isLoading === false &&
                                getCurrentUsers.isError === true
                            "
                            class="myPrimaryParagraphError"
                        >
                            {{ getCurrentUsers.error }}
                        </div>

                        <div
                            v-if="
                                getCurrentUsers &&
                                getCurrentUsers.fetchedData &&
                                getCurrentUsers.fetchedData.users &&
                                getCurrentUsers.fetchedData.total_results !== 0
                            "
                            class="flex items-center justify-around border-t border-gray-200 bg-white py-3 mt-4 gap-2 flex-wrap-reverse"
                        >
                            <TailwindPagination
                                :limit="2"
                                :keepLength="true"
                                :class="[
                                    'space-x-1',
                                    'shadow-none',
                                    'tailwind-pagination-package',
                                ]"
                                :active-classes="[
                                    'bg-myPrimaryLinkColor',
                                    'text-white',
                                    'rounded-full',
                                ]"
                                :item-classes="[
                                    'p-0',
                                    'm-0',
                                    'border-none',
                                    'bg-gray-200',
                                    'hover:bg-gray-300',
                                    'text-myPrimaryDarkGrayColor',
                                    'rounded-full',
                                ]"
                                :data="getCurrentUsers.fetchedData.users"
                                @pagination-change-page="getResultsForPage"
                            >
                                <template #prev-nav>
                                    <span> Prev </span>
                                </template>
                                <template #next-nav>
                                    <span>Next</span>
                                </template>
                            </TailwindPagination>
                        </div>

                        <div
                            v-if="
                                getCurrentUsers &&
                                getCurrentUsers.isLoading === true &&
                                getCurrentUsers.isError === false
                            "
                        >
                            <div
                                class="flex items-center justify-center min-h-[20rem] mb-12"
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
                        <!-- loading - end -->
                        <template
                            v-if="
                                getCurrentUsers &&
                                getCurrentUsers.isLoading === false &&
                                getCurrentUsers.isError === false &&
                                getCurrentUsers.isSuccess === true &&
                                getCurrentUsers.fetchedData &&
                                getCurrentUsers.fetchedData.users &&
                                getCurrentUsers.fetchedData.users.data &&
                                getCurrentUsers.fetchedData.total_results !== 0
                            "
                        >
                            <div
                                class="h-full md:max-h-[26.3rem] max-h-[13rem] overflow-y-scroll pr-1"
                            >
                                <div
                                    class="flex flex-col w-full gap-2 px-2 p-4 border border-myPrimaryLightGrayColor rounded"
                                >
                                    <div
                                        v-for="user in getCurrentUsers
                                            .fetchedData.users.data"
                                        :key="user.id"
                                        class="myPrimaryBorderFullRoundedUsers"
                                    >
                                        <div
                                            class="flex justify-between items-center w-full"
                                        >
                                            <div
                                                class="flex items-center gap-4 my-2"
                                            >
                                                <!-- start photo -->
                                                <div
                                                    class="flex-shrink-0"
                                                    v-if="
                                                        user &&
                                                        user.profile_photo_path !==
                                                            null
                                                    "
                                                >
                                                    <img
                                                        v-if="
                                                            user.profile_photo_path !==
                                                            null
                                                        "
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
                                                        user.profile_photo_path ===
                                                            null
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
                                                    <span class="italic">
                                                        {{ user.role }}
                                                    </span>
                                                </span>
                                            </div>

                                            <button
                                                @click="handleAttachUser(user)"
                                                v-if="
                                                    isUserAlreadyAdded(user) ===
                                                    false
                                                "
                                                class="myPrimaryButton text-xs flex items-center"
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke-width="2"
                                                    stroke="currentColor"
                                                    class="w-4 h-4"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M12 4.5v15m7.5-7.5h-15"
                                                    />
                                                </svg>
                                            </button>
                                            <button
                                                @click="
                                                    handleRemoveAttachedUser(
                                                        user.id
                                                    )
                                                "
                                                v-if="
                                                    isUserAlreadyAdded(user) ===
                                                    true
                                                "
                                                class="myPrimaryDeleteButton text-xs flex justify-center items-center"
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke-width="2"
                                                    stroke="currentColor"
                                                    class="w-4 h-4"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M18 12H6"
                                                    />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </main>
                <aside
                    aria-label="sidebar"
                    class="h-full md:max-h-[38.5rem] max-h-[12rem] md:w-3/5 w-full bg-gray-50 pl-2 border border-gray-200 overflow-y-scroll rounded"
                >
                    <div
                        v-if="getCurrentAttachedUsers.length === 0"
                        class="pb-6 space-y-6"
                    >
                        <p class="myPrimaryParagraph text-xs p-2">
                            No users added
                        </p>
                    </div>

                    <div
                        v-if="getCurrentAttachedUsers.length !== 0"
                        class="flex flex-col w-full divide-y divide-gray-200 p-2"
                    >
                        <div class="flex justify-start items-center">
                            <p class="myPrimaryTag">
                                Added
                                {{ getCurrentAttachedUsers.length }} User(s)
                            </p>
                        </div>
                        <div
                            v-for="user in getCurrentAttachedUsers"
                            :key="user.id"
                            class="px-2 hover:bg-white"
                        >
                            <div class="flex justify-between items-center">
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
                                                user.first_name + user.last_name
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
                                        <span>
                                            {{ user.email }}
                                        </span>
                                        <span> Role: {{ user.role }} </span>
                                    </span>
                                </div>
                                <div @click="handleRemoveAttachedUser(user.id)">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor"
                                        class="w-4 h-4 text-myPrimaryErrorColor cursor-pointer"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"
                                        />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--v-if-->
                </aside>
            </div>
        </div>
        <!-- content end -->

        <div
            class="bg-gray-100 px-6 py-4 absolute bottom-0 left-0 right-0 flex sm:justify-end justify-center"
        >
            <div class="sm:w-3/6 w-full px-2 my-2 flex gap-2 justify-end">
                <button
                    v-if="firstButtonText"
                    ref="firstButtonRef"
                    class="mySecondaryButton"
                    type="button"
                    @click="firstButton"
                >
                    {{ firstButtonText }}
                </button>
                <button
                    v-if="secondButtonText"
                    class="myPrimaryButton"
                    type="button"
                    @click="secondButton"
                >
                    {{ secondButtonText }}
                </button>
            </div>
        </div>
    </Modal>
</template>
