<script setup>
import { IconDots } from "@tabler/icons-vue";
import AppCard from "@/Components/AppCard.vue";
import AppTable from "@/Components/AppTable.vue";
import TableHead from "@/Components/TableHead.vue";
import TableRow from "@/Components/TableRow.vue";
import TableHeader from "@/Components/TableHeader.vue";
import TableBody from "@/Components/TableBody.vue";
import TableCell from "@/Components/TableCell.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    projects: {
        type: Array,
        required: true,
    },
});

const emit = defineEmits(["delete-project"]);
</script>

<template>
    <AppCard class="px-6 py-4">
        <AppTable>
            <TableHead>
                <TableRow>
                    <TableHeader>Name</TableHeader>
                    <TableHeader>Code</TableHeader>
                    <TableHeader>Lead</TableHeader>
                </TableRow>
            </TableHead>
            <TableBody>
                <TableRow v-for="project in projects" :key="project.id">
                    <TableCell>
                        <Link :href="route('project.show', project.id)">
                            <p
                                class="font-semibold text-indigo-500 hover:text-indigo-400"
                            >
                                {{ project.name }}
                            </p>
                        </Link>
                    </TableCell>
                    <TableCell>
                        <p>{{ project.code }}</p>
                    </TableCell>
                    <TableCell>
                        <div class="flex">
                            <img
                                class="w-6 h-6 rounded-full"
                                :src="project.lead.profile_photo_url"
                                :alt="project.lead.name"
                            />
                            <p
                                class="ml-2 text-indigo-500 font-semibold hover:text-indigo-400"
                            >
                                {{ project.lead.name }}
                            </p>
                        </div>
                    </TableCell>
                    <TableCell>
                        <Dropdown class="ml-auto max-w-fit w-full">
                            <template #trigger>
                                <div
                                    class="cursor-pointer p-1 text-zinc-500 rounded-md hover:text-zinc-950 hover:bg-zinc-950/5"
                                >
                                    <IconDots class="w-5 h-5" />
                                </div>
                            </template>

                            <template #content>
                                <DropdownLink as="button"> Edit </DropdownLink>
                                <DropdownLink
                                    as="button"
                                    @click="$emit('delete-project', project)"
                                >
                                    Delete
                                </DropdownLink>
                            </template>
                        </Dropdown>
                    </TableCell>
                </TableRow>
            </TableBody>
        </AppTable>
    </AppCard>
</template>
