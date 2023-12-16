<script setup>
import ChatFormStandard from '@/Components/Mgpt/ChatFormStandard.vue';
import ImageForm from '@/Components/Mgpt/ImageForm.vue';
import GPT4ChatForm from '@/Components/Mgpt/GPT4ChatForm.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import CloseIcon from '@/Components/Icons/CloseIcon.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const showImageForm = ref(false);
const showStandardChatForm = ref(false);
const showGPT4ChatForm = ref(false);

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

const toggleGPT4ChatForm = () => {
    if (showGPT4ChatForm.value == false) {
        showGPT4ChatForm.value = true;
    }
    else {
        showGPT4ChatForm.value = false;
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
                        <template v-if="!showImageForm && !showGPT4ChatForm && !showStandardChatForm">
                            <h1 class="mb-2 text-2xl font-bold text-center underline">Select an Option</h1>
                        </template>
                        <template v-if="showImageForm">
                            <div class="col">
                                <button class="float-right btn btn-sm text-error" @click.prevent="toggleImageForm()">
                                    <CloseIcon />
                                    <span class="sr-only"> Close</span>
                                </button>
                            </div>
                            <div class="col">
                                <ImageForm />
                            </div>
                        </template>
                        <template v-else>
                            <template v-if="!showStandardChatForm && !showGPT4ChatForm">
                                <div class="text-center col">
                                    <button class="btn" @click.prevent="toggleImageForm()">Create an Image</button>
                                </div>
                            </template>
                        </template>
                        <template v-if="showStandardChatForm">
                            <div class="col">
                                <button class="float-right btn btn-sm text-error" @click.prevent="toggleStandardChatForm()">
                                    <CloseIcon />
                                    <span class="sr-only"> Close</span>
                                </button>
                            </div>
                            <div class="col">
                                <ChatFormStandard />
                            </div>
                        </template>
                        <template v-else>
                            <template v-if="!showImageForm && !showGPT4ChatForm">
                                <div class="text-center col">
                                    <button class="btn" @click.prevent="toggleStandardChatForm()">Ask MarkGPT</button>
                                </div>
                            </template>
                        </template>


                        <template v-if="showGPT4ChatForm">
                            <div class="col">
                                <button class="float-right btn btn-sm text-error" @click.prevent="toggleGPT4ChatForm()">
                                    <CloseIcon />
                                    <span class="sr-only"> Close</span>
                                </button>
                            </div>
                            <div class="col">
                                <GPT4ChatForm />
                            </div>

                        </template>
                        <template v-else>
                            <template v-if="!showStandardChatForm && !showImageForm">
                                <div class="text-center col">
                                    <button class="btn" @click.prevent="toggleGPT4ChatForm()">Ask GPT 4</button>
                                </div>
                            </template>
                        </template>

                        <!--Make Options for Original Chat GPT, Mark GPT Refined and Image Generation-->


                    </div>

                </div>
            </div>

        </div>

    </AuthenticatedLayout>
</template>
