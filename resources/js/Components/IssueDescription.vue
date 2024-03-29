<script setup>
import { usePage } from "@inertiajs/vue3";
import Editor from "@tinymce/tinymce-vue";
import { getCurrentInstance, inject, ref, watch } from "vue";

const emitter = getCurrentInstance().appContext.config.globalProperties.emitter;
const editing = ref(false);
const issue = inject("issue");
const savingDescription = ref(false);
const description = ref(issue.value.description);
const tinyApiKey = usePage().props.tiny_api_key;

const updateDescription = () => {
    const oldValue = issue.value.description;

    editing.value = false;
    if (description.value === issue.value.description) {
        return;
    }

    savingDescription.value = true;
    axios
        .put(route("api.issue.description.update", { issue: issue.value.id }), {
            description: description.value,
        })
        .then((response) => {
            emitter.emit("issue-updated", response.data);
        })
        .catch((error) => {
            description.value = oldValue;
        })
        .finally(() => {
            savingDescription.value = false;
        });
};

watch(issue, (newIssue) => {
    description.value = newIssue ? newIssue.description : null;
});
</script>

<template>
    <div>
        <div
            v-if="!editing"
            class="min-h-[120px] border-2 border-transparent -mx-4 -mb-4 text-sm rounded-lg overflow-hidden hover:border-zinc-950/10"
            :class="{
                'opacity-50 pointer-events-none': savingDescription,
            }"
            @click.stop="editing = 'description'"
        >
            <div class="p-4 text-zinc-950">
                <div
                    class="prose prose-zinc text-sm"
                    v-if="description"
                    v-html="description"
                ></div>
                <p v-else class="text-zinc-300">No description</p>
            </div>
        </div>
        <div v-show="editing" class="-mx-4 -mb-4">
            <Editor
                v-model="description"
                :init="{
                    menubar: false,
                    auto_focus: true,
                    min_height: 120,
                    statusbar: false,
                    toolbar_location: 'bottom',
                    plugins:
                        'autolink autoresize code visualblocks visualchars fullscreen image link media codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists  quickbars',
                }"
                :api-key="tinyApiKey"
                @blur="updateDescription"
            />
        </div>
    </div>
</template>
