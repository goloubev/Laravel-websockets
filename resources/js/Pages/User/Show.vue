<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<script>
import axios from "axios";

export default {
    name: "Show",
    props: [
        'user'
    ],
    data() {
        return {
            like_str: ''
        }
    },
    created() {
        // console.log(this.$page.props);
        // this.$page.props.auth.user.id
        // this.$page.props.auth.user.name

        // Dynamic channel
        Echo.private(`send_like_${this.$page.props.auth.user.id}`)
            // WITH "." !!! '.send_like'
            .listen('.send_like', res => {
                this.like_str = res.like_str;
            })
    },
    methods: {
        sendLike() {
            axios.post(`/users/${this.user.id}`, {
                from_id: this.$page.props.auth.user.id
            })
            .then(res => {
                this.like_str = res.data.like_str
            })
        }
    }
}
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ user.name }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="mb-4">
                            <a @click.prevent="sendLike" href="#" class="w-auto bg-sky-400 text-white text-center py-3 p-5">Send LIKE</a>
                        </div>
                        <div v-if="like_str">
                            {{ like_str }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
