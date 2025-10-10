<script setup lang="ts">
import { ref } from "vue";
import { type Product } from "@/types/maspos";
import { Plus } from "lucide-vue-next";

const products = ref<Product[]>([
    {
        id: 1,
        name: "Pepperoni Cheese",
        price: 45000,
        category: "Pizza",
        image: "",
    },
    {
        id: 2,
        name: "Pepperoni Fiesta",
        price: 55000,
        category: "Pizza",
        image: "",
    },
    {
        id: 3,
        name: "Smoky Bacon Ranch",
        price: 47500,
        category: "Pizza",
        image: "",
    },
    {
        id: 4,
        name: "Mediterranean Feast",
        price: 57000,
        category: "Pizza",
        image: "",
    },
    {
        id: 5,
        name: "Mushroom & Truffle",
        price: 38000,
        category: "Pizza",
        image: "",
    },
    {
        id: 6,
        name: "French Fries",
        price: 18000,
        category: "Snack",
        image: "",
    },
    {
        id: 7,
        name: "Burger Gilz",
        price: 29000,
        category: "Burger",
        image: "",
    },
    {
        id: 8,
        name: "Big Mac Cheese",
        price: 31000,
        category: "Burger",
        image: "",
    },
    {
        id: 9,
        name: "Lechy Tea",
        price: 12000,
        category: "Drink",
        image: "",
    },
    {
        id: 10,
        name: "Coca Cola",
        price: 10000,
        category: "Drink",
        image: "",
    },
]);
</script>

<template>
    <div class="grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-6">
        <div
            v-for="product in products"
            :key="product.id"
            class="p-4 text-left bg-white border rounded-lg"
        >
            <img
                :src="product.image || '/assets/images/no_image.jpg'"
                :alt="product.name"
                class="object-contain w-full h-[177px] mb-2 rounded-lg"
            />
            <h3 class="text-base text-[#0F0F0F] font-medium">
                {{ product.name }}
            </h3>
            <p class="font-bold text-base text-[#23A948]">
                Rp {{ product.price.toLocaleString("id-ID") }}
            </p>
            <button
                class="w-full px-3 flex items-center justify-center font-medium py-2 mt-2 text-base text-[#F5F5F5] transition bg-[#2C59E5] rounded-lg hover:bg-blue-700"
            >
                <Plus color="white" />
                <span>Keranjang</span>
            </button>
        </div>
    </div>
</template>
