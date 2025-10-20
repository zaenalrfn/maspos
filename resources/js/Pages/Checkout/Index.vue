<script setup lang="ts">
import { computed, watch } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { useCartStore } from "@/stores/cart";
import { useCheckoutStore } from "@/stores/checkout";
import CustomPaymentDropdown from "@/Components/CustomPaymentDropdown.vue";

const cart = useCartStore();
const checkout = useCheckoutStore();

const totalAmount = computed(() => cart.totalAmount);

function handleCheckout() {
    checkout.createTransaction();
}
</script>

<template>
    <Head title="Checkout" />

    <AuthenticatedLayout :categories="[]">
        <div class="relative flex flex-col items-center py-10">
            <div class="w-full mb-5 text-left md:w-3/4 lg:w-1/2">
                <Link :href="route('cart.index')">
                    <button
                        class="px-5 py-2 text-[#2C59E5] border border-[#2C59E5] rounded-lg hover:bg-[#2C59E5] hover:text-white bg-transparent"
                    >
                        Kembali
                    </button>
                </Link>
            </div>
            <div
                class="w-full p-6 bg-white md:w-3/4 lg:w-1/2 rounded-xl md:p-8"
            >
                <h2
                    class="text-xl md:text-2xl font-semibold text-[#2C59E5] mb-6 text-center"
                >
                    Checkout Pembayaran
                </h2>

                <!-- List Keranjang -->
                <div v-if="cart.items.length" class="mb-6 space-y-3">
                    <div
                        v-for="item in cart.items"
                        :key="item.id"
                        class="flex items-center justify-between pb-2 border-b last:border-0"
                    >
                        <div class="flex items-center gap-3">
                            <img
                                :src="
                                    item.image
                                        ? '/storage/' + item.image
                                        : '/assets/images/no_image.jpg'
                                "
                                alt="product"
                                class="object-cover rounded-md w-14 h-14"
                            />
                            <div>
                                <p class="font-medium text-gray-800">
                                    {{ item.name }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ item.quantity }} × Rp
                                    {{ item.price.toLocaleString("id-ID") }}
                                </p>
                            </div>
                        </div>
                        <p
                            class="font-semibold text-[#2C59E5] text-sm md:text-base whitespace-nowrap"
                        >
                            Rp
                            {{
                                (item.price * item.quantity).toLocaleString(
                                    "id-ID"
                                )
                            }}
                        </p>
                    </div>
                </div>

                <div
                    v-else
                    class="py-6 text-center text-gray-500 border rounded-lg"
                >
                    Keranjang masih kosong.
                </div>

                <!-- Pilihan metode pembayaran -->
                <div class="mt-6 mb-6">
                    <label class="block mb-2 text-sm font-medium text-gray-700">
                        Pilih Metode Pembayaran
                    </label>
                    <CustomPaymentDropdown
                        v-model="checkout.payment_method"
                        :options="checkout.paymentOptions"
                    />
                </div>
            </div>

            <div
                class="fixed bottom-0 left-0 flex items-center justify-between w-full gap-6 p-5 bg-white border-t border-gray-200 shadow-md"
            >
                <!-- Bagian Total -->
                <div
                    class="flex flex-col flex-1 sm:flex-row sm:items-center sm:gap-3"
                >
                    <p class="text-sm text-gray-600 sm:text-base">
                        Total Tagihan:
                    </p>
                    <p class="text-lg sm:text-xl font-bold text-[#2C59E5]">
                        Rp {{ totalAmount.toLocaleString("id-ID") }}
                    </p>
                </div>

                <!-- Tombol Checkout -->
                <div class="flex-1 max-w-xs">
                    <button
                        @click="handleCheckout"
                        :disabled="checkout.isProcessing || !cart.items.length"
                        class="bg-[#2C59E5] hover:bg-blue-700 transition text-white w-full py-3 rounded-lg font-semibold disabled:opacity-50"
                    >
                        <span v-if="checkout.isProcessing">Memproses...</span>
                        <span v-else>Bayar Sekarang</span>
                    </button>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
