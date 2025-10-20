<script setup lang="ts">
import { router } from "@inertiajs/vue3";
import { Trash2 } from "lucide-vue-next";
interface DataDelete {
    id: number | null;
    name: string | null;
}

const props = defineProps<{
    show: boolean;
    data: DataDelete;
    title: string;
    message: string;
    link: string;
}>();

const emits = defineEmits(["close", "deleted"]);

const handleSubmit = () => {
    if (!props.data.id) {
        alert("ID data tidak valid!");
        return;
    }
    router.delete(route(props.link, props.data.id), {
        onSuccess: () => {
            emits("deleted");
            emits("close");
        },
        onError: (err) => {
            alert("Gagal menghapus data: " + err.message);
        },
    });
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
                        class="flex flex-col justify-start px-6 py-2 rounded-t-lg"
                    >
                        <div
                            class="my-3 bg-[#FFA8A8] w-[64px] h-[64px] flex items-center justify-center rounded-full"
                        >
                            <div
                                class="w-[48px] h-[48px] bg-[#FF2928] flex items-center justify-center rounded-full"
                            >
                                <Trash2 class="w-6 h-6 text-white" />
                            </div>
                        </div>
                        <h2 class="text-xl text-[#0F0F0F] font-semibold">
                            {{ title }}
                        </h2>
                    </div>

                    <form class="px-6 py-2" @submit.prevent="handleSubmit">
                        <p class="mb-6 text-[#5C5C5C]">
                            {{ message }}
                        </p>
                        <div class="flex items-center justify-between mb-3">
                            <button
                                type="button"
                                @click="emits('close')"
                                class="bg-transparent border border-[#FF2928] text-[#FF2928] hover:bg-[#FF2928] hover:text-white transition-all duration-100 ease-in px-8 py-2 rounded"
                            >
                                Batal
                            </button>
                            <button
                                type="submit"
                                class="bg-[#FF2928] text-white px-8 py-2 rounded hover:bg-red-600 transition-all duration-100 ease-in"
                            >
                                Hapus
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
