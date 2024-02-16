<script setup>
import { IconCalendarTime } from "@tabler/icons-vue";
import AppButton from "@/Components/AppButton.vue";
import { IconTrashFilled } from "@tabler/icons-vue";
import { getCurrentInstance, inject, provide, ref, watch } from "vue";
import { useForm, usePage } from "@inertiajs/vue3";
import Editor from "@tinymce/tinymce-vue";
import axios from "axios";
import SelectInput from "@/Components/SelectInput.vue";
import IssueStatusIcon from "@/Components/IssueStatusIcon.vue";
import IssueTitle from "@/Components/IssueTitle.vue";
import SubIssues from "@/Components/SubIssues.vue";
import AppSlideover from "@/Components/AppSlideover.vue";

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    issue: {
        type: Object,
        required: false,
    },
});
const issue = ref(null);
provide("issue", issue);

const emitter = getCurrentInstance().appContext.config.globalProperties.emitter;
const savingDescription = ref(false);
const savingStatus = ref(false);
const editing = ref(null);
const confirmDelete = ref(false);
const description = ref(null);
const status = ref(1);
const tinyApiKey = usePage().props.tiny_api_key;
const deleteForm = useForm({
    _method: "DELETE",
});
const statuses = inject("statuses");
const statusOptions = statuses.map((status) => {
    return {
        label: status.formatted_name,
        value: status.id,
    };
});

const handleDelete = () => {
    deleteForm.post(route("issue.destroy", props.issue.id), {
        onSuccess: () => {
            emit("close");
        },
    });
};

const stopEditing = (closing = false) => {
    if (!closing) {
        if (editing.value === "description") {
            updateDescription(description.value);
        }

        emitter.emit("stop-editing-issue");
    }

    editing.value = null;
};

const updateDescription = (description) => {
    if (description === props.issue.description) {
        return;
    }

    savingDescription.value = true;
    axios
        .put(route("api.issue.description.update", { issue: props.issue.id }), {
            description,
        })
        .then((response) => {
            emitter.emit("update-issue", props.issue.id);
        })
        .catch((error) => {
            console.log(error);
        })
        .finally(() => {
            savingDescription.value = false;
        });
};

const updateStatus = (newStatus) => {
    const oldStatus = props.issue.issue_status_id;
    savingStatus.value = true;

    axios
        .put(route("api.issue.status.update", { issue: props.issue.id }), {
            issue_status_id: newStatus,
        })
        .then((response) => {
            status.value = newStatus;
            emitter.emit("update-issue", props.issue.id);
        })
        .catch((error) => {
            status.value = oldStatus;
        })
        .finally(() => {
            savingStatus.value = false;
        });
};

const handleClosing = () => {
    stopEditing(true);
    confirmDelete.value = false;
    emit("close");
};

const emit = defineEmits(["close"]);

watch(
    props,
    (newProps) => {
        issue.value = newProps.issue;

        description.value = newProps.issue ? newProps.issue.description : null;
        status.value = newProps.issue ? newProps.issue.issue_status_id : 1;
    },
    { deep: true }
);
</script>

<template>
    <AppSlideover :show="show" @close="handleClosing">
        <div
            class="fixed inset-0"
            v-show="confirmDelete"
            @click="confirmDelete = false"
        ></div>

        <div class="p-8" @click="stopEditing()">
            <div class="flex items-start space-x-6 mb-6">
                <div class="flex-1">
                    <IssueTitle :issue="issue" @click.stop />
                    <p class="text-sm font-semibold text-zinc-400 mt-1 mb-2">
                        {{ issue.code }}
                    </p>

                    <div class="flex space-x-2 items-center">
                        <IssueStatusIcon
                            size-class="size-6"
                            :status="issue.status"
                        />

                        <SelectInput
                            :model-value="status"
                            :options="statusOptions"
                            :disabled="savingStatus"
                            class="text-sm"
                            @update:model-value="updateStatus"
                        />
                    </div>
                </div>

                <div class="flex items-center space-x-2">
                    <div class="relative">
                        <AppButton
                            plain
                            @click="confirmDelete = !confirmDelete"
                        >
                            <IconTrashFilled size="16" />
                            <span>Delete</span>
                        </AppButton>

                        <transition
                            enter-active-class="origin-top-right transition transform ease-out duration-200"
                            enter-from-class="opacity-0 scale-95"
                            enter-to-class=" opacity-100 scale-100"
                            leave-active-class="origin-top-right transition transform ease-in duration-200"
                            leave-from-class="scale-100 opacity-100"
                            leave-to-class="scale-90 opacity-0"
                        >
                            <div
                                class="absolute z-10 min-w-60 mt-1 top-full right-0 bg-zinc-100 rounded-xl p-3"
                                v-show="confirmDelete"
                            >
                                <p class="font-medium text-sm">
                                    This action is irreversible, your data will
                                    be lost.
                                </p>

                                <div class="flex justify-end space-x-1 mt-3">
                                    <AppButton
                                        size="sm"
                                        plain
                                        @click="confirmDelete = false"
                                        >Cancel</AppButton
                                    >
                                    <AppButton
                                        size="sm"
                                        color="red"
                                        @click="handleDelete"
                                        :disabled="deleteForm.processing"
                                    >
                                        Delete
                                    </AppButton>
                                </div>
                            </div>
                        </transition>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-3 text-sm items-center">
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
                <div class="col-start-1">
                    <p class="text-zinc-500">Due date</p>
                </div>

                <div>
                    <div class="flex items-center space-x-2">
                        <div class="p-2 rounded-full bg-zinc-100">
                            <IconCalendarTime class="text-zinc-400" size="16" />
                        </div>
                        <p>{{ issue.due_date ?? "No due date" }}</p>
                    </div>
                </div>
            </div>

            <p class="text-sm text-zinc-500 py-3 leading-8">Description</p>
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
            <div v-show="editing === 'description'" class="-mx-4 -mb-4">
                <Editor
                    v-model="description"
                    :init="{
                        menubar: false,
                        min_height: 120,
                        statusbar: false,
                        toolbar_location: 'bottom',
                        plugins:
                            'autolink autoresize code visualblocks visualchars fullscreen image link media codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists  quickbars',
                    }"
                    :api-key="tinyApiKey"
                />
            </div>

            <SubIssues class="mt-8" />
        </div>
    </AppSlideover>
</template>
