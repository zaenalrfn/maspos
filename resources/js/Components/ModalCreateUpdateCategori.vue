<script lang="ts" setup>
import { watch } from "vue";
import { useForm } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";

interface Category {
    id: number | null;
    name: string | null;
}

const props = defineProps<{
    show: boolean;
    data: Category;
}>();

const emits = defineEmits(["close", "saved"]);

const form = useForm({
    id: props.data.id,
    name: props.data.name ?? "",
});

// update form setiap kali props.data berubah
watch(
    () => props.data,
    (newData) => {
        form.id = newData.id;
        form.name = newData.name ?? "";
        form.clearErrors();
    },
    { deep: true }
);

const handleSubmit = () => {
    if (!form.name.trim()) {
        form.setError("name", "Nama kategori tidak boleh kosong!");
        return;
    }

    if (form.id) {
        // Mode update
        form.put(route("categories.update", form.id), {
            onSuccess: () => {
                emits("saved");
                emits("close");
            },
        });
    } else {
        // Mode create
        form.post(route("categories.store"), {
            onSuccess: () => {
                emits("saved");
                emits("close");
            },
        });
    }
};
</script>

<template>
    <Transition name="fade">
        <div
            v-if="show"
            class="fixed top-0 left-0 z-30 flex items-center justify-center w-full h-screen bg-black/50"
        >
            <Transition name="scale">
                <div
                    class="bg-white rounded-lg w-[400px] shadow-lg"
                    v-if="show"
                >
                    <div
                        class="bg-[#F5F5F5] p-6 flex justify-start items-center rounded-t-lg"
                    >
                        <h2 class="text-xl font-semibold">
                            {{ form.id ? "Edit Kategori" : "Tambah Kategori" }}
                        </h2>
                    </div>

                    <form @submit.prevent="handleSubmit" class="p-6">
                        <div class="mb-4">
                            <label class="block mb-2 text-gray-700" for="name">
                                Nama Kategori
                            </label>
                            <input
                                v-model="form.name"
                                class="w-full px-3 py-2 border border-gray-300 rounded"
                                type="text"
                                id="name"
                                placeholder="Masukkan nama kategori"
                            />
                            <!-- Error message could go here if needed -->
                            <InputError
                                class="mt-2"
                                :message="
                                    Array.isArray(form.errors.name)
                                        ? form.errors.name[0]
                                        : form.errors.name
                                "
                            />
                        </div>

                        <div class="flex items-center justify-between">
                            <button
                                type="button"
                                @click="emits('close')"
                                class="bg-transparent border border-[#2C59E5] text-[#2C59E5] hover:bg-[#2C59E5] hover:text-white transition-all duration-100 ease-in px-8 py-2 rounded"
                            >
                                Batal
                            </button>
                            <button
                                type="submit"
                                class="bg-[#2C59E5] text-white px-8 py-2 rounded hover:bg-blue-600 transition-all duration-100 ease-in"
                            >
                                {{ form.id ? "Update" : "Simpan" }}
                            </button>
                        </div>
                    </form>
                </div>
            </Transition>
        </div>
    </Transition>
</template>

<style scoped>
/* Fade backdrop */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

/* Scale content */
.scale-enter-active,
.scale-leave-active {
    transition: all 0.25s ease;
}
.scale-enter-from {
    opacity: 0;
    transform: scale(0.9);
}
.scale-leave-to {
    opacity: 0;
    transform: scale(0.95);
}
</style>
