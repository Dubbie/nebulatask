<script setup>
import { IconSquareRounded } from "@tabler/icons-vue";
import { IconCalendarDue } from "@tabler/icons-vue";
import { IconSquareRoundedCheckFilled } from "@tabler/icons-vue";
import { computed, getCurrentInstance, ref, watch } from "vue";
import Dropdown from "@/Components/Dropdown.vue";
import { IconDots } from "@tabler/icons-vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import axios from "axios";
import { IconCheck } from "@tabler/icons-vue";
import AppButton from "./AppButton.vue";
import { IconGripVertical } from "@tabler/icons-vue";
import { IconChevronRight } from "@tabler/icons-vue";
import { IconX } from "@tabler/icons-vue";

const props = defineProps({
    subIssue: {
        type: Object,
        required: true,
    },
    dragging: {
        type: Boolean,
        default: false,
    },
});

const processing = ref(false);
const emitter = getCurrentInstance().appContext.config.globalProperties.emitter;
const preToggle = ref(false);
const completed = computed(() => {
    const isComplete = props.subIssue.is_complete;
    return preToggle.value ? !isComplete : isComplete;
});

const deleteSubIssue = () => {
    if (confirm("Are you sure you want to delete this sub-issue?")) {
        processing.value = true;
        axios
            .delete(
                route("api.sub-issue.destroy", { subIssue: props.subIssue.id })
            )
            .then((response) => {
                emitter.emit("issue-updated", response.data.data);
            })
            .finally(() => {
                processing.value = false;
            });
    }
};

const handleShowSubIssue = () => {
    emitter.emit("show-issue-details", props.subIssue.id);
};

const toggleComplete = () => {
    preToggle.value = true;

    axios
        .put(
            route("api.sub-issue.complete.toggle", {
                subIssue: props.subIssue.id,
            })
        )
        .then((response) => {
            emitter.emit("issue-updated", response.data.data);
        })
        .catch((error) => {
            preToggle.value = false;
        });
};

watch(
    () => props.subIssue,
    (newSubIssue) => {
        if (newSubIssue) {
            preToggle.value = false;
        }
    },
    { immediate: true, deep: true }
);
</script>
<template>
    <div
        class="-ml-6 group/item flex space-x-1 items-center"
        :class="{
            'pointer-events-none opacity-50': processing,
        }"
    >
        <div
            class="handle opacity-0"
            :class="{
                'group-hover/item:opacity-100': !dragging,
            }"
        >
            <IconGripVertical class="text-zinc-500 size-5" />
        </div>

        <div
            class="sub-issue-wrapper rounded-xl border-2 border-transparent flex-1"
        >
            <div
                class="sub-issue bg-white flex items-center space-x-2 py-1 dark:bg-transparent"
            >
                <div class="cursor-pointer" @click="toggleComplete()">
                    <IconSquareRoundedCheckFilled
                        class="text-green-500 hover:text-green-500/60"
                        v-if="completed"
                    />
                    <div class="group/checkbox relative" v-else>
                        <IconCheck
                            :stroke-width="3"
                            class="pointer-events-none absolute mx-1.5 my-1.5 top-0 left-0 text-zinc-300 opacity-0 size-3 group-hover/checkbox:opacity-100"
                        />
                        <IconSquareRounded
                            class="text-zinc-300 group-hover/checkbox:text-zinc-400 dark:text-zinc-600"
                        />
                    </div>
                </div>

                <p
                    class="flex-1 text-sm font-semibold"
                    :class="{ 'line-through text-zinc-300': completed }"
                >
                    {{ subIssue.title }}
                </p>

                <div
                    class="opacity-0 flex items-center space-x-2 group-hover/item:opacity-100"
                >
                    <div class="flex space-x-1">
                        <AppButton
                            size="xs"
                            plain
                            square
                            @click="deleteSubIssue()"
                        >
                            <IconX
                                class="cursor-pointer rounded-lg text-zinc-400 size-5"
                            />
                        </AppButton>
                        <AppButton
                            size="xs"
                            plain
                            square
                            @click="handleShowSubIssue()"
                        >
                            <IconChevronRight
                                class="cursor-pointer rounded-lg text-zinc-400 size-5"
                            />
                        </AppButton>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.ghost .sub-issue-wrapper {
    border: 2px dashed rgba(9, 9, 11, 0.1);
}
.ghost .sub-issue {
    opacity: 0;
}

.chosen .sub-issue {
    opacity: 0;
}
</style>
