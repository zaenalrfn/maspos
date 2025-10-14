<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, usePage } from "@inertiajs/vue3";
import ProductComponent from "@/Components/ProductComponent.vue";
import { Product } from "@/types/maspos";
import { computed, watch } from "vue";
import { Notifications, notify } from "@kyvg/vue3-notification";

interface Category {
    id: number;
    name: string;
}

// handle notification
const flash = computed(() => usePage().props.flash);
watch(
    flash,
    (value: any) => {
        if (value?.success) {
            notify({
                title: "Sukses!",
                text: value.success,
                type: "success",
                duration: 3000,
            });
        } else if (value?.error) {
            notify({
                title: "Error!",
                text: value.error,
                type: "error",
                duration: 3000,
            });
        }
    },
    { immediate: true }
);

const props = defineProps<{
    products: Product[];
    categories: Category[];
}>();
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout :categories="categories">
        <Notifications />
        <div class="py-12">
            <div class="w-full">
                <div>
                    <ProductComponent :products="products" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
