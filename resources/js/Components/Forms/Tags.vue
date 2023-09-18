<script setup>
import { computed, onMounted, ref, watch } from "vue";

const props = defineProps({
    tagsOnLoad: {
        type: String,
    },
    clearTags: {
        required: true,
    },
});

const clearTagsStatus = computed(() => {
    return props.clearTags;
});

watch(clearTagsStatus, () => {
    tagsEntered.value = [];
});

const error = ref(null);
const vueTag = ref("");
const tagsEntered = ref([]);
const emit = defineEmits(["handleTags"]);
const regexSpecialCharacters = /[ `!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~]/;

// handle input
const handleInput = function () {
    // error
    error.value = null;
    // spaces are not allowed
    if (vueTag.value.includes(" ")) {
        error.value = "Spaces not allowed.";
        return;
    }
    // check for empty tag and special characters
    if (vueTag.value === "" || regexSpecialCharacters.test(vueTag.value)) {
        error.value = "Tag can not be empty or includes special characters.";
        return;
    }

    // check if tag already have been added
    if (tagsEntered.value.includes(vueTag.value)) {
        error.value = "This tag have already been added.";
        return;
    }
    // add to tags entered & remove all whitespaces
    if (!tagsEntered.value.includes(vueTag.value)) {
        tagsEntered.value.push(vueTag.value.trim());
        emit("handleTags", tagsEntered.value.toString(","));
    }
    // clear vue model for tag
    vueTag.value = "";
};

// delete tag
const deleteTag = function (e) {
    tagsEntered.value = tagsEntered.value.filter((tag) => {
        return tag !== e.target.getAttribute("data-tag");
    });
    emit("handleTags", tagsEntered.value.toString(","));
};

onMounted(() => {
    if (typeof props.tagsOnLoad === "string" && props.tagsOnLoad.length !== 0) {
        tagsEntered.value = props.tagsOnLoad.split(",");
    }
    if (typeof props.tagsOnLoad === "string" && props.tagsOnLoad.length === 0) {
        tagsEntered.value = [];
    }
});
</script>

<template>
    <div>
        <div class="flex gap-2 items-center flex-wrap">
            <TransitionGroup name="tags">
                <template v-for="tag in tagsEntered" :key="tag">
                    <div
                        :data-tag="tag"
                        @click="deleteTag"
                        class="hover:text-white flex flex-wrap justify-center items-center gap-2 myPrimaryTag hover:bg-red-500 cursor-pointer"
                    >
                        <p :data-tag="tag">
                            {{ tag }}
                            <span :data-tag="tag">×</span>
                        </p>
                    </div>
                </template>
            </TransitionGroup>
        </div>
    </div>

    <div class="flex mt-3">
        <div
            class="mt-1 relative flex items-center w-full border myPrimaryInput py-0 p-0"
        >
            <input
                v-model="vueTag"
                type="text"
                class="myPrimaryInput border-none rounded-r-none ml-0 w-full"
                placeholder="Enter tags.."
                @keydown.enter.tab.prevent="handleInput"
                autocomplete="off"
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
    <p class="myPrimaryParagraphError">{{ error }}</p>
</template>
