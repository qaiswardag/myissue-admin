<script setup>
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import Breadcrumbs from "@/Components/Breadcrumbs/Breadcrumbs.vue";

const breadcrumbsLinks = [
    {
        label: "Your Teams",
        route: {
            name: "user.teams",
            parameters: null,
        },
    },
];
</script>

<template>
    <LoggedInLayout>
        <Head title="Your Teams" />
        <template #header>
            <h2 class="myPrimaryMainPageHeader">Your Teams</h2>
        </template>
        <template #description>
            This is Team index page ipsum dolor sit, amet consectetur
            adipisicing elit. Animi, distinctio.
        </template>
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
                                    <p class="font-medium">
                                        {{ $page.props.user.first_name }}
                                        {{ $page.props.user.last_name }}
                                    </p>
                                    <p
                                        v-if="
                                            $page.props.currentUserTeamRole
                                                ?.name
                                        "
                                    >
                                        Team Role:
                                        {{
                                            $page.props.currentUserTeamRole
                                                ?.name
                                        }}
                                    </p>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="divide-y divide-gray-200 shadow sm:grid sm:grid-cols-2 sm:gap-px sm:divide-y-0"
            >
                <div
                    class="relative group bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-myPrimaryBrandColor cursor-pointer"
                >
                    <div>
                        <span
                            class="rounded bg-gray-50 text-myPrimaryBrandColor justify-center inline-flex p-3 ring-4 ring-white"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="text-myPrimaryBrandColor flex-shrink-0 h-6 w-6"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"
                                />
                            </svg>
                        </span>
                    </div>
                    <div class="mt-8">
                        <h3 class="text-lg font-normal">
                            <Link
                                :href="route('user.teams.switch')"
                                class="focus:outline-none"
                            >
                                <span
                                    class="absolute inset-0"
                                    aria-hidden="true"
                                />
                                Switch Teams
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

                <div
                    class="relative group bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-myPrimaryBrandColor cursor-pointer"
                >
                    <div>
                        <span
                            class="rounded bg-gray-50 text-myPrimaryBrandColor justify-center inline-flex p-3 ring-4 ring-white"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="text-myPrimaryBrandColor flex-shrink-0 h-6 w-6"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                        </span>
                    </div>
                    <div class="mt-8">
                        <h3 class="text-lg font-normal">
                            <Link
                                :href="route('user.teams.create')"
                                class="focus:outline-none"
                            >
                                <span
                                    class="absolute inset-0"
                                    aria-hidden="true"
                                />
                                Create Team
                            </Link>
                        </h3>
                        <p class="mt-2 text-sm text-gray-500">
                            Browser Sessions and Profile deletion
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
