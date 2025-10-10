<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import ProductComponent from "@/Components/ProductComponent.vue";
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="w-full">
                <div>
                    <ProductComponent />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
