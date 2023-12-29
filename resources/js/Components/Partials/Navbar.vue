<script setup>
import { ref, computed, onMounted } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import MicrophoneIcon from '@/Components/Icons/MicrophoneIcon.vue';
import ChatIcon from '@/Components/Icons/ChatIcon.vue';
import ImageIcon from '@/Components/Icons/ImageIcon.vue';
import TranslateIcon from '@/Components/Icons/TranslateIcon.vue';
import UserIcon from '@/Components/Icons/UserIcon.vue';
import { Link, usePage } from '@inertiajs/vue3';

onMounted(() => {
    globalThis.addEventListener('click', function (e) {
        document.querySelectorAll('.dropdown').forEach(function (dropdown) {
            if (!dropdown.contains(e.target)) {
                // Click was outside the dropdown, close it
                dropdown.open = false;
            }
        });
    });
});
</script>

<template>
    <div class="navbar bg-base-100">
        <div class="navbar-start">
            <div class="dropdown">
                <div tabindex="0" role="button" class="btn btn-ghost lg:hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" />
                    </svg>
                </div>
                <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52">
                    <li>
                        <Link :href="route('chat.show')">
                        <ChatIcon />
                        Text</Link>
                    </li>
                    <li>
                        <Link :href="route('voice.show')">
                        <MicrophoneIcon />
                        Voice</Link>
                    </li>
                    <li>
                        <Link :href="route('image.show')">
                        <ImageIcon />
                        Image</Link>
                    </li>
                    <li>
                        <Link :href="route('translate.show')">
                        <TranslateIcon />
                        Translator</Link>
                    </li>
                    <li>
                        <details>
                            <summary>
                                <UserIcon />
                                {{ $page.props.auth.user.name }}
                            </summary>
                            <ul class="p-2">
                                <li>
                                    <Link :href="route('profile.edit')">Profile</Link>
                                </li>
                                <li>
                                    <Link :href="route('logout')" method="post">Log Out</Link>
                                </li>
                            </ul>
                        </details>
                    </li>
                </ul>
            </div>
            <Link :href="route('dashboard')">
            <ApplicationLogo class="block w-auto text-gray-800 fill-current h-9" />
            </Link>
        </div>
        <div class="justify-end hidden w-full lg:flex">
            <ul class="px-1 menu menu-horizontal">
                <li>
                    <Link :href="route('chat.show')">
                    <ChatIcon />
                    Text</Link>
                </li>
                <li>
                    <Link :href="route('voice.show')">
                    <MicrophoneIcon />
                    Voice</Link>
                </li>
                <li>
                    <Link :href="route('image.show')">
                    <ImageIcon />
                    Image</Link>
                </li>
                <li>
                    <Link :href="route('translate.show')">
                    <TranslateIcon />
                    Translator</Link>
                </li>
                <li>
                    <details>
                        <summary>
                            <UserIcon />
                            {{ $page.props.auth.user.name }}
                        </summary>
                        <ul class="p-2">
                            <li>
                                <Link :href="route('profile.edit')">Profile</Link>
                            </li>
                            <li>
                                <Link :href="route('logout')" method="post">Log Out</Link>
                            </li>
                        </ul>
                    </details>
                </li>

            </ul>
        </div>

    </div>
</template>