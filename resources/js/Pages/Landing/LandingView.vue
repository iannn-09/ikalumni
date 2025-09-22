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

                        <a
                            href="#about"
                            class="border-2 border-blue-600 text-blue-600 dark:border-blue-400 dark:text-blue-400 hover:bg-blue-600 hover:text-white dark:hover:bg-blue-400 px-8 py-4 rounded-xl font-semibold text-center transition-all duration-200"
                        >
                            Pelajari Lebih Lanjut
                        </a>
                    </div>

                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-8 pt-8">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">1000+</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Alumni Terdaftar</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-purple-600 dark:text-purple-400">50+</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Program Studi</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-green-600 dark:text-green-400">95%</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Tingkat Kepuasan</div>
                        </div>
                    </div>
                </div>

                <!-- Right Content - Illustration -->
                <div class="relative">
                    <!-- Background Elements -->
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-100 to-purple-100 dark:from-blue-900/20 dark:to-purple-900/20 rounded-full transform rotate-6 scale-110"></div>

                    <!-- Main Illustration Area -->
                    <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-8 transform -rotate-2 hover:rotate-0 transition-transform duration-500">
                        <!-- Illustration Placeholder -->
                        <div class="aspect-square bg-gradient-to-br from-blue-400 via-purple-500 to-pink-500 rounded-xl flex items-center justify-center">
                            <!-- Alumni Figures Illustration -->
                            <div class="text-center text-white">
                                <svg class="w-24 h-24 mx-auto mb-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                </svg>
                                <div class="text-lg font-semibold">Alumni Network</div>
                                <div class="text-sm opacity-90">Terhubung & Berkembang</div>
                            </div>
                        </div>

                        <!-- Floating Elements -->
                        <div class="absolute -top-4 -right-4 w-8 h-8 bg-yellow-400 rounded-full animate-bounce"></div>
                        <div class="absolute -bottom-2 -left-2 w-6 h-6 bg-pink-400 rounded-full animate-pulse"></div>
                        <div class="absolute top-1/2 -right-6 w-4 h-4 bg-green-400 rounded-full animate-ping"></div>
                    </div>

                    <!-- Decorative Elements -->
                    <div class="absolute top-10 right-10 w-20 h-20 bg-gradient-to-r from-blue-300 to-purple-300 rounded-full opacity-20 animate-pulse"></div>
                    <div class="absolute bottom-10 left-10 w-16 h-16 bg-gradient-to-r from-pink-300 to-yellow-300 rounded-full opacity-20 animate-bounce"></div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <section id="about" class="py-20 bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm">
            <div class="container mx-auto px-6">
                <div class="text-center mb-16">
                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                        Mengapa Bergabung dengan Kami?
                    </h2>
                    <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
                        Jadilah bagian dari komunitas alumni yang solid dan saling mendukung
                    </p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Jaringan Alumni</h3>
                        <p class="text-gray-600 dark:text-gray-300">Terhubung dengan ribuan alumni dari berbagai program studi dan angkatan.</p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Peluang Karir</h3>
                        <p class="text-gray-600 dark:text-gray-300">Akses informasi lowongan kerja dan peluang bisnis dari sesama alumni.</p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-green-600 rounded-xl flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Pengembangan Diri</h3>
                        <p class="text-gray-600 dark:text-gray-300">Ikuti berbagai acara, seminar, dan workshop untuk mengembangkan skill.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- News Section -->
        <BeritaView />
    </LandingLayout>
</template>
