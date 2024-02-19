<script setup>
import { getCurrentInstance, inject } from "vue";
import { IconChevronRight } from "@tabler/icons-vue";

const issue = inject("issue");
const emitter = getCurrentInstance().appContext.config.globalProperties.emitter;

const handleShowParent = () => {
    emitter.emit("show-issue-details", issue.value.parent.id);
};
</script>

<template>
    <div class="grid grid-cols-3 text-sm items-center">
        <div class="col-start-1">
            <p class="text-zinc-500">Parent issue</p>
        </div>

        <div class="col-span-2">
            <div
                class="cursor-pointer flex items-center justify-between p-2 rounded-2xl hover:bg-zinc-100"
                @click="handleShowParent"
            >
                <div>
                    <p class="font-medium">{{ issue.parent.title }}</p>
                    <p class="text-xs font-semibold text-zinc-400">
                        {{ issue.parent.code }}
                    </p>
                </div>

                <div>
                    <IconChevronRight
                        class="text-indigo-500"
                        :stroke-width="3"
                        size="20"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
