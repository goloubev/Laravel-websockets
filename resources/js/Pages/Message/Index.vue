<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<script>
import axios from "axios";

export default {
    name: "Index",
    props: [
        'messages'
    ],
    data() {
        return {
            body: ''
        }
    },
    created() {
        // Static channel
        Echo.channel('store_message')
            // WITH "." !!! '.store_message'
            .listen('.store_message', res => {
                this.messages.unshift(res.message);
            })
    },
    methods: {
        store() {
            axios.post('/messages', {
                body: this.body
            })
            .then( res => {
                this.messages.unshift(res.data);
                this.body = '';
            })
        }
    }
}
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Messages</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="mb-4">
                            <input type="text" name="user_message" id="user_message" v-model="body" placeholder="Your message" class="w-3/4 mr-5" />
                            <a @click.prevent="store" href="#" class="w-auto bg-sky-400 text-white text-center py-3 p-5">SEND</a>
                        </div>
                        <div class="mb-4" v-if="messages.length > 0">
                            <div class="pt-4">
                                <div v-for="message in messages" class="text-sm pb-4 mb-4 border-b">
                                    <p>{{ message.body }}</p>
                                    <p>{{ message.time }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
