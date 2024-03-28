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

const onSelectUser = (user) => {
    selectedUser.value = user;
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
        });
});

onUnmounted(() => {
    Echo.leave(`App.Models.User.${user.id}`);
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
                <Contacts :users="users" @userSelected='onSelectUser'/>
            </div>

            <ChatArea v-if="selectedUser" :fromUser="user" :messages='messages' :toUser='selectedUser'/>

        </div>
    </AuthenticatedLayout>
</template>
