<script setup>
import { ref } from "vue";

const { active } = defineProps({
    active: {
        type: Boolean,
        default: false,
    },
});

const showContent = ref(false);
</script>

<template>
    <div
        class="relative inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out"
        :class="{
            'text-gray-900 border-indigo-400': active,
            'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300':
                !active,
        }"
    >
        <div class="cursor-pointer" @click="showContent = !showContent">
            <slot name="trigger" />
        </div>

        <div
            v-if="showContent"
            class="fixed inset-0 z-10"
            @click="showContent = false"
        />

        <transition
            enter-active-class="transition ease-in-out duration-200"
            enter-from-class="opacity-0 translate-y-2"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100 translate-y-0"
            leave-to-class="opacity-0 translate-y-2"
        >
            <div
                v-show="showContent"
                class="absolute top-full left-1/2 transform -translate-x-1/2 z-10 min-w-64 mt-2 p-4 rounded-2xl shadow-lg shadow-zinc-950/5 bg-white border border-zinc-950/10"
            >
                <slot name="content" />
            </div>
        </transition>
    </div>
</template>
