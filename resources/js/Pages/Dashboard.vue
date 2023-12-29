<script setup>
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import ChatFormStandard from '@/Components/Mgpt/ChatFormStandard.vue';
import ImageForm from '@/Components/Mgpt/ImageForm.vue';
import UniversalTranslator from '@/Components/Mgpt/UniversalTranslator.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import CloseIcon from '@/Components/Icons/CloseIcon.vue';
import VoiceChatForm from '@/Components/Mgpt/VoiceChatForm.vue';
import MicrophoneIcon from '@/Components/Icons/MicrophoneIcon.vue';
import ChatIcon from '@/Components/Icons/ChatIcon.vue';
import ImageIcon from '@/Components/Icons/ImageIcon.vue';
import TranslateIcon from '@/Components/Icons/TranslateIcon.vue';
import Options from '@/Components/Mgpt/Options.vue';
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
        <div class="h-auto px-4 py-6 mx-auto mb-auto md:w-fit dark:invert">
            <div class="p-4 my-auto bg-white rounded-md shadow sm:rounded-lg md:p-8">

                <div class="flex flex-wrap items-center justify-center mb-4 text-center">

                    <div class="w-full">
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

                <div class="grid items-center justify-center grid-cols-1 mt-auto space-y-2">
                    <div class="text-center col">
                        <ul class="px-1">
                            <li>
                                <Link :href="route('chat.show')"
                                    class="my-2 btn btn-secondary hover:btn-primary active:btn-primary focus:btn-primary">
                                <ChatIcon />
                                Text Chat</Link>
                            </li>
                            <li>
                                <Link :href="route('voice.show')"
                                    class="my-2 btn btn-secondary hover:btn-primary active:btn-primary focus:btn-primary">
                                <MicrophoneIcon />
                                Voice Chat</Link>
                            </li>
                            <li>
                                <Link :href="route('image.show')"
                                    class="my-2 btn btn-secondary hover:btn-primary active:btn-primary focus:btn-primary">
                                <ImageIcon />
                                Image Generator</Link>
                            </li>
                            <li>
                                <Link :href="route('translate.show')"
                                    class="my-2 btn btn-secondary hover:btn-primary active:btn-primary focus:btn-primary">
                                <TranslateIcon />
                                Translator</Link>
                            </li>

                        </ul>
                    </div>
                    <div class="text-center col">
                        <Options />
                    </div>
                </div>
            </div>
        </div>


    </AuthenticatedLayout>
</template>
