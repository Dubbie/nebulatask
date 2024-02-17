<script setup>
import { computed, onMounted, ref } from "vue";

const props = defineProps({
    modelValue: String,
    invalid: {
        type: Boolean,
        default: false,
    },
    size: {
        type: String,
        default: "md",
    },
});

defineEmits(["update:modelValue"]);

const input = ref(null);

const sizeClasses = computed(() => {
    return {
        xs: "px-2 py-1 text-xs",
        sm: "px-2.5 py-1 text-sm",
        md: "px-3 py-1.5 text-sm",
        lg: "px-4 py-2.5 text-lg",
    }[props.size];
});

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
        :class="[
            { 'ring-red-500': invalid, 'ring-zinc-950/10': !invalid },
            sizeClasses,
        ]"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
    />
</template>
