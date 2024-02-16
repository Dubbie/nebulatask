<script setup>
import AppCard from "@/Components/AppCard.vue";
import IssueStatusIcon from "@/Components/IssueStatusIcon.vue";

const props = defineProps({
    issue: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <AppCard class="handle px-4 py-2 cursor-pointer hover:bg-zinc-100/50">
        <p class="font-semibold text-xs text-zinc-400">{{ issue.code }}</p>
        <p class="font-medium text-zinc-700 mb-2">{{ issue.title }}</p>

        <div class="flex items-center space-x-2">
            <div class="flex-1">
                <IssueStatusIcon :status="issue.status" />
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
