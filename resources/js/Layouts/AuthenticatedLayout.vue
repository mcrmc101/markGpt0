<script setup>
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import Navbar from '@/Components/Partials/Navbar.vue';
import Footer from '@/Components/Partials/Footer.vue';


const showingNavigationDropdown = ref(false);

const showBackground = computed(() => usePage().props.showBackground);
</script>

<template>
    <div class="flex flex-col min-h-screen my-auto bg-neutral-100 dark:invert">

        <Navbar />
        <main class="flex-grow dark:invert" :class="showBackground ? 'robotBg' : ''">

            <slot />
        </main>
        <Footer />

    </div>
</template>
