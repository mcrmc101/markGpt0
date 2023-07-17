<script setup>
import ToggleDarkMode from '@/Components/ToggleDarkMode.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { onMounted } from 'vue';
import { ref, watch, nextTick } from 'vue';


const query = ref('');
const textarea = ref();
const greeting = ref();
const buttontext = ref();
const result = ref('');
const error = ref('');
const loading = ref(false);
const chatHistory = ref([]);

const getNewGreeting = () => {
    axios.get(route('greeting.new'))
        .then((res) => {
            greeting.value = res.data.greeting;
            buttontext.value = res.data.button;
        })
        .catch((err) => {
            console.log(err);
        });
};

watch(query, () => {
    textarea.value.style.height = 'auto';

    nextTick(() => {
        textarea.value.style.height = textarea.value.scrollHeight + 'px';
    });
});

const submitForm = () => {
    loading.value = true;
    var msg = '';
    if (query.value.length < 5) {
        msg = 'Be more Verbose!';
        result.value = msg;
        chatHistory.value.push({ sender: 'bot', text: msg, type: 'e' });
        query.value = '';
        loading.value = false;
        return getNewGreeting();
    }
    axios.post(route('wisdom.seek', { query: query.value }))
        .then((res) => {
            msg = res.data.message.data;
            result.value = msg;
            console.log(chatHistory.value);
            // speechSynthesis.speak(new SpeechSynthesisUtterance(msg));
            chatHistory.value.push({ sender: 'user', text: query.value, type: 'm' });
            chatHistory.value.push({ sender: 'bot', text: msg, type: 'm' });
            query.value = '';
            error.value = '';
            loading.value = false;
            getNewGreeting();
        })
        .catch((err) => {
            console.log(err);
            error.value = 'Rate Limit has been abused!';
            loading.value = false;
        });
};



const test = () => {
    axios.get(route('test')).then((res) => {
        result.value = res.data;
        console.log(res.data);
    }).catch((err) => {
        console.log(err);
    });
};




onMounted(() => {

    getNewGreeting();
});
</script>

<template>
    <Head title="markGPT" />

    <AuthenticatedLayout>


        <div class="py-6">
            <div class="container px-4 mx-auto mb-auto">
                <div class="bg-white shadow-sm sm:rounded-lg p-4 md:p-8 items-center my-auto">
                    <div v-if="result.length > 0 || error">

                        <!--Results-->
                        <template v-if="result.length > 0">
                            <div v-for="(chat, index) in chatHistory" :key="index" class="">
                                <div class="fade-in" :class="['chat', chat.sender === 'user' ? 'chat-start' : 'chat-end']">
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
                                    <div class="chat-bubble shadow">
                                        <p v-if="chat.sender === 'bot'" v-html="chat.text" class="font-bold mx-auto my-2"
                                            :class="[chat.type === 'e' ? 'text-error' : 'text-neutral-900']">
                                        </p>
                                        <p v-else class="font-bold mx-auto my-2" v-html="chat.text"></p>
                                    </div>
                                </div>
                            </div>
                        </template>
                        <!--Errors-->
                        <div v-if="error" class="chat chat-end">
                            <div class="chat-image avatar">
                                <div class="w-10 rounded-full">
                                    <img src="/img/catprofile.jpg" alt="markGPT cat" class="dark:invert" />
                                </div>
                            </div>
                            <div class="chat-bubble shadow">
                                <span class="label-text text-error dark:invert">{{ error }}</span>
                            </div>
                        </div>


                    </div>
                    <div v-else class="justify-content-center">
                        <img src="img/spin.svg" class="m-auto max-h-64 opacity-10" alt="">
                    </div>
                    <div class="flex flex-wrap items-end mt-auto">
                        <div class="w-full mt-6">

                            <form @submit.prevent="submitForm()">
                                <textarea ref="textarea" :placeholder="greeting" class="textarea textarea-bordered w-full"
                                    v-model="query"></textarea>

                                <button class="btn btn-success my-2 font-extrabold" type="submit">
                                    <template v-if="loading">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor"
                                            class="bi bi-arrow-clockwise loadspin text-white" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z" />
                                            <path
                                                d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z" />
                                        </svg>
                                    </template>
                                    <template v-else>
                                        {{ buttontext }}
                                    </template>
                                </button>

                            </form>
                        </div>

                    </div>
                </div>
            </div>
            <div class="flex flex-wrap my-2 px-4 text-center">
                <div class="divider w-full"></div>
                <div class="w-full md:w-1/3">

                    <ToggleDarkMode class="" />
                </div>
                <div class="w-full md:w-1/3">
                    <a href="https://mcrmc.co.uk" class="text-center text-sm hover:text-primary hover:underline">mcrmc</a>
                </div>
                <div class="w-full md:w-1/3">
                    <button @click.prevent="test()">Test!!!</button>

                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
<style scoped>
.loadspin {
    animation: loadspin 1s linear infinite;
}

@keyframes loadspin {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}
</style>
