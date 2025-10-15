import { defineStore } from "pinia";
import { useCartStore } from "./cart";
import { router } from "@inertiajs/vue3";

export const useCheckoutStore = defineStore("checkout", {
    state: () => ({
        transaction: null as any | null,
        payment_method: "cash",
        isProcessing: false,
        paymentOptions: [
            { value: "cash", label: "Bayar Tunai (Cash)" },
            { value: "qris", label: "QRIS" },
        ],
    }),

    actions: {
        async createTransaction() {
            const cart = useCartStore();

            if (cart.items.length === 0) {
                alert("Keranjang masih kosong!");
                return;
            }

            const payload = {
                payment_method: this.payment_method,
                total_amount: cart.totalAmount,
                items: cart.items.map((item) => ({
                    product_id: item.id,
                    quantity: item.quantity,
                    price: item.price,
                    subtotal: item.price * item.quantity,
                })),
            };

            this.isProcessing = true;

            router.post(route('transactions.store'),
                payload,
                {
                    onSuccess: () => {
                        cart.clearCart();
                        this.resetTransaction();
                    },
                    onError: () => {
                        alert("Gagal membuat transaksi!");
                    },
                    onFinish: () => {
                        this.isProcessing = false;
                    },
                }
            );
        },

        // Batalkan transaksi jika user ubah cart
        resetTransaction() {
            this.transaction = null;
        },
    },
});
