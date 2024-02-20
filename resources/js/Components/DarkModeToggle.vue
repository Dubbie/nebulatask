<script setup>
import { onMounted, ref } from "vue";

const darkMode = ref(null);

const toggleDarkMode = () => {
    if (darkMode.value === true) {
        localStorage.theme = "light";
        darkMode.value = false;
        document.documentElement.classList.remove("dark");
    } else {
        localStorage.theme = "dark";
        darkMode.value = true;
        document.documentElement.classList.add("dark");
    }
};

onMounted(() => {
    darkMode.value =
        localStorage.theme === "dark" ||
        (!("theme" in localStorage) &&
            window.matchMedia("(prefers-color-scheme: dark)").matches);
});
</script>

<template>
    <div>
        <div
            class="relative h-5 rounded-full w-9 bg-blue-200 border"
            @click.stop="toggleDarkMode()"
            :class="{
                'bg-blue-100 border-transparent': !darkMode,
                'bg-blue-900 border-white/20': darkMode,
            }"
        >
            <div
                class="absolute left-px top-px w-4 h-4 rounded-full border transition-all"
                :class="{
                    'bg-yellow-200 left-px border-yellow-300': !darkMode,
                    'bg-yellow-50 translate-x-4': darkMode,
                }"
            ></div>
        </div>
    </div>
</template>
