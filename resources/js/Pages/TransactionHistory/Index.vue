<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import {
    useVueTable,
    getCoreRowModel,
    getFilteredRowModel,
    FlexRender,
} from "@tanstack/vue-table";
import { ref, watch } from "vue";
import { columns, type Transaction } from "./Columns";

const props = defineProps<{
    transactions: {
        data: Transaction[];
        links: { url: string | null; label: string; active: boolean }[];
    };
    filters: {
        search?: string;
    };
}>();

const searchQuery = ref(props.filters?.search || "");

const table = useVueTable({
    data: props.transactions.data,
    columns,
    getCoreRowModel: getCoreRowModel(),
    getFilteredRowModel: getFilteredRowModel(),
});

let debounceTimeout: number | null = null;

watch(searchQuery, (value) => {
    if (debounceTimeout) clearTimeout(debounceTimeout);

    debounceTimeout = setTimeout(() => {
        // Jika field kosong, kembalikan ke halaman awal tanpa filter
        const query = value?.trim() ? { search: value } : {};

        router.get(route("transactions.history"), query, {
            preserveScroll: true,
            replace: true, // agar tidak menumpuk di riwayat browser
        });
    }, 500);
});
</script>

<template>
    <Head title="Riwayat Transaksi" />
    <AuthenticatedLayout :categories="[]">
        <div class="max-w-6xl py-10 mx-auto">
            <h2 class="mb-6 text-2xl font-semibold text-gray-800">
                Riwayat Transaksi
            </h2>

            <div class="flex justify-start mb-4">
                <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="Cari kode, metode, atau status..."
                    class="px-4 py-2 text-sm border border-[#2C59E5] rounded-lg w-72 focus:ring-2 focus:ring-blue-400 focus:outline-none"
                />
            </div>

            <div
                v-if="props.transactions.data.length === 0"
                class="py-10 text-center text-gray-500 border rounded-lg"
            >
                Tidak ada transaksi ditemukan.
            </div>

            <!-- Tabel data -->
            <div v-else class="overflow-x-auto bg-white rounded-lg">
                <table class="min-w-full border-collapse">
                    <thead class="bg-gray-100">
                        <tr
                            v-for="headerGroup in table.getHeaderGroups()"
                            :key="headerGroup.id"
                        >
                            <th
                                v-for="header in headerGroup.headers"
                                :key="header.id"
                                class="px-6 py-3 text-xs font-medium text-left text-gray-600 uppercase"
                            >
                                <FlexRender
                                    :render="header.column.columnDef.header"
                                    :props="header.getContext()"
                                />
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr
                            v-for="row in table.getRowModel().rows"
                            :key="row.id"
                            class="transition border-b hover:bg-gray-50"
                        >
                            <td
                                v-for="cell in row.getVisibleCells()"
                                :key="cell.id"
                                class="px-6 py-4 text-gray-700"
                            >
                                <FlexRender
                                    :render="cell.column.columnDef.cell"
                                    :props="cell.getContext()"
                                />
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- PAGINATION -->
                <div class="flex items-center justify-end gap-2 px-6 py-4">
                    <template
                        v-for="(link, index) in props.transactions.links"
                        :key="index"
                    >
                        <Link
                            v-if="link.url"
                            :href="`${link.url}${
                                searchQuery ? `&search=${searchQuery}` : ''
                            }`"
                            class="px-3 py-1 text-sm border rounded"
                            :class="{
                                'bg-[#2C59E5] text-white': link.active,
                                'text-gray-700 hover:bg-gray-100': !link.active,
                            }"
                            v-html="link.label"
                        />
                        <span
                            v-else
                            class="px-3 py-1 text-gray-400"
                            v-html="link.label"
                        />
                    </template>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
