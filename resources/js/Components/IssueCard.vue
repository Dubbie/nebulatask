<script setup>
import AppCard from "@/Components/AppCard.vue";
import IssueStatusIcon from "@/Components/IssueStatusIcon.vue";
import { IconSitemap } from "@tabler/icons-vue";

const props = defineProps({
    issue: {
        type: Object,
        required: true,
    },
    selected: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <AppCard
        borderless
        class="border border-zinc-950/10 handle p-4 cursor-pointer"
        :class="{
            'border-indigo-500': selected,
            'hover:border-zinc-950/20': !selected,
        }"
    >
        <div class="flex items-center justify-between mb-2">
            <p class="font-medium leading-4 text-zinc-700">
                {{ issue.title }}
            </p>

            <div
                v-if="issue.sub_issues.length > 0"
                class="flex items-center space-x-2"
            >
                <IconSitemap class="text-zinc-400" size="16" />
                <p class="text-sm">+{{ issue.sub_issues.length }}</p>
            </div>
        </div>

        <div class="flex items-center space-x-2">
            <div class="flex-1">
                <div class="flex items-center space-x-2">
                    <IssueStatusIcon :status="issue.status" />
                    <p class="font-semibold text-xs text-zinc-400">
                        {{ issue.code }}
                    </p>
                </div>
            </div>

            <img
                :src="issue.assignee.profile_photo_url"
                alt=""
                class="size-6 rounded-full"
            />
        </div>
    </AppCard>
</template>

<style scoped>
.ghost::before {
    content: "";
    position: absolute;
    top: -2px;
    left: -2px;
    right: -2px;
    bottom: -2px;
    background-color: white;
    border-radius: 0.75rem;
    border: 2px dashed rgb(212, 212, 216);
}
</style>
