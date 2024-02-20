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
    <div class="relative h-full">
        <div
            class="flex items-center justify-center cursor-pointer h-full pt-1 border-b-2 text-sm font-medium leading-5 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out"
            :class="{
                'border-indigo-400 text-zinc-900 dark:border-transparent':
                    active,
                'border-transparent text-zinc-500 hover:text-zinc-700 hover:border-zinc-300 dark:hover:border-transparent':
                    !active,
            }"
            @click="showContent = !showContent"
        >
            <div
                class="px-4 py-2 rounded-xl"
                :class="{
                    'dark:bg-white/15 dark:text-white': active,
                    'dark:hover:bg-white/5 dark:text-zinc-400 dark:hover:text-white':
                        !active,
                }"
            >
                <slot name="trigger" />
            </div>
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
                class="absolute top-full left-1/2 transform -translate-x-1/2 z-10 min-w-64 mt-2 p-4 rounded-2xl shadow-lg shadow-zinc-950/5 bg-white border border-zinc-950/10 dark:bg-white/10 dark:backdrop-blur dark:border-white/15"
            >
                <slot name="content" />
            </div>
        </transition>
    </div>
</template>
