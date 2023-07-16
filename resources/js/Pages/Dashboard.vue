<script setup>
import SecondaryButton from '@/Components/SecondaryButton.vue';
import ToggleDarkMode from '@/Components/ToggleDarkMode.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import axios from 'axios';
import { onMounted } from 'vue';
import { ref } from 'vue';


const query = ref();
const greeting = ref();
const result = ref('');

const getNewGreeting = () => {
    axios.get(route('greeting.new'))
        .then((res) => {
            greeting.value = res.data;
        })
        .catch((err) => {
            console.log(err);
        });
};

const submitForm = () => {
    axios.post(route('wisdom.seek', { query: query.value }))
        .then((res) => { console.log(res.data); })
        .catch((err) => { console.log(err); });
};



onMounted(() => {
    getNewGreeting();
    //console.log(greeting);
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>

            <ToggleDarkMode />
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 text-center justify-items-center">
                    <form @submit.prevent="submitForm()">
                        <div class="form-control w-full text-center mx-auto">
                            <label class="label">
                                <span class="label-text text-2xl">{{ greeting }}</span>
                            </label>
                            <input type="text" placeholder="Type here" class="input input-bordered w-full"
                                v-model="query" />
                            <label class="label">

                            </label>
                        </div>
                        <button class="btn btn-success" type="submit">Submit</button>

                    </form>
                    <p v-html="result"></p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
