<script setup>
import {Link, Head, useForm} from '@inertiajs/vue3';
import {router} from "@inertiajs/vue3";

const props = defineProps({
    dumps: {
        type: Object,
        required: true
    },
    fileTypes: {
        type: Array,
        required: true
    }
})

const exportForm = useForm({
    id: [],
    type: null,
})

import { notify } from "notiwind"

const checkedDumps = ref([])

onMounted(() => {
    checkedDumps.value = props.dumps.data.filter((dump) => dump.isSelected);
})

const selectDump =  dump => {
    dump.isSelected = !dump.isSelected;
    checkedDumps.value = props.dumps.data.filter((dump) => dump.isSelected);
}

const exportDumps = type => {
    exportForm.id = checkedDumps.value.map(item => {
        return item.id
    })
    exportForm.type = type
    exportForm.post(route('dumps.export'))
}
const deleteDump = id => {
    router.delete(route('dumps.destroy', id), {
        onError: res => {
            notify({
                group: "error",
                title: "Error!",
                text: res.dump
            }, 2000);
        },
        onSuccess: res => {
            notify({
                group: 'success',
                title: "Success!",
                text: res.props.flash.message
            }, 2000)
        }
    })
}

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Table from "@/Components/Table.vue";
import TableRow from "@/Components/TableRow.vue";
import DangerButton from "@/Components/DangerButton.vue";
import Pagination from "@/Components/Pagination.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Checkbox from "@/Components/Checkbox.vue";
import {onMounted, ref} from "vue";
</script>

<template>
    <Head>
        <title>Dumps</title>
    </Head>
    <AuthenticatedLayout>
        <div class="container m-auto mt-10">
            <div class="flex items-center">
                <h3 class="text-xl mr-4">
                    Dumps
                </h3>
                <div class="flex gap-4">
                    <Link :href="route('dumps.create')">
                        <PrimaryButton>
                            Import dumps
                        </PrimaryButton>
                    </Link>
                    <template v-if="checkedDumps.length > 1">
                        <PrimaryButton v-for="fileType in fileTypes" type="button" :key="fileType" @click.prevent="exportDumps(fileType)">
                            Export in {{ fileType }}
                        </PrimaryButton>
                    </template>
                </div>
            </div>
            <div class="my-4">
                <template v-if="dumps.data.length">
                    <Table>
                        <template #head>
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Select
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Filename
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Created At
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Delete
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Show
                                </th>
                            </tr>
                        </template>
                        <TableRow :key="`${dump.id}-dump`" v-for="dump in dumps.data">
                            <td class="px-6 py-3">
                                <Checkbox :checked="dump.isSelected !== undefined && dump.isSelected" @update:checked="selectDump(dump)"/>
                            </td>
                            <td class="px-6 py-3">
                                {{ dump.filename }}
                            </td>
                            <td class="px-6 py-3">
                                {{ new Date(dump.created_at).toDateString() }}
                            </td>
                            <td class="px-6 py-3">
                                <form @submit.prevent="deleteDump(dump.id)">
                                    <DangerButton type="submit">
                                        Delete
                                    </DangerButton>
                                </form>
                            </td>
                            <td class="px-6 py-3">
                                <Link class="text-blue-500" :href="route('dumps.show', dump.id)">
                                    Show
                                </Link>
                            </td>
                        </TableRow>
                    </Table>
                    <Pagination :links="dumps.links" />
                </template>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
