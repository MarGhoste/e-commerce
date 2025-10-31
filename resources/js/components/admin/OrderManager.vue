<template>
  <div>
    <h2>Gestión de Órdenes 📦</h2>
    
    <p v-if="loading">Cargando órdenes...</p>
    
    <table class="table mt-4" v-else>
      <thead>
        <tr>
          <th># Orden</th>
          <th>Cliente</th>
          <th>Monto Total</th>
          <th>Estado</th>
          <th>Fecha</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="order in orders" :key="order.id">
          <td>{{ order.order_number }}</td>
          <td>{{ order.user ? order.user.name : 'Invitado' }}</td>
          <td>${{ order.total_amount }}</td>
          <td>
            <span :class="['status-' + order.status]">{{ order.status.toUpperCase() }}</span>
          </td>
          <td>{{ formatDate(order.created_at) }}</td>
          <td>
            <button @click="viewOrderDetails(order.id)" class="btn btn-sm btn-info">Ver Detalle</button>
          </td>
        </tr>
        <tr v-if="orders.length === 0 && !loading">
            <td colspan="6" class="text-center">No hay órdenes registradas.</td>
        </tr>
      </tbody>
    </table>

    <div v-if="selectedOrder" class="order-detail-modal">
        <div class="modal-content">
            <h3>Detalle de Orden #{{ selectedOrder.order_number }}</h3>
            <p>Cliente: <strong>{{ selectedOrder.user ? selectedOrder.user.name : 'Invitado' }}</strong></p>
            <p>Dirección: {{ selectedOrder.shipping_address_line_1 }}, {{ selectedOrder.shipping_city }}</p>
            <p>Monto Total: <strong>${{ selectedOrder.total_amount }}</strong></p>

            <h4>Productos</h4>
            <ul>
                <li v-for="item in selectedOrder.items" :key="item.id">
                    {{ item.product ? item.product.name : 'Producto Eliminado' }} (x{{ item.quantity }}) @ ${{ item.price }}
                </li>
            </ul>

            <div class="status-update">
                <label for="status">Cambiar Estado:</label>
                <select id="status" v-model="newStatus">
                    <option v-for="s in allowedStatuses" :key="s" :value="s">{{ s.toUpperCase() }}</option>
                </select>
                <button @click="updateOrderStatus" class="btn btn-sm btn-success">Actualizar</button>
            </div>

            <button @click="selectedOrder = null" class="btn btn-secondary mt-3">Cerrar</button>
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
            selectedOrder: null, // Objeto para el detalle de la orden seleccionada
            newStatus: '', // El nuevo estado seleccionado por el admin
            // Estados permitidos (debe coincidir con la validación de OrderController)
            allowedStatuses: ['pending', 'paid', 'shipped', 'delivered', 'cancelled'], 
        };
    },
    mounted() {
        this.fetchOrders();
    },
    methods: {
        formatDate(date) {
            // Utiliza una función de JS para formatear la fecha
            return new Date(date).toLocaleDateString();
        },

        async fetchOrders(page = 1) {
            this.loading = true;
            try {
                // Llama al método index() de Admin\OrderController
                const response = await axios.get(`/admin/orders?page=${page}`);
                this.orders = response.data.data;
                
                // Extrae la metadata de paginación
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
                // Llama al método show() de Admin\OrderController
                const response = await axios.get(`/admin/orders/${orderId}`);
                this.selectedOrder = response.data;
                this.newStatus = response.data.status; // Establecer el estado actual para el select
            } catch (error) {
                console.error("Error al cargar detalle de orden:", error);
                alert('No se pudo cargar el detalle de la orden.');
            }
        },

        async updateOrderStatus() {
            if (!this.selectedOrder || !this.newStatus) return;

            try {
                // Llama al método update() de Admin\OrderController
                const response = await axios.put(`/admin/orders/${this.selectedOrder.id}`, {
                    status: this.newStatus
                });

                // Actualizar la orden en la lista y en el modal
                this.selectedOrder.status = response.data.status;
                
                // Actualizar la orden en el array 'orders' para que la tabla se refresque
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

<style scoped>
/* Estilos básicos para diferenciar estados y modal */
.status-pending { color: orange; font-weight: bold; }
.status-paid { color: blue; font-weight: bold; }
.status-shipped { color: purple; font-weight: bold; }
.status-delivered { color: green; font-weight: bold; }
.status-cancelled { color: red; font-weight: bold; text-decoration: line-through; }

.order-detail-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    justify-content: center;
    align-items: center;
}
.modal-content {
    background: white;
    padding: 30px;
    border-radius: 8px;
    width: 90%;
    max-width: 600px;
}
.status-update {
    margin-top: 20px;
    padding-top: 10px;
    border-top: 1px solid #ccc;
}
.btn-secondary { background-color: #6c757d; color: white; }
</style>