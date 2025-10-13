<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import ProductComponent from "@/Components/ProductComponent.vue";
import { Product } from "@/types/maspos";

const props = defineProps<{
    products: Product[];
}>();
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="w-full">
                <div>
                    <ProductComponent :products="products" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
