<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {useForm, usePage} from "@inertiajs/vue3";
import {notify} from "notiwind";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import {ref} from "vue";
import {Head} from "@inertiajs/vue3";

const dumpFiles = ref(null)

const form = useForm({
    files: [],
});

const submit = () => {
    form.files = dumpFiles.value.files
    form.post(route('dumps.store'), {
        onSuccess: res => {
            notify({
                group: 'success',
                title: "Success!",
                text: res.props.flash.message
            }, 2000)
        },
        onError: res => {
            const message = Object.values(res).join('<br>')
            notify({
                group: 'error',
                title: 'Erorr!',
                text: message
            })
        }
    });
};
</script>

<template>
    <Head>
        <title>Import Dumps</title>
    </Head>
<AuthenticatedLayout>
    <div class="container m-auto mt-10">
        <div class="flex items-center">
            <h3 class="text-xl mr-4">
                Import Dumps
            </h3>
        </div>
        <div class="my-4">
            <form @submit.prevent="submit">
                <div>
                    <InputLabel for="dump-files" value="Files" />

                    <input class="block p-2 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer focus:outline-none mt-1" type="file"
                           multiple ref="dumpFiles"
                           id="dump-files" />
                </div>

                <div class="mt-4">
                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Import Dumps
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </div>
</AuthenticatedLayout>
</template>

<style scoped>

</style>
