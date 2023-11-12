<script setup>

import {Link, Head} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Article from "@/Components/Article.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const props = defineProps({
    dump: {
        type: Object,
        required: true,
    }
})

</script>

<template>
    <Head>
        <title>
            {{ dump.filename }}
        </title>
    </Head>
    <AuthenticatedLayout>
        <div class="container m-auto mt-10">
            <div class="flex items-center">
                <h3 class="text-xl mr-4">
                    {{ dump.filename }} Articles
                </h3>
                <div class="flex gap-4">
                    <template v-for="file in dump.files">
                        <a :href="file.path">
                            <PrimaryButton>
                                Download {{ file.type }}
                            </PrimaryButton>
                        </a>
                    </template>
                </div>
            </div>
            <div class="mt-4">
                <Article :key="`${article.id}-article`" v-for="article in dump.articles">
                    <Link :href="route('articles.show', article.id)">
                        {{ article.title }}
                    </Link>
                </Article>
            </div>

        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
