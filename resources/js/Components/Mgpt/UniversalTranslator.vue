<script setup>
import { router } from '@inertiajs/vue3';
import axios from 'axios';
import { onBeforeUnmount, onMounted, ref } from 'vue';
import VueMarkdown from 'vue-markdown-render';

const recordVoice = ref(true);
const stopButton = ref();
const recordButton = ref();
const isRecording = ref(false);
const mediaRecorder = ref();

const translateRoute = ref('translate.to');

const selectedLanguage = ref('French');

const languages = [
    'Afrikaans', 'Arabic', 'Armenian', 'Azerbaijani', 'Belarusian', 'Bosnian', 'Bulgarian', 'Catalan', 'Chinese', 'Croatian', 'Czech', 'Danish', 'Dutch', 'English', 'Estonian', 'Finnish', 'French', 'Galician', 'German', 'Greek', 'Hebrew', 'Hindi', 'Hungarian', 'Icelandic', 'Indonesian', 'Italian', 'Japanese', 'Kannada', 'Kazakh', 'Korean', 'Latvian', 'Lithuanian', 'Macedonian', 'Malay', 'Marathi', 'Maori', 'Nepali', 'Norwegian', 'Persian', 'Polish', 'Portuguese', 'Romanian', 'Russian', 'Serbian', 'Slovak', 'Slovenian', 'Spanish', 'Swahili', 'Swedish', 'Tagalog', 'Tamil', 'Thai', 'Turkish', 'Ukrainian', 'Urdu',
    'Vietnamese', 'Welsh'
];

//globalThis
const speechSynth = window.speechSynthesis;



const result = ref('');
const error = ref('');
const loading = ref(false);

const chatHistory = ref([]);

onMounted(() => {
    recordMe();
});

onBeforeUnmount(() => {
    recordVoice.value = false;
    mediaRecorder.value.stop();
});

const thinkingWord = () => {
    var items = [
        'Working',
        'Thinking',
        'Reticulating Splines',
        'Processing'
    ];
    //   return items[items.length * Math.random() | 0];
    return 'Working';
};

const speakMe = (str) => {
    return speechSynth.speak(new SpeechSynthesisUtterance(str));
};

const stopSpeaking = () => {
    speechSynth.cancel();
};
const recordMe = () => {
    recordVoice.value = true;

    if (navigator.mediaDevices.getUserMedia) {
        console.log('getUserMedia supported.');

        const constraints = { audio: true };
        let chunks = [];

        let onSuccess = function (stream) {
            mediaRecorder.value = new MediaRecorder(stream);

            recordButton.value.onclick = function () {
                stopSpeaking();
                isRecording.value = true;
                loading.value = true;
                mediaRecorder.value.start();
                console.log(mediaRecorder.value.state);
                console.log("recorder started");

                stopButton.value.disabled = false;
                recordButton.value.disabled = true;
            };

            stopButton.value.onclick = function () {
                isRecording.value = false;
                mediaRecorder.value.requestData();

                stopButton.value.disabled = true;
                recordButton.value.disabled = false;
                console.log(mediaRecorder.value.state);
                console.log("recorder stopped");
                mediaRecorder.value.stop();
            };

            mediaRecorder.value.onstop = function (e) {
                console.log('sending to laravel');
                speakMe(thinkingWord());
                const blob = new Blob(chunks, { 'type': 'audio/og' });
                chunks = [];

                const formData = new FormData();
                formData.append('audio', blob); // Append the blob data with a filename
                formData.append('language', selectedLanguage.value);
                axios.post(route(translateRoute.value), formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                })
                    .then((res) => {
                        console.log(res.data.message);
                        result.value = res.data.message;
                        // console.log(res.data.message);
                        speakMe(res.data.message);
                        chatHistory.value.push({ sender: 'user', text: res.data.originalQuestion, type: 'm' });
                        chatHistory.value.push({ sender: 'bot', text: res.data.message, type: 'm' });
                        console.log(chatHistory);
                        //  questionText.value = '';
                        error.value = '';
                        loading.value = false;
                        // questionText.value = res.data.message.text;
                        //  sendQuestionToChat();
                    })
                    .catch((error) => {
                        console.error(error);
                        error.value = 'Chat GPT is stupid and confused! Press Reset and ask again!';
                        loading.value = false;
                    });

            };

            /*
            mediaRecorder.value.onstop = () => {
                const tracks = stream.getTracks();
                // When all tracks have been stopped the stream will
                // no longer be active and release any permissioned input
                tracks.forEach(track => track.stop());
            };
            */

            mediaRecorder.value.ondataavailable = function (e) {
                chunks.push(e.data);
            };
        };

        let onError = function (err) {
            console.log('The following error occured: ' + err);
            loading.value = false;
        };

        navigator.mediaDevices.getUserMedia(constraints).then(onSuccess, onError);

    } else {
        console.log('getUserMedia not supported on your browser!');
        loading.value = false;
    }
};



