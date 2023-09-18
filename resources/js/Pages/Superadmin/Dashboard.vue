<script setup>
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import {
    AcademicCapIcon,
    BanknotesIcon,
    CheckBadgeIcon,
    ClockIcon,
    ReceiptRefundIcon,
    UsersIcon,
    BeakerIcon,
} from "@heroicons/vue/24/outline";

import Breadcrumbs from "@/Components/Breadcrumbs/Breadcrumbs.vue";

const breadcrumbsLinks = [
    {
        label: "Admin Dashboard",
        route: {
            name: "admin.dashboard",
            parameters: null,
        },
    },
];

const actions = [
    {
        title: "Users",
        href: "admin.users",
        icon: UsersIcon,
        iconForeground: "text-myPrimaryBrandColor",
        iconBackground: "bg-gray-50",
    },
];
</script>

<template>
    <LoggedInLayout>
        <Head title="Dashboard" />
        <template #header>
            <h2 class="myPrimaryMainPageHeader">Super Admininistrator</h2>
        </template>

        <template #description>Super Admininistrator Dashboard </template>

        <template #breadcrumbs>
            <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
        </template>

        <div class="myPrimarySection">
            <div
                class="mb-6 py-6 px-6 relative group bg-white focus-within:ring-2 focus-within:ring-inset focus-within:ring-myPrimaryBrandColor"
            >
                <div
                    class="p-2 rounded-md min-h-[4rem] max-h-[18rem] flex flex-col w-full border border-myPrimaryLightGrayColor divide-y divide-gray-200"
                >
                    <div class="hover:bg-white px-2">
                        <div class="rounded">
                            <!-- start photo -->
                            <div class="flex items-center gap-2 my-4">
                                <div
                                    v-if="
                                        $page.props.user.profile_photo_path !==
                                        null
                                    "
                                    class="flex-shrink-0"
                                >
                                    <img
                                        class="object-cover w-12 h-12 rounded-full"
                                        :src="`/storage/${$page.props.user.profile_photo_path}`"
                                        :alt="
                                            $page.props.user.first_name +
                                            $page.props.user.last_name
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
                                <div
                                    class="flex flex-col items-left gap-0.5 myPrimaryParagraph text-xs"
                                >
                                    <p class="font-medium">
                                        {{ $page.props.user.first_name }}
                                        {{ $page.props.user.last_name }}
                                    </p>
                                    <p>
                                        Superadmin Role:
                                        <span
                                            v-if="
                                                $page.props.user.superadmin &&
                                                $page.props.user.superadmin
                                                    .role === 'reader'
                                            "
                                        >
                                            {{
                                                $page.props.user.superadmin &&
                                                $page.props.user.superadmin.role
                                                    .charAt(0)
                                                    .toUpperCase() +
                                                    $page.props.user.superadmin.role.slice(
                                                        1
                                                    )
                                            }}
                                        </span>
                                        <span
                                            v-if="
                                                $page.props.user.superadmin &&
                                                $page.props.user.superadmin
                                                    .role === 'editor'
                                            "
                                        >
                                            {{
                                                $page.props.user.superadmin &&
                                                $page.props.user.superadmin.role
                                                    .charAt(0)
                                                    .toUpperCase() +
                                                    $page.props.user.superadmin.role.slice(
                                                        1
                                                    )
                                            }}
                                        </span>
                                        <span
                                            v-if="
                                                $page.props.user.superadmin &&
                                                $page.props.user.superadmin
                                                    .role === 'admin'
                                            "
                                        >
                                            {{
                                                $page.props.user.superadmin &&
                                                $page.props.user.superadmin.role
                                                    .charAt(0)
                                                    .toUpperCase() +
                                                    $page.props.user.superadmin.role.slice(
                                                        1
                                                    )
                                            }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="divide-y divide-gray-200 shadow sm:grid sm:grid-cols-2 sm:gap-px sm:divide-y-0"
            >
                <div
                    v-for="(action, actionIdx) in actions"
                    :key="action.title"
                    :class="[
                        actionIdx === 0 ? '' : '',
                        actionIdx === 1 ? '' : '',
                        actionIdx === actions.length - 2 ? '' : '',
                        actionIdx === actions.length - 1 ? '' : '',
                        'relative group bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-myPrimaryBrandColor cursor-pointer',
                    ]"
                >
                    <div>
                        <span
                            :class="[
                                action.iconBackground,
                                action.iconForeground,
                                'inline-flex p-3 ring-4 ring-white',
                            ]"
                        >
                            <component
                                :is="action.icon"
                                class="h-6 w-6"
                                aria-hidden="true"
                            />
                        </span>
                    </div>
                    <div class="mt-8">
                        <h3 class="text-lg font-normal">
                            <Link
                                :href="route(action.href)"
                                class="focus:outline-none"
                            >
                                <!-- Extend touch target to entire panel -->
                                <span
                                    class="absolute inset-0"
                                    aria-hidden="true"
                                />
                                {{ action.title }}
                            </Link>
                        </h3>
                        <p class="mt-2 text-sm text-gray-500">
                            Doloribus dolores nostrum quia qui natus officia
                            quod et dolorem. Sit repellendus qui ut at
                            blanditiis et quo et molestiae.
                        </p>
                    </div>
                    <span
                        class="pointer-events-none absolute top-6 right-6 text-gray-300 group-hover:text-gray-400"
                        aria-hidden="true"
                    >
                        <svg
                            class="h-6 w-6"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                d="M20 4h1a1 1 0 00-1-1v1zm-1 12a1 1 0 102 0h-2zM8 3a1 1 0 000 2V3zM3.293 19.293a1 1 0 101.414 1.414l-1.414-1.414zM19 4v12h2V4h-2zm1-1H8v2h12V3zm-.707.293l-16 16 1.414 1.414 16-16-1.414-1.414z"
                            />
                        </svg>
                    </span>
                </div>
            </div>
        </div>
    </LoggedInLayout>
</template>
