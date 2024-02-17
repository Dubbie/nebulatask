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

const props = defineProps({
    subIssue: {
        type: Object,
        required: true,
    },
});

const emitter = getCurrentInstance().appContext.config.globalProperties.emitter;
const showActions = ref(false);
const preToggle = ref(false);
const completed = computed(() => {
    const isComplete = props.subIssue.issue_status_id === 4;
    return preToggle.value ? !isComplete : isComplete;
});

const deleteSubIssue = () => {
    const parentIssueId = props.subIssue.parent_issue_id;
    if (confirm("Are you sure you want to delete this sub-issue?")) {
        axios
            .delete(
                route("api.sub-issue.destroy", { subIssue: props.subIssue.id })
            )
            .then((response) => {
                emitter.emit("update-issue", parentIssueId);
            });
    }
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
            emitter.emit("update-issue", props.subIssue.parent_issue_id);
        });
};

watch(props, (newProps) => {
    if (newProps.subIssue) {
        preToggle.value = false;
    }
});
</script>
<template>
    <div
        class="flex items-center space-x-2 py-1"
        @mouseenter="showActions = true"
        @mouseleave="showActions = false"
    >
        <div class="cursor-pointer" @click="toggleComplete()">
            <IconSquareRoundedCheckFilled
                class="text-green-500 hover:text-green-500/50"
                v-if="completed"
            />
            <div class="group relative" v-else>
                <IconCheck
                    :stroke-width="3"
                    class="pointer-events-none absolute mx-1.5 my-1.5 top-0 left-0 text-zinc-300 opacity-0 size-3 group-hover:opacity-100"
                />
                <IconSquareRounded
                    class="text-zinc-300 group-hover:text-zinc-400"
                />
            </div>
        </div>

        <p
            class="flex-1 font-semibold"
            :class="{ 'line-through text-zinc-300': completed }"
        >
            {{ subIssue.title }}
        </p>

        <div
            class="opacity-0 flex items-center space-x-2"
            :class="{ 'opacity-100': showActions }"
        >
            <!-- <img
                :src="subIssue.assignee.profile_photo_url"
                class="size-8 rounded-full"
            /> -->

            <!-- <div class="p-1 border rounded-full border-zinc-950/20">
                <IconCalendarDue class="text-zinc-950/20 size-6" />
            </div> -->

            <Dropdown>
                <template #trigger>
                    <AppButton size="xs" plain square>
                        <IconDots
                            class="cursor-pointer rounded-lg text-zinc-400 size-5"
                        />
                    </AppButton>
                </template>

                <template #content>
                    <DropdownLink
                        as="button"
                        class="text-sm"
                        @click="deleteSubIssue"
                    >
                        Delete
                    </DropdownLink>
                </template>
            </Dropdown>
        </div>
    </div>
</template>
