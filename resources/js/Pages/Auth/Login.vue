<script setup lang="ts">
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { ref, onMounted, onBeforeUnmount } from "vue";

defineProps<{
    canResetPassword?: boolean;
    status?: string;
}>();

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const showPassword = ref(false);
const currentSlide = ref(0);
const slides = ref([
    {
        title: "Slide 1",
        image: "/assets/images/banner_login.png",
        text: "Desain yang user-friendly membuat navigasi cepat dan mudah, bahkan bagi pemula.",
    },
    {
        title: "Slide 2",
        image: "/assets/images/banner_login_2.png",
        text: "Kelola transaksi dan stok barang dengan mudah melalui sistem POS modern.",
    },
    {
        title: "Slide 3",
        image: "/assets/images/banner_login_3.png",
        text: "Nikmati tampilan intuitif dan fitur lengkap untuk kemudahan operasional.",
    },
]);

const submit = () => {
    form.post(route("login"), {
        onFinish: () => {
            form.reset("password");
        },
    });
};

let slideInterval: number | undefined;
onMounted(() => {
    slideInterval = setInterval(() => {
        currentSlide.value = (currentSlide.value + 1) % slides.value.length;
    }, 4000);
});

onBeforeUnmount(() => clearInterval(slideInterval));
</script>

<template>
    <Head title="Log in" />

    <div class="flex items-center justify-center min-h-screen bg-[#EDF0F2] p-8">
        <div class="flex w-full overflow-hidden">
            <div
                class="hidden lg:flex w-1/2 relative bg-[radial-gradient(circle_at_top_left,_#4288F2,_#113E82)] rounded-lg text-white flex-col justify-between overflow-hidden"
            >
                <transition name="fade" mode="out-in">
                    <img
                        :key="slides[currentSlide].image"
                        :src="slides[currentSlide].image"
                        :alt="slides[currentSlide].title"
                        class="absolute left-0 top-10 rounded-e-lg w-4/5 h-[370px] opacity-90"
                    />
                </transition>

                <div class="relative z-10 p-12 mt-auto">
                    <p class="text-lg leading-relaxed text-center">
                        {{ slides[currentSlide].text }}
                    </p>
                    <div class="flex justify-center mt-6 space-x-2">
                        <span
                            v-for="(s, i) in slides"
                            :key="i"
                            class="block transition-all duration-300 rounded-full"
                            :class="
                                i === currentSlide
                                    ? 'w-8 h-2 bg-white'
                                    : 'w-2 h-2 bg-white/50'
                            "
                        ></span>
                    </div>
                </div>
            </div>

            <div
                class="flex flex-col relative justify-center w-full p-8 lg:w-1/2 lg:p-16 bg-[#EDF0F2]"
            >
                <div class="absolute top-0 right-0 text-[40px] text-[#1C4F99]">
                    MASPOS
                </div>
                <div class="mt-8">
                    <h1 class="mb-2 text-[32px] font-medium text-[#0F0F0F]">
                        Selamat Datang di MASPOS
                    </h1>
                    <p class="text-[#0F0F0F] text-[20px]">
                        Masuk untuk mengelola bisnis Anda dengan mudah dan
                        efisien. MASPOS menghadirkan solusi point-of-sale
                        terbaik untuk kemudahan operasional sehari-hari.
                    </p>

                    <form @submit.prevent="submit" class="mt-10">
                        <div>
                            <InputLabel
                                for="email"
                                value="Email"
                                class="font-semibold text-gray-700"
                            />
                            <TextInput
                                id="email"
                                type="text"
                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                v-model="form.email"
                                required
                                autofocus
                                autocomplete="email"
                                placeholder="Email"
                            />
                            <InputError
                                class="mt-2"
                                :message="form.errors.email"
                            />
                        </div>

                        <div class="mt-6">
                            <InputLabel
                                for="password"
                                value="Password"
                                class="font-semibold text-gray-700"
                            />
                            <div class="relative">
                                <TextInput
                                    id="password"
                                    :type="showPassword ? 'text' : 'password'"
                                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    v-model="form.password"
                                    required
                                    autocomplete="current-password"
                                    placeholder="Password"
                                />
                                <button
                                    type="button"
                                    @click="showPassword = !showPassword"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor"
                                        class="w-5 h-5"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573
                                            3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49
                                            16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"
                                        />
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                        />
                                    </svg>
                                </button>
                            </div>
                            <InputError
                                class="mt-2"
                                :message="form.errors.password"
                            />
                        </div>

                        <div class="mt-8">
                            <button
                                class="w-full justify-center rounded-lg bg-[#2C59E5] py-3 text-sm font-semibold text-white hover:bg-blue-700"
                                :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing"
                            >
                                Masuk
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.8s;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
