<script setup>
defineProps({
    css: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <div :class="css" class="flex flex-col sm:justify-center items-center">
        <div>
            <slot name="logo" />
        </div>

        <div
            class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white mx.32 border border-gray-100 overflow-hidden sm:rounded-lg opacity-90"
        >
            <slot />
        </div>
    </div>
</template>
