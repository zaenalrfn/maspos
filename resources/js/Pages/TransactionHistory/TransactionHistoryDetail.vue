<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import dayjs from "dayjs";
import { formatRupiah } from "@/utils/formatRupiah";
import "dayjs/locale/id";
dayjs.locale("id");

interface Category {
    id: number;
    name: string;
}

interface Product {
    id: number;
    name: string;
    category: Category;
}

interface TransactionItem {
    id: number;
    product_name: string;
    quantity: number;
    price: number;
    product: Product;
}

interface Transaction {
    transaction_code: string;
    total_amount: number;
    payment_method: string;
    status: string;
    created_at: string;
    transaction_items: TransactionItem[];
}

const { transaction } = defineProps<{
    transaction: Transaction;
}>();

const formatDate = (date: string) => dayjs(date).format("DD MMMM YYYY, HH:mm");
</script>

<template>
    <Head :title="`Detail Transaksi ${transaction.transaction_code}`" />

    <AuthenticatedLayout :categories="[]">
        <div class="max-w-5xl py-10 mx-auto space-y-8">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-semibold text-gray-800">
                    Detail Transaksi
                </h2>
                <Link
                    :href="route('transactions.history')"
                    class="text-sm text-blue-600 hover:underline"
                >
                    ← Kembali ke Riwayat
                </Link>
            </div>

            <!-- Ringkasan Transaksi -->
            <div class="p-6 bg-white border border-gray-100 rounded-lg">
                <h3 class="mb-4 text-lg font-semibold text-gray-800">
                    Ringkasan Transaksi
                </h3>

                <div class="grid gap-4 text-gray-700 sm:grid-cols-2">
                    <div>
                        <p class="font-medium text-gray-600">Kode Transaksi</p>
                        <p>{{ transaction.transaction_code }}</p>
                    </div>

                    <div>
                        <p class="font-medium text-gray-600">
                            Metode Pembayaran
                        </p>
                        <p>{{ transaction.payment_method }}</p>
                    </div>

                    <div>
                        <p class="font-medium text-gray-600">Status</p>
                        <span
                            class="inline-block px-3 py-1 mt-1 text-xs font-semibold rounded-full"
                            :class="{
                                'bg-green-100 text-green-700':
                                    transaction.status === 'completed',
                                'bg-yellow-100 text-yellow-700':
                                    transaction.status === 'pending',
                                'bg-red-100 text-red-700':
                                    transaction.status === 'failed',
                            }"
                        >
                            {{ transaction.status }}
                        </span>
                    </div>

                    <div>
                        <p class="font-medium text-gray-600">Tanggal</p>
                        <p>{{ formatDate(transaction.created_at) }}</p>
                    </div>

                    <div class="sm:col-span-2">
                        <p class="font-medium text-gray-600">
                            Total Pembayaran
                        </p>
                        <p class="text-xl font-semibold text-gray-900">
                            {{ formatRupiah(transaction.total_amount) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Daftar Item -->
            <div class="p-6 bg-white border border-gray-100 rounded-lg">
                <h3 class="mb-4 text-lg font-semibold text-gray-800">
                    Daftar Produk
                </h3>

                <!-- <div
                    v-if="!transaction.transaction_items.length "
                    class="py-6 text-center text-gray-500"
                >
                    Tidak ada item dalam transaksi ini.
                </div> -->

                <div class="overflow-x-auto">
                    <table class="min-w-full border-collapse">
                        <thead class="bg-gray-100 border-b">
                            <tr>
                                <th
                                    class="px-6 py-3 text-xs font-medium text-left text-gray-600 uppercase"
                                >
                                    Produk
                                </th>
                                <th
                                    class="px-6 py-3 text-xs font-medium text-center text-gray-600 uppercase"
                                >
                                    Jumlah
                                </th>
                                <th
                                    class="px-6 py-3 text-xs font-medium text-right text-gray-600 uppercase"
                                >
                                    Harga Satuan
                                </th>
                                <th
                                    class="px-6 py-3 text-xs font-medium text-right text-gray-600 uppercase"
                                >
                                    Subtotal
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="item in transaction.transaction_items"
                                :key="item.id"
                                class="transition border-b hover:bg-gray-50"
                            >
                                <td class="px-6 py-4 text-gray-800">
                                    {{ item.product.name }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    {{ item.quantity }}
                                </td>
                                <td class="px-6 py-4 text-right text-gray-700">
                                    {{ formatRupiah(item.price) }}
                                </td>
                                <td class="px-6 py-4 font-medium text-right">
                                    {{
                                        formatRupiah(item.price * item.quantity)
                                    }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
