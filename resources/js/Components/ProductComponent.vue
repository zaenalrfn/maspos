<script setup lang="ts">
import { Product } from "@/types/maspos";
import { Plus } from "lucide-vue-next";

const props = defineProps<{
    products: Product[];
}>();
</script>

<template>
    <div class="grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-6">
        <div
            v-for="product in products"
            :key="product.id"
            class="p-4 text-left bg-white border rounded-lg"
        >
            <img
                :src="
                    product.image
                        ? `/storage/${product.image}`
                        : '/assets/images/no_image.jpg'
                "
                :alt="product.name"
                class="w-full h-[177px] mb-4 rounded-lg object-fill"
            />
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
</template>
