<script setup>
import Modal from "./Modal.vue";

const emit = defineEmits(["close", "submit"]);

defineProps({
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

const close = () => {
    emit("close");
};
</script>

<template>
    <Modal
        :show="show"
        :max-width="maxWidth"
        :closeable="closeable"
        @close="close"
    >
        <form @submit.prevent="$emit('submit')">
            <div class="p-8 pb-0">
                <div
                    class="text-lg/6 text-balance font-semibold text-zinc-950 sm:text-base/6"
                >
                    <slot name="title" />
                </div>
                <div
                    class="text-pretty text-base/6 text-zinc-500 mt-2 sm:text-sm/6"
                    v-if="$slots.description"
                >
                    <slot name="description" />
                </div>

                <div class="mt-4 text-sm text-gray-600">
                    <slot name="content" />
                </div>
            </div>

            <div class="flex flex-row space-x-2 justify-end p-8 text-end">
                <slot name="footer" />
            </div>
        </form>
    </Modal>
</template>
