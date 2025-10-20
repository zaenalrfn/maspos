<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, usePage } from "@inertiajs/vue3";
import ProductComponent from "@/Components/ProductComponent.vue";
import { Product } from "@/types/maspos";
import { computed, ref, watch } from "vue";
import { notify } from "@kyvg/vue3-notification";

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

const searchQuery = ref("");
const activeCategory = ref<number | null>(null);
const filteredProducts = computed(() => {
    return props.products.filter((p) => {
        const matchSearch = p.name
            .toLowerCase()
            .includes(searchQuery.value.toLowerCase());

        const matchCategory =
            !activeCategory.value ||
            (p.category && (p.category as any)?.id === activeCategory.value);

        return matchSearch && matchCategory;
    });
});

function handleFilter(categoryId: number | null) {
    activeCategory.value = categoryId;
}

function handleSearch(value: string) {
    searchQuery.value = value;
}
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout
        :categories="categories"
        @filter="handleFilter"
        @search="handleSearch"
    >
        <div class="py-12">
            <Notifications />
            <div class="w-full">
                <div>
                    <ProductComponent :products="filteredProducts" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
