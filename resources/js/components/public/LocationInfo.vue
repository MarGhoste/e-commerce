<template>
  <div class="bg-gray-900 py-12 sm:py-16">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
      <div class="mx-auto max-w-2xl lg:mx-0 lg:max-w-none">
        <div class="grid grid-cols-1 gap-x-8 gap-y-10 lg:grid-cols-2">
          <div class="lg:pr-8">
             <div class="relative overflow-hidden rounded-lg bg-gray-800 aspect-[16/9]">
              <iframe
                class="absolute inset-0 h-full w-full"
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3901.9999999999995!2d-77.0368706!3d-12.0463744!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x9105c8b5d3a4a369%3A0x1a7b3b4b3b3b3b3b!2sPlaza%20de%20Armas%20de%20Lima!5e0!3m2!1ses!2spe!4v1675000000000"
                style="border:0;"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
              ></iframe>
            </div>
          </div>
          <div class="flex flex-col justify-center">
            <h2 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">Visítanos o Contáctanos</h2>
            <p class="mt-4 text-lg leading-8 text-gray-300">
              Estamos listos para ayudarte a construir la PC de tus sueños o encontrar el componente que necesitas.
            </p>
            <dl class="mt-10 max-w-xl space-y-8 text-base leading-7 text-gray-300 lg:max-w-none">
              <div class="flex gap-x-4">
                <dt class="flex-none">
                  <span class="sr-only">Dirección</span>
                  <svg class="h-7 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5c0-.966.784-1.75 1.75-1.75h3.5a1.75 1.75 0 011.75 1.75V21m-19.5 0v-7.5c0-.966.784-1.75 1.75-1.75h3.5a1.75 1.75 0 011.75 1.75V21m-19.5 0h19.5" />
                  </svg>
                </dt>
                <dd>Av. Principal 123, Distrito Tecnológico<br>Lima, Perú</dd>
              </div>
              <div class="flex gap-x-4">
                <dt class="flex-none">
                  <span class="sr-only">WhatsApp</span>
                  <svg class="h-7 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 6.75z" />
                  </svg>
                </dt>
                <dd><a class="hover:text-white" href="https://wa.me/51902079944" target="_blank">+51 902 079 944</a></dd>
              </div>
              <div class="flex gap-x-4">
                <dt class="flex-none">
                  <span class="sr-only">Email</span>
                  <svg class="h-7 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                  </svg>
                </dt>
                <dd><a class="hover:text-white" href="mailto:marcoscarpiocorazon@gmail.com">marcoscarpiocorazon@gmail.com</a></dd>
              </div>
            </dl>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
// Este componente es puramente presentacional, no se requiere lógica de script.
</script>