<script setup lang="ts">
import { ref, watch, computed, onMounted } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import axios from "axios";
import { notify } from "@kyvg/vue3-notification";

interface Props {
    userId: number;
}

const props = defineProps<Props>();

const selectedFile = ref<File | null>(null);
const previewUrl = ref<string | null>(null);
const isLoading = ref(false);
const isFetching = ref(true);
const currentImage = ref<string | null>(null);

// handle notification
const flash = computed(() => usePage().props.flash);
watch(flash, (value: any) => {
    if (value?.success) {
        notify({
            title: "Sukses!",
            text: value.success,
            type: "success",
            duration: 3000,
        });
    }
});

// Fetch user image
const fetchUserImage = async () => {
    try {
        isFetching.value = true;
        const response = await axios.get(`/users/${props.userId}/image`, {
            headers: {
                "X-Requested-With": "XMLHttpRequest",
            },
        });
        currentImage.value = response.data.image;
    } catch (error) {
        console.error("Error fetching user image:", error);
    } finally {
        isFetching.value = false;
    }
};

// Compute full image URL
const currentImageUrl = computed(() => {
    if (!currentImage.value) return null;
    return `/storage/${currentImage.value}`;
});

// Handle file selection
const handleFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    const file = target.files?.[0];

    if (file) {
        // Validate file type
        if (!file.type.startsWith("image/")) {
            alert("File harus berupa gambar");
            return;
        }

        // Validate file size (2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert("Ukuran file maksimal 2MB");
            return;
        }

        selectedFile.value = file;
        previewUrl.value = URL.createObjectURL(file);
    }
};

// Cancel selection
const cancelSelection = () => {
    selectedFile.value = null;
    if (previewUrl.value) {
        URL.revokeObjectURL(previewUrl.value);
    }
    previewUrl.value = null;
    const fileInput = document.getElementById("imageInput") as HTMLInputElement;
    if (fileInput) fileInput.value = "";
};

// Submit form
const handleSubmit = () => {
    if (!selectedFile.value) {
        alert("Silakan pilih gambar terlebih dahulu");
        return;
    }

    isLoading.value = true;

    const formData = new FormData();
    formData.append("image", selectedFile.value);
    formData.append("_method", "PUT");

    router.post(`/users/${props.userId}/update-image`, formData, {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            cancelSelection();
            fetchUserImage(); // Refresh image
            isLoading.value = false;
        },
        onError: (errors) => {
            isLoading.value = false;
            console.error("Upload error:", errors);
        },
    });
};

// Remove existing image
const removeImage = () => {
    if (!confirm("Apakah Anda yakin ingin menghapus gambar?")) return;

    isLoading.value = true;

    router.delete(`/users/${props.userId}/remove-image`, {
        preserveScroll: true,
        onSuccess: () => {
            currentImage.value = null;
        },
        onFinish: () => {
            isLoading.value = false;
        },
    });
};

onMounted(() => {
    fetchUserImage();
});
</script>

<template>
    <div>
        <Notifications />
        <h2 class="text-lg font-medium text-gray-900 mb-2">
            Update Foto Profil
        </h2>

        <!-- Loading State -->
        <div v-if="isFetching" class="mb-6 flex items-center justify-center">
            <div
                class="w-32 h-32 bg-gray-100 rounded-lg flex items-center justify-center"
            >
                <svg
                    class="animate-spin h-8 w-8 text-[#2C59E5]"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                >
                    <circle
                        class="opacity-25"
                        cx="12"
                        cy="12"
                        r="10"
                        stroke="currentColor"
                        stroke-width="4"
                    ></circle>
                    <path
                        class="opacity-75"
                        fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                    ></path>
                </svg>
            </div>
        </div>

        <template v-else>
            <!-- Current Image -->
            <div v-if="currentImageUrl && !previewUrl" class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Gambar Saat Ini
                </label>
                <div class="relative inline-block">
                    <img
                        :src="currentImageUrl"
                        alt="Current profile"
                        class="w-32 h-32 object-cover rounded-lg border-2 border-gray-300"
                    />
                    <button
                        type="button"
                        @click="removeImage"
                        :disabled="isLoading"
                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1.5 hover:bg-red-600 disabled:opacity-50 shadow-lg transition-colors"
                        title="Hapus gambar"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- No Image -->
            <div v-else-if="!currentImageUrl && !previewUrl" class="mb-6">
                <div
                    class="w-32 h-32 bg-gray-100 rounded-lg flex items-center justify-center border-2 border-dashed border-gray-300"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-12 w-12 text-gray-400"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                        />
                    </svg>
                </div>
                <p class="text-sm text-gray-500 mt-2">Belum ada gambar</p>
            </div>

            <!-- Preview New Image -->
            <div v-if="previewUrl" class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Preview Gambar Baru
                </label>
                <div class="relative inline-block">
                    <img
                        :src="previewUrl"
                        alt="Preview"
                        class="w-32 h-32 object-cover rounded-lg border-2 border-[#2C59E5] shadow-md"
                    />
                    <button
                        type="button"
                        @click="cancelSelection"
                        class="absolute -top-2 -right-2 bg-gray-600 text-white rounded-full p-1.5 hover:bg-gray-700 shadow-lg transition-colors"
                        title="Batal pilih"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- File Input -->
            <div class="mb-6">
                <label
                    for="imageInput"
                    class="block text-sm font-medium text-gray-700 mb-2"
                >
                    {{ currentImageUrl ? "Pilih Gambar Baru" : "Pilih Gambar" }}
                </label>
                <input
                    id="imageInput"
                    type="file"
                    accept="image/*"
                    @change="handleFileChange"
                    :disabled="isLoading"
                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 disabled:opacity-50 cursor-pointer"
                />
                <p class="text-xs text-gray-500 mt-1">
                    Format: JPG, PNG (Max: 2MB)
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3">
                <button
                    type="button"
                    @click="handleSubmit"
                    :disabled="!selectedFile || isLoading"
                    class="bg-[#2C59E5] text-white py-2.5 px-4 rounded-lg font-medium hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors shadow-sm"
                >
                    <span
                        v-if="isLoading"
                        class="flex items-center justify-center gap-2"
                    >
                        <svg
                            class="animate-spin h-4 w-4"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <circle
                                class="opacity-25"
                                cx="12"
                                cy="12"
                                r="10"
                                stroke="currentColor"
                                stroke-width="4"
                            ></circle>
                            <path
                                class="opacity-75"
                                fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                            ></path>
                        </svg>
                        Menyimpan...
                    </span>
                    <span v-else>Update Gambar</span>
                </button>

                <button
                    type="button"
                    @click="cancelSelection"
                    v-if="selectedFile"
                    :disabled="isLoading"
                    class="px-4 py-2.5 border border-gray-300 rounded-lg font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50 transition-colors"
                >
                    Batal
                </button>
            </div>
        </template>
    </div>
</template>
