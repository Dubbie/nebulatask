<script setup>
import { IconX } from "@tabler/icons-vue";
import {
    computed,
    getCurrentInstance,
    onMounted,
    onUnmounted,
    watch,
} from "vue";

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    maxWidth: {
        type: String,
        default: "2xl",
    },
    closeable: {
        type: Boolean,
        default: true,
    },
});

const emitter = getCurrentInstance().appContext.config.globalProperties.emitter;
const emit = defineEmits(["close"]);

watch(
    () => props.show,
    () => {
        if (props.show) {
            document.body.style.overflow = "hidden";
        } else {
            document.body.style.overflow = null;
        }
    }
);

const close = () => {
    if (props.closeable) {
        emit("close");
    }
};

const closeOnEscape = (e) => {
    if (e.key === "Escape" && props.show) {
        close();
    }
};

onMounted(() => {
    emitter.on("close-slideover", close);
    document.addEventListener("keydown", closeOnEscape);
});

onUnmounted(() => {
    emitter.off("close-slideover", close);
    document.removeEventListener("keydown", closeOnEscape);
    document.body.style.overflow = null;
});

const maxWidthClass = computed(() => {
    return {
        sm: "sm:max-w-sm",
        md: "sm:max-w-md",
        lg: "sm:max-w-lg",
        xl: "sm:max-w-xl",
        "2xl": "sm:max-w-2xl",
    }[props.maxWidth];
});
</script>

<template>
    <teleport to="body">
        <transition leave-active-class="duration-200">
            <div
                v-show="show"
                class="fixed top-16 border-t border-transparent bottom-0 right-0 min-w-[720px] overflow-y-auto overflow-x-hidden pl-8 z-50"
                scroll-region
            >
                <transition
                    enter-active-class="transform transition will-change ease-out-expo duration-500"
                    enter-from-class="translate-x-full"
                    enter-to-class="translate-x-0"
                    leave-active-class="transform transition will-change ease-out-expo duration-500"
                    leave-from-class="translate-x-0"
                    leave-to-class="translate-x-full"
                >
                    <div class="h-full" v-show="show">
                        <div
                            class="fixed -ml-5 mt-7 p-2 z-10 cursor-pointer bg-white border border-zinc-950/10 rounded-full text-zinc-300 hover:text-zinc-500 dark:bg-zinc-950 dark:border-white/15 dark:text-zinc-600"
                            @click="close"
                        >
                            <IconX />
                        </div>

                        <div
                            class="h-full bg-white shadow-xl shadow-zinc-700/5 border-l border-zinc-950/10 transform transition-all sm:w-full dark:bg-zinc-950 dark:text-white dark:border-white/15 dark:shadow-none"
                            :class="maxWidthClass"
                        >
                            <slot v-if="show" />
                        </div>
                    </div>
                </transition>
            </div>
        </transition>
    </teleport>
</template>
