<script setup lang="ts">
import { ref, watch, computed } from "vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { columns, type Category } from "@/Pages/CategoryPage/Columns";
import ModalCreateUpdateCategori from "@/Components/ModalCreateUpdateCategori.vue";
import DataTable from "@/Components/DataTable.vue";
import ModalDelete from "@/Components/ModalDelete.vue";
import { notify } from "@kyvg/vue3-notification";

interface Props {
    categories: Category[];
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

const props = defineProps<Props>();
const showModal = ref(false);
const showModalDelete = ref(false);
const selectedCategory = ref({ id: null, name: null });

// State
const globalFilter = ref("");
const searchQuery = ref("");
let debounceTimer: any = null;

// debounce search
watch(searchQuery, (val) => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        globalFilter.value = val;
    }, 300);
});

// Actions modal
const openCreateModal = () => {
    selectedCategory.value = { id: null, name: null };
    showModal.value = true;
};

const openEditModal = (category: any) => {
    selectedCategory.value = { ...category };
    showModal.value = true;
};

const openDeleteModal = (category: any) => {
    selectedCategory.value = { ...category };
    showModalDelete.value = true;
};
</script>

<template>
    <Head title="Daftar Kategori" />
    <AuthenticatedLayout :categories="categories">
        <div class="px-4 py-6 mx-auto sm:px-6 lg:px-8 max-w-7xl">
            <Notifications />
            <!-- Page Header -->
            <div
                class="flex flex-col gap-4 mb-6 sm:flex-row sm:items-center sm:justify-between"
            >
                <div>
                    <h1
                        class="text-2xl font-semibold text-gray-900 sm:text-3xl"
                    >
                        Daftar Kategori
                    </h1>
                </div>
                <div>
                    <button
                        @click.prevent="openCreateModal"
                        class="inline-flex items-center justify-center w-full gap-2 px-4 py-2 text-sm font-medium text-white transition-colors duration-200 bg-blue-600 rounded-lg hover:bg-blue-700 sm:w-auto"
                    >
                        <span class="text-lg">+</span>
                        <span>Tambah Kategori</span>
                    </button>
                </div>
            </div>

            <!-- Search Bar -->
            <div class="mb-5">
                <input
                    v-model="searchQuery"
                    type="text"
                    class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Cari kategori..."
                />
            </div>

            <!-- Data Table -->
            <DataTable
                :data="props.categories"
                :columns="columns"
                :globalFilter="globalFilter"
                @edit="openEditModal"
                @deleted="openDeleteModal"
            />

            <!-- Modal Create/Update Category -->
            <ModalCreateUpdateCategori
                :show="showModal"
                :data="selectedCategory"
                @close="showModal = false"
                @saved="router.reload({ only: ['categories'] })"
            />

            <!-- Modal Delete Category -->
            <ModalDelete
                :show="showModalDelete"
                :data="selectedCategory"
                link="categories.destroy"
                title="Hapus Kategori"
                message="Apakah Anda yakin ingin menghapus kategori ini?"
                @close="showModalDelete = false"
            />
        </div>
    </AuthenticatedLayout>
</template>
