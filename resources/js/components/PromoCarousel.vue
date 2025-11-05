<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const promoSlides = ref([
    { id: 1, src: '/images/promo/promo1.jpg', alt: 'Promoción Laptops 20% OFF' },
    { id: 2, src: '/images/promo/promo2.jpg', alt: 'Ofertas PC Gamer' },
    { id: 3, src: '/images/promo/promo3.jpg', alt: 'Accesorios 2x1' }
]);

const currentSlide = ref(0);
const slidesCount = promoSlides.value.length;
let intervalId = null;

const nextSlide = () => {
    currentSlide.value = (currentSlide.value + 1) % slidesCount;
};

const prevSlide = () => {
    currentSlide.value = (currentSlide.value - 1 + slidesCount) % slidesCount;
};

const goToSlide = (index) => {
    currentSlide.value = index;
};

onMounted(() => {
    intervalId = setInterval(nextSlide, 2000);
});

onUnmounted(() => {
    clearInterval(intervalId);
});
</script>

<template>
    <div class="relative w-full overflow-hidden">
        <div class="flex transition-transform duration-700 ease-in-out" :style="{ transform: `translateX(-${currentSlide * 100}%)` }">
            <div v-for="slide in promoSlides" :key="slide.id" class="w-full flex-shrink-0">
                <img :src="slide.src" :alt="slide.alt" class="w-full h-80 object-cover">
            </div>
        </div>

        <button @click="prevSlide" class="absolute top-1/2 left-4 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-3 rounded-full hover:bg-opacity-75 transition z-20">
            &lt;
        </button>
        <button @click="nextSlide" class="absolute top-1/2 right-4 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-3 rounded-full hover:bg-opacity-75 transition z-20">
            &gt;
        </button>

        <div class="absolute bottom-4 left-0 right-0 flex justify-center space-x-2 z-20">
            <button v-for="(slide, index) in slidesCount" :key="index" @click="goToSlide(index)" :class="{'bg-white': currentSlide === index, 'bg-gray-500': currentSlide !== index}" class="w-3 h-3 rounded-full transition"></button>
        </div>
    </div>
</template>