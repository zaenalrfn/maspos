<script setup lang="ts">
import { ref } from "vue";
import Navbar from "@/Components/NavBar.vue";
interface Category {
    id: number;
    name: string;
}
const props = defineProps<{
    categories: Category[];
}>();
const emit = defineEmits(["filter", "search"]);
</script>

<template>
    <div>
        <div class="min-h-screen p-4 bg-[#EDF0F2] md:p-8">
            <Notifications />
            <!-- Navigation -->
            <Navbar
                :categories="categories"
                @filter="(id) => emit('filter', id)"
                @search="(val) => emit('search', val)"
            />

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
