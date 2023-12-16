<script setup>
import axios from 'axios';
import { ref, watch, nextTick, onBeforeUnmount } from 'vue';

const generatedImage = ref();
const query = ref('');
const textarea = ref();
const loading = ref(false);
const selectedSize = ref('1024x1024');
const selectedQuality = ref('standard');


const submitForm = () => {
    loading.value = true;
    axios.post(route('image.create'), {
        query: query.value,
        size: selectedSize.value,
        quality: selectedQuality.value
    })
        .then((res) => {
            loading.value = false;
            generatedImage.value = res.data[0].url;
        })
        .catch((error) => {
            loading.value = false;
            console.error(error);
        });
};
watch(query, () => {
    textarea.value.style.height = 'auto';
    nextTick(() => {
        textarea.value.style.height = textarea.value.scrollHeight + 'px';
    });
});

onBeforeUnmount(() => {
    if (generatedImage.value) {
        axios.post(route('model.create', { chatType: 'image' }));
    }
});
</script>
<template>
    <div class="flex flex-col">
        <template v-if="generatedImage">
            <img :src="generatedImage" alt="" class="w-full mb-2 rounded shadow dark:invert">
        </template>
        <form @submit.prevent="submitForm()">
            <textarea ref="textarea" class="w-full mb-2 textarea textarea-bordered" v-model="query"
                placeholder="Describe what you want to see"></textarea>
            <select class="mb-2 mr-2 select select-bordered" v-model="selectedSize">
                <option value="1024x1024">Square</option>
                <option value="1024x1792">Portrait</option>
                <option value="1792x1024">Landscape</option>
            </select>
            <select class="mb-2 mr-2 select select-bordered" v-model="selectedQuality">
                <option value="standard">Standard</option>
                <option value="hd">HD</option>
            </select>
            <button class="mb-2 font-extrabold w-fit btn btn-success" type="submit">
                <template v-if="loading">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor"
                        class="text-white bi bi-arrow-clockwise loadspin" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z" />
                        <path
                            d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z" />
                    </svg>
                    One Mo...
                </template>
                <template v-else>
                    Create Image
                </template>
            </button>

        </form>
    </div>
</template>