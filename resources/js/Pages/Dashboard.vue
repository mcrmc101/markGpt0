<script setup>
import SecondaryButton from '@/Components/SecondaryButton.vue';
import ToggleDarkMode from '@/Components/ToggleDarkMode.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const query = ref();

const result = ref('');

const submitForm = () => {
    axios.post(route('wisdom.seek', { query: query.value }))
        .then((res) => { console.log(res.data); })
        .catch((err) => { console.log(err); });
};
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">MarkGPT</h2>
            <ToggleDarkMode />
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 flex flex-wrap justify-items-center">
                    <form @submit.prevent="submitForm()">
                        <div class="form-control w-full max-w-xs text-center">
                            <label class="label">
                                <span class="label-text">What can I do for you?</span>
                            </label>
                            <input type="text" placeholder="Type here" class="input input-bordered w-full max-w-xs"
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
