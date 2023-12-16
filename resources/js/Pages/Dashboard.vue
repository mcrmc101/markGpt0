<script setup>
import ChatFormStandard from '@/Components/Mgpt/ChatFormStandard.vue';
import ImageForm from '@/Components/Mgpt/ImageForm.vue';
import ToggleDarkMode from '@/Components/ToggleDarkMode.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const showImageForm = ref(false);
const showStandardChatForm = ref(false);
const showGPTChatForm = ref(false);

const toggleImageForm = () => {
    if (showImageForm.value == false) {
        showImageForm.value = true;
    }
    else {
        showImageForm.value = false;
    }
};

const toggleStandardChatForm = () => {
    if (showStandardChatForm.value == false) {
        showStandardChatForm.value = true;
    }
    else {
        showStandardChatForm.value = false;
    }
};
</script>

<template>
    <Head title="markGPT" />

    <AuthenticatedLayout>
        <div class="py-6">
            <div class="container px-4 mx-auto mb-auto">
                <div class="items-center p-4 my-auto bg-white shadow-sm sm:rounded-lg md:p-8">


                    <div class="grid justify-center grid-cols-1 mt-auto space-y-2">

                        <template v-if="showImageForm">
                            <div class="col">
                                <ImageForm />
                            </div>
                            <div class="col">
                                <button class="btn btn-sm btn-error" @click.prevent="toggleImageForm()">Close</button>
                            </div>
                        </template>
                        <template v-else>
                            <template v-if="!showStandardChatForm">
                                <div class="text-center col">
                                    <button class="btn" @click.prevent="toggleImageForm()">Create an Image</button>
                                </div>
                            </template>
                        </template>
                        <template v-if="showStandardChatForm">
                            <div class="col">
                                <ChatFormStandard />
                            </div>
                            <div class="col">
                                <button class="btn btn-sm btn-error"
                                    @click.prevent="toggleStandardChatForm()">Close</button>
                            </div>
                        </template>
                        <template v-else>
                            <template v-if="!showImageForm">
                                <div class="text-center col">
                                    <button class="btn" @click.prevent="toggleStandardChatForm()">Ask MarkGPT</button>
                                </div>
                            </template>
                        </template>

                        <!--Make Options for Original Chat GPT, Mark GPT Refined and Image Generation-->


                    </div>

                </div>
            </div>
            <div class="divider"></div>
            <div class="flex items-center flex-auto px-4 my-2 text-center">
                <ToggleDarkMode class="mx-auto" />
                <!--
                <img src="/img/catprofile.jpg" alt="markGPT cat" class="float-right h-12 rounded-full dark:invert spinMe" />
                -->


                <!--
                    <button @click.prevent="test()">Test!!!</button>
                    -->

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
        </div>

    </AuthenticatedLayout>
</template>
