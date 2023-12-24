<script setup>
import { ref, computed } from 'vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import GearIcon from '../Icons/GearIcon.vue';

const options = computed(() => usePage().props.options);

const showOptions = ref(false);

const sarcasm = ref(options.value.sarcasm);
const humour = ref(options.value.humour);
const manc = ref(options.value.manc);
const background = ref(options.value.background);

const toggleOptions = () => {
    var data = {
        sarcasm: sarcasm.value,
        humour: humour.value,
        manc: manc.value,
        background: background.value
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
    <div class="flex flex-wrap items-end justify-center gap-4 md:justify-between">
        <button class="w-full btn btn-secondary hover:btn-primary active:btn-primary focus:btn-primary"
            @click.prevent="toggleShowOptions()">
            <GearIcon />
            Options
        </button>
        <template v-if="showOptions">

            <select class="select select-primary" v-model="manc" @change="toggleOptions()">
                <option value="1">Manc On</option>
                <option value="0">Manc Off</option>
            </select>

            <select class="select select-primary" v-model="sarcasm" @change="toggleOptions()">
                <option value="1">Sarcasm On</option>
                <option value="0">Sarcasm Off</option>
            </select>

            <select class="select select-primary" v-model="humour" @change="toggleOptions()">
                <option value="1">Humour On</option>
                <option value="0">Humour Off</option>
            </select>
            <select class="select select-primary" v-model="background" @change="toggleOptions()">
                <option value="1">Background On</option>
                <option value="0">Background Off</option>
            </select>
        </template>
    </div>
</template>