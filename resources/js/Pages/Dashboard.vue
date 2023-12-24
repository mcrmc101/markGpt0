<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import ChatFormStandard from '@/Components/Mgpt/ChatFormStandard.vue';
import ImageForm from '@/Components/Mgpt/ImageForm.vue';
import UniversalTranslator from '@/Components/Mgpt/UniversalTranslator.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import CloseIcon from '@/Components/Icons/CloseIcon.vue';
import VoiceChatForm from '@/Components/Mgpt/VoiceChatForm.vue';
import Options from '@/Components/Mgpt/Options.vue';
import MicrophoneIcon from '@/Components/Icons/MicrophoneIcon.vue';
import ChatIcon from '@/Components/Icons/ChatIcon.vue';
import ImageIcon from '@/Components/Icons/ImageIcon.vue';
import TranslateIcon from '@/Components/Icons/TranslateIcon.vue';
import { onMounted } from 'vue';

const showImageForm = ref(false);
const showVoiceForm = ref(false);
const showStandardChatForm = ref(false);
const showUniversalTranslator = ref(false);

const greeting = ref('');


const getNewGreeting = () => {
    axios.get(route('greeting.new'))
        .then((res) => {
            greeting.value = res.data.greeting;
        })
        .catch((err) => {
            console.log(err);
        });
};

const toggleImageForm = () => {
    if (showImageForm.value == false) {
        showImageForm.value = true;
    }
    else {
        showImageForm.value = false;
    }
};

const toggleVoiceForm = () => {
    if (showVoiceForm.value == false) {
        showVoiceForm.value = true;
    }
    else {
        showVoiceForm.value = false;
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

const toggleUniversalTranslator = () => {
    if (showUniversalTranslator.value == false) {
        showUniversalTranslator.value = true;
    }
    else {
        showUniversalTranslator.value = false;
    }
};

onMounted(() => {
    getNewGreeting();
})

</script>

<template>
    <Head title="markGPT" />

    <AuthenticatedLayout>
        <div class="h-auto px-4 py-6 mx-auto mb-auto md:w-4/5 dark:invert">
            <div class="p-4 my-auto bg-white rounded-md shadow sm:rounded-lg md:p-8">


                <template v-if="!showImageForm && !showUniversalTranslator && !showStandardChatForm && !showVoiceForm">
                    <div class="flex flex-wrap items-center justify-center mb-4 text-center">
                        <div class="w-full md:w-2/3">
                            <h1 class="m-2 text-2xl font-bold text-center underline md:mb-4 md:text-4xl"
                                v-html="greeting" />
                        </div>
                        <div class="w-full md:w-1/3">
                            <div class="text-center shadow stats">
                                <div class="stat">
                                    <div class="stat-figure text-secondary">
                                        <div class="avatar">
                                            <div class="w-16 rounded-full">
                                                <img src="/img/catprofile.jpg" alt="markGPT cat" class="dark:invert" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="stat-title">Chat Count</div>
                                    <div class="stat-value">{{ $page.props.chatCount }}</div>
                                    <div class="stat-desc">Don't rag it.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
                <div class="grid items-center justify-center grid-cols-1 mt-auto space-y-2">

                    <div class="col">
                        <div class="grid items-end justify-center grid-cols-1 mt-auto space-y-2">

                            <template v-if="showImageForm">
                                <div class="col">
                                    <button class="float-right btn btn-sm text-error" @click.prevent="toggleImageForm()">
                                        <small>Image</small>
                                        <CloseIcon />
                                        <span class="sr-only"> Close</span>
                                    </button>
                                </div>
                                <div class="col">
                                    <ImageForm />
                                </div>
                            </template>
                            <template v-else>
                                <template v-if="!showStandardChatForm && !showUniversalTranslator && !showVoiceForm">
                                    <div class="text-center col">
                                        <button class="w-full btn btn-secondary hover:btn-primary"
                                            @click.prevent="toggleImageForm()">
                                            <ImageIcon />
                                            Create an
                                            Image
                                        </button>
                                    </div>
                                </template>
                            </template>
                            <template v-if="showStandardChatForm">
                                <div class="col">
                                    <button class="float-right btn btn-sm text-error"
                                        @click.prevent="toggleStandardChatForm()">
                                        <small>Text Chat</small>
                                        <CloseIcon />
                                        <span class="sr-only"> Close</span>
                                    </button>
                                </div>
                                <div class="col">
                                    <ChatFormStandard />
                                </div>
                            </template>
                            <template v-else>
                                <template v-if="!showImageForm && !showUniversalTranslator && !showVoiceForm">
                                    <div class="text-center col">
                                        <button class="w-full btn btn-secondary hover:btn-primary"
                                            @click.prevent="toggleStandardChatForm()">
                                            <ChatIcon />
                                            Text Chat
                                        </button>
                                    </div>
                                </template>
                            </template>


                            <template v-if="showVoiceForm">
                                <div class="col">
                                    <button class="float-right btn btn-sm text-error" @click.prevent="toggleVoiceForm()">
                                        <small>Voice Chat</small>
                                        <CloseIcon />
                                        <span class="sr-only"> Close</span>
                                    </button>
                                </div>
                                <div class="col">
                                    <VoiceChatForm />
                                </div>
                            </template>
                            <template v-else>
                                <template v-if="!showStandardChatForm && !showUniversalTranslator && !showImageForm">
                                    <div class="text-center col">
                                        <button class="w-full btn btn-secondary hover:btn-primary"
                                            @click.prevent="toggleVoiceForm()">
                                            <MicrophoneIcon />
                                            Voice Chat
                                        </button>
                                    </div>
                                </template>
                            </template>

                            <template v-if="showUniversalTranslator">
                                <div class="col">
                                    <button class="float-right btn btn-sm text-error"
                                        @click.prevent="toggleUniversalTranslator()">
                                        <small>Universal Translator</small>
                                        <CloseIcon />
                                        <span class="sr-only"> Close</span>
                                    </button>
                                </div>
                                <div class="col">
                                    <UniversalTranslator />
                                </div>

                            </template>
                            <template v-else>
                                <template v-if="!showStandardChatForm && !showImageForm && !showVoiceForm">
                                    <div class="text-center col">
                                        <button class="w-full btn btn-secondary hover:btn-primary"
                                            @click.prevent="toggleUniversalTranslator()">
                                            <TranslateIcon />
                                            Universal
                                            Translator
                                        </button>
                                    </div>
                                </template>
                            </template>
                            <template
                                v-if="!showStandardChatForm && !showUniversalTranslator && !showImageForm && !showVoiceForm">
                                <div class="text-center col">
                                    <Options />
                                </div>
                            </template>
                        </div>
                    </div>

                </div>
            </div>
        </div>


    </AuthenticatedLayout>
</template>
