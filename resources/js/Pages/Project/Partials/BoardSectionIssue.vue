<script setup>
import AppCard from "@/Components/AppCard.vue";
import IssueTypeIcon from "@/Components/IssueTypeIcon.vue";
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
    <AppCard
        class="draggable relative border p-4"
        borderless
        :class="{
            'cursor-grabbing border-transparent mb-3': dragging,
            'cursor-pointer ': !dragging,
            'border-zinc-950/10 hover:border-zinc-950/25':
                !selected && !dragging,
            'border-indigo-500 bg-indigo-100/50': selected,
        }"
    >
        <div
            v-if="dragging"
            class="absolute inset-0 bg-zinc-100 -m-px z-10"
        ></div>

        <p class="text-sm font-medium text-zinc-700 mb-2 -mt-1">
            {{ issue.title }}
        </p>

        <div class="flex items-center space-x-2">
            <IssueTypeIcon size-class="size-3" :type="issue.type" />
            <p class="text-xs font-semibold text-zinc-400 flex-1">
                {{ issue.code }}
            </p>

            <img
                :src="issue.assignee.profile_photo_url"
                class="size-5 rounded-full"
                alt=""
            />
        </div>
    </AppCard>
</template>
