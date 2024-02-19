<script setup>
import TextInput from "@/Components/TextInput.vue";

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
});

const emit = defineEmits(["update:title", "blur"]);
</script>

<template>
    <div class="flex space-x-2">
        <TextInput
            autofocus
            class="text-sm w-full"
            :model-value="title"
            @update:model-value="$emit('update:title', $event)"
            type="text"
            @blur="$emit('blur')"
        />
        <img
            :src="$page.props.auth.user.profile_photo_url"
            class="size-9 rounded-full"
            alt=""
        />
    </div>
</template>
