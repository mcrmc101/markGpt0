<script setup>
import ToggleDarkMode from '@/Components/ToggleDarkMode.vue';
</script>
<template>
    <footer class="mt-auto bg-white ">
        <div class="flex items-center flex-auto px-4 my-2 text-center">
            <ToggleDarkMode class="mx-auto" />

        </div>
        <div class="flex flex-col p-2 space-y-1 text-center text-gray-600" style="font-size: 0.8em;">
            <small>Warning: This program talks crap, speaks bollocks and witters nonsense.
                It is not to be
                relied upon for
                any legal, political, military, medical, relationship, pet or child care advice.</small>

            <small>No liability will be accepted for anything, ever.</small>

            <small>Powered by Chat GPT</small>
            <small>&copy;&trade;&nbsp; mcrmc {{ new Date().getFullYear() }}</small>
        </div>
    </footer>
</template>