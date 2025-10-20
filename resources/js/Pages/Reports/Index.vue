<script setup>
import { router } from "@inertiajs/vue3";
import { ref } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import BaseSelect from "@/Components/BaseSelect.vue";
import { Head } from "@inertiajs/vue3";

const props = defineProps({
    transactions: Array,
    summary: Object,
    filters: Object,
});

const type = ref(props.filters?.type || "daily");
const start = ref(props.filters?.start || "");
const end = ref(props.filters?.end || "");

const fetchData = () => {
    router.get(route("reports.index"), {
        type: type.value,
        start: start.value,
        end: end.value,
    });
};

const downloadPdf = () => {
    window.open(
        route("reports.exportPdf", {
            type: type.value,
            start: start.value,
            end: end.value,
        })
    );
};

const downloadExcel = () => {
    window.open(
        route("reports.exportExcel", {
            type: type.value,
            start: start.value,
            end: end.value,
        })
    );
};
</script>

<template>
    <Head title="Report" />
    <AuthenticatedLayout
        :categories="categories"
        @filter="handleFilter"
        @search="handleSearch"
    >
        <div class="py-12">
            <Notifications />
            <div class="w-full">
                <div class="p-4 bg-white rounded-lg">
                    <h2 class="mb-4 text-2xl font-semibold">
                        Laporan Transaksi Penjualan
                    </h2>

                    <div class="flex flex-wrap items-center gap-2 mb-4">
                        <div
                            class="flex flex-wrap items-end gap-3 bg-whiterounded-lg"
                        >
                            <div class="flex flex-col">
                                <label
                                    class="mb-1 text-sm font-medium text-gray-600"
                                    >Tipe Laporan</label
                                >
                                <BaseSelect
                                    v-model="type"
                                    :options="[
                                        { value: 'daily', label: 'Per Hari' },
                                        {
                                            value: 'range',
                                            label: 'Rentang Tanggal',
                                        },
                                        {
                                            value: 'monthly',
                                            label: 'Per Bulan',
                                        },
                                        { value: 'yearly', label: 'Per Tahun' },
                                    ]"
                                    placeholder="Pilih jenis laporan"
                                    class="min-w-[180px]"
                                />
                            </div>

                            <div class="flex flex-col">
                                <label
                                    class="mb-1 text-sm font-medium text-gray-600"
                                    >Tanggal Awal</label
                                >
                                <input
                                    type="date"
                                    v-model="start"
                                    class="px-3 py-2 text-gray-700 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                />
                            </div>

                            <div v-if="type === 'range'" class="flex flex-col">
                                <label
                                    class="mb-1 text-sm font-medium text-gray-600"
                                    >Tanggal Akhir</label
                                >
                                <input
                                    type="date"
                                    v-model="end"
                                    class="px-3 py-2 text-gray-700 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                />
                            </div>

                            <div class="flex items-end">
                                <button
                                    @click="fetchData"
                                    class="px-5 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-all focus:ring-2 focus:ring-blue-400"
                                >
                                    <i
                                        class="mr-1 fa-solid fa-magnifying-glass"
                                    ></i>
                                    Tampilkan
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-2 mb-4">
                        <button
                            @click="downloadPdf"
                            class="px-4 py-2 text-white bg-red-500 rounded"
                        >
                            Download PDF
                        </button>
                        <button
                            @click="downloadExcel"
                            class="px-4 py-2 text-white bg-green-600 rounded"
                        >
                            Download Excel
                        </button>
                    </div>

                    <div class="my-4">
                        <p>
                            <strong>Total Transaksi:</strong>
                            {{ props.summary.total_transactions }}
                        </p>
                        <p>
                            <strong>Total Penjualan:</strong> Rp
                            {{
                                parseFloat(
                                    props.summary.total_amount
                                ).toLocaleString("id-ID")
                            }}
                        </p>
                    </div>

                    <div
                        class="w-full overflow-x-auto bg-white border border-gray-200 rounded-lg"
                    >
                        <table
                            class="min-w-full text-sm text-left text-gray-700"
                        >
                            <thead
                                class="text-xs text-gray-600 uppercase bg-gray-100"
                            >
                                <tr>
                                    <th class="px-4 py-3">Kode Transaksi</th>
                                    <th class="px-4 py-3">Metode Pembayaran</th>
                                    <th class="px-4 py-3">Status</th>
                                    <th class="px-4 py-3 text-right">
                                        Total (Rp)
                                    </th>
                                    <th class="px-4 py-3 text-center">
                                        Tanggal
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="trx in props.transactions"
                                    :key="trx.transaction_code"
                                    class="transition border-t hover:bg-gray-50"
                                >
                                    <td
                                        class="px-4 py-3 font-medium text-gray-800"
                                    >
                                        {{ trx.transaction_code }}
                                    </td>
                                    <td class="px-4 py-3 capitalize">
                                        {{ trx.payment_method }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <span
                                            :class="[
                                                'px-2 py-1 rounded-full text-xs font-semibold',
                                                trx.status === 'completed'
                                                    ? 'bg-green-100 text-green-700'
                                                    : trx.status === 'pending'
                                                    ? 'bg-yellow-100 text-yellow-700'
                                                    : 'bg-red-100 text-red-700',
                                            ]"
                                        >
                                            {{ trx.status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        Rp
                                        {{
                                            parseFloat(
                                                trx.total_amount
                                            ).toLocaleString("id-ID")
                                        }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        {{
                                            new Date(
                                                trx.created_at
                                            ).toLocaleDateString("id-ID", {
                                                day: "2-digit",
                                                month: "short",
                                                year: "numeric",
                                            })
                                        }}
                                    </td>
                                </tr>

                                <tr v-if="props.transactions.length === 0">
                                    <td
                                        colspan="5"
                                        class="px-4 py-6 italic text-center text-gray-500"
                                    >
                                        Tidak ada data transaksi.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
