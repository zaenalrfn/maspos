<script setup lang="ts">
import { ref, watch } from "vue";
import { usePage } from "@inertiajs/vue3";
import { Search } from "lucide-vue-next";
interface Category {
    id: number;
    name: string;
}
const props = defineProps<{
    categories: Category[];
}>();

const emit = defineEmits(["filter", "search"]);
const page = usePage();
const isHome = page.url === "/dashboard";

const selectedCategoryId = ref<number | null>(null);
const searchQuery = ref("");

function selectCategory(categoryId: number | null) {
    selectedCategoryId.value = categoryId;
    emit("filter", categoryId);
}

let debounceTimeout: ReturnType<typeof setTimeout>;
watch(searchQuery, (val) => {
    clearTimeout(debounceTimeout);
    debounceTimeout = setTimeout(() => {
        emit("search", val);
    }, 300);
});
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
                    v-model="searchQuery"
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
                    class="px-4 py-1.5 rounded-lg text-sm font-medium whitespace-nowrap transition border border-[#2C59E5]"
                    :class="{
                        'bg-[#2C59E5] text-white': selectedCategoryId === null,
                        'bg-[#F6F8FB] text-[#2C59E5]':
                            selectedCategoryId !== null,
                    }"
                    @click="selectCategory(null)"
                >
                    Semua
                </button>

                <button
                    v-for="(category, i) in categories"
                    :key="category.id"
                    class="px-4 py-1.5 rounded-lg text-sm font-medium whitespace-nowrap transition border border-[#2C59E5]"
                    :class="{
                        'bg-[#2C59E5] text-white':
                            selectedCategoryId === category.id,
                        'bg-[#F6F8FB] text-[#2C59E5]':
                            selectedCategoryId !== category.id,
                    }"
                    @click="selectCategory(category.id)"
                >
                    {{ category.name }}
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
