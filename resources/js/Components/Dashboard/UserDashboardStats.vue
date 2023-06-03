<script setup>
import FullWidthElement from "@/Components/Layouts/FullWidthElement.vue";
import { MinusSmallIcon, PlusSmallIcon } from "@heroicons/vue/24/outline";
import { Disclosure, DisclosureButton, DisclosurePanel } from "@headlessui/vue";
import EmptySectionBorder from "@/Components/Sections/EmptySectionBorder.vue";
import { useStore } from "vuex";
import { onMounted, computed } from "vue";

// store
const store = useStore();

const props = defineProps({
    user: {
        required: true,
    },
    currentUserTeam: {
        required: true,
    },
    currentUserTeamRole: {
        required: true,
    },
});

const getDashboardStats = computed(() => {
    return store.getters["userDashboard/getDashboardStats"];
});

const faqs = [
    {
        question: "Current user",
        answer: props.user,
    },
    {
        question: "Current user team",
        answer: props.currentUserTeam,
    },
    {
        question: "Current user  team role",
        answer: props.currentUserTeamRole,
    },
];

const stats = [
    { id: 1, name: "Creators on the platform", value: "8,000+" },
    { id: 2, name: "Flat platform fee", value: "3%" },
    { id: 3, name: "Uptime guarantee", value: "99.9%" },
    { id: 4, name: "Paid out to creators", value: "$70M" },
];

onMounted(() => {
    store.dispatch("userDashboard/loadDashboardStats", {
        teamId: props.currentUserTeam && props.currentUserTeam.id,
    });
});
</script>

