<script setup>
import {
    Listbox,
    ListboxButton,
    ListboxOption,
    ListboxOptions,
} from "@headlessui/vue";
import { computed, ref, watch } from "vue";

const props = defineProps({
    modelValue: {
        type: [String, Number],
    },
    options: {
        type: Array,
        required: true,
    },
    id: {
        type: String,
        default: null,
    },
    trailing: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(["update:modelValue"]);
const selectedOption = ref(props.modelValue);
const label = computed(() => {
    return props.options.find((option) => {
        return option.value === props.modelValue;
    }).label;
});

watch(selectedOption, (value) => {
    emit("update:modelValue", value);
});
</script>

<template>
    <Listbox v-model="selectedOption">
        <div class="relative">
            <ListboxButton
                :id="id"
                class="relative text-left px-3 py-1.5 border-none pr-8 text-zinc-950 ring-[1px] shadow-sm ring-inset ring-zinc-950/10 hover:ring-zinc-950/20 disabled:opacity-50 disabled:pointer-events-none dark:bg-white/10 dark:text-white dark:ring-white/20 dark:hover:ring-white/30"
                :class="{
                    'rounded-lg': !trailing,
                    'rounded-tr-lg rounded-br-lg -mx-px': trailing,
                }"
            >
                <span class="block flex-1 whitespace-nowrap">{{ label }}</span>
                <span class="absolute inset-y-0 right-0 flex items-center pr-2">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 16 16"
                        fill="currentColor"
                        class="w-4 h-4 text-zinc-950/30"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M5.22 10.22a.75.75 0 0 1 1.06 0L8 11.94l1.72-1.72a.75.75 0 1 1 1.06 1.06l-2.25 2.25a.75.75 0 0 1-1.06 0l-2.25-2.25a.75.75 0 0 1 0-1.06ZM10.78 5.78a.75.75 0 0 1-1.06 0L8 4.06 6.28 5.78a.75.75 0 0 1-1.06-1.06l2.25-2.25a.75.75 0 0 1 1.06 0l2.25 2.25a.75.75 0 0 1 0 1.06Z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </span>
            </ListboxButton>

            <transition
                leave-active-class="transition ease-in duration-200 origin-top-left"
                leave-from-class="opacity-100 scale-100"
                leave-to-class="opacity-0 scale-75"
            >
                <ListboxOptions
                    class="z-10 overflow-auto absolute p-1 mt-1 max-h-60 font-medium bg-white border border-zinc-950/10 rounded-xl dark:bg-white/15 dark:backdrop-blur-sm dark:border-white/15"
                >
                    <ListboxOption
                        v-for="option in options"
                        :key="option.label"
                        :value="option.value"
                        v-slot="{ active, selected }"
                    >
                        <span
                            class="text-sm cursor-pointer whitespace-nowrap block px-2 py-1 rounded-lg"
                            :class="{
                                'bg-zinc-200': active && !selected,
                                'bg-indigo-500 text-white': selected,
                            }"
                            >{{ option.label }}</span
                        >
                    </ListboxOption>
                </ListboxOptions>
            </transition>
        </div>
    </Listbox>
</template>