const resetChat = () => {
    return router.visit(route('chat.clear'));
};

onBeforeUnmount(() => {
    if (chatHistory.value.length > 0) {
        axios.post(route('model.create', { chatType: 'translate' }));
    }
});
</script>
<template>
    <div v-if="result || error">

        <!--Results-->
        <template v-if="result">
            <div v-for="(chat, index) in chatHistory" :key="index" class="my-6">
                <div class="fade-in" :class="['md:chat', chat.sender === 'user' ? 'md:chat-start' : 'md:chat-end']">
                    <div v-if="chat.sender === 'bot'" class="chat-image avatar">
                        <div class="w-10 rounded-full">
                            <img src="/img/catprofile.jpg" alt="markGPT cat" class="dark:invert" />
                        </div>
                    </div>
                    <div v-else class="chat-image">
                        <div class="chat-header">

                            {{ $page.props.auth.user.name }}
                        </div>
                    </div>
                    <div class="shadow chat-bubble bg-neutral-50">
                        <vue-markdown v-if="chat.sender === 'bot'" :source="chat.text" class="mx-auto my-4 prose max-w-none"
                            :class="[chat.type === 'e' ? 'text-error' : 'text-neutral-900']" />

                        <vue-markdown v-else class="mx-auto my-4 prose max-w-none" :source="chat.text" />

                    </div>
                </div>
            </div>
        </template>
        <!--Errors-->
        <div v-if="error" class="chat chat-end">
            <div class="md:chat-image avatar">
                <div class="w-10 rounded-full">
                    <img src="/img/catprofile.jpg" alt="markGPT cat" class="dark:invert" />
                </div>
            </div>
            <div class="shadow chat-bubble">
                <span class="label-text text-error dark:invert">{{ error }}</span>
            </div>
        </div>


    </div>
    <div class="flex flex-col items-center justify-center w-full my-4">
        <template v-if="recordVoice">
            <button class="btn-error btn btn-lg" v-show="isRecording" ref="stopButton">Stop Recording</button>

            <button class="btn btn-secondary btn-lg" v-show="!isRecording" ref="recordButton">
                <template v-if="loading">
                    <span class="loading loading-spinner loading-lg"></span>
                </template>
                <template v-else>
                    <span>Start Recording</span>
                </template>
            </button>
        </template>

    </div>
    <div class="justify-center w-full space-x-4 space-y-4">
        <select class="select select-primary" v-model="translateRoute">
            <option value="translate.from">Translate From</option>
            <option value="translate.to">Translate To</option>
        </select>
        <select class="select select-primary" v-model="selectedLanguage">
            <template v-for="(lang, i) in languages" :key="i">
                <option :value="lang" v-html="lang" />
            </template>
        </select>
        <button @click.prevent="stopSpeaking()" class="btn btn-info">Stop Speaking</button>
        <button @click.prevent="resetChat()" class="btn btn-warning">Reset</button>
    </div>
</template>