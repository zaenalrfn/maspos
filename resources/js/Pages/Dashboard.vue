<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import ProductComponent from "@/Components/ProductComponent.vue";
import { Product } from "@/types/maspos";

interface Category {
    id: number;
    name: string;
}
const props = defineProps<{
    products: Product[];
    categories: Category[];
}>();
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout :categories="categories">
        <div class="py-12">
            <div class="w-full">
                <div>
                    <ProductComponent :products="products" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
