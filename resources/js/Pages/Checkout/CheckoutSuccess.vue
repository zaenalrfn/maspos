// resources/js/Pages/Dashboard.vue

<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { formatRupiah } from "@/utils/formatRupiah";

const props = defineProps({
    transaction_createdAt: Object,
    total_amount: String,
});
</script>

<template>
    <Head title="Pembayaran Berhasil" />

    <AuthenticatedLayout :categories="[]">
        <div class="py-12">
            <div class="max-w-md p-4 mx-auto sm:px-6 lg:px-8">
                <div
                    class="flex flex-col items-center p-6 text-center bg-white rounded-lg md:p-8"
                >
                    <div class="w-48 h-48 mb-4 md:w-56 md:h-56">
                        <img
                            src="/assets/images/checkout_success.png"
                            alt="Pembayaran Sukses"
                            class="object-contain w-full h-full"
                        />
                    </div>

                    <h1 class="text-2xl font-bold text-[#23A948] md:text-3xl">
                        Pembayaran Sukses
                    </h1>

                    <div class="w-full py-2 my-2">
                        <p class="text-sm text-gray-500">Total</p>
                        <h3 class="my-3 text-2xl font-bold text-gray-800">
                            {{ formatRupiah(Number(total_amount) || 0) }}
                        </h3>
                        <p class="text-sm text-gray-500">
                            {{ transaction_createdAt }}
                        </p>
                    </div>

                    <Link
                        :href="route('dashboard')"
                        class="w-full px-4 py-3 font-semibold text-white transition duration-300 bg-blue-600 rounded-lg hover:bg-blue-700"
                    >
                        Kembali
                    </Link>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
