<script setup lang="ts">
import { Product } from "@/types/maspos";
import { Plus, Trash2 } from "lucide-vue-next";
import { router } from "@inertiajs/vue3";
import ModalDelete from "@/Components/ModalDelete.vue";
import { ref } from "vue";

const props = defineProps<{
    products: Product[];
}>();

const showModalDelete = ref(false);
const selectedProduct = ref<Product | { id: null; name: null }>({
    id: null,
    name: null,
});
const openDeleteModal = (product: Product) => {
    showModalDelete.value = true;
    selectedProduct.value = { ...product };
};
</script>

<template>
    <div
        v-if="products.length === 0"
        class="flex items-center justify-center col"
    >
        <p class="text-gray-500">Produk tidak tersedia</p>
    </div>
    <div v-else class="grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-6">
        <div
            v-for="product in products"
            :key="product.id"
            class="p-2 text-left bg-white border rounded-lg"
        >
            <div class="relative">
                <img
                    :src="
                        product.image
                            ? `/storage/${product.image}`
                            : '/assets/images/no_image.jpg'
                    "
                    :alt="product.name"
                    class="w-full h-[177px] mb-4 rounded-lg object-cover"
                />
                <div
                    @click.prevent="openDeleteModal(product)"
                    class="absolute flex items-center justify-center p-1 bg-white border border-red-600 rounded-lg opacity-50 cursor-pointer bottom-2 right-2 backdrop-blur-md"
                >
                    <Trash2 class="w-6 h-6 text-red-600" />
                </div>
            </div>
            <h3 class="text-base text-[#0F0F0F] font-medium">
                {{ product.name }}
            </h3>
            <p class="font-bold text-base text-[#23A948]">
                Rp {{ product.price.toLocaleString("id-ID") }}
            </p>
            <button
                class="w-full px-3 flex items-center justify-center font-medium py-2 mt-2 text-base text-[#F5F5F5] transition bg-[#2C59E5] rounded-lg hover:bg-blue-700"
            >
                <Plus color="white" />
                <span>Keranjang</span>
            </button>
        </div>
    </div>

    <ModalDelete
        :show="showModalDelete"
        :data="selectedProduct"
        link="products.destroy"
        title="Hapus Produk"
        message="Apakah Anda yakin ingin menghapus produk ini?"
        @close="showModalDelete = false"
    />
</template>
