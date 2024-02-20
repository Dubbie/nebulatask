<script setup>
import { IconCalendarTime } from "@tabler/icons-vue";
import { inject } from "vue";

const issue = inject("issue");
</script>

<template>
    <div class="grid grid-cols-3 text-sm items-center">
        <div class="col-start-1">
            <p class="text-zinc-500">Due date</p>
        </div>

        <div>
            <div class="flex items-center space-x-2">
                <div class="p-2 rounded-full bg-zinc-100 dark:bg-white/10">
                    <IconCalendarTime class="text-zinc-400" size="16" />
                </div>
                <p class="dark:text-zinc-500">
                    {{ issue.due_date ?? "No due date" }}
                </p>
            </div>
        </div>
    </div>
</template>
