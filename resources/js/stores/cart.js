// resources/js/stores/cart.js

import { defineStore } from 'pinia';
import axios from 'axios';

export const useCartStore = defineStore('cart', {
    // ESTADO (STATE)
    state: () => ({
        items: [],          // Array de CartItem objects
        subtotal: 0,        // Subtotal calculado por Laravel
        isLoading: false,   // Para control de UI
        isInitialized: false, // Para saber si ya se cargó el carrito inicial
    }),

    // GETTERS (propiedades computadas)
    getters: {
        // Devuelve el número total de artículos (no la suma de cantidades)
        itemCount: (state) => state.items.length, 
        
        // Devuelve la suma total de las cantidades de productos
        totalQuantity: (state) => state.items.reduce((total, item) => total + item.quantity, 0),
    },

    // ACCIONES (ACTIONS) - Lógica de negocio y llamadas a la API
    actions: {
        /**
         * Obtiene el carrito del backend (API /cart)
         */
        async fetchCart() {
            if (this.isLoading) return;

            this.isLoading = true;
            try {
                const response = await axios.get('/api/cart');
                const cart = response.data;

                this.items = cart.items;
                // El subtotal es un accessor del modelo Cart de Laravel
                this.subtotal = cart.subtotal || 0; 
                this.isInitialized = true;
            } catch (error) {
                console.error('Error al cargar el carrito:', error);
                // Opcional: Mostrar un mensaje de error al usuario
            } finally {
                this.isLoading = false;
            }
        },

        /**
         * Añade un producto al carrito (o incrementa la cantidad).
         * @param {number} productId 
         * @param {number} quantity 
         */
        async addProductToCart(productId, quantity = 1) {
            this.isLoading = true;
            try {
                const response = await axios.post('/api/cart', {
                    product_id: productId,
                    quantity: quantity,
                });

                // Reemplazar o añadir el artículo en el store
                const newItem = response.data.cart_item;
                const index = this.items.findIndex(item => item.product_id === newItem.product_id);

                if (index !== -1) {
                    this.items[index].quantity = newItem.quantity; // Actualizar cantidad
                } else {
                    this.items.push(newItem); // Añadir nuevo artículo
                }

                this.subtotal = response.data.cart_subtotal;
                // Opcional: Mostrar notificación de éxito
                
            } catch (error) {
                console.error('Error al añadir producto:', error);
            } finally {
                this.isLoading = false;
            }
        },

        /**
         * Actualiza la cantidad de un artículo existente.
         * @param {object} item - El objeto CartItem a modificar.
         * @param {number} newQuantity - La nueva cantidad.
         */
        async updateCartItemQuantity(item, newQuantity) {
            this.isLoading = true;
            try {
                const response = await axios.patch(`/api/cart/${item.id}`, { 
                    quantity: newQuantity 
                });

                if (response.data.action === 'deleted') {
                    // Si se eliminó (cantidad = 0)
                    this.items = this.items.filter(i => i.id !== item.id);
                } else {
                    // Si se actualizó
                    const index = this.items.findIndex(i => i.id === item.id);
                    if (index !== -1) {
                        this.items[index].quantity = newQuantity;
                    }
                }
                
                this.subtotal = response.data.cart_subtotal;
            } catch (error) {
                console.error('Error al actualizar cantidad:', error);
            } finally {
                this.isLoading = false;
            }
        },

        /**
         * Elimina completamente un artículo del carrito.
         * @param {object} item - El objeto CartItem a eliminar.
         */
        async removeCartItem(item) {
            this.isLoading = true;
            try {
                await axios.delete(`/api/cart/${item.id}`);

                // Filtrar el array de artículos para eliminar el item
                this.items = this.items.filter(i => i.id !== item.id);

                // Recalcular subtotal (llama al fetchCart completo o calcula localmente si es necesario)
                // Por simplicidad, volvamos a calcular el subtotal haciendo un fetch si es necesario, 
                // o lo podemos calcular localmente si el backend no lo devuelve de inmediato.
                // Usaremos un fetch simplificado para esto:
                await this.fetchCart(); 

            } catch (error) {
                console.error('Error al eliminar artículo:', error);
            } finally {
                this.isLoading = false;
            }
        }
    }
});