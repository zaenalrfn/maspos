import { defineStore } from 'pinia'

export interface CartItem {
    id: number
    name: string
    price: number
    quantity: number
    image?: string
}

interface CartState {
    items: CartItem[]
}

export const useCartStore = defineStore('cart', {
    state: (): CartState => ({
        items: JSON.parse(localStorage.getItem('cart') || '[]'),
    }),

    getters: {
        totalAmount: (state) =>
            state.items.reduce((total, item) => total + item.price * item.quantity, 0),

        totalItems: (state) =>
            state.items.reduce((count, item) => count + item.quantity, 0),
    },

    actions: {
        saveToLocalStorage() {
            localStorage.setItem('cart', JSON.stringify(this.items))
        },

        addToCart(product: CartItem) {
            const existing = this.items.find((item) => item.id === product.id)
            if (existing) {
                existing.quantity += product.quantity || 1
            } else {
                this.items.push({ ...product, quantity: product.quantity || 1 })
            }
            this.saveToLocalStorage()
        },

        removeFromCart(productId: number) {
            this.items = this.items.filter((item) => item.id !== productId)
            this.saveToLocalStorage()
        },

        updateQuantity(productId: number, quantity: number) {
            const item = this.items.find((i) => i.id === productId)
            if (item && quantity > 0) {
                item.quantity = quantity
                this.saveToLocalStorage()
            }
        },

        clearCart() {
            this.items = []
            this.saveToLocalStorage()
        },
    },
})
