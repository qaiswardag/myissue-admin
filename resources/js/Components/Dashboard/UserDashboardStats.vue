<script setup>
import FullWidthElement from "@/Components/Layouts/FullWidthElement.vue";
import { MinusSmallIcon, PlusSmallIcon } from "@heroicons/vue/24/outline";
import { Disclosure, DisclosureButton, DisclosurePanel } from "@headlessui/vue";
import EmptySectionBorder from "@/Components/Sections/EmptySectionBorder.vue";
import { useStore } from "vuex";
import { onMounted, computed } from "vue";
import { parseISO, format } from "date-fns";

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
            <div v-if="$page.props && $page.props.currentUserTeam === null">
                <p class="myPrimaryParagraph">
                    Looks like no Team is Selected!
                </p>
            </div>
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
                    <Link
                        :href="
                            route(
                                'team.members',
                                $page.props.user.current_team.reference_id
                            )
                        "
                    >
                        <h2
                            class="my-2 mb-4 myFourthHeader text-myPrimaryLinkColor hover:underline"
                        >
                            Latest Team Members
                        </h2>
                    </Link>

                    <div class="min-h-[36rem] max-h-[36rem] overflow-y-scroll">
                        <div
                            v-if="
                                Array.isArray(
                                    getDashboardStats.fetchedData
                                        .latestTeamMembers
                                ) &&
                                getDashboardStats.fetchedData.latestTeamMembers
                                    .length === 0
                            "
                        >
                            <p class="myPrimaryParagraph">
                                Looks like there are no Team Members.
                            </p>
                        </div>
                        <ul role="list" class="grid grid-cols-1 myPrimaryGap">
                            <li
                                v-for="member in getDashboardStats.fetchedData
                                    .latestTeamMembers"
                                :key="member.id"
                                class="p-2 rounded-md min-h-[2rem] max-h-[6rem] flex flex-col w-full border border-myPrimaryLightGrayColor divide-y divide-gray-200 bg-white hover:border-myPrimaryMediumGrayColor"
                            >
                                <!-- start photo -->
                                <div class="flex items-center gap-4 my-2">
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
                    <Link
                        :href="
                            route(
                                'media.index',
                                $page.props.user.current_team.reference_id
                            )
                        "
                    >
                        <h2
                            class="my-2 mb-4 myFourthHeader text-myPrimaryLinkColor hover:underline"
                        >
                            Latest Team Images
                        </h2>
                    </Link>

                    <div class="min-h-[36rem] max-h-[36rem] overflow-y-scroll">
                        <div
                            v-if="
                                Array.isArray(
                                    getDashboardStats.fetchedData.latestMedia
                                ) &&
                                getDashboardStats.fetchedData.latestMedia
                                    .length === 0
                            "
                        >
                            <p class="myPrimaryParagraph">
                                Looks like there are no Images.
                            </p>
                        </div>
                        <ul
                            role="list"
                            class="grid grid-cols-[repeat(auto-fit,minmax(6rem,1fr))] gap-y-6 gap-x-6"
                        >
                            <li
                                v-for="file in getDashboardStats.fetchedData
                                    .latestMedia"
                                :key="file.id"
                                class="whitespace-pre-line flex-1 h-auto rounded pb-12"
                            >
                                <img
                                    :src="`/storage/uploads/${file.medium_path}`"
                                    alt="Image"
                                    class="pointer-events-none object-cover group-hover:opacity-75 cursor-pointer rounded"
                                />
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- column end -->
                <!-- column start -->
                <div
                    class="lg:col-span-6 w-full rounded py-10 px-4 bg-white h-full"
                >
                    <Link
                        :href="
                            route(
                                'team.posts.index',
                                $page.props.user.current_team.reference_id
                            )
                        "
                    >
                        <h2
                            class="my-2 mb-4 myFourthHeader text-myPrimaryLinkColor hover:underline"
                        >
                            Latest Team Posts
                        </h2>
                    </Link>

                    <div class="min-h-[36rem] max-h-[36rem] overflow-y-scroll">
                        <div
                            v-if="
                                Array.isArray(
                                    getDashboardStats.fetchedData.latestPosts
                                ) &&
                                getDashboardStats.fetchedData.latestPosts
                                    .length === 0
                            "
                        >
                            <p class="myPrimaryParagraph">
                                Looks like there are no Posts.
                            </p>
                        </div>
                        <ul
                            role="list"
                            class="grid grid-cols-[repeat(auto-fit,minmax(8rem,1fr))] gap-y-6 gap-x-6"
                        >
                            <li
                                v-for="post in getDashboardStats.fetchedData
                                    .latestPosts"
                                :key="post.id"
                                class="whitespace-pre-line flex-1 bg-gray-100 h-auto rounded pb-2"
                            >
                                <!-- start photo -->

                                <template
                                    v-if="
                                        post && post.cover_image_medium !== null
                                    "
                                >
                                    <img
                                        class="pointer-events-none object-cover group-hover:opacity-75 cursor-pointer"
                                        :src="`/storage/uploads/${post.cover_image_medium}`"
                                        alt="Image"
                                    />
                                </template>

                                <div class="px-2 pb-2">
                                    <ul
                                        class="flex flex-wrap gap-y-0 gap-x-2 mt-2"
                                    >
                                        <li
                                            v-for="tag in post.tags &&
                                            post.tags.split(',')"
                                            :key="tag"
                                            class="myPrimaryParagraph leading-4 font-medium cursor-pointer flex-none"
                                        >
                                            <span class="text-[10px] uppercase">
                                                {{ tag }}
                                            </span>
                                        </li>
                                    </ul>
                                    <p
                                        class="myPrimaryParagraph text-sm font-medium mt-2 mb-2"
                                    >
                                        {{ post.title.slice(0, 12) }}..
                                    </p>
                                    <p
                                        v-html="
                                            `${post.content.slice(0, 24)}..`
                                        "
                                        class="myPrimaryParagraph text-xs"
                                    ></p>
                                    <p
                                        class="myPrimaryParagraph font-medium mt-2 mb-2 text-[10px] text-myPrimaryMediumGrayColor"
                                    >
                                        Updated:
                                        {{
                                            format(
                                                parseISO(post.updated_at),
                                                "dd/MM/yyyy HH:mm"
                                            )
                                        }}
                                    </p>
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
                    <h2
                        class="my-2 mb-4 myFourthHeader text-myPrimaryLinkColor"
                    >
                        Latest Team Stats
                    </h2>

                    <div class="min-h-[36rem] max-h-[36rem] overflow-y-scroll">
                        <div class="mx-auto">
                            <h3 class="myFourthHeader">
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
