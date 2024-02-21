<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import ProjectSidebar from "@/Pages/Project/Partials/ProjectSidebar.vue";
import FormSection from "@/Components/FormSection.vue";
import AppButton from "@/Components/AppButton.vue";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    project: {
        type: Object,
        required: true,
    },
});

const handleDelete = () => {
    const form = useForm({});

    form.delete(route("project.destroy", props.project.id), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <AppLayout :full-width="true" title="Settings">
        <div class="grid grid-cols-12 flex-1">
            <ProjectSidebar :project="project" />

            <div
                class="flex flex-col col-span-9 bg-white py-8 px-4 sm:px-6 lg:px-8 lg:col-span-9 xl:col-span-10 dark:bg-zinc-950"
            >
                <h2 class="text-3xl font-semibold mb-6">Settings</h2>

                <FormSection>
                    <template #title>Danger zone</template>
                    <template #description>Be careful here.</template>

                    <template #form>
                        <div class="col-span-full flex justify-between">
                            <div class="flex-1">
                                <p class="font-semibold">Delete this project</p>
                                <p
                                    class="text-sm text-zinc-500 dark:text-zinc-400"
                                >
                                    Once you delete the project, there is no
                                    going back.
                                </p>
                            </div>

                            <div>
                                <AppButton
                                    outline
                                    color="red"
                                    @click="handleDelete"
                                    >Delete this project</AppButton
                                >
                            </div>
                        </div>
                    </template>
                </FormSection>
            </div>
        </div>
    </AppLayout>
</template>
