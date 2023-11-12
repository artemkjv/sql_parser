<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    links: {
        type: Array,
        required: true
    }
})

</script>
<template>
    <div class="mt-8">
        <div class="flex flex-wrap">
            <template v-for="(link, key) in links" :key="key">
                <div
                    v-if="link.url === null"
                    class="mr-2 mb-1 px-4 py-3 text-md leading-4 text-heavy-blue hover:text-white hover:bg-heavy-blue duration-300 border-heavy-blue border rounded-xl cursor-pointer"
                    v-html="link.label"
                />

                <Link
                    v-else
                    class="mr-2 mb-1 px-4 py-3 text-md leading-4 border-heavy-blue border rounded-xl text-heavy-blue hover:!text-white duration-300 hover:!bg-heavy-blue"
                    :class="{ '!bg-heavy-blue': link.active, '!text-white': link.active }"
                    :href="link.url"
                    v-html="link.label"
                />
            </template>
        </div>
    </div>
</template>
