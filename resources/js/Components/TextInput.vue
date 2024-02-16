<script setup>
import { onMounted, ref } from "vue";

const props = defineProps({
    modelValue: String,
    invalid: {
        type: Boolean,
        default: false,
    },
});

defineEmits(["update:modelValue"]);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute("autofocus")) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        ref="input"
        class="border-none ring-inset ring-1 rounded-lg shadow-sm focus:ring-inset focus:ring-2 focus:ring-indigo-500 disabled:opacity-50"
        :class="{ 'ring-red-500': invalid, 'ring-zinc-950/10': !invalid }"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
    />
</template>