<template>
    <FullWidthElement :descriptionArea="true" class="bg-gray-100">
        <template #title>Latest team resources</template>
        <template #description>
            You are currently viewing the latest resources uploaded by your
            team. These resources are sorted by date, with the most recent ones
            displayed first.
        </template>
        <template #content>
            <!-- Error - start -->
            <div
                v-if="
                    getDashboardStats &&
                    getDashboardStats.isSuccess === false &&
                    getDashboardStats.isLoading === false &&
                    getDashboardStats.isError === true
                "
                class="myPrimaryParagraphError"
            >
                {{ getDashboardStats.error }}
            </div>
            <!-- Error - end -->
            <!-- Loading - end -->
            <div
                v-if="
                    getDashboardStats &&
                    getDashboardStats.isLoading === true &&
                    getDashboardStats.isError === false
                "
                class="bordermx-auto block w-full rounded-sm object-cover object-center cursor-pointer"
            >
                <div class="flex items-center justify-center pt-12">
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
            <!-- Loading - end -->
            <div
                v-if="
                    $page.props &&
                    $page.props.currentUserTeam !== null &&
                    getDashboardStats &&
                    getDashboardStats.fetchedData &&
                    getDashboardStats.isSuccess === true &&
                    getDashboardStats.isLoading === false &&
                    getDashboardStats.isError === false
                "
                class="grid grid-cols-1 lg:gap-8 gap-12 lg:grid-cols-12"
            >
                <!-- column start -->
                <div
                    class="lg:col-span-6 w-full rounded py-10 px-4 bg-white h-full"
                >
                    <h2 class="my-2 mb-4 myPrimaryParagraph font-medium">
                        Latest Team Members
                    </h2>

                    <div class="min-h-[36rem] max-h-[36rem] overflow-y-scroll">
                        <ul role="list" class="grid grid-cols-1 myPrimaryGap">
                            <li
                                v-for="member in getDashboardStats.fetchedData
                                    .latestTeamMembers"
                                :key="member.id"
                                class="p-2 rounded-md min-h-[2rem] max-h-[6rem] flex flex-col w-full border border-myPrimaryLightGrayColor divide-y divide-gray-200 bg-white hover:border-myPrimaryMediumGrayColor"
                            >
                                <!-- start photo -->
                                <div class="flex items-center gap-2 my-2">
                                    <div
                                        v-if="
                                            member &&
                                            member.profile_photo_path !== null
                                        "
                                        class="flex-shrink-0"
                                    >
                                        <img
                                            class="object-cover w-10 h-10 rounded-full"
                                            :src="`/storage/${member.profile_photo_path}`"
                                            alt="User Image
                                            
                                        "
                                        />
                                    </div>

                                    <div
                                        v-if="
                                            $page.props.user &&
                                            member.profile_photo_path === null
                                        "
                                        class="flex-shrink-0 w-10 h-10 rounded-full bg-myPrimaryBrandColor flex justify-center items-center text-xs font-normal text-white"
                                    >
                                        {{
                                            member.first_name
                                                .charAt(0)
                                                .toUpperCase()
                                        }}
                                        {{
                                            member.last_name
                                                .charAt(0)
                                                .toUpperCase()
                                        }}
                                    </div>
                                    <span
                                        class="flex flex-col items-left gap-0.5 myPrimaryParagraph text-xs"
                                    >
                                        <p class="font-medium">
                                            {{ member.first_name }}
                                            {{ member.last_name }}
                                        </p>
                                        <p>Team Role: {{ member.role }}</p>
                                    </span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- column end -->

                <!-- column start -->
                <div
                    class="lg:col-span-6 w-full rounded py-10 px-4 bg-white h-full"
                >
                    <h2 class="my-2 mb-4 myPrimaryParagraph font-medium">
                        Latest Team Images
                    </h2>

                    <div class="min-h-[36rem] max-h-[36rem] overflow-y-scroll">
                        <ul
                            role="list"
                            class="grid grid-cols-2 myPrimaryGap lg:grid-cols-2 xl:grid-cols-4"
                        >
                            <li
                                v-for="file in getDashboardStats.fetchedData
                                    .latestMedia"
                                :key="file.id"
                                class="rounded"
                            >
                                <div
                                    class="group aspect-w-10 aspect-h-7 block w-full overflow-hidden rounded focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 focus-within:ring-offset-gray-100"
                                >
                                    <img
                                        :src="`/storage/uploads/${file.medium_path}`"
                                        alt="Image"
                                        class="w-full pointer-events-none object-cover group-hover:opacity-75"
                                    />
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- column end -->
                <!-- column start -->
                <div
                    class="lg:col-span-6 w-full rounded py-10 px-4 bg-white h-full"
                >
                    <h2 class="my-2 mb-4 myPrimaryParagraph font-medium">
                        Latest Team Posts
                    </h2>

                    <div class="min-h-[36rem] max-h-[36rem] overflow-y-scroll">
                        <ul role="list" class="grid grid-cols-1 myPrimaryGap">
                            <li
                                v-for="post in getDashboardStats.fetchedData
                                    .latestPosts"
                                :key="post.id"
                                class="p-2 rounded-md min-h-[2rem] max-h-[6rem] flex flex-col w-full border border-myPrimaryLightGrayColor bg-white hover:border-myPrimaryMediumGrayColor"
                            >
                                <!-- start photo -->
                                <div class="flex items-center gap-2 my-2">
                                    <div
                                        v-if="
                                            post &&
                                            post.cover_image_medium !== null
                                        "
                                        class="flex-shrink-0"
                                    >
                                        <img
                                            class="object-cover w-10 h-10 rounded-full"
                                            :src="`/storage/uploads/${post.cover_image_medium}`"
                                            alt="Image"
                                        />
                                    </div>
                                    <div
                                        v-if="
                                            post &&
                                            post.cover_image_medium === null
                                        "
                                    ></div>

                                    <span
                                        class="flex flex-col items-left gap-0.5 myPrimaryParagraph text-xs"
                                    >
                                        <p class="font-medium">
                                            {{ post.title }}
                                        </p>
                                    </span>
                                </div>
                                <p class="text-xs">
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
                                                : "Privat"
                                        }}</span
                                    >
                                </p>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- column end -->
                <!-- column start -->
                <div
                    class="lg:col-span-6 w-full rounded py-10 px-4 bg-white h-full"
                >
                    <h2 class="my-2 mb-4 myPrimaryParagraph font-medium">
                        Latest Team Stats
                    </h2>

                    <div class="min-h-[36rem] max-h-[36rem] overflow-y-scroll">
                        <div class="mx-auto max-w-2xl lg:mr-0 lg:max-w-lg">
                            <h3 class="myTertiaryHeader">
                                Trusted by thousands of creators&nbsp;worldwide
                            </h3>
                            <p class="myPrimaryParagrph">
                                Lorem ipsum, dolor sit amet consectetur
                                adipisicing elit. Maiores impedit perferendis
                                suscipit eaque, iste dolor cupiditate blanditiis
                                ratione.
                            </p>
                            <dl
                                class="mt-16 grid max-w-xl grid-cols-1 gap-8 sm:mt-20 sm:grid-cols-2 xl:mt-16"
                            >
                                <div
                                    v-for="stat in stats"
                                    :key="stat.id"
                                    class="flex flex-col gap-y-3 border-l border-gray-900/10 pl-6"
                                >
                                    <dt class="myPrimaryParagraph">
                                        {{ stat.name }}
                                    </dt>
                                    <dd
                                        class="order-first text-3xl font-semibold tracking-tight text-gray-900"
                                    >
                                        {{ stat.value }}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
                <!-- column end -->
            </div>
        </template>
    </FullWidthElement>

    <FullWidthElement :descriptionArea="true" class="bg-red-100">
        <template #title
            >Details about the logged-in user and team insights</template
        >
        <template #description>
            Retrieve details about the logged-in user and team insights for
            current user. Discover insights about the logged-in user and the
            team.
        </template>
        <template #content>
            <div class="grid grid-cols-1 lg:gap-8 gap-12 lg:grid-cols-2">
                <div>
                    <h2 class="my-2 mb-4 myPrimaryParagraph font-medium">
                        Details about the logged-in user
                    </h2>
                    <dl class="divide-y divide-myPrimaryMediumGrayColor">
                        <Disclosure
                            as="div"
                            v-for="faq in faqs"
                            :key="faq.question"
                            v-slot="{ open }"
                        >
                            <dt>
                                <DisclosureButton
                                    class="py-6 flex w-full items-center justify-between text-left myPrimaryParagraph"
                                >
                                    <span class="myPrimaryParagraph font-medium"
                                        >{{ faq.question }}
                                    </span>
                                    <span class="ml-6 flex h-7 items-center">
                                        <PlusSmallIcon
                                            v-if="!open"
                                            class="h-5 w-5"
                                            aria-hidden="true"
                                        />
                                        <MinusSmallIcon
                                            v-else
                                            class="h-5 w-5"
                                            aria-hidden="true"
                                        />
                                    </span>
                                </DisclosureButton>
                            </dt>
                            <DisclosurePanel as="dd" class="mt-2 pr-12 pb-16">
                                <p
                                    class="myPrimaryParagraph whitespace-pre-line"
                                >
                                    {{ faq.answer }}
                                </p>
                            </DisclosurePanel>
                        </Disclosure>
                    </dl>
                </div>

                <div>
                    <h2 class="my-2 mb-4 myPrimaryParagraph font-medium">
                        Details about the logged-in user
                    </h2>
                    <dl class="divide-y divide-myPrimaryMediumGrayColor">
                        <Disclosure
                            as="div"
                            v-for="faq in faqs"
                            :key="faq.question"
                            v-slot="{ open }"
                        >
                            <dt>
                                <DisclosureButton
                                    class="py-6 flex w-full items-center justify-between text-left myPrimaryParagraph"
                                >
                                    <span class="myPrimaryParagraph font-medium"
                                        >{{ faq.question }}
                                    </span>
                                    <span class="ml-6 flex h-7 items-center">
                                        <PlusSmallIcon
                                            v-if="!open"
                                            class="h-5 w-5"
                                            aria-hidden="true"
                                        />
                                        <MinusSmallIcon
                                            v-else
                                            class="h-5 w-5"
                                            aria-hidden="true"
                                        />
                                    </span>
                                </DisclosureButton>
                            </dt>
                            <DisclosurePanel as="dd" class="mt-2 pr-12 pb-16">
                                <p
                                    class="myPrimaryParagraph whitespace-pre-line"
                                >
                                    {{ faq.answer }}
                                </p>
                            </DisclosurePanel>
                        </Disclosure>
                    </dl>
                </div>
            </div>
        </template>
    </FullWidthElement>
</template>