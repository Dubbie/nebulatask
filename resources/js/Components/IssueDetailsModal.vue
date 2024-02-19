<script setup>
import AppButton from "@/Components/AppButton.vue";
import { IconTrashFilled } from "@tabler/icons-vue";
import {
    getCurrentInstance,
    onMounted,
    onUnmounted,
    provide,
    ref,
    watch,
} from "vue";
import { useForm } from "@inertiajs/vue3";
import IssueTitle from "@/Components/IssueTitle.vue";
import SubIssues from "@/Components/SubIssues.vue";
import AppSlideover from "@/Components/AppSlideover.vue";
import IssueDescription from "@/Components/IssueDescription.vue";
import IssueBoardSectionSelector from "@/Components/IssueBoardSectionSelector.vue";
import IssueAssigneeChooser from "@/Components/IssueAssigneeChooser.vue";
import IssueDueChooser from "@/Components/IssueDueChooser.vue";
import IssueTypeIcon from "@/Components/IssueTypeIcon.vue";
import IssueParent from "@/Components/IssueParent.vue";

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
const confirmDelete = ref(false);
const deleting = ref(false);

const handleDelete = () => {
    const issueId = props.issue.id;
    deleting.value = true;

    axios
        .delete(route("api.issue.destroy", issueId))
        .then(() => {
            emitter.emit("issue-deleted", issueId);
            emit("close");
        })
        .finally(() => {
            deleting.value = false;
        });
};

const stopEditing = (closing = false) => {
    if (!closing) {
        emitter.emit("stop-editing-issue");
    }
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
        if (!newProps.issue) {
            handleClosing();
        }

        issue.value = newProps.issue;
    },
    { deep: true }
);

onMounted(() => {
    emitter.on("stop-editing", () => {
        handleClosing();
    });
});

onUnmounted(() => {
    emitter.off("stop-editing");
});
</script>

<template>
    <AppSlideover :show="show" @close="handleClosing">
        <div
            class="fixed inset-0"
            v-show="confirmDelete"
            @click="confirmDelete = false"
        ></div>

        <div v-if="issue" class="h-full p-8" @click="stopEditing()">
            <div class="flex items-start space-x-6 mb-6">
                <div class="flex-1">
                    <IssueTitle @click.stop />

                    <div class="flex items-center space-x-2 mt-1 mb-2">
                        <IssueBoardSectionSelector />

                        <IssueTypeIcon :type="issue.type" size-class="size-4" />

                        <p class="text-sm font-semibold text-zinc-400">
                            {{ issue.id }}
                        </p>
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
                                        :disabled="deleting"
                                    >
                                        Delete
                                    </AppButton>
                                </div>
                            </div>
                        </transition>
                    </div>
                </div>
            </div>

            <div class="space-y-3">
                <IssueAssigneeChooser />
                <IssueDueChooser />
                <IssueParent v-if="issue.parent" />
            </div>

            <p class="text-sm text-zinc-500 py-3 leading-8">Description</p>
            <IssueDescription @click.stop />

            <SubIssues class="mt-8" />
        </div>
    </AppSlideover>
</template>
