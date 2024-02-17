<script setup>
import { inject } from "vue";

const issue = inject("issue");
</script>

<template>
    <div class="grid grid-cols-3 text-sm items-center">
        <div class="col-start-1">
            <p class="text-zinc-500">Assignee</p>
        </div>

        <div>
            <div class="flex items-center space-x-2">
                <img
                    :src="issue.assignee.profile_photo_url"
                    alt=""
                    class="size-8 rounded-full"
                />
                <p class="font-semibold">{{ issue.assignee.name }}</p>
            </div>
        </div>
    </div>
</template>
