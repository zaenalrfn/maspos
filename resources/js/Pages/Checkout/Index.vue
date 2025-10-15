<script setup lang="ts">
import { computed, watch } from "vue";
import { Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { useCartStore } from "@/stores/cart";
import { useCheckoutStore } from "@/stores/checkout";

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
        <div class="py-12">
            <div class="max-w-2xl px-4 mx-auto">
                <div class="p-6 bg-white rounded-xl md:p-8">
                    <h2
                        class="text-2xl font-semibold text-[#2C59E5] mb-6 text-center"
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
                                class="font-semibold text-[#2C59E5] text-sm md:text-base"
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
                        <label
                            class="block mb-2 text-sm font-medium text-gray-700"
                        >
                            Pilih Metode Pembayaran
                        </label>
                        <select
                            v-model="checkout.payment_method"
                            class="border rounded-lg w-full p-2 focus:outline-none focus:ring-2 focus:ring-[#2C59E5]"
                        >
                            <option
                                v-for="option in checkout.paymentOptions"
                                :key="option.value"
                                :value="option.value"
                            >
                                {{ option.label }}
                            </option>
                        </select>
                    </div>

                    <!-- Total Tagihan -->
                    <div
                        class="flex items-center justify-between py-4 mb-6 border-t border-b border-gray-100"
                    >
                        <p class="font-medium text-gray-700">Total Tagihan:</p>
                        <p class="text-xl font-bold text-[#2C59E5]">
                            Rp {{ totalAmount.toLocaleString("id-ID") }}
                        </p>
                    </div>

                    <!-- Tombol checkout -->
                    <button
                        @click="handleCheckout"
                        :disabled="checkout.isProcessing || !cart.items.length"
                        class="bg-[#2C59E5] hover:bg-blue-700 transition text-white w-full py-3 rounded-lg font-semibold disabled:opacity-50"
                    >
                        <span v-if="checkout.isProcessing">Memproses...</span>
                        <span v-else> Bayar Sekarang </span>
                    </button>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
