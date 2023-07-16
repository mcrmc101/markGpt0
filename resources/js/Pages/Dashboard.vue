<script setup>
import ToggleDarkMode from '@/Components/ToggleDarkMode.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
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
    axios.post(route('wisdom.seek', { query: query.value }))
        .then((res) => {
            var msg = res.data.message;
            result.value = msg;
            speechSynthesis.speak(new SpeechSynthesisUtterance(msg));
            query.value = '';
            getNewGreeting();
        })
        .catch((err) => {
            console.log(err);
            error.value = 'Rate Limit has been abused!';
        });
};

const test = () => {
    axios.get(route('test')).then((res) => { console.log(res.data); }).catch((err) => {
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


        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 items-center my-auto">
                    <form @submit.prevent="submitForm()">




                        <textarea ref="textarea" :placeholder="greeting" class="textarea textarea-bordered w-full"
                            v-model="query"></textarea>
                        <button class="btn btn-success my-2" type="submit">{{ buttontext }}</button>

                        <label class="label">
                            <span v-if="error" class="label-text text-error dark:invert">{{ error }}</span>
                        </label>

                    </form>
                    <p v-html="result" class="font-bold mx-auto"></p>
                </div>
            </div>
        </div>
        <template #footer>

            <ToggleDarkMode />

            <button class="btn btn-warning" @click.prevent="test()">Test!!!</button>
        </template>
    </AuthenticatedLayout>
</template>
