<script setup>
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import {useForm, usePage} from "@inertiajs/vue3";
import {debounce} from "lodash";

const props = defineProps(['toUser', 'messages', 'typing']);
const form = useForm({
    message: '',
    receiver_id: null,
});

const page = usePage();
const authUser = page.props.auth.user;

const handleSubmit = () => {
    form.receiver_id = props.toUser.id;
    form.post(route('chats.store'), {
        preserveScroll: true,
        preserveState: true,
    });
    form.reset();
};

const userStopTyping = debounce(async () => {
    Echo.private(`App.Models.User.${props.toUser.id}`)
        .whisper('stop-typing', {
            name: authUser.name,
            id: authUser.id,
        });
}, 1000);

const userTyping = () => {
    Echo.private(`App.Models.User.${props.toUser.id}`)
        .whisper('typing', {
            name: authUser.name,
            id: authUser.id,
        });
};
</script>
<template>
    <div v-if="props.toUser" class="flex-1">
        <!-- Chat Header -->
        <header class="bg-white p-4 text-gray-700">
            <h1 class="text-2xl font-semibold">{{ props.toUser.name }}</h1>
        </header>

        <!-- Chat Messages -->
        <div class="h-screen overflow-y-auto p-4 pb-36">
            <div v-for="(message,index) in props.messages" :key="index">
                <div v-if="message.sender_id === props.toUser.id" class="flex mb-4 cursor-pointer">
                    <div :title="props.toUser.name" class="w-9 h-9 rounded-full flex items-center justify-center mr-2">
                        <img :src="`https://ui-avatars.com/api/?name=${props.toUser.name}`"
                             alt="User Avatar"
                             class="w-8 h-8 rounded-full">
                    </div>
                    <div class="flex max-w-96 bg-white rounded-lg p-3 gap-3">
                        <p class="text-gray-700">{{ message.body }}</p>
                    </div>
                </div>
                <div v-else class="flex justify-end mb-4 cursor-pointer">
                    <div class="flex max-w-96 bg-indigo-500 text-white rounded-lg p-3 gap-3">
                        <p>{{ message.body }}</p>
                    </div>
                    <div :title="authUser.name" class="w-9 h-9 rounded-full flex items-center justify-center ml-2">
                        <img :src="`https://ui-avatars.com/api/?name=${authUser.name}`"
                             alt="My Avatar"
                             class="w-8 h-8 rounded-full">
                    </div>
                </div>
            </div>
        </div>

        <!-- Chat Input -->
        <footer class="bg-white border-t border-gray-300 p-2 absolute bottom-0 w-3/4">
            <form @submit.prevent="handleSubmit">
                <div class="flex items-center">
                    <TextInput
                        id="message"
                        v-model="form.message"
                        autocomplete="message"
                        autofocus
                        class="mt-1 block w-full"
                        type="text"
                        @keydown="userTyping"
                        @keyup="userStopTyping"
                    />
                    <button :disabled="form.processing"
                            class="bg-indigo-500 text-white px-4 py-2 rounded-md ml-2"
                            type="submit"
                    >Send
                    </button>
                </div>
                <div>
                    <span v-if="props.typing.status" class="text-gray-500 text-sm">
                        {{ props.typing.name }} is typing...
                    </span>
                </div>
                <div>
                    <InputError :message="form.errors.message" class="mt-2"/>
                </div>
            </form>
        </footer>
    </div>
</template>
