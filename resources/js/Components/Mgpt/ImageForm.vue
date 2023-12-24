<script setup>
import axios from 'axios';
import { ref, watch, nextTick, onBeforeUnmount } from 'vue';

const generatedImage = ref();
const query = ref('');
const textarea = ref();
const loading = ref(false);
const selectedSize = ref('1024x1024');
const selectedQuality = ref('standard');
const errorMessage = ref('');


const submitForm = () => {
    errorMessage.value = '';
    loading.value = true;
    axios.post(route('image.create'), {
        query: query.value,
        size: selectedSize.value,
        quality: selectedQuality.value
    })
        .then((res) => {

            if (res.status == 200) {
                if (res.data[0].url) {
                    generatedImage.value = res.data[0].url;
                }
                else {
                    errorMessage.value = 'Something has Gone Wrong!!!';
                }
            }
            else {
                errorMessage.value = 'Something has Gone Wrong!!!';
            }
            loading.value = false;
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
            <div class="w-full">
                <img :src="generatedImage" alt="" class="w-full mb-2 rounded shadow dark:invert">
            </div>
        </template>
        <template v-if="errorMessage">
            <div class="w-full">
                <p class="text-center text-error" v-html="errorMessage" />
            </div>
        </template>
        <form @submit.prevent="submitForm()">
            <div class="w-full">
                <textarea ref="textarea" class="w-full mb-2 textarea textarea-bordered" v-model="query"
                    placeholder="Describe what you want to see"></textarea>
            </div>
            <div class="items-end w-full">
                <select class="mb-2 mr-2 select select-bordered" v-model="selectedSize">
                    <option value="1024x1024">Square</option>
                    <option value="1024x1792">Portrait</option>
                    <option value="1792x1024">Landscape</option>
                </select>
                <select class="mb-2 mr-2 select select-bordered" v-model="selectedQuality">
                    <option value="standard">Standard</option>
                    <option value="hd">HD</option>
                </select>
                <button class="mb-2 font-extrabold w-fit btn btn-secondary" type="submit">
                    <template v-if="loading">
                        <span class="loading loading-spinner"></span>
                        One Mo...
                    </template>
                    <template v-else>
                        Create Image
                    </template>
                </button>
            </div>
        </form>
    </div>
</template>