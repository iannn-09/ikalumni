<script setup>
import { Head, Link } from '@inertiajs/vue3';
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

    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
        <!-- Navigation Header -->
        <header class="relative z-10">
            <nav class="flex items-center justify-between px-6 py-4 lg:px-8">
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-lg">SV</span>
                    </div>
                    <div class="hidden sm:block">
                        <h1 class="text-lg font-bold text-gray-800 dark:text-white">SEKOLAH VOKASI</h1>
                        <p class="text-sm text-gray-600 dark:text-gray-300">UNIVERSITAS SEBELAS MARET</p>
                    </div>
                </div>

                <!-- Navigation Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium transition-colors">
                        Beranda
                    </a>
                    <a href="#" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium transition-colors">
                        Informasi
                    </a>
                    <a href="#" class="text-blue-600 dark:text-blue-400 font-medium">
                        Tentang
                    </a>
                </div>

                <!-- Auth Links -->
                <div v-if="canLogin" class="flex items-center space-x-4">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors"
                    >
                        Dashboard
                    </Link>

                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium transition-colors"
                        >
                            Log in
                        </Link>

                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors"
                        >
                            Register
                        </Link>
                    </template>
                </div>
            </nav>
        </header>

        <!-- Main Content -->
        <main class="relative">
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
            <BeritaView />
        </main>

        <!-- Footer -->
        <footer class="bg-gray-900 dark:bg-black text-white py-12">
            <div class="container mx-auto px-6">
                <div class="grid md:grid-cols-4 gap-8">
                    <div class="col-span-2">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg flex items-center justify-center">
                                <span class="text-white font-bold">SV</span>
                            </div>
                            <div>
                                <h3 class="font-bold">Sekolah Vokasi UNS</h3>
                                <p class="text-sm text-gray-400">Keluarga Alumni</p>
                            </div>
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
                            <li><a href="#" class="hover:text-white transition-colors">Beranda</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Tentang</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Alumni</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Karir</a></li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="font-semibold mb-4">Kontak</h4>
                        <ul class="space-y-2 text-gray-400">
                            <li>Jl. Ahmad Yani 200A</li>
                            <li>Surakarta, Jawa Tengah</li>
                            <li>alumni@vokasi.uns.ac.id</li>
                            <li>(0271) 647-481</li>
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
