<script setup>

import {toRef} from "vue";
import {usePage} from "@inertiajs/vue3";

const props = defineProps(['users']);
const emit = defineEmits(['userSelected']);
const users = toRef(props.users);

const page = usePage();

const auth = page.props.auth.user;

const handleUserClick = (user) => {
    emit('userSelected', user);
};
</script>
<template>
    <div class="overflow-y-auto h-screen p-3 mb-9 pb-20">
        <div v-for="user in users.filter((user) => user.id !== auth.id)"
             class="flex items-center mb-4 cursor-pointer hover:bg-gray-100 p-2 rounded-md"
             @click='handleUserClick(user)'>
            <div class="w-12 h-12 bg-gray-300 rounded-full mr-3">
                <img :src="`https://ui-avatars.com/api/?name=${user.name}`"
                     alt="User Avatar"
                     class="w-12 h-12 rounded-full">
            </div>
            <div class="flex-1">
                <h2 class="text-lg font-semibold">{{ user.name }}</h2>
                <p v-if="user.latestMessage" class="text-gray-600">{{ user.latestMessage.body }}</p>
            </div>
        </div>
    </div>
</template>
