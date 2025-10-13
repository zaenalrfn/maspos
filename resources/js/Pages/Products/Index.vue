<script setup lang="ts">
import { watch, computed } from "vue";
import { useForm, Head, usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputError from "@/Components/InputError.vue";
import { Notifications, notify } from "@kyvg/vue3-notification";

interface Category {
    id: number;
    name: string;
}

const props = defineProps<{
    categories: Category[];
}>();

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

const form = useForm({
    name: "",
    price: "",
    category_id: "",
    image: null as File | null,
});

// handle file upload
const handleFileChange = (e: Event) => {
    const target = e.target as HTMLInputElement;
    const file = target.files?.[0];
    if (file) {
        form.image = file;
    }
};

// submit data
const handleSubmit = () => {
    form.post(route("products.store"), {
        forceFormData: true,
        onSuccess: () => {
            alert("Produk berhasil ditambahkan!");
            form.reset();
        },
    });
};
</script>

<template>
    <Head title="Tambah Produk" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="w-full">
                <Notifications />
                <div class="flex items-center justify-center min-h-screen">
                    <div
                        class="w-full max-w-md p-6 bg-white shadow-md rounded-2xl sm:p-8"
                    >
                        <h2
                            class="mb-6 text-lg font-semibold text-gray-800 sm:text-xl"
                        >
                            Tambah Produk
                        </h2>

                        <!-- Upload Gambar -->
                        <div
                            class="p-6 mb-6 text-center transition border-2 border-gray-300 border-dashed cursor-pointer rounded-xl hover:border-blue-400"
                        >
                            <input
                                type="file"
                                id="image"
                                class="hidden"
                                @change="handleFileChange"
                                accept="image/*"
                            />
                            <label for="image" class="block">
                                <div
                                    class="flex flex-col items-center justify-center"
                                >
                                    <div
                                        class="flex items-center justify-center w-12 h-12 mb-2 bg-blue-100 rounded-full"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.5"
                                            stroke="currentColor"
                                            class="w-6 h-6 text-blue-500"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M12 4.5v15m7.5-7.5h-15"
                                            />
                                        </svg>
                                    </div>
                                    <p class="text-sm text-gray-600">
                                        Seret & jatuhkan file, atau klik untuk
                                        unggah
                                    </p>
                                    <p class="mt-1 text-xs text-gray-400">
                                        Format yang didukung: JPG, PNG
                                    </p>
                                </div>
                            </label>
                        </div>

                        <!-- Form -->
                        <form @submit.prevent="handleSubmit" class="space-y-4">
                            <div>
                                <label
                                    for="name"
                                    class="block mb-1 text-sm font-medium text-gray-700"
                                >
                                    Produk
                                </label>
                                <input
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    placeholder="Nama produk"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none"
                                />
                                <InputError :message="form.errors.name" />
                            </div>

                            <div>
                                <label
                                    for="price"
                                    class="block mb-1 text-sm font-medium text-gray-700"
                                >
                                    Harga
                                </label>
                                <input
                                    id="price"
                                    v-model="form.price"
                                    type="number"
                                    placeholder="Harga"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none"
                                />
                                <InputError :message="form.errors.price" />
                            </div>

                            <div>
                                <label
                                    for="category_id"
                                    class="block mb-1 text-sm font-medium text-gray-700"
                                >
                                    Pilih Kategori
                                </label>
                                <select
                                    id="category_id"
                                    v-model="form.category_id"
                                    class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none"
                                >
                                    <option value="">Pilih kategori</option>
                                    <option
                                        v-for="category in categories"
                                        :key="category.id"
                                        :value="category.id"
                                    >
                                        {{ category.name }}
                                    </option>
                                </select>
                                <InputError
                                    :message="form.errors.category_id"
                                />
                            </div>

                            <!-- Tombol -->
                            <div class="flex justify-between mt-6">
                                <button
                                    type="button"
                                    @click="form.reset()"
                                    class="px-6 py-2 text-blue-500 transition border border-blue-500 rounded-lg hover:bg-blue-500 hover:text-white"
                                >
                                    Batal
                                </button>

                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="px-6 py-2 text-white transition bg-blue-600 rounded-lg hover:bg-blue-700 disabled:opacity-60"
                                >
                                    Tambah
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
