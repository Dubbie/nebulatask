<script setup>
import { getCurrentInstance, onMounted, onUpdated, ref, watch } from "vue";
import TextInput from "@/Components/TextInput.vue";
const props = defineProps({
    issue: {
        type: Object,
        required: true,
    },
});

const emitter = getCurrentInstance().appContext.config.globalProperties.emitter;
const editing = ref(false);
const saving = ref(false);
const title = ref(props.issue.title);

onMounted(() => {
    emitter.on("stop-editing-issue", () => {
        if (editing.value) {
            updateTitle();
        }
    });
});

const updateTitle = () => {
    const oldTitle = props.issue.title;
    saving.value = true;

    axios
        .put(route("api.issue.title.update", { issue: props.issue.id }), {
            title: title.value,
        })
        .then((response) => {
            emitter.emit("update-issue", props.issue.id);
            reset(title.value);
        })
        .catch((error) => {
            title.value = oldTitle;
        })
        .finally(() => {
            saving.value = false;
        });
};

const reset = (newTitle = props.issue.title) => {
    editing.value = false;
    title.value = newTitle;
};

watch(
    () => props.issue.title,
    (newTitle) => {
        title.value = newTitle;
    }
);
</script>

<template>
    <div>
        <h1
            class="text-2xl font-medium"
            v-if="!editing"
            @click="editing = true"
        >
            {{ title }}
        </h1>
        <div
            class="-ml-3 -my-[calc(theme(spacing[1])+0.5px)] flex space-x-3"
            v-else
        >
            <TextInput
                :disabled="saving"
                class="font-semibold py-1 text-2xl flex-1"
                autofocus
                v-model="title"
                @keyup.enter="updateTitle"
            />
        </div>
    </div>
</template>
