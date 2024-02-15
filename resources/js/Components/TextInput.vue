<script setup>
import { onMounted, ref } from "vue";

defineProps({
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
        class="border-none ring-inset ring-1 ring-zinc-950/10 rounded-lg shadow-sm focus:ring-inset focus:ring-2 focus:ring-indigo-500"
        :class="{ 'ring-red-500': invalid }"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
    />
</template>
