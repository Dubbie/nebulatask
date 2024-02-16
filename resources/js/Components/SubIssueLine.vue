<script setup>
import { IconSquareRounded } from "@tabler/icons-vue";
import { IconCalendarDue } from "@tabler/icons-vue";
import { IconSquareRoundedCheckFilled } from "@tabler/icons-vue";
import { computed, getCurrentInstance } from "vue";
import Dropdown from "./Dropdown.vue";
import { IconDots } from "@tabler/icons-vue";
import DropdownLink from "./DropdownLink.vue";

const props = defineProps({
    subIssue: {
        type: Object,
        required: true,
    },
});

const emitter = getCurrentInstance().appContext.config.globalProperties.emitter;
const completed = computed(() => {
    return props.subIssue.issue_status_id === 4;
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
</script>
<template>
    <div class="group flex items-center space-x-2 py-1">
        <div>
            <IconSquareRoundedCheckFilled
                class="text-green-500"
                v-if="completed"
            />
            <IconSquareRounded
                class="text-zinc-400 hover:text-zinc-600"
                v-else
            />
        </div>

        <p class="flex-1 font-semibold">
            {{ subIssue.title }}
        </p>

        <div
            class="opacity-0 group-hover:opacity-100 flex items-center space-x-2"
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
                    <IconDots
                        class="text-zinc-400 hover:text-zinc-600 size-6"
                    />
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
