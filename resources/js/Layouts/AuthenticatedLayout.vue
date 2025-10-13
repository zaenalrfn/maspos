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
</script>

<template>
    <div>
        <div class="min-h-screen p-4 bg-[#EDF0F2] md:p-8">
            <Navbar :categories="categories" />

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
