<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {usePage} from "@inertiajs/vue3";
import {onMounted, onUnmounted, ref} from "vue";
import {Head} from "@inertiajs/vue3";
import ProgressBar from "@/Components/ProgressBar.vue";

const props = defineProps({
    filePath: {
        type: String,
        required: true
    }
})

const isLoading = ref(true)
const showPath = ref(true)

onMounted(() => {
    window.Echo
        .private(`App.Models.User.${usePage().props.auth.user.id}`)
        .listen(".articles.exported", (e) => {
            isLoading.value = false
            showPath.value = true
        });
})

onUnmounted(() => {
    window.Echo.private(`App.Models.User.${usePage().props.auth.user.id}`)
        .stopListening('.article.exported')
})

</script>

<template>
    <Head>
        <title>Export Articles</title>
    </Head>
<AuthenticatedLayout>
    <div class="container m-auto mt-10">
        <div class="flex items-center">
            <h3 class="text-xl mr-4">
                Export Articles
            </h3>
        </div>
        <div class="my-10">
            <div v-if="isLoading" class="m-auto max-w-[300px]">
                <progress-bar />
                <p class="text-center mt-4">Loading...</p>
            </div>
            <div v-if="!isLoading && showPath">
                <a :href="filePath">Your link is ready!</a>
            </div>
        </div>
    </div>
</AuthenticatedLayout>
</template>

<style scoped>

</style>
