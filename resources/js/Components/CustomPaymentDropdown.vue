<script setup lang="ts">
import { ref, computed, onMounted, onBeforeUnmount } from "vue";

const props = defineProps<{
    modelValue: string;
    options: Array<{ value: string; label: string }>;
}>();

const emit = defineEmits<{
    "update:modelValue": [value: string];
}>();

const isOpen = ref(false);
const dropdownRef = ref<HTMLElement | null>(null);

const selectedOption = computed(() => {
    return props.options.find((opt) => opt.value === props.modelValue);
});

function selectOption(option: { value: string; label: string }) {
    emit("update:modelValue", option.value);
    isOpen.value = false;
}

function toggleDropdown() {
    isOpen.value = !isOpen.value;
}

// Close dropdown when clicking outside
function handleClickOutside(event: MouseEvent) {
    if (
        dropdownRef.value &&
        !dropdownRef.value.contains(event.target as Node)
    ) {
        isOpen.value = false;
    }
}

onMounted(() => {
    document.addEventListener("click", handleClickOutside);
});

onBeforeUnmount(() => {
    document.removeEventListener("click", handleClickOutside);
});
</script>

<template>
    <div ref="dropdownRef" class="relative w-full">
        <!-- Button trigger -->
        <button
            type="button"
            @click="toggleDropdown"
            class="w-full px-4 py-3 text-left bg-white border-2 border-gray-300 rounded-lg hover:border-[#2C59E5] focus:outline-none focus:ring-2 focus:ring-[#2C59E5] focus:border-[#2C59E5] transition-all duration-150 flex items-center justify-between"
            :class="{ 'border-[#2C59E5] ring-2 ring-[#2C59E5]': isOpen }"
        >
            <span
                class="font-medium"
                :class="selectedOption ? 'text-gray-800' : 'text-gray-400'"
            >
                {{ selectedOption?.label || "Pilih metode pembayaran" }}
            </span>

            <!-- Arrow icon -->
            <svg
                class="w-5 h-5 text-gray-400 transition-transform duration-200"
                :class="{ 'rotate-180': isOpen }"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 9l-7 7-7-7"
                />
            </svg>
        </button>

        <!-- Dropdown options -->
        <transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="scale-95 -translate-y-2 opacity-0"
            enter-to-class="scale-100 translate-y-0 opacity-100"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="scale-100 translate-y-0 opacity-100"
            leave-to-class="scale-95 -translate-y-2 opacity-0"
        >
            <div
                v-if="isOpen"
                class="absolute z-50 w-full mt-2 overflow-hidden bg-white border-2 border-gray-200 rounded-lg shadow-xl"
            >
                <div
                    v-for="option in options"
                    :key="option.value"
                    @click="selectOption(option)"
                    class="flex items-center justify-between px-4 py-3 transition-all duration-150 border-b border-gray-100 cursor-pointer last:border-0 group"
                    :class="{
                        'bg-[#f3f6ff] text-[#2C59E5]':
                            modelValue === option.value,
                        'hover:bg-[#f3f6ff] text-gray-700':
                            modelValue !== option.value,
                    }"
                >
                    <span class="font-medium">{{ option.label }}</span>

                    <!-- Checkmark for selected option -->
                    <svg
                        v-if="modelValue === option.value"
                        class="w-5 h-5 text-[#2C59E5]"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M5 13l4 4L19 7"
                        />
                    </svg>
                </div>
            </div>
        </transition>
    </div>
</template>
