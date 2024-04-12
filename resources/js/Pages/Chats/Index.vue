<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, usePage} from '@inertiajs/vue3';
import {onMounted, onUnmounted, toRef} from "vue";
import Contacts from "@/Pages/Chats/Contacts.vue";
import ChatArea from "@/Pages/Chats/ChatArea.vue";

const props = defineProps(['users']);
const users = toRef(props.users);
const messages = toRef(null);
const selectedUser = toRef(null);
const page = usePage();
const user = page.props.auth.user;

let onlineUsers = toRef([]);
let typing = toRef({
    status: false,
    name: ''
});

const onSelectUser = (user) => {
    selectedUser.value = user;
    typing.value.status = false;
    getMessages();
};

const getMessages = async () => {
    const response = await axios.get(route('chats.messages.get', {id: selectedUser.value.id}));
    messages.value = response.data;
};

onMounted(() => {
    Echo.private(`App.Models.User.${user.id}`)
        .listen('MessageSent', (event) => {
            messages.value.push(event.message);
        })
        .listenForWhisper('typing', (event) => {
            if (event.id === selectedUser.value.id) {
                typing.value.status = true;
                typing.value.name = event.name;
            }
        })
        .listenForWhisper('stop-typing', (event) => {
            typing.value.status = false;
            typing.value.name = '';
        });

    Echo.join(`chat`)
        .joining((user) => {
            onlineUsers.value.push(user);
        })
        .leaving((user) => {
            onlineUsers.value = onlineUsers.value.filter(u => u.id !== user.id);
        }).here((users) => {
        onlineUsers.value = users;
    });
});

onUnmounted(() => {
    Echo.leave(`App.Models.User.${user.id}`);
    Echo.leave(`chat`);
});

</script>

<template>
    <Head title="Chats"/>

    <AuthenticatedLayout>
        <div class="flex h-screen overflow-hidden">
            <!-- Sidebar -->
            <div class="w-1/4 bg-white border-r border-gray-300">
                <!-- Sidebar Header -->
                <header class="p-4 border-b border-gray-300 flex justify-between items-center bg-indigo-600 text-white">
                    <h1 class="text-2xl font-semibold">Chats</h1>
                </header>

                <!-- Contact List -->
                <Contacts :online-users="onlineUsers" :users="users" @userSelected='onSelectUser'/>
            </div>

            <ChatArea v-if="selectedUser" :fromUser="user" :messages='messages' :toUser='selectedUser'
                      :typing="typing"/>

        </div>
    </AuthenticatedLayout>
</template>
