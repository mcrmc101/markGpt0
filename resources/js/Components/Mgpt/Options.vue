<script setup>
import { defineProps, ref, computed } from 'vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import { onMounted } from 'vue';

const options = computed(() => usePage().props.options);

const showOptions = ref(false);

const sarcasm = ref(options.value.sarcasm);
const humour = ref(options.value.humour);
const manc = ref(options.value.manc);

const toggleOptions = () => {
    var data = {
        sarcasm: sarcasm.value,
        humour: humour.value,
        manc: manc.value,
    };
    axios.post(route('toggle.options', data)).then((res) => {
        router.visit(route('chat.clear'));
    }).catch((err) => {
        console.log(err);
    });
};

const toggleShowOptions = () => {
    if (showOptions.value == true) {
        showOptions.value = false;
    }
    else {
        showOptions.value = true;
    }
    return;
};
onMounted(() => {
    //console.log(options.value.sarcasm);
});
</script>
<template>
    <button class="btn" @click.prevent="toggleShowOptions()">Options</button>
    <template v-if="showOptions">
        <select class="select select-primary" v-model="manc" @change="toggleOptions()">
            <option value="true">Manc On</option>
            <option value="false">Manc Off</option>
        </select>

        <select class="select select-primary" v-model="sarcasm" @change="toggleOptions()">
            <option value="true">Sarcasm On</option>
            <option value="false">Sarcasm Off</option>
        </select>

        <select class="select select-primary" v-model="humour" @change="toggleOptions()">
            <option value="true">Humour On</option>
            <option value="false">Humour Off</option>
        </select>
    </template>
</template>