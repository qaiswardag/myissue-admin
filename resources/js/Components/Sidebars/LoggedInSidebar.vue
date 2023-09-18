<script setup>
import { router } from "@inertiajs/vue3";
import SideBarLink from "@/Components/MenuLinks/SideBarLink.vue";
import {
    WrenchIcon,
    FlagIcon,
    StarIcon,
    FolderIcon,
    ShoppingBagIcon,
    TagIcon,
    DocumentTextIcon,
    Squares2X2Icon,
    UserIcon,
    PhotoIcon,
    Cog6ToothIcon,
    Cog8ToothIcon,
    Square3Stack3DIcon,
} from "@heroicons/vue/24/outline";
</script>

<template>
    <nav class="flex-1 space-y-2 px-2 pb-4" aria-label="menu">
        <template
            v-if="
                $page.props.user.all_teams.length > 0 &&
                $page.props.user.current_team &&
                $page.props.jetstream.hasTeamFeatures
            "
        >
            <p
                class="text-myPrimaryDarkGrayColor justify-between group flex items-center px-2 py-2 text-xs font-normal border-b border-gray-200"
            >
                <span>Team:</span>

                <Link
                    :href="route('user.teams.switch')"
                    class="flex justify-center gap-2 items-center text-xs px-2 rounded-full ml-2 py-1.5 hover:ring-1 hover:ring-myPrimaryBrandColor hover:bg-gray-50 ring-1 ring-gray-100"
                    :class="{
                        myPrimaryLink: route().current('user.teams.switch'),
                    }"
                >
                    <template
                        v-if="
                            $page.props.currentUserTeam?.logo_thumbnail !== null
                        "
                    >
                        <div class="h-8 w-8 flex-shrink-0">
                            <img
                                class="w-8 h-8 rounded-full object-cover cursor-pointer"
                                :src="`/storage/uploads/${$page.props.currentUserTeam?.logo_thumbnail}`"
                                alt="Logo"
                            />
                        </div>
                    </template>
                    {{ $page.props.currentUserTeam?.name }}
                </Link>
            </p>
            <p
                class="text-myPrimaryDarkGrayColor justify-between group flex items-center px-2 py-2 text-xs font-normal border-b border-gray-200"
            >
                <span>Role:</span>
                <Link
                    :href="route('user.teams.switch')"
                    class="text-xs px-2 rounded-full ml-2 py-1.5 hover:ring-1 hover:ring-myPrimaryBrandColor hover:bg-gray-50 ring-1 ring-gray-100"
                    :class="{
                        myPrimaryLink: route().current('user.teams.switch'),
                    }"
                >
                    {{ $page.props.currentUserTeamRole?.name }}
                </Link>
            </p>
        </template>

        <!--  -->
        <template
            v-if="
                $page.props.user.all_teams.length > 0 &&
                !$page.props.user.current_team &&
                $page.props.jetstream.hasTeamFeatures
            "
        >
            <p
                class="text-myPrimaryDarkGrayColor justify-between group flex items-center px-2 py-2 text-xs font-normal border-b border-gray-200"
            >
                <span>Team:</span>

                <Link
                    :href="route('user.teams.switch')"
                    class="flex justify-center gap-2 items-center text-xs px-2 rounded-full ml-2 py-1.5 hover:ring-1 hover:ring-myPrimaryBrandColor hover:bg-gray-50 ring-1 ring-gray-100"
                    :class="{
                        myPrimaryLink: route().current('user.teams.switch'),
                    }"
                >
                    No Team selected
                </Link>
            </p>
        </template>
        <!--  -->
        <template
            v-if="
                $page.props.user.all_teams.length === 0 &&
                $page.props.user.current_team === null &&
                $page.props.jetstream.hasTeamFeatures
            "
        >
            <p
                class="text-myPrimaryDarkGrayColor justify-between group flex items-center px-2 py-2 text-xs font-normal border-b border-gray-200"
            >
                <span>Team:</span>

                <Link
                    :href="route('user.teams.create')"
                    class="flex justify-center gap-2 items-center text-xs px-2 rounded-full ml-2 py-1.5 hover:ring-1 hover:ring-myPrimaryBrandColor hover:bg-gray-50 ring-1 ring-gray-100"
                    :class="{
                        myPrimaryLink: route().current('user.teams.create'),
                    }"
                >
                    Create a Team
                </Link>
            </p>
        </template>

        <SideBarLink
            :href="route('dashboard')"
            :active="route().current('dashboard')"
        >
            <Squares2X2Icon
                class="text-myPrimaryDarkGrayColor mr-4 flex-shrink-0 h-6 w-6"
            ></Squares2X2Icon>
            Dashboard
        </SideBarLink>
        <SideBarLink
            :href="route('profile.show')"
            :active="
                route().current('profile.show') ||
                route().current('user.profile.update') ||
                route().current('user.profile.password') ||
                route().current('user.profile.security')
            "
        >
            <UserIcon
                class="text-myPrimaryDarkGrayColor mr-4 flex-shrink-0 h-6 w-6"
            ></UserIcon>
            Your Profile
        </SideBarLink>

        <template
            v-if="
                $page.props.user.all_teams.length > 0 &&
                $page.props.user.current_team &&
                $page.props.jetstream.hasTeamFeatures
            "
        >
            <SideBarLink
                :href="
                    route(
                        'media.index',
                        $page.props.user.current_team.reference_id
                    )
                "
                :active="route().current('media.index')"
            >
                <PhotoIcon
                    class="text-myPrimaryDarkGrayColor mr-4 flex-shrink-0 h-6 w-6"
                ></PhotoIcon>
                Media Team
            </SideBarLink>
        </template>

        <template
            v-if="
                $page.props.user.all_teams.length > 0 &&
                $page.props.user.current_team &&
                $page.props.jetstream.hasTeamFeatures
            "
        >
            <SideBarLink
                :href="
                    route(
                        'team.posts.index',
                        $page.props.user.current_team.reference_id
                    )
                "
                :active="
                    route().current('team.posts.index') ||
                    route().current('team.posts.create') ||
                    route().current('team.posts.post.edit')
                "
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="text-myPrimaryDarkGrayColor mr-4 flex-shrink-0 h-6 w-6"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"
                    />
                </svg>
                Team Posts
            </SideBarLink>
        </template>
        <template
            v-if="
                $page.props.user.all_teams.length > 0 &&
                $page.props.user.current_team &&
                $page.props.jetstream.hasTeamFeatures
            "
        >
            <SideBarLink
                :href="
                    route(
                        'teams.show',
                        $page.props.user.current_team.reference_id
                    )
                "
                :active="
                    route().current('teams.show') ||
                    route().current('team.update.information') ||
                    route().current('team.members') ||
                    route().current('team.delete')
                "
            >
                <Cog6ToothIcon
                    class="text-myPrimaryDarkGrayColor mr-4 flex-shrink-0 h-6 w-6"
                ></Cog6ToothIcon>
                Team Settings
            </SideBarLink>
        </template>

        <SideBarLink
            :href="route('user.teams')"
            :active="
                route().current('user.teams') ||
                route().current('user.teams.switch') ||
                route().current('user.teams.create')
            "
        >
            <Square3Stack3DIcon
                class="text-myPrimaryDarkGrayColor mr-4 flex-shrink-0 h-6 w-6"
            ></Square3Stack3DIcon>
            All Teams
        </SideBarLink>
        <SideBarLink
            v-if="$page.props.user.superadmin !== null"
            :href="route('admin.dashboard')"
            :active="
                route().current('admin.dashboard') ||
                route().current('admin.users')
            "
        >
            <WrenchIcon
                class="text-myPrimaryDarkGrayColor mr-4 flex-shrink-0 h-6 w-6"
            >
            </WrenchIcon>
            Superadmin
        </SideBarLink>
    </nav>
</template>
