<script setup lang="ts">
import DangerButton from "@/Components/DangerButton.vue";
import { router } from "@inertiajs/vue3";

const confirmUserLogout = () => {
    router.post(route("logout"));
};
</script>
<template>
    <section class="space-y-6">
        <header>
            <h2 class="text-lg font-medium text-gray-900">Logout Account</h2>
        </header>

        <DangerButton @click="confirmUserLogout">Logout</DangerButton>
    </section>
</template>
