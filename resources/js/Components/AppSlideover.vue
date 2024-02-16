<script setup>
import { IconX } from "@tabler/icons-vue";
import { computed, onMounted, onUnmounted, watch } from "vue";

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

const close = (event) => {
    console.log(event.target);
    if (props.closeable) {
        emit("close");
    }
};

const closeOnEscape = (e) => {
    if (e.key === "Escape" && props.show) {
        close();
    }
};

onMounted(() => document.addEventListener("keydown", closeOnEscape));

onUnmounted(() => {
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
                class="fixed top-16 border-t border-transparent bottom-0 right-0 min-w-[720px] overflow-y-auto pl-8 z-50"
                scroll-region
            >
                <transition
                    enter-active-class="ease-out duration-300"
                    enter-from-class="opacity-0 translate-x-10"
                    enter-to-class="opacity-100 translate-x-0"
                    leave-active-class="ease-in duration-200"
                    leave-from-class="opacity-100 translate-x-0"
                    leave-to-class="opacity-0 translate-x-10"
                >
                    <div class="h-full" v-show="show">
                        <div
                            class="fixed -ml-5 mt-7 p-2 z-10 cursor-pointer bg-white border border-zinc-950/10 rounded-full text-zinc-300 hover:text-zinc-500"
                            @click="close"
                        >
                            <IconX />
                        </div>

                        <div
                            class="h-full bg-white shadow-xl shadow-zinc-700/5 border-l border-zinc-950/10 transform transition-all sm:w-full"
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
