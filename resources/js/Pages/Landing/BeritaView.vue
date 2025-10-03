<script setup>
import { ref, onMounted, computed, onUnmounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import axios from 'axios';

// News data state
const news = ref([]);
const isLoadingNews = ref(true);

// Screen size detection
const isMobile = ref(false);

// Pagination state
const currentPage = ref(1);
const totalItems = ref(0);

// Dynamic items per page based on screen size
const itemsPerPage = computed(() => isMobile.value ? 1 : 3);

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
    },
    {
        id: 4,
        judul: 'Pengumuman Beasiswa S2 untuk Alumni',
        slug: 'pengumuman-beasiswa-s2-untuk-alumni',
        content: 'Dapatkan kesempatan melanjutkan studi S2 dengan beasiswa penuh. Program ini terbuka untuk alumni dengan prestasi akademik yang baik.',
        thumbnail: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&h=250&fit=crop&crop=center',
        kategori: { nama: 'Beasiswa' },
        published_at: '2025-01-01T00:00:00.000000Z'
    },
    {
        id: 5,
        judul: 'Seminar Nasional Teknologi Terapan 2025',
        slug: 'seminar-nasional-teknologi-terapan-2025',
        content: 'Ikuti seminar nasional dengan tema teknologi terapan yang akan menghadirkan pembicara dari berbagai universitas terkemuka di Indonesia.',
        thumbnail: 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=400&h=250&fit=crop&crop=center',
        kategori: { nama: 'Acara' },
        published_at: '2024-12-28T00:00:00.000000Z'
    },
    {
        id: 6,
        judul: 'Training Entrepreneur untuk Alumni Muda',
        slug: 'training-entrepreneur-untuk-alumni-muda',
        content: 'Program training khusus untuk alumni yang ingin memulai bisnis. Dapatkan mentoring dari entrepreneur sukses dan networking yang luas.',
        thumbnail: 'https://images.unsplash.com/photo-1556761175-b413da4baf72?w=400&h=250&fit=crop&crop=center',
        kategori: { nama: 'Workshop' },
        published_at: '2024-12-25T00:00:00.000000Z'
    }
];

// Screen size detection function
const checkScreenSize = () => {
    isMobile.value = window.innerWidth < 768; // md breakpoint
};

// Fetch news from API
const fetchNews = async () => {
    try {
        isLoadingNews.value = true;
        const response = await axios.get('/api/berita');
        const newsData = response.data || [];
        const publishedNews = newsData.filter(item => item.status === 'published');

        totalItems.value = publishedNews.length;

        // Get paginated data
        const startIndex = (currentPage.value - 1) * itemsPerPage.value;
        const endIndex = startIndex + itemsPerPage.value;
        news.value = publishedNews.slice(startIndex, endIndex);
    } catch (error) {
        console.error('Error fetching news:', error);
        // Fallback to sample data if API fails
        const sampleData = getSampleNews();
        totalItems.value = sampleData.length;

        const startIndex = (currentPage.value - 1) * itemsPerPage.value;
        const endIndex = startIndex + itemsPerPage.value;
        news.value = sampleData.slice(startIndex, endIndex);
    } finally {
        isLoadingNews.value = false;
    }
};

// Computed properties for pagination
const totalPages = computed(() => Math.ceil(totalItems.value / itemsPerPage.value));

const visiblePages = computed(() => {
    const pages = [];
    const maxVisible = 5;
    let start = Math.max(1, currentPage.value - Math.floor(maxVisible / 2));
    let end = Math.min(totalPages.value, start + maxVisible - 1);

    if (end - start + 1 < maxVisible) {
        start = Math.max(1, end - maxVisible + 1);
    }

    for (let i = start; i <= end; i++) {
        pages.push(i);
    }

    return pages;
});

const hasNextPage = computed(() => currentPage.value < totalPages.value);
const hasPrevPage = computed(() => currentPage.value > 1);

// Pagination methods
const goToPage = (page) => {
    if (page >= 1 && page <= totalPages.value && page !== currentPage.value) {
        currentPage.value = page;
        fetchNews();
        scrollToTop();
    }
};

const nextPage = () => {
    if (hasNextPage.value) {
        goToPage(currentPage.value + 1);
    }
};

const prevPage = () => {
    if (hasPrevPage.value) {
        goToPage(currentPage.value - 1);
    }
};

const scrollToTop = () => {
    const newsSection = document.querySelector('.news-section');
    if (newsSection) {
        newsSection.scrollIntoView({ behavior: 'smooth' });
    }
};

// Utility functions
const formatDate = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });
};

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

const truncateText = (text, maxLength = 150) => {
    if (!text) return '';
    if (text.length <= maxLength) return text;
    return text.substring(0, maxLength) + '...';
};

// Initialize component
onMounted(() => {
    checkScreenSize();
    window.addEventListener('resize', checkScreenSize);
    fetchNews();
});

