<script setup>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head} from "@inertiajs/vue3";

const props = defineProps({
    article: {
        type: Object,
        required: true,
    }
})

</script>

<template>
    <Head>
        <title>{{ article.title }}</title>
    </Head>
    <AuthenticatedLayout>
        <div class="container m-auto mt-10">
            <div class="flex items-center">
                <h3 class="text-xl mr-4">
                    {{ article.title }}
                </h3>
            </div>
            <div class="mt-4">
                <div v-html="article.content"></div>
            </div>

        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
