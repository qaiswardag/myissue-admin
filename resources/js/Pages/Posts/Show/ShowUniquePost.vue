<script setup>
import GuestsLayout from "@/Layouts/GuestsLayout.vue";
import SubmitButton from "@/Components/Buttons/SubmitButton.vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import DynamicModal from "@/Components/Modals/DynamicModal.vue";
import DefaultTemplate from "@/Components/PageTemplates/DefaultTemplate.vue";
import FullWidthElement from "@/Components/Layouts/FullWidthElement.vue";
import { parseISO, format } from "date-fns";
import ArticleTemplate from "../../../Components/PageTemplates/ArticleTemplate.vue";

defineProps({
    post: {
        required: true,
    },
    authors: {
        required: false,
    },
});
</script>
<template>
    <div>
        <div class="myPrimaryMainPageHeaderParagraph my-0 py-0">
            <h1 class="myPrimaryMainPageHeaderNotLoggedIn">
                {{ post.title }}
            </h1>
        </div>
        <ArticleTemplate :sidebarArea="true" :actionsArea="false">
            <template #main>
                <div>
                    <div
                        v-if="post.cover_image_large !== null"
                        class="bg-green-200 rounded-md"
                    >
                        <img
                            :src="`/storage/uploads/${post.cover_image_large}`"
                            alt=""
                            class="inset-0 -z-10 w-full object-cover"
                        />
                    </div>

                    <div class="py-4 my-4">
                        <ul class="flex flex-wrap gap-y-0 gap-x-2 mt-2">
                            <li
                                v-for="tag in post.tags.split(',')"
                                :key="tag"
                                class="myPrimaryParagraph leading-4 font-medium cursor-pointer flex-none"
                            >
                                <span>
                                    {{ tag }}
                                </span>
                            </li>
                        </ul>

                        <p
                            class="flex gap-4 items center myPrimaryParagraph font-medium mt-2 mb-2 text-[10px] text-myPrimaryMediumGrayColor"
                        >
                            <span>
                                Created:
                                {{
                                    format(
                                        parseISO(post.created_at),
                                        "dd/MM/yyyy HH:mm"
                                    )
                                }}
                            </span>
                            <span>
                                Updated:
                                {{
                                    format(
                                        parseISO(post.updated_at),
                                        "dd/MM/yyyy HH:mm"
                                    )
                                }}
                            </span>
                        </p>
                    </div>
                    <p v-html="post.content" class="myPrimaryParagraph"></p>
                </div>
            </template>

            <template #sidebar>
                <div class="flex gap-8 flex-col">
                    <!-- Post created / updated by - start -->
                    <div v-if="post.updatedBy !== null" class="myPrimaryWidget">
                        <h1 class="myTertiaryHeader">Updated By</h1>

                        <div
                            class="p-2 rounded-md min-h-[4rem] max-h-[18rem] flex flex-col w-full bg-gray-100 hover:bg-white divide-y divide-gray-200"
                        >
                            <div class="px-2">
                                <div class="rounded">
                                    <div class="flex items-center gap-2 my-4">
                                        <div
                                            v-if="
                                                post.updatedBy
                                                    .profile_photo_path !== null
                                            "
                                            class="flex-shrink-0"
                                        >
                                            <img
                                                class="object-cover w-12 h-12 rounded-full"
                                                :src="`/storage/${post.updatedBy.profile_photo_path}`"
                                                alt="User Image
                                            
                                        "
                                            />
                                        </div>

                                        <div
                                            v-if="
                                                post.updatedBy
                                                    .profile_photo_path === null
                                            "
                                            class="flex-shrink-0 w-12 h-12 rounded-full bg-myPrimaryBrandColor flex justify-center items-center text-xs font-normal text-white"
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
                                            class="flex flex-col items-left gap-0.5 myPrimaryParagraph text-xs"
                                        >
                                            <p class="font-medium">
                                                {{ post.updatedBy.first_name }}
                                                {{ post.updatedBy.last_name }}
                                            </p>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Post created / updated by - end -->
                    <!-- Trash - start -->
                    <div v-if="post.trash === 1" class="myPrimaryWidget">
                        <h1 class="myTertiaryHeader">Trash</h1>
                        <p
                            class="bg-red-100 inline-flex rounded-full px-2 font-medium leading-5 text-myPrimaryErrorColor"
                        >
                            Deleted
                        </p>
                    </div>
                    <!-- Trash - end -->
                    <!-- Published - start -->
                    <div v-if="post.published === 0" class="myPrimaryWidget">
                        <h1 class="myTertiaryHeader">Published</h1>
                        <p
                            class="bg-red-100 inline-flex rounded-full px-2 font-medium leading-5 text-myPrimaryErrorColor"
                        >
                            Private
                        </p>
                    </div>
                    <div v-if="post.published !== 0" class="myPrimaryWidget">
                        <h1 class="myTertiaryHeader">Published</h1>
                        <p
                            class="myPrimaryParagraph bg-green-100 inline-flex rounded-full px-2 font-medium leading-5 text-green-800"
                        >
                            Published
                        </p>
                    </div>
                    <!-- Published - end -->
                    <!-- Show Authors - start -->
                    <div v-if="post.show_author === 0" class="myPrimaryWidget">
                        <h1 class="myTertiaryHeader">Show Authors</h1>
                        <p
                            class="bg-red-100 inline-flex rounded-full px-2 font-medium leading-5 text-myPrimaryErrorColor"
                        >
                            Hidden
                        </p>
                    </div>
                    <div v-if="post.show_author !== 0" class="myPrimaryWidget">
                        <h1 class="myTertiaryHeader">Show Authors</h1>
                        <p
                            class="myPrimaryParagraph bg-green-100 inline-flex rounded-full px-2 font-medium leading-5 text-green-800"
                        >
                            Visible
                        </p>
                    </div>
                    <!-- Show Authors - start -->
                    <!-- List of Authors - start -->
                    <div
                        class="myPrimaryWidget"
                        v-if="
                            post.authors !== null &&
                            Array.isArray(post.authors) &&
                            post.authors.length > 0
                        "
                    >
                        <h1 class="myTertiaryHeader">List of Authors</h1>
                        <div
                            class="p-2 rounded-md min-h-[4rem] max-h-[18rem] flex flex-col w-full overflow-y-scroll border border-myPrimaryLightGrayColor divide-y divide-gray-200"
                        >
                            <div
                                v-for="(author, index) in post.authors"
                                :key="index"
                            >
                                <!-- Author profile box - start -->

                                <div class="rounded">
                                    <div class="flex items-center gap-2 my-4">
                                        <div
                                            v-if="
                                                author.profile_photo_path !==
                                                null
                                            "
                                            class="flex-shrink-0"
                                        >
                                            <img
                                                class="object-cover w-12 h-12 rounded-full"
                                                :src="`/storage/${author.profile_photo_path}`"
                                                alt="User Image
                                            
                                        "
                                            />
                                        </div>

                                        <div
                                            v-if="
                                                author.profile_photo_path ===
                                                null
                                            "
                                            class="flex-shrink-0 w-12 h-12 rounded-full bg-myPrimaryBrandColor flex justify-center items-center text-xs font-normal text-white"
                                        >
                                            {{
                                                author.first_name
                                                    .charAt(0)
                                                    .toUpperCase()
                                            }}
                                            {{
                                                author.last_name
                                                    .charAt(0)
                                                    .toUpperCase()
                                            }}
                                        </div>
                                        <span
                                            class="flex flex-col items-left gap-0.5 myPrimaryParagraph text-xs"
                                        >
                                            <p class="font-medium">
                                                {{ author.first_name }}
                                                {{ author.last_name }}
                                            </p>
                                        </span>
                                    </div>
                                </div>
                                <!-- Author profile box - end -->
                            </div>
                        </div>
                    </div>
                    <!-- List of Authors - end -->
                </div>
            </template>
        </ArticleTemplate>
    </div>
</template>