onUnmounted(() => {
    window.removeEventListener('resize', checkScreenSize);
});
</script>

<template>
    <section class="py-12 md:py-16 lg:py-20 bg-gradient-to-r from-blue-50 to-purple-50 dark:from-gray-900 dark:to-gray-800 news-section transition-all duration-300 ease-in-out">
        <div class="container mx-auto px-4 md:px-6 transition-all duration-300 ease-in-out">
            <!-- Section Header -->
            <div class="text-center mb-8 md:mb-12 lg:mb-16 transition-all duration-500 ease-in-out">
                <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white mb-3 md:mb-4 transition-colors duration-300 ease-in-out">
                    Berita & Informasi Terkini
                </h2>
                <p class="text-base md:text-lg lg:text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto transition-colors duration-300 ease-in-out px-4">
                    Tetap update dengan berita terbaru dari komunitas alumni Sekolah Vokasi UNS
                </p>
            </div>

            <!-- Loading State -->
            <div v-if="isLoadingNews" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 lg:gap-8 mb-8 md:mb-12">
                <div v-for="n in itemsPerPage" :key="n" class="animate-pulse transition-all duration-300 ease-in-out">
                    <div class="bg-white dark:bg-gray-800 rounded-xl md:rounded-2xl shadow-lg overflow-hidden transition-all duration-300 ease-in-out">
                        <div class="aspect-video bg-gray-300 dark:bg-gray-600"></div>
                        <div class="p-4 md:p-6">
                            <div class="h-3 md:h-4 bg-gray-300 dark:bg-gray-600 rounded w-20 md:w-24 mb-2 md:mb-3"></div>
                            <div class="h-5 md:h-6 bg-gray-300 dark:bg-gray-600 rounded w-full mb-2 md:mb-3"></div>
                            <div class="h-3 md:h-4 bg-gray-300 dark:bg-gray-600 rounded w-full mb-1 md:mb-2"></div>
                            <div class="h-3 md:h-4 bg-gray-300 dark:bg-gray-600 rounded w-3/4 mb-3 md:mb-4"></div>
                            <div class="h-3 md:h-4 bg-gray-300 dark:bg-gray-600 rounded w-24 md:w-32"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- News Cards -->
            <div v-else-if="news && news.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 lg:gap-8 mb-8 md:mb-12">
                <article
                    v-for="item in news"
                    :key="item.id"
                    class="bg-white dark:bg-gray-800 rounded-xl md:rounded-2xl shadow-lg hover:shadow-xl transition-all duration-500 ease-in-out overflow-hidden group transform hover:-translate-y-1 md:hover:-translate-y-2"
                >
                    <div class="aspect-video relative overflow-hidden">
                        <img
                            :src="item.thumbnail || `https://images.unsplash.com/photo-1585829365295-ab7cd400c167?w=400&h=250&fit=crop&crop=center`"
                            :alt="item.judul || 'News image'"
                            class="w-full h-full object-cover group-hover:scale-105 md:group-hover:scale-110 transition-transform duration-500 ease-in-out"
                            @error="$event.target.src = 'https://images.unsplash.com/photo-1585829365295-ab7cd400c167?w=400&h=250&fit=crop&crop=center'"
                        />
                        <div :class="['absolute top-2 md:top-4 left-2 md:left-4 text-white px-2 md:px-3 py-1 rounded-full text-xs md:text-sm font-medium transition-all duration-300 ease-in-out', getCategoryColor(item.kategori?.nama)]">
                            {{ item.kategori?.nama || 'Berita' }}
                        </div>
                    </div>
                    <div class="p-4 md:p-6 transition-all duration-300 ease-in-out">
                        <div class="flex items-center gap-1 md:gap-2 text-xs md:text-sm text-gray-500 dark:text-gray-400 mb-2 md:mb-3 transition-colors duration-300 ease-in-out">
                            <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>{{ formatDate(item.published_at || item.created_at).split(' ').slice(0, 2).join(' ') }}</span>
                        </div>
                        <h3 class="text-base md:text-lg lg:text-xl font-bold text-gray-900 dark:text-white mb-2 md:mb-3 line-clamp-2 transition-colors duration-300 ease-in-out">
                            {{ item.judul || 'Untitled' }}
                        </h3>
                        <p class="text-gray-600 dark:text-gray-300 text-sm md:text-base line-clamp-3 mb-3 md:mb-4 transition-colors duration-300 ease-in-out">
                            {{ truncateText((item.content || '').replace(/<[^>]*>/g, ''), 100) }}
                        </p>
                        <Link
                            :href="'/berita/' + (item.slug || item.id)"
                            class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium text-sm md:text-base group transition-all duration-300 ease-in-out hover:translate-x-1"
                        >
                            Baca Selengkapnya
                            <svg class="w-4 h-4 ml-1 group-hover:translate-x-2 transition-transform duration-300 ease-in-out" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </Link>
                    </div>
                </article>
            </div>

            <!-- Empty State -->
            <div v-else-if="!isLoadingNews" class="text-center py-8 md:py-12 px-4">
                <svg class="w-12 h-12 md:w-16 md:h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                </svg>
                <h3 class="text-base md:text-lg font-semibold text-gray-900 dark:text-white mb-2">Belum Ada Berita</h3>
                <p class="text-sm md:text-base text-gray-600 dark:text-gray-400">Berita akan ditampilkan di sini setelah dipublikasikan.</p>
            </div>

            <!-- Pagination -->
            <div v-if="!isLoadingNews && totalPages > 1" class="flex flex-col items-center space-y-3 md:space-y-4 transition-all duration-300 ease-in-out px-4">
                <!-- Pagination Controls -->
                <nav class="flex items-center space-x-1 md:space-x-2 transition-all duration-300 ease-in-out" aria-label="Pagination">
                    <!-- Previous Button -->
                    <button
                        @click="prevPage"
                        :disabled="!hasPrevPage"
                        :class="[
                            'flex items-center px-2 md:px-4 py-1.5 md:py-2 rounded-lg text-xs md:text-sm font-medium transition-all duration-300 ease-in-out transform hover:scale-105',
                            hasPrevPage
                                ? 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 border border-gray-300 dark:border-gray-600 hover:shadow-lg'
                                : 'bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500 cursor-not-allowed'
                        ]"
                    >
                        <svg class="w-3 h-3 md:w-4 md:h-4 mr-1 md:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        <span class="hidden sm:inline">Sebelumnya</span>
                        <span class="sm:hidden">Prev</span>
                    </button>

                    <!-- Page Numbers -->
                    <div class="flex items-center space-x-1">
                        <!-- First page if not visible -->
                        <template v-if="visiblePages[0] > 1">
                            <button
                                @click="goToPage(1)"
                                class="w-8 h-8 md:w-10 md:h-10 flex items-center justify-center rounded-lg text-xs md:text-sm font-medium bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 border border-gray-300 dark:border-gray-600 transition-colors"
                            >
                                1
                            </button>
                            <span v-if="visiblePages[0] > 2" class="px-1 md:px-2 text-gray-500 dark:text-gray-400 text-xs md:text-sm">...</span>
                        </template>

                        <!-- Visible page numbers -->
                        <button
                            v-for="page in visiblePages"
                            :key="page"
                            @click="goToPage(page)"
                            :class="[
                                'w-8 h-8 md:w-10 md:h-10 flex items-center justify-center rounded-lg text-xs md:text-sm font-medium transition-colors',
                                page === currentPage
                                    ? 'bg-blue-600 text-white shadow-md'
                                    : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 border border-gray-300 dark:border-gray-600'
                            ]"
                        >
                            {{ page }}
                        </button>

                        <!-- Last page if not visible -->
                        <template v-if="visiblePages[visiblePages.length - 1] < totalPages">
                            <span v-if="visiblePages[visiblePages.length - 1] < totalPages - 1" class="px-1 md:px-2 text-gray-500 dark:text-gray-400 text-xs md:text-sm">...</span>
                            <button
                                @click="goToPage(totalPages)"
                                class="w-8 h-8 md:w-10 md:h-10 flex items-center justify-center rounded-lg text-xs md:text-sm font-medium bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 border border-gray-300 dark:border-gray-600 transition-colors"
                            >
                                {{ totalPages }}
                            </button>
                        </template>
                    </div>

                    <!-- Next Button -->
                    <button
                        @click="nextPage"
                        :disabled="!hasNextPage"
                        :class="[
                            'flex items-center px-2 md:px-4 py-1.5 md:py-2 rounded-lg text-xs md:text-sm font-medium transition-all duration-300 ease-in-out',
                            hasNextPage
                                ? 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 border border-gray-300 dark:border-gray-600'
                                : 'bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500 cursor-not-allowed'
                        ]"
                    >
                        <span class="hidden sm:inline">Selanjutnya</span>
                        <span class="sm:hidden">Next</span>
                        <svg class="w-3 h-3 md:w-4 md:h-4 ml-1 md:ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </nav>
            </div>
        </div>
    </section>
</template>

<style scoped>
/* Enhanced animations and transitions */
@keyframes slide-up {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.news-section {
  animation: slide-up 0.8s ease-out;
}

/* Smooth card hover effects */
article:hover {
  transform: translateY(-8px) scale(1.02);
}

/* Enhanced button transitions */
button, .bg-white, .bg-gray-800 {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Text and color transitions */
h2, h3, p, span, div {
  transition: color 0.3s ease-in-out;
}

/* Background transitions */
.bg-gradient-to-r {
  transition: background 0.3s ease-in-out;
}
</style>
