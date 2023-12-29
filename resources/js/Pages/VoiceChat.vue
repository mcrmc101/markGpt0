<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import VoiceChatForm from '@/Components/Mgpt/VoiceChatForm.vue';
</script>
<template>
    <Head title="Voice Chat" />

    <AuthenticatedLayout>
        <div class="h-auto px-4 py-6 mx-auto mb-auto md:w-4/5 dark:invert">
            <div class="p-4 my-auto bg-white rounded-md shadow sm:rounded-lg md:p-8">
                <VoiceChatForm />
            </div>
        </div>
    </AuthenticatedLayout>
</template>