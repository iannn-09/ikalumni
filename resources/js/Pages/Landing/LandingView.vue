<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/Components/ui/button';
import LandingLayout from '@/Layouts/LandingLayout.vue';
import BeritaView from '@/Pages/Landing/BeritaView.vue';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});
</script>

<template>
    <Head title="Keluarga Alumni - Sekolah Vokasi UNS" />

    <LandingLayout :can-login="canLogin" :can-register="canRegister">
        <!-- Hero Section -->
        <div class="container mx-auto px-6 py-16 lg:py-24 transition-all duration-300 ease-in-out">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Left Content -->
                <div class="space-y-8 transition-all duration-300 ease-in-out">
                    <div class="space-y-6">
                        <h1 class="text-4xl lg:text-6xl font-bold text-gray-900 dark:text-white leading-tight">
                            Keluarga Alumni
                            <span class="text-blue-600 dark:text-blue-400">(KA)</span>
                        </h1>

                        <p class="text-xl text-gray-600 dark:text-gray-300 leading-relaxed">
                            Sekolah Vokasi UNS
                        </p>

                        <p class="text-gray-600 dark:text-gray-400 leading-relaxed max-w-lg">
                            Bergabunglah dengan komunitas alumni Sekolah Vokasi Universitas Sebelas Maret.
                            Terhubung, berbagi pengalaman, dan kembangkan jaringan profesional Anda.
                        </p>
                    </div>

                    <!-- CTA Buttons - Only show if user is logged in -->
                    <div v-if="$page.props.auth?.user" class="flex flex-col sm:flex-row gap-4">
                        <Button
                            as-child
                            size="lg"
                            class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200"
                        >
                            <Link :href="route('dashboard')">
                                Ke Dashboard
                            </Link>
                        </Button>
                    </div>

                    <!-- Login prompt for guests -->
                    <div v-else class="flex flex-col sm:flex-row gap-4">
                        <Button
                            as-child
                            size="lg"
                            class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200"
                        >
                            <Link :href="route('login')">
                                Masuk ke Akun
                            </Link>
                        </Button>
                    </div>
                </div>

                <!-- Right Content - Illustration -->
                <div class="relative max-w-md mx-auto lg:mx-0 transition-all duration-500 ease-in-out">
                    <!-- Background Elements -->
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-100 to-purple-100 dark:from-blue-900/20 dark:to-purple-900/20 rounded-full transform rotate-6 scale-105 transition-all duration-500 ease-in-out"></div>

                    <!-- Main Illustration Area -->
                    <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-xl p-6 transform -rotate-2 hover:rotate-0 transition-all duration-500 ease-in-out hover:scale-105">
                        <!-- Illustration Placeholder -->
                        <div class="aspect-square bg-gradient-to-br from-blue-400 via-purple-500 to-pink-500 rounded-lg flex items-center justify-center transition-all duration-300 ease-in-out">
                            <!-- Alumni Figures Illustration -->
                            <div class="text-center text-white transition-all duration-300 ease-in-out">
                                <svg class="w-16 h-16 mx-auto mb-3 transition-transform duration-300 ease-in-out hover:scale-110" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                </svg>
                                <div class="text-base font-semibold transition-all duration-300 ease-in-out">Alumni Network</div>
                                <div class="text-sm opacity-90 transition-all duration-300 ease-in-out">Terhubung & Berkembang</div>
                            </div>
                        </div>

                        <!-- Floating Elements with enhanced animations -->
                        <div class="absolute -top-3 -right-3 w-5 h-5 bg-yellow-400 rounded-full animate-bounce transition-all duration-300 ease-in-out"></div>
                        <div class="absolute -bottom-2 -left-2 w-4 h-4 bg-pink-400 rounded-full animate-pulse transition-all duration-300 ease-in-out"></div>
                        <div class="absolute top-1/2 -right-4 w-3 h-3 bg-green-400 rounded-full animate-ping transition-all duration-300 ease-in-out"></div>
                    </div>

                    <!-- Decorative Elements with smooth transitions -->
                    <div class="absolute top-6 right-6 w-10 h-10 bg-gradient-to-r from-blue-300 to-purple-300 rounded-full opacity-20 animate-pulse transition-all duration-500 ease-in-out"></div>
                    <div class="absolute bottom-6 left-6 w-8 h-8 bg-gradient-to-r from-pink-300 to-yellow-300 rounded-full opacity-20 animate-bounce transition-all duration-500 ease-in-out"></div>
                </div>
            </div>
        </div>
        <BeritaView />
    </LandingLayout>
</template>

<style scoped>
/* Enhanced animations for theme transitions */
@keyframes smooth-fade-in {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.container {
  animation: smooth-fade-in 0.6s ease-out;
}

/* Smooth hover effects */
.hover\:scale-105:hover {
  transform: scale(1.05);
  transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Enhanced button transitions */
.bg-gradient-to-r {
  transition: all 0.3s ease-in-out;
}

.bg-gradient-to-r:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

/* Text transitions */
h1, p, div {
  transition: color 0.2s ease-in-out;
}
</style>
