<script setup>
import { ref } from "vue";
// dropdown start
import {
    Listbox,
    ListboxButton,
    ListboxLabel,
    ListboxOption,
    ListboxOptions,
} from "@headlessui/vue";
import { CheckIcon, ChevronUpDownIcon } from "@heroicons/vue/20/solid";

const props = defineProps({
    modelValue: {
        required: true,
    },
    categories: {
        required: true,
    },
    selectedOnLoad: {
        required: false,
    },
    placeholderButton: {
        required: true,
    },
    placeholderInput: {
        required: true,
    },
});

const input = ref(null);
const selected = ref(null);

const emit = defineEmits([
    "update:modelValue",
    "selectedUpdated",
    "firstButtonClick",
]);

if (
    props.selectedOnLoad === null ||
    (props.selectedOnLoad && props.selectedOnLoad.length === 0)
) {
    selected.value = "Select Category";
}
if (
    props.selectedOnLoad === true ||
    (props.selectedOnLoad && props.selectedOnLoad.length >= 1)
) {
    selected.value = props.selectedOnLoad;
}

// first button function
const firstButtonClick = function () {
    emit("firstButtonClick");
};
const selectedUpdated = function (chosen) {
    emit("selectedUpdated", chosen);
};

// dropdown end
</script>
<template>
    <div class="flex gap-2 myPrimarySection">
        <Listbox as="div" v-model="selected">
            <div class="relative md:min-w-[9rem] min-w-[7rem]">
                <ListboxButton class="myPrimarySelect">
                    <span class="block truncate">{{ selected }}</span>
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
                        class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-2 ring-myPrimaryBrandColor ring-opacity-5 focus:outline-none sm:text-sm"
                    >
                        <ListboxOption
                            as="template"
                            @click="selectedUpdated(category)"
                            v-for="category in categories"
                            :key="category"
                            :value="category"
                            v-slot="{ active, selected }"
                        >
                            <li
                                :class="[
                                    active
                                        ? 'text-white bg-myPrimaryBrandColor'
                                        : 'text-gray-900',
                                    'relative cursor-default select-none py-2 pl-8 pr-4',
                                ]"
                            >
                                <span
                                    :class="[
                                        selected
                                            ? 'font-semibold'
                                            : 'font-normal',
                                        'block truncate',
                                    ]"
                                    >{{ category }}</span
                                >

                                <span
                                    v-if="selected"
                                    :class="[
                                        active
                                            ? 'text-white'
                                            : 'text-myPrimaryBrandColor',
                                        'absolute inset-y-0 left-0 flex items-center pl-1.5',
                                    ]"
                                >
                                    <CheckIcon
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
        <!-- drop down end -->
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
                        stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                    ></path>
                </svg>
            </div>
            <input
                :value="modelValue"
                @input="$emit('update:modelValue', $event.target.value)"
                type="search"
                id="search_query"
                class="myPrimarySearchInput"
                autocomplete="off"
                :placeholder="placeholderInput"
            />
        </div>
        <button @click="firstButtonClick" type="button" class="myPrimaryButton">
            {{ placeholderButton }}
        </button>
    </div>
</template>