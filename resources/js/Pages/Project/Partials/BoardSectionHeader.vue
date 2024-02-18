<script setup>
import AppButton from "@/Components/AppButton.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import { IconDots } from "@tabler/icons-vue";
import { IconPlus } from "@tabler/icons-vue";

const props = defineProps({
    sectionName: {
        type: String,
        required: true,
    },
    issuesCount: {
        type: Number,
        required: true,
    },
});

const emit = defineEmits(["newIssue", "highlight", "deleteSection"]);
</script>

<template>
    <div class="flex items-center px-2 mb-2">
        <p
            class="ml-1.5 flex-1 text-sm/6 handle cursor-grab text-zinc-500 font-medium"
            @mouseenter="$emit('highlight', true)"
            @mouseleave="$emit('highlight', false)"
        >
            <span>{{ sectionName }}</span>
            <span class="text-zinc-300 ml-2">{{ issuesCount }}</span>
        </p>

        <div class="flex shrink-0">
            <AppButton size="sm" plain square @click="$emit('newIssue')">
                <IconPlus class="text-zinc-500" :size="16" />
            </AppButton>
            <Dropdown @click.stop>
                <template #trigger>
                    <AppButton size="sm" plain square>
                        <IconDots class="text-zinc-500" :size="16" />
                    </AppButton>
                </template>

                <template #content>
                    <DropdownLink as="button" @click="$emit('deleteSection')"
                        >Delete section</DropdownLink
                    >
                </template>
            </Dropdown>
        </div>
    </div>
</template>
