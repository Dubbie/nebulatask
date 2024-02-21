<script setup>
import AppCard from "@/Components/AppCard.vue";
import IssueTypeIcon from "@/Components/IssueTypeIcon.vue";
import { IconSubtask, IconCalendarDue } from "@tabler/icons-vue";
import { computed, inject } from "vue";

const props = defineProps({
    issue: {
        type: Object,
        required: true,
    },
    dragging: {
        type: Boolean,
        default: false,
    },
});

const selectedIssue = inject("selectedIssue");
const selected = computed(() => props.issue.id === selectedIssue.value?.id);
</script>

<template>
    <AppCard class="draggable relative p-4" borderless :class="{
        'cursor-grabbing ring-transparent mb-3': dragging,
        'cursor-pointer ': !dragging,
        'ring-zinc-950/10 hover:ring-zinc-950/25 dark:ring-white/20 dark:hover:ring-white/30':
            !selected && !dragging,
        'ring-1': !selected,
        'ring-2 ring-indigo-500 bg-indigo-100/80': selected,
    }">
        <div v-if="dragging" class="absolute inset-0 bg-zinc-100 -m-px z-10 dark:bg-zinc-800"></div>

        <div class="flex space-x-3 -mt-1">
            <p class="flex-1 text-sm font-medium text-zinc-700  dark:text-white">
                {{ issue.title }}
            </p>

            <div class="flex space-x-1" v-if="issue.sub_issues.length > 0">
                <IconSubtask class="text-zinc-400 size-5" />

                <p class="text-sm/5 font-semibold text-zinc-400">
                    {{ issue.sub_issues.length }}
                </p>
            </div>
        </div>

        <div v-if="issue.due_date" class="flex space-x-1 items-center text-zinc-400">
            <IconCalendarDue :size="16" />
            <p class="text-xs font-semibold ">{{ issue.due_date }}</p>
        </div>

        <div class="flex items-center space-x-2 mt-2">
            <IssueTypeIcon size-class="size-3" :type="issue.type" />
            <p class="text-xs font-semibold text-zinc-400 flex-1 dark:text-zinc-500">
                {{ issue.id }}
            </p>

            <img :src="issue.assignee.profile_photo_url" class="size-5 rounded-full" alt="" />
        </div>
    </AppCard>
</template>
