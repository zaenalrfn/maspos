<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import { Plus, ShoppingCart, Menu, X } from "lucide-vue-next";
import SearchProdukKategori from "./SearchProdukKategori.vue";
import { computed, ref } from "vue";
import { useCartStore } from "@/stores/cart";
interface Category {
    id: number;
    name: string;
}
const props = defineProps<{
    categories: Category[];
}>();
const emit = defineEmits(["filter", "search"]);

// state management
const cart = useCartStore();

const totalItems = computed(() =>
    cart.items.reduce((acc, item) => acc + item.quantity, 0)
);

const totalAmount = computed(() =>
    cart.items.reduce((acc, item) => acc + item.price * item.quantity, 0)
);

const menuOpen = ref(false);
</script>

<template>
    <nav class="bg-white border-b border-gray-100 rounded-lg">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div
                class="flex flex-wrap items-center justify-between gap-3 py-3 md:py-4"
            >
                <!-- Logo -->
                <Link :href="route('dashboard')">
                    <h1 class="text-[#1843C3] font-semibold text-lg">MASPOS</h1>
                </Link>

                <!-- Tombol menu mobile -->
                <button
                    class="text-gray-600 md:hidden focus:outline-none"
                    @click="menuOpen = !menuOpen"
                >
                    <Menu class="text-[#2C59E5]" v-if="!menuOpen" />
                    <X class="text-[#2C59E5]" v-else />
                </button>

                <!-- Menu desktop -->
                <div class="items-center hidden gap-4 md:flex">
                    <!-- Tambah Kategori -->
                    <Link :href="route('categories.index')">
                        <div
                            class="bg-[#2C59E5] text-center rounded-lg px-2 lg:px-4 py-2 text-white flex items-center hover:bg-blue-700"
                        >
                            <span>Tambah Kategori</span>
                        </div>
                    </Link>

                    <!-- Tambah Produk -->
                    <Link :href="route('products.create')">
                        <div
                            class="bg-[#2C59E5] rounded-lg px-4 py-2 text-white flex items-center hover:bg-blue-700"
                        >
                            <Plus color="white" />
                            <span class="ml-2">Tambah Produk</span>
                        </div>
                    </Link>

                    <!-- Cart -->
                    <Link :href="route('cart.index')">
                        <div
                            class="flex items-center gap-3 bg-[#E8EEFF] rounded-lg"
                        >
                            <div class="relative bg-[#2C59E5] p-2 rounded-lg">
                                <ShoppingCart color="white" />
                                <span
                                    v-if="totalItems > 0"
                                    class="absolute -top-1 -right-1 flex items-center justify-center w-5 h-5 text-[10px] font-bold text-white bg-green-500 border-2 border-white rounded-full"
                                >
                                    {{ totalItems }}
                                </span>
                            </div>

                            <div
                                class="text-[#2C59E5] font-medium mr-2"
                                v-if="cart.items?.length > 0"
                            >
                                <span>Total Tagihan </span>
                                <span class="font-bold text-[#2C59E5]">
                                    Rp
                                    {{ totalAmount.toLocaleString("id-ID") }}
                                </span>
                            </div>
                        </div>
                    </Link>

                    <!-- Profil -->
                    <Link href="/profile">
                        <div
                            class="rounded-lg px-4 py-2 gap-2 text-[#0F0F0F] flex items-center"
                        >
                            <img
                                src="/assets/images/profil.png"
                                alt="profil"
                                class="w-[48px] h-[48px] rounded-full"
                            />
                            <span>Ahmad</span>
                        </div>
                    </Link>
                </div>
            </div>

            <!-- Dropdown mobile -->
            <transition
                enter-active-class="transition duration-200 ease-out"
                enter-from-class="-translate-y-2 opacity-0"
                enter-to-class="translate-y-0 opacity-100"
                leave-active-class="transition duration-150 ease-in"
                leave-from-class="translate-y-0 opacity-100"
                leave-to-class="-translate-y-2 opacity-0"
            >
                <div
                    v-if="menuOpen"
                    class="flex flex-col gap-3 p-4 mt-4 rounded-lg md:hidden bg-gray-50"
                >
                    <Link :href="route('categories.index')">
                        <div
                            class="bg-[#2C59E5] rounded-lg px-4 py-2 text-white text-center hover:bg-blue-700"
                        >
                            Tambah Kategori
                        </div>
                    </Link>

                    <Link :href="route('products.create')">
                        <div
                            class="bg-[#2C59E5] rounded-lg px-4 py-2 text-white text-center hover:bg-blue-700"
                        >
                            Tambah Produk
                        </div>
                    </Link>

                    <Link :href="route('cart.index')">
                        <div
                            class="flex items-center justify-between bg-[#E8EEFF] rounded-lg px-4 py-2"
                        >
                            <div class="flex items-center gap-2">
                                <div
                                    class="relative bg-[#2C59E5] p-2 rounded-lg"
                                >
                                    <ShoppingCart color="white" />
                                    <span
                                        v-if="totalItems > 0"
                                        class="absolute -top-1 -right-1 flex items-center justify-center w-5 h-5 text-[10px] font-bold text-white bg-green-500 border-2 border-white rounded-full"
                                    >
                                        {{ totalItems }}
                                    </span>
                                </div>
                                <span class="text-[#2C59E5] font-medium">
                                    Total Tagihan:
                                </span>
                            </div>
                            <span
                                class="font-bold text-[#2C59E5]"
                                v-if="cart.items?.length > 0"
                            >
                                Rp
                                {{ totalAmount.toLocaleString("id-ID") }}
                            </span>
                        </div>
                    </Link>

                    <Link href="/profile">
                        <div
                            class="flex items-center justify-center gap-3 py-2 text-[#0F0F0F]"
                        >
                            <img
                                src="/assets/images/profil.png"
                                alt="profil"
                                class="w-[40px] h-[40px] rounded-full"
                            />
                            <span>Ahmad</span>
                        </div>
                    </Link>
                </div>
            </transition>

            <!-- Komponen Search -->
            <SearchProdukKategori
                :categories="categories"
                @filter="(id) => emit('filter', id)"
                @search="(val) => emit('search', val)"
                class="mt-3"
            />
        </div>
    </nav>
</template>
