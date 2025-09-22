<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import { Link } from "@inertiajs/vue3";

defineProps({
  canLogin: {
    type: Boolean,
    default: true,
  },
  canRegister: {
    type: Boolean,
    default: true,
  },
});

// Function to scroll to news section
const scrollToBerita = () => {
  const newsSection = document.querySelector('.news-section');
  if (newsSection) {
    newsSection.scrollIntoView({
      behavior: 'smooth',
      block: 'start'
    });
  } else {
    // If not on homepage, navigate to homepage first
    window.location.href = '/#berita';
  }
};

// Function to scroll to footer menu
const scrollToNews = () => {
  const newsSection = document.querySelector('.news-section');
  if (newsSection) {
    newsSection.scrollIntoView({
      behavior: 'smooth',
      block: 'start'
    });
  }
};
</script>

<template>
  <!-- Tambahkan overflow-x-hidden di container utama -->
  <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 overflow-x-hidden">
    <!-- Navigation Header -->
    <header class="relative z-10 w-full">
      <nav class="flex items-center justify-between px-6 py-4 lg:px-8 max-w-full">
        <!-- Logo -->
        <Link href="/" class="flex items-center gap-3 flex-shrink-0">
              <img
                src="/storage/logo/logo-sv-putih.png"
                alt="Sekolah Vokasi UNS"
                class="h-12 max-w-full"
              />
        </Link>

        <!-- Navigation Menu -->
        <div class="hidden md:flex items-center space-x-8 flex-shrink-0">
          <Link href="/" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium transition-colors whitespace-nowrap">
            Beranda
          </Link>
          <button
            @click="scrollToBerita"
            class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium transition-colors whitespace-nowrap"
          >
            Berita
          </button>
        </div>

        <!-- Auth Links -->
        <div v-if="canLogin" class="flex items-center space-x-4 flex-shrink-0">
          <Link
            v-if="$page.props.auth?.user"
            :href="route('dashboard')"
            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors whitespace-nowrap"
          >
            Dashboard
          </Link>

          <template v-else>
            <Link
              :href="route('login')"
              class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium transition-colors whitespace-nowrap"
            >
              Log in
            </Link>

            <Link
              v-if="canRegister"
              :href="route('register')"
              class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors whitespace-nowrap"
            >
              Register
            </Link>
          </template>
        </div>
      </nav>
    </header>

    <!-- Main Content -->
    <main class="relative w-full overflow-x-hidden">
      <slot />
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 dark:bg-black text-white py-12 w-full overflow-x-hidden">
      <div class="container mx-auto px-6 max-w-full">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
          <div class="md:col-span-2">
            <div class="mb-4">
              <img
                src="/storage/logo/logo-sv-putih.png"
                alt="Sekolah Vokasi UNS"
                class="h-12 max-w-full"
              />
            </div>
            <p class="text-gray-400 mb-4">
              Membangun jejaring alumni yang kuat untuk kemajuan bersama.
            </p>
            <div class="flex space-x-4">
              <a href="#" class="text-gray-400 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                </svg>
              </a>
              <a href="#" class="text-gray-400 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.20-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.60 1.56-1.36 2.14-2.23z"/>
                </svg>
              </a>
              <a href="#" class="text-gray-400 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                </svg>
              </a>
            </div>
          </div>

          <div>
            <h4 class="font-semibold mb-4">Menu</h4>
            <ul class="space-y-2 text-gray-400">
              <li><Link href="/" class="hover:text-white transition-colors">Beranda</Link></li>
              <li>
                <button
                  @click="scrollToNews"
                  class="hover:text-white transition-colors text-left"
                >
                  Berita
                </button>
              </li>
            </ul>
          </div>

          <div>
            <h4 class="font-semibold mb-4">Kontak</h4>
            <ul class="space-y-2 text-gray-400">
              <li>Jl. Kolonel Sutarto No.150K</li>
              <li>Jebres, Kec. Jebres</li>
              <li>Kota Surakarta, Jawa Tengah</li>
              <li>(0271) 662622</li>
            </ul>
          </div>
        </div>

        <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
          <p>&copy; 2025 Keluarga Alumni Sekolah Vokasi UNS. All rights reserved.</p>
        </div>
      </div>
    </footer>
  </div>
</template>

<style scoped>
/* Tambahkan CSS untuk memastikan tidak ada horizontal scroll */
html, body {
  overflow-x: hidden;
  max-width: 100%;
}

* {
  box-sizing: border-box;
}
</style>
