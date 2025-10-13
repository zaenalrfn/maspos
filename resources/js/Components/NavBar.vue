<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import { Plus, ShoppingCart } from "lucide-vue-next";
import SearchProdukKategori from "./SearchProdukKategori.vue";
</script>

<template>
    <nav class="bg-white border-b border-gray-100 rounded-lg">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <Link :href="route('dashboard')">
                    <h1 class="text-[#1843C3] font-semibold text-lg">MASPOS</h1>
                </Link>

                <div class="flex items-center gap-4">
                    <Link :href="route('categories.index')">
                        <div
                            class="bg-[#2C59E5] rounded-lg px-4 py-2 text-white flex items-center hover:bg-blue-700"
                        >
                            <span class="ml-2">Tambah Kategori</span>
                        </div>
                    </Link>

                    <Link :href="route('products.create')">
                        <div
                            class="bg-[#2C59E5] rounded-lg px-4 py-2 text-white flex items-center hover:bg-blue-700"
                        >
                            <Plus color="white" />
                            <span class="ml-2">Tambah Produk</span>
                        </div>
                    </Link>

                    <Link href="/cart">
                        <div
                            class="bg-[#2C59E5] rounded-lg px-4 py-2 text-white flex items-center hover:bg-blue-700"
                        >
                            <ShoppingCart color="white" />
                        </div>
                    </Link>

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
            <SearchProdukKategori />
        </div>
    </nav>
</template>
