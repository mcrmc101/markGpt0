<script setup>
import Options from '@/Components/Mgpt/Options.vue';
import ToggleDarkMode from '@/Components/ToggleDarkMode.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import axios from 'axios';
import { ref, watch, nextTick, onMounted } from 'vue';
import VueMarkdown from 'vue-markdown-render';




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
            msg = res.data.message;
            result.value = msg;
            // console.log(res.data.message);
            //speechSynthesis.speak(new SpeechSynthesisUtterance(msg));
            chatHistory.value.push({ sender: 'user', text: query.value, type: 'm' });
            chatHistory.value.push({ sender: 'bot', text: msg, type: 'm' });
            query.value = '';
            error.value = '';
            loading.value = false;
            getNewGreeting();
        })
        .catch((err) => {
            console.log(err);
            error.value = 'Chat GPT is stupid and confused! Press Reset and ask again!';
            loading.value = false;
        });
};

const resetChat = () => {
    return router.visit(route('chat.clear'));
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

                    <div v-if="result || error">

                        <!--Results-->
                        <template v-if="result">
                            <div v-for="(chat, index) in chatHistory" :key="index" class="my-6">
                                <div class="fade-in"
                                    :class="['md:chat', chat.sender === 'user' ? 'md:chat-start' : 'md:chat-end']">
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
                                        <vue-markdown v-if="chat.sender === 'bot'" :source="chat.text"
                                            class="mx-auto my-4 prose max-w-none"
                                            :class="[chat.type === 'e' ? 'text-error' : 'text-neutral-900']" />

                                        <vue-markdown v-else class="mx-auto my-4 prose max-w-none" :source="chat.text" />
                                        <!--
                                        <div v-else class="mx-auto my-4 prose">{{ chat.text }}</div>
                                        -->
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
                            <div class="chat-bubble shadow">
                                <span class="label-text text-error dark:invert">{{ error }}</span>
                            </div>
                        </div>


                    </div>
                    <div v-else class="justify-content-center">
                        <!--
                        <img src="img/spin.svg" class="m-auto max-h-64 opacity-10" alt="">
                        -->
                        <img src="/img/catprofile.jpg" alt="markGPT cat"
                            class="dark:invert rounded-full mx-auto spinMe shadow" />

                    </div>
                    <div class="flex flex-wrap items-end mt-auto">
                        <div class="w-full mt-6">

                            <form @submit.prevent="submitForm()">
                                <textarea ref="textarea" :placeholder="greeting" class="textarea textarea-bordered w-full"
                                    v-model="query"></textarea>

                                <button class="btn btn-success my-2 font-extrabold w-full" type="submit">
                                    <template v-if="loading">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor"
                                            class="bi bi-arrow-clockwise loadspin text-white" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z" />
                                            <path
                                                d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z" />
                                        </svg>
                                        Thinkifying...
                                    </template>
                                    <template v-else>
                                        {{ buttontext }}
                                    </template>
                                </button>

                            </form>
                        </div>
                        <div class="w-full space-x-4 space-y-4">
                            <button @click="resetChat()" class="btn">Reset</button>
                            <Options />
                        </div>
                    </div>

                </div>
            </div>
            <div class="divider"></div>
            <div class="flex flex-auto my-2 px-4 text-center items-center">
                <ToggleDarkMode class="mx-auto" />
                <!--
                <img src="/img/catprofile.jpg" alt="markGPT cat" class="dark:invert spinMe rounded-full h-12 float-right" />
                -->


                <!--
                    <button @click.prevent="test()">Test!!!</button>
                    -->

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
