<template>
    <div class="bg-gray-900 text-white py-12 sm:py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-2 lg:gap-x-8 lg:items-start">
                <!-- Product Image Gallery -->
                <div class="flex flex-col-reverse">
                    <!-- Image Selector -->
                    <div v-if="product.images && product.images.length > 1" class="mx-auto mt-6 hidden w-full max-w-2xl sm:block lg:max-w-none">
                        <div class="grid grid-cols-4 gap-6">
                            <button v-for="(image, index) in product.images" :key="image.id" @click="activeImage = getImageUrl(image)" :class="[activeImage === getImageUrl(image) ? 'ring-cyan-500' : 'ring-transparent', 'relative flex h-24 w-24 cursor-pointer items-center justify-center rounded-md bg-gray-800 text-sm font-medium uppercase text-gray-900 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800']">
                                <span class="sr-only"> {{ product.name }} image {{ index + 1 }}</span>
                                <span class="absolute inset-0 overflow-hidden rounded-md">
                                    <img :src="getImageUrl(image)" :alt="product.name" class="h-full w-full object-cover object-center">
                                </span>
                            </button>
                        </div>
                    </div>

                    <!-- Main Image with Zoom -->
                    <div class="w-full aspect-h-1 aspect-w-1 relative overflow-hidden rounded-lg bg-gray-800">
                        <div @mousemove="handleMouseMove" @mouseleave="resetZoom" @mouseenter="isZoomed = true" class="relative h-full w-full cursor-zoom-in">
                            <img :src="activeImage" :alt="product.name" class="h-full w-full object-cover object-center transition-transform duration-100 ease-out" :style="zoomStyle">
                        </div>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="mt-10 px-4 sm:mt-16 sm:px-0 lg:mt-0">
                    <h1 class="text-3xl font-extrabold tracking-tight text-white">{{ product.name }}</h1>

                    <div class="mt-3">
                        <h2 class="sr-only">Información del producto</h2>
                        <p class="text-3xl tracking-tight text-cyan-400">${{ product.price.toFixed(2) }}</p>
                    </div>

                    <div class="mt-6">
                        <h3 class="sr-only">Descripción</h3>
                        <div class="space-y-6 text-base text-gray-300" v-html="product.description"></div>
                    </div>

                    <div class="mt-6">
                        <h3 class="text-sm font-medium text-white">Cantidad</h3>
                        <div class="flex items-center mt-2">
                            <button @click="decrementQuantity" type="button" class="flex-shrink-0 p-2 border border-gray-700 rounded-md text-gray-300 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-cyan-500">
                                <span class="sr-only">Disminuir cantidad</span>
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" /></svg>
                            </button>
                            <input type="text" v-model.number="quantity" class="mx-2 w-16 text-center bg-gray-800 border border-gray-700 rounded-md text-white focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500" readonly>
                            <button @click="incrementQuantity" type="button" class="flex-shrink-0 p-2 border border-gray-700 rounded-md text-gray-300 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-cyan-500">
                                <span class="sr-only">Aumentar cantidad</span>
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                            </button>
                        </div>
                    </div>

                    <form class="mt-6">
                        <button @click.prevent="addToCart" :disabled="cartStore.isLoading" type="submit" class="w-full flex items-center justify-center rounded-md border border-transparent bg-cyan-600 px-8 py-3 text-base font-medium text-white hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-cyan-500">
                            {{ cartStore.isLoading ? 'Añadiendo...' : 'Añadir al Carrito' }}
                        </button>
                    </form>

                    <p v-if="addedSuccess" class="mt-4 text-green-500 text-center">¡Producto añadido al carrito!</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useCartStore } from '../../stores/cart';
import { defineProps } from 'vue';

const props = defineProps({
    product: {
        type: Object,
        required: true
    }
});

const cartStore = useCartStore();
const quantity = ref(1);
const addedSuccess = ref(false);
const activeImage = ref('');

// Zoom functionality
const zoomFactor = 2;
const isZoomed = ref(false);
const mouseX = ref(0);
const mouseY = ref(0);
const imageContainerRef = ref(null); // Ref to the image container

const zoomStyle = computed(() => {
    if (!isZoomed.value) {
        return { transform: 'scale(1)', transformOrigin: 'center center' };
    }

    // Calculate transform origin based on mouse position
    // We need the actual dimensions of the image container
    let containerWidth = 1; // Default to avoid division by zero
    let containerHeight = 1;

    if (imageContainerRef.value) {
        containerWidth = imageContainerRef.value.offsetWidth;
        containerHeight = imageContainerRef.value.offsetHeight;
    }

    const originX = (mouseX.value / containerWidth) * 100;
    const originY = (mouseY.value / containerHeight) * 100;

    return {
        transform: `scale(${zoomFactor})`,
        transformOrigin: `${originX}% ${originY}%`,
    };
});

const handleMouseMove = (event) => {
    if (!imageContainerRef.value) return;

    const rect = event.currentTarget.getBoundingClientRect();
    mouseX.value = event.clientX - rect.left;
    mouseY.value = event.clientY - rect.top;
};

const resetZoom = () => {
    isZoomed.value = false;
    mouseX.value = 0;
    mouseY.value = 0;
};

// Helper to get image URL (similar to CartComponent) - assuming product.images[x].url is the raw path
const getImageUrl = (imageObject) => {
    if (!imageObject || !imageObject.url) {
        return '/images/placeholder.png'; // Fallback placeholder
    }
    const imageUrl = imageObject.url;
    if (imageUrl.startsWith('http') || imageUrl.startsWith('/storage/')) {
        return imageUrl;
    }
    return `/storage/${imageUrl}`;
};

const incrementQuantity = () => {
    quantity.value++;
};

const decrementQuantity = () => {
    if (quantity.value > 1) {
        quantity.value--;
    }
};

const addToCart = async () => {
    await cartStore.addProductToCart(props.product.id, quantity.value);
    addedSuccess.value = true;
    setTimeout(() => {
        addedSuccess.value = false;
    }, 2000);
};

onMounted(() => {
    // Set the initial active image if product has images
    if (props.product.images && props.product.images.length > 0) {
        activeImage.value = getImageUrl(props.product.images[0]);
    } else if (props.product.image_url) {
        // Fallback if product has a direct image_url (e.g., from catalog list)
        activeImage.value = getImageUrl({ url: props.product.image_url });
    } else {
        activeImage.value = '/images/placeholder.png';
    }
});
</script>

<style scoped>
/* Tailwind handles most styling, but ensure aspect ratios work */
.aspect-h-1 {
    padding-bottom: 100%; /* 1:1 Aspect Ratio */
}
.aspect-w-1 {
    position: relative;
    height: 0;
}
.aspect-w-1 > div {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}
</style>
