<script setup>
import AppButton from "@/Components/AppButton.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import TextInput from "@/Components/TextInput.vue";
import { IconDots } from "@tabler/icons-vue";
import { IconPlus } from "@tabler/icons-vue";
import { getCurrentInstance, ref } from "vue";

const props = defineProps({
    section: {
        type: Object,
        required: true,
    },
});

const emitter = getCurrentInstance().appContext.config.globalProperties.emitter;
const editing = ref(false);
const name = ref(props.section.name);

const handleUpdate = () => {
    if (name.value === props.section.name || name.value === "") {
        editing.value = false;
        return;
    }

    // Update the section title
    axios
        .put(route("api.board-section.update", props.section.id), {
            name: name.value,
        })
        .then((response) => {
            emitter.emit("update-section-issues", {
                name: response.data.data.name,
                sectionId: response.data.data.id,
                issues: response.data.data.issues,
            });
            editing.value = false;
        });
};

const emit = defineEmits(["newIssue", "highlight", "deleteSection"]);
</script>

<template>
    <div>
        <div class="flex items-center px-2 mb-2" v-if="!editing">
            <p
                class="ml-1.5 flex-1 text-sm/6 handle cursor-grab text-zinc-500 font-medium dark:text-zinc-400"
                @mouseenter="$emit('highlight', true)"
                @mouseleave="$emit('highlight', false)"
                @click="editing = true"
            >
                <span>{{ section.name }}</span>
                <span class="text-zinc-300 ml-2 dark:text-zinc-600">{{
                    section.issues.length
                }}</span>
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
                        <DropdownLink
                            as="button"
                            @click="$emit('deleteSection')"
                            >Delete section</DropdownLink
                        >
                    </template>
                </Dropdown>
            </div>
        </div>

        <TextInput
            class="mb-1"
            v-else
            autofocus
            type="text"
            v-model="name"
            @blur="handleUpdate"
        />
    </div>
</template>
