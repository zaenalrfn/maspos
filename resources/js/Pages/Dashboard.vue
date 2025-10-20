<script lang="ts" setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, usePage, router } from "@inertiajs/vue3";
import ProductComponent from "@/Components/ProductComponent.vue";
import { Product } from "@/types/maspos";
import { computed, ref, watch, onMounted, onUnmounted } from "vue";
import { notify } from "@kyvg/vue3-notification";
import { useCartStore } from "@/stores/cart";
import { ShoppingCart } from "lucide-vue-next";

interface Category {
    id: number;
    name: string;
}

// state management cart
const cart = useCartStore();

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
const showTotalCart = ref(false);

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

const handleCart = () => {
    router.visit("/cart");
};

onMounted(() => {
    const onScroll = () => {
        const product = document.getElementById("product-section");
        if (!product) return;

        const top = product.getBoundingClientRect().top;
        showTotalCart.value = top < -(-150);
    };

    window.addEventListener("scroll", onScroll);
    onUnmounted(() => window.removeEventListener("scroll", onScroll));
});
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
                <div id="product-section">
                    <ProductComponent :products="filteredProducts" />
                </div>
                <div class="fixed z-50 bottom-5 right-5">
                    <transition name="fade">
                        <button
                            v-if="showTotalCart"
                            @click="handleCart"
                            class="bg-[#2C59E5] text-white rounded-full p-4 shadow-lg hover:bg-blue-700 transition relative"
                        >
                            <ShoppingCart class="w-6 h-6" />
                            <span
                                v-if="cart.totalItems > 0"
                                class="absolute -top-1 -right-1 flex items-center justify-center w-5 h-5 text-[10px] font-bold text-white bg-green-500 border-2 border-white rounded-full"
                            >
                                {{ cart.totalItems }}
                            </span>
                        </button>
                    </transition>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
