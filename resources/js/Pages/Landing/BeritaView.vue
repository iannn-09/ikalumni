<script setup>
import { ref, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import axios from 'axios';

// News data state - properly initialize as empty array
const news = ref([]);
const isLoadingNews = ref(true);

// Sample news data as fallback
const getSampleNews = () => [
    {
        id: 1,
        judul: 'Reuni Akbar Alumni Sekolah Vokasi UNS 2025',
        slug: 'reuni-akbar-alumni-sekolah-vokasi-uns-2025',
        content: 'Bergabunglah dalam acara reuni akbar yang akan mempertemukan alumni dari berbagai angkatan. Acara ini akan dilaksanakan di kampus Sekolah Vokasi UNS dengan berbagai kegiatan menarik.',
        thumbnail: 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=400&h=250&fit=crop&crop=center',
        kategori: { nama: 'Acara' },
        published_at: '2025-01-15T00:00:00.000000Z'
    },
    {
        id: 2,
        judul: 'Job Fair Alumni: Peluang Karir Terbuka Lebar',
        slug: 'job-fair-alumni-peluang-karir-terbuka-lebar',
        content: 'Temukan peluang karir impian Anda di Job Fair khusus alumni. Lebih dari 50 perusahaan terkemuka akan hadir untuk membuka kesempatan kerja di berbagai bidang.',
        thumbnail: 'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=400&h=250&fit=crop&crop=center',
        kategori: { nama: 'Karir' },
        published_at: '2025-01-10T00:00:00.000000Z'
    },
    {
        id: 3,
        judul: 'Workshop Digital Marketing untuk Alumni',
        slug: 'workshop-digital-marketing-untuk-alumni',
        content: 'Tingkatkan skill digital marketing Anda melalui workshop intensif yang dihadiri oleh praktisi berpengalaman. Cocok untuk alumni yang ingin mengembangkan bisnis.',
        thumbnail: 'https://images.unsplash.com/photo-1517048676732-d65bc937f952?w=400&h=250&fit=crop&crop=center',
        kategori: { nama: 'Workshop' },
        published_at: '2025-01-05T00:00:00.000000Z'
    }
];

// Fetch news from API
const fetchNews = async () => {
    try {
        isLoadingNews.value = true;
        const response = await axios.get('/api/berita');
        // Ensure response.data exists and is an array
        const newsData = response.data || [];
        // Filter only published news and take first 3
        news.value = newsData
            .filter(item => item.status === 'published')
            .slice(0, 3);
    } catch (error) {
        console.error('Error fetching news:', error);
        // Fallback to sample data if API fails
        news.value = getSampleNews();
    } finally {
        isLoadingNews.value = false;
    }
};

// Format date function
const formatDate = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });
};

// Get category color based on category name
const getCategoryColor = (categoryName) => {
    const colors = {
        'Acara': 'bg-blue-600',
        'Karir': 'bg-green-600',
        'Workshop': 'bg-purple-600',
        'Pengumuman': 'bg-orange-600',
        'Beasiswa': 'bg-yellow-600'
    };
    return colors[categoryName] || 'bg-gray-600';
};

// Truncate text function
const truncateText = (text, maxLength = 150) => {
    if (!text) return '';
    if (text.length <= maxLength) return text;
    return text.substring(0, maxLength) + '...';
};

// Fetch news on component mount
onMounted(() => {
    fetchNews();
});
</script>

<template>
    <section class="py-20 bg-gradient-to-r from-blue-50 to-purple-50 dark:from-gray-900 dark:to-gray-800">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                    Berita & Informasi Terkini
                </h2>
                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
                    Tetap update dengan berita terbaru dari komunitas alumni Sekolah Vokasi UNS
                </p>
            </div>

            <!-- Loading State -->
            <div v-if="isLoadingNews" class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                <div v-for="n in 3" :key="n" class="animate-pulse">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden">
                        <div class="aspect-video bg-gray-300 dark:bg-gray-600"></div>
                        <div class="p-6">
                            <div class="h-4 bg-gray-300 dark:bg-gray-600 rounded w-24 mb-3"></div>
                            <div class="h-6 bg-gray-300 dark:bg-gray-600 rounded w-full mb-3"></div>
                            <div class="h-4 bg-gray-300 dark:bg-gray-600 rounded w-full mb-2"></div>
                            <div class="h-4 bg-gray-300 dark:bg-gray-600 rounded w-3/4 mb-4"></div>
                            <div class="h-4 bg-gray-300 dark:bg-gray-600 rounded w-32"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- News Cards -->
            <div v-else-if="news && news.length > 0" class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                <article
                    v-for="item in news"
                    :key="item.id"
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden group"
                >
                    <div class="aspect-video relative overflow-hidden">
                        <img
                            :src="item.thumbnail || `https://images.unsplash.com/photo-1585829365295-ab7cd400c167?w=400&h=250&fit=crop&crop=center`"
                            :alt="item.judul || 'News image'"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                            @error="$event.target.src = 'https://images.unsplash.com/photo-1585829365295-ab7cd400c167?w=400&h=250&fit=crop&crop=center'"
                        />
                        <div :class="['absolute top-4 left-4 text-white px-3 py-1 rounded-full text-sm font-medium', getCategoryColor(item.kategori?.nama)]">
                            {{ item.kategori?.nama || 'Berita' }}
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>{{ formatDate(item.published_at || item.created_at) }}</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3 line-clamp-2">
                            {{ item.judul || 'Untitled' }}
                        </h3>
                        <p class="text-gray-600 dark:text-gray-300 text-sm line-clamp-3 mb-4">
                            {{ truncateText((item.content || '').replace(/<[^>]*>/g, '')) }}
                        </p>
                        <Link
                            :href="'/berita/' + (item.slug || item.id)"
                            class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium text-sm group"
                        >
                            Baca Selengkapnya
                            <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </Link>
                    </div>
                </article>
            </div>

            <!-- Empty State -->
            <div v-else-if="!isLoadingNews" class="text-center py-12">
                <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Belum Ada Berita</h3>
                <p class="text-gray-600 dark:text-gray-400">Berita akan ditampilkan di sini setelah dipublikasikan.</p>
            </div>

            <!-- View All News Button -->
            <div v-if="!isLoadingNews && news && news.length > 0" class="text-center">
                <a href="#"
                    class="inline-flex items-center bg-white dark:bg-gray-800 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-gray-700 px-8 py-4 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-200 border border-blue-200 dark:border-gray-600">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                    Lihat Semua Berita
                </a>
            </div>
        </div>
    </section>
</template>
