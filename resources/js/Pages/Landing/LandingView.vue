<script setup>
import { Head } from '@inertiajs/vue3';
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
        <div class="container mx-auto px-6 py-16 lg:py-24">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Left Content -->
                <div class="space-y-8">
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

                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <Link
                            v-if="!$page.props.auth.user"
                            :href="route('register')"
                            class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white px-8 py-4 rounded-xl font-semibold text-center shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200"
                        >
                            Bergabung Sekarang
                        </Link>

                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white px-8 py-4 rounded-xl font-semibold text-center shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200"
                        >
                            Ke Dashboard
                        </Link>
                    </div>

                </div>

                <!-- Right Content - Illustration -->
                <div class="relative">
                    <!-- Background Elements -->
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-100 to-purple-100 dark:from-blue-900/20 dark:to-purple-900/20 rounded-full transform rotate-6 scale-105"></div>

                    <!-- Main Illustration Area -->
                    <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-xl p-6 transform -rotate-2 hover:rotate-0 transition-transform duration-500">
                        <!-- Illustration Placeholder -->
                        <div class="aspect-square bg-gradient-to-br from-blue-400 via-purple-500 to-pink-500 rounded-lg flex items-center justify-center">
                            <!-- Alumni Figures Illustration -->
                            <div class="text-center text-white">
                                <svg class="w-16 h-16 mx-auto mb-3" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                </svg>
                                <div class="text-base font-semibold">Alumni Network</div>
                                <div class="text-xs opacity-90">Terhubung & Berkembang</div>
                            </div>
                        </div>

                        <!-- Floating Elements -->
                        <div class="absolute -top-3 -right-3 w-6 h-6 bg-yellow-400 rounded-full animate-bounce"></div>
                        <div class="absolute -bottom-2 -left-2 w-4 h-4 bg-pink-400 rounded-full animate-pulse"></div>
                        <div class="absolute top-1/2 -right-4 w-3 h-3 bg-green-400 rounded-full animate-ping"></div>
                    </div>

                    <!-- Decorative Elements -->
                    <div class="absolute top-8 right-8 w-16 h-16 bg-gradient-to-r from-blue-300 to-purple-300 rounded-full opacity-20 animate-pulse"></div>
                    <div class="absolute bottom-8 left-8 w-12 h-12 bg-gradient-to-r from-pink-300 to-yellow-300 rounded-full opacity-20 animate-bounce"></div>
                </div>
            </div>
        </div>
        <BeritaView />
    </LandingLayout>
</template>
