<script setup lang="ts">
import { ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import { Search } from "lucide-vue-next";
const categories = ref([
    "Semua",
    "Burger",
    "Pizza",
    "Minuman",
    "Makanan Ringan",
    "Roti",
    "Kopi",
    "Beer",
    "Parfum",
    "Buah",
    "Kue",
    "Makanan Ringan",
    "Roti",
    "Kopi",
    "Beer",
    "Parfum",
    "Buah",
    "Kue",
]);

const page = usePage();
const isHome = page.url === "/dashboard";
</script>

<template>
    <div v-if="isHome" class="py-3 border-t border-gray-100">
        <div class="flex items-center gap-3 overflow-x-auto scrollbar-hide">
            <!-- Search -->
            <div
                class="flex items-center bg-[#F6F8FB] rounded-lg px-3 py-2 shadow-sm border border-gray-200 min-w-[200px] flex-shrink-0"
            >
                <Search class="text-[#2C59E5]" />
                <input
                    type="text"
                    placeholder="Cari nama produk..."
                    class="flex-1 ml-2 text-sm text-gray-700 bg-transparent border-none focus:outline-none"
                />
            </div>

            <!-- Kategori list scrollable -->
            <div
                class="flex items-center gap-2 pb-1 overflow-x-auto scrollbar-hide"
            >
                <button
                    v-for="(category, i) in categories"
                    :key="i"
                    class="px-4 py-1.5 rounded-lg text-sm font-medium whitespace-nowrap transition border border-[#2C59E5] hover:bg-[#2C59E5] hover:text-white"
                    :class="{
                        'bg-[#2C59E5] text-white': i === 0,
                        'bg-[#F6F8FB] text-[#2C59E5]': i !== 0,
                    }"
                >
                    {{ category }}
                </button>
            </div>
        </div>
    </div>
</template>

<style scoped>
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
