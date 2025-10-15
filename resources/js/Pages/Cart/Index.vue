<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { useCartStore } from "@/stores/cart";
import { Plus, Minus } from "lucide-vue-next";
import { formatRupiah } from "@/utils/formatRupiah";

const cart = useCartStore();

function handleCheckout() {
    router.visit("/transactions");
}

function goBack() {
    router.visit("/dashboard");
}
</script>

<template>
    <Head title="Keranjang Belanja" />

    <AuthenticatedLayout :categories="[]">
        <div class="w-full py-10 mx-auto">
            <div class="relative bg-white rounded-lg shadow-sm">
                <!-- Tabel untuk layar besar -->

                <div
                    class="hidden rounded-lg overflow-x-auto max-h-[65vh] md:block hide-scrollbar"
                >
                    <table class="w-full">
                        <thead class="bg-gray-100 border-b">
                            <tr>
                                <th class="px-4 py-3 text-left">Produk</th>
                                <th class="px-4 py-3 text-left">Harga</th>
                                <th class="w-32 px-4 py-3 text-left">Jumlah</th>
                                <th class="px-4 py-3 text-left">Sub Total</th>
                                <th class="px-4 py-3 text-left"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="item in cart.items"
                                :key="item.id"
                                class="transition border-b hover:bg-gray-50"
                            >
                                <!-- Produk -->
                                <td class="flex items-center px-4 py-4 gap-x-6">
                                    <img
                                        :src="
                                            item.image
                                                ? '/storage/' + item.image
                                                : '/assets/images/no_image.jpg'
                                        "
                                        alt="product"
                                        class="object-cover w-[158px] h-[120px] rounded-lg"
                                    />
                                    <span class="font-medium text-gray-800">{{
                                        item.name
                                    }}</span>
                                </td>

                                <!-- Harga -->
                                <td class="px-4 py-4">
                                    {{ formatRupiah(item.price) }}
                                </td>

                                <!-- Jumlah -->
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-2">
                                        <button
                                            @click="
                                                cart.updateQuantity(
                                                    item.id,
                                                    item.quantity - 1
                                                )
                                            "
                                            class="px-2 py-1 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50"
                                            :disabled="item.quantity <= 1"
                                        >
                                            <Minus class="w-5 h-5" />
                                        </button>
                                        <span class="w-6 text-center">{{
                                            item.quantity
                                        }}</span>
                                        <button
                                            @click="
                                                cart.updateQuantity(
                                                    item.id,
                                                    item.quantity + 1
                                                )
                                            "
                                            class="px-2 py-1 bg-gray-200 rounded hover:bg-gray-300"
                                        >
                                            <Plus class="w-5 h-5" />
                                        </button>
                                    </div>
                                </td>

                                <!-- Subtotal -->
                                <td
                                    class="px-4 py-4 font-semibold text-gray-700"
                                >
                                    {{
                                        formatRupiah(item.price * item.quantity)
                                    }}
                                </td>

                                <!-- Hapus -->
                                <td class="px-4 py-4 text-white cursor-pointer">
                                    <button
                                        class="px-4 py-2 rounded-lg bg-[#FF2928] hover:bg-red-700"
                                        @click="cart.removeFromCart(item.id)"
                                    >
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Tampilan mobile -->
                <div class="p-4 space-y-4 md:hidden">
                    <div
                        v-for="item in cart.items"
                        :key="item.id"
                        class="flex flex-col gap-3 p-4 border rounded-lg"
                    >
                        <!-- Gambar + nama -->
                        <div class="flex gap-3">
                            <img
                                :src="
                                    item.image
                                        ? '/storage/' + item.image
                                        : '/assets/images/no_image.jpg'
                                "
                                alt="product"
                                class="object-cover w-24 h-20 rounded-md"
                            />
                            <div class="flex-1">
                                <h4 class="text-sm font-semibold text-gray-800">
                                    {{ item.name }}
                                </h4>
                                <p class="text-sm text-gray-500">
                                    Rp
                                    {{
                                        Number(item.price).toLocaleString(
                                            "id-ID"
                                        )
                                    }}
                                </p>
                            </div>
                        </div>

                        <!-- Jumlah + Subtotal -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <button
                                    @click="
                                        cart.updateQuantity(
                                            item.id,
                                            item.quantity - 1
                                        )
                                    "
                                    class="px-2 py-1 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50"
                                    :disabled="item.quantity <= 1"
                                >
                                    <Minus class="w-5 h-5" />
                                </button>
                                <span class="w-6 text-center">{{
                                    item.quantity
                                }}</span>
                                <button
                                    @click="
                                        cart.updateQuantity(
                                            item.id,
                                            item.quantity + 1
                                        )
                                    "
                                    class="px-2 py-1 bg-gray-200 rounded hover:bg-gray-300"
                                >
                                    <Plus class="w-5 h-5" />
                                </button>
                            </div>
                            <div
                                class="text-sm font-semibold text-right text-gray-700"
                            >
                                Rp
                                {{
                                    (item.price * item.quantity).toLocaleString(
                                        "id-ID"
                                    )
                                }}
                            </div>
                        </div>

                        <!-- Tombol hapus -->
                        <button
                            @click="cart.removeFromCart(item.id)"
                            class="self-end text-sm font-medium text-red-500 hover:underline"
                        >
                            Hapus
                        </button>
                    </div>
                </div>

                <!-- Jika kosong -->
                <div
                    v-if="cart.items.length === 0"
                    class="py-10 text-lg text-center text-gray-500"
                >
                    Keranjang masih kosong.
                </div>
                <div
                    class="sticky bottom-0 flex justify-end px-6 py-3 bg-white border-t rounded-b-lg gap-x-4"
                >
                    <button
                        @click="goBack"
                        class="px-5 py-2 text-[#2C59E5] border border-[#2C59E5] rounded-lg hover:bg-[#2C59E5] hover:text-white bg-transparent"
                    >
                        Kembali
                    </button>
                    <button
                        @click="handleCheckout"
                        class="px-5 py-2 text-white bg-[#2C59E5] rounded-lg hover:bg-blue-700"
                        :disabled="cart.items.length === 0"
                    >
                        Checkout
                    </button>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
