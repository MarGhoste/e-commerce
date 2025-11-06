<template>
  <div>
    <h2 class="text-2xl font-bold mb-4">Gestión de Órdenes 📦</h2>
    
    <p v-if="loading">Cargando órdenes...</p>
    
    <div v-else class="overflow-x-auto">
        <table class="min-w-full bg-white">
            <thead class="bg-gray-200">
                <tr>
                    <th class="py-2 px-4 border-b"># Orden</th>
                    <th class="py-2 px-4 border-b">Cliente</th>
                    <th class="py-2 px-4 border-b">Monto Total</th>
                    <th class="py-2 px-4 border-b">Estado</th>
                    <th class="py-2 px-4 border-b">Fecha</th>
                    <th class="py-2 px-4 border-b">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="order in orders" :key="order.id" class="hover:bg-gray-50">
                    <td class="py-2 px-4 border-b">{{ order.order_number }}</td>
                    <td class="py-2 px-4 border-b">{{ order.user ? order.user.name : 'Invitado' }}</td>
                    <td class="py-2 px-4 border-b text-right">${{ order.total_amount }}</td>
                    <td class="py-2 px-4 border-b">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold"
                              :class="statusClasses[order.status]">
                              {{ order.status.toUpperCase() }}
                        </span>
                    </td>
                    <td class="py-2 px-4 border-b">{{ formatDate(order.created_at) }}</td>
                    <td class="py-2 px-4 border-b">
                        <button @click="viewOrderDetails(order.id)" class="px-2 py-1 bg-blue-500 text-white rounded text-sm hover:bg-blue-600">Ver Detalle</button>
                    </td>
                </tr>
                <tr v-if="orders.length === 0 && !loading">
                    <td colspan="6" class="text-center py-4">No hay órdenes registradas.</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Order Detail Modal -->
    <div v-if="selectedOrder" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
        <div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-2xl max-h-full overflow-y-auto">
            <h3 class="text-2xl font-bold mb-4">Detalle de Orden #{{ selectedOrder.order_number }}</h3>
            
            <div class="space-y-2 mb-4">
                <p><strong>Cliente:</strong> {{ selectedOrder.user ? selectedOrder.user.name : 'Invitado' }}</p>
                <p><strong>Dirección:</strong> {{ selectedOrder.shipping_address_line_1 }}, {{ selectedOrder.shipping_city }}</p>
                <p><strong>Monto Total:</strong> <span class="font-bold text-lg">${{ selectedOrder.total_amount }}</span></p>
            </div>

            <h4 class="text-xl font-semibold mb-2">Productos</h4>
            <ul class="list-disc list-inside mb-4 space-y-1">
                <li v-for="item in selectedOrder.items" :key="item.id">
                    {{ item.product ? item.product.name : 'Producto Eliminado' }} (x{{ item.quantity }}) @ ${{ item.price }}
                </li>
            </ul>

            <div class="mt-6 pt-4 border-t">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Cambiar Estado:</label>
                <div class="flex items-center space-x-2">
                    <select id="status" v-model="newStatus" class="block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option v-for="s in allowedStatuses" :key="s" :value="s">{{ s.toUpperCase() }}</option>
                    </select>
                    <button @click="updateOrderStatus" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">Actualizar</button>
                </div>
            </div>

            <div class="mt-6 text-right">
                <button @click="selectedOrder = null" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">Cerrar</button>
            </div>
        </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            orders: [],
            pagination: {}, 
            loading: false,
            selectedOrder: null, 
            newStatus: '', 
            allowedStatuses: ['pending', 'paid', 'shipped', 'delivered', 'cancelled'],
            statusClasses: {
                pending: 'bg-yellow-200 text-yellow-800',
                paid: 'bg-blue-200 text-blue-800',
                shipped: 'bg-purple-200 text-purple-800',
                delivered: 'bg-green-200 text-green-800',
                cancelled: 'bg-red-200 text-red-800 line-through'
            }
        };
    },
    mounted() {
        this.fetchOrders();
    },
    methods: {
        formatDate(date) {
            return new Date(date).toLocaleDateString();
        },

        async fetchOrders(page = 1) {
            this.loading = true;
            try {
                const response = await axios.get(`/admin/orders?page=${page}`);
                this.orders = response.data.data;
                const { current_page, last_page, next_page_url, prev_page_url } = response.data;
                this.pagination = { current_page, last_page, next_page_url, prev_page_url };
            } catch (error) {
                console.error("Error al cargar órdenes:", error);
            } finally {
                this.loading = false;
            }
        },

        async viewOrderDetails(orderId) {
            try {
                const response = await axios.get(`/admin/orders/${orderId}`);
                this.selectedOrder = response.data;
                this.newStatus = response.data.status;
            } catch (error) {
                console.error("Error al cargar detalle de orden:", error);
                alert('No se pudo cargar el detalle de la orden.');
            }
        },

        async updateOrderStatus() {
            if (!this.selectedOrder || !this.newStatus) return;

            try {
                const response = await axios.put(`/admin/orders/${this.selectedOrder.id}`, {
                    status: this.newStatus
                });

                this.selectedOrder.status = response.data.status;
                
                const index = this.orders.findIndex(o => o.id === this.selectedOrder.id);
                if (index !== -1) {
                    this.orders[index].status = response.data.status;
                }
                
                alert(`Estado de la orden #${this.selectedOrder.order_number} actualizado a ${this.newStatus.toUpperCase()}.`);

            } catch (error) {
                console.error("Error al actualizar estado:", error.response || error);
                alert('Error al actualizar el estado.');
            }
        }
    }
}
</script>