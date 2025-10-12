<script setup>
import { ref, onMounted, computed } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import LandingLayout from "@/Layouts/LandingLayout.vue";
// replace axios with centralized api
import api from '@/lib/axios';

const props = defineProps({
  slug: {
    type: String,
    required: true,
  },
});

// News data state
const article = ref(null);
const relatedNews = ref([]);
const recommendedNews = ref([]);
const isLoading = ref(true);

// helper: normalisasi thumbnail
const normalizeThumbnail = (thumb) => {
  if (!thumb) return "";
  try {
    const url = new URL(thumb, window.location.origin);
    return url.href;
  } catch {
    return thumb;
  }
};

// Fetch article details from API
const fetchArticle = async () => {
  try {
    isLoading.value = true;
    const response = await api.get(`/api/berita/${props.slug}`);
    const item = response.data;
    article.value = { ...item, thumbnail: normalizeThumbnail(item.thumbnail) };

    // Fetch related news and recommendations
    if (response.data.kategori_id) {
      await fetchRelatedNews(response.data.kategori_id);
    }
    await fetchRecommendedNews();
  } catch (error) {
    console.error("Error fetching article:", error);
    // Fallback to sample data if API fails
    article.value = getSampleArticle();
  } finally {
    isLoading.value = false;
  }
};

// Fetch related news
const fetchRelatedNews = async (categoryId) => {
  try {
    const response = await api.get("/api/berita");
    const all = Array.isArray(response.data) ? response.data : response.data?.data || [];
    relatedNews.value = all
      .filter(
        (item) =>
          item.status === "published" &&
          item.kategori_id === categoryId &&
          item.slug !== props.slug
      )
      .slice(0, 3)
      .map(i => ({ ...i, thumbnail: normalizeThumbnail(i.thumbnail) }));
  } catch (error) {
    console.error("Error fetching related news:", error);
    relatedNews.value = [];
  }
};

// Fetch recommended news (latest news excluding current article)
const fetchRecommendedNews = async () => {
  try {
    const response = await api.get("/api/berita");
    const all = Array.isArray(response.data) ? response.data : response.data?.data || [];
    recommendedNews.value = all
      .filter(
        (item) =>
          item.status === "published" &&
          item.slug !== props.slug
      )
      .sort((a, b) => new Date(b.published_at || b.created_at) - new Date(a.published_at || a.created_at))
      .slice(0, 5)
      .map(i => ({ ...i, thumbnail: normalizeThumbnail(i.thumbnail) }));
  } catch (error) {
    console.error("Error fetching recommended news:", error);
    // Fallback to sample recommendations
    recommendedNews.value = getSampleRecommendations().map(i => ({ ...i, thumbnail: normalizeThumbnail(i.thumbnail) }));
  }
};

// Sample article data as fallback
const getSampleArticle = () => ({
  id: 1,
  judul: "Reuni Akbar Alumni Sekolah Vokasi UNS 2025",
  slug: "reuni-akbar-alumni-sekolah-vokasi-uns-2025",
  content: `
        <p>Sekolah Vokasi Universitas Sebelas Maret (UNS) akan menggelar acara Reuni Akbar Alumni 2025 yang direncanakan akan berlangsung pada tanggal 15 Februari 2025 di kampus Sekolah Vokasi UNS.</p>

        <p>Acara ini merupakan momen bersejarah yang akan mempertemukan ribuan alumni dari berbagai angkatan dan program studi. Reuni akbar ini diharapkan dapat memperkuat tali silaturahmi antar alumni serta membangun jejaring yang lebih solid untuk kemajuan bersama.</p>

        <h3>Rangkaian Acara</h3>
        <p>Reuni Akbar Alumni 2025 akan menghadirkan berbagai kegiatan menarik, antara lain:</p>
        <ul>
            <li>Upacara pembukaan dan sambutan dari Direktur Sekolah Vokasi UNS</li>
            <li>Talkshow dengan tema "Alumni Sukses: Inspirasi dan Motivasi"</li>
            <li>Pameran karya dan produk alumni</li>
            <li>Bazar kuliner khas daerah</li>
            <li>Pertunjukan seni dan budaya</li>
            <li>Malam gala dinner dan hiburan</li>
        </ul>

        <h3>Pendaftaran dan Informasi</h3>
        <p>Alumni yang ingin mengikuti acara ini dapat mendaftar melalui website resmi Sekolah Vokasi UNS atau menghubungi panitia penyelenggara. Biaya pendaftaran sangat terjangkau dan sudah termasuk konsumsi, merchandise, dan doorprize menarik.</p>

        <p>Untuk informasi lebih lanjut, silakan hubungi:</p>
        <ul>
            <li>Email: alumni@vokasi.uns.ac.id</li>
            <li>Telepon: (0271) 647-481</li>
            <li>WhatsApp: 0812-3456-7890</li>
        </ul>
    `,
  thumbnail:
    "https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=800&h=400&fit=crop&crop=center",
  kategori: { nama: "Acara", id: 1 },
  user: { name: "Admin Sekolah Vokasi" },
  published_at: "2025-01-15T10:00:00.000000Z",
  created_at: "2025-01-15T10:00:00.000000Z",
});

// Sample recommendations data as fallback
const getSampleRecommendations = () => [
  {
    id: 2,
    judul: "Workshop Digital Marketing untuk Alumni",
    thumbnail: "https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=100&h=80&fit=crop&crop=center",
    kategori: { nama: "Workshop", id: 2 },
    published_at: "2025-01-14T10:00:00.000000Z",
  },
  {
    id: 3,
    judul: "Lowongan Kerja Terbaru untuk Alumni",
    thumbnail: "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=80&fit=crop&crop=center",
    kategori: { nama: "Karir", id: 3 },
    published_at: "2025-01-13T10:00:00.000000Z",
  },
  {
    id: 4,
    judul: "Beasiswa S2 untuk Alumni Berprestasi",
    thumbnail: "https://images.unsplash.com/photo-1434030216411-0b793f4b4173?w=100&h=80&fit=crop&crop=center",
    kategori: { nama: "Beasiswa", id: 4 },
    published_at: "2025-01-12T10:00:00.000000Z",
  },
  {
    id: 5,
    judul: "Pengumuman Jadwal Kuliah Tamu",
    thumbnail: "https://images.unsplash.com/photo-1557804506-669a67965ba0?w=100&h=80&fit=crop&crop=center",
    kategori: { nama: "Pengumuman", id: 5 },
    published_at: "2025-01-11T10:00:00.000000Z",
  },
];

// Format date function
const formatDate = (dateString) => {
  if (!dateString) return "";
  const date = new Date(dateString);
  return date.toLocaleDateString("id-ID", {
    weekday: "long",
    day: "numeric",
    month: "long",
    year: "numeric",
  });
};

// Get category color based on category name
const getCategoryColor = (categoryName) => {
  const colors = {
    Acara: "bg-blue-600",
    Karir: "bg-green-600",
    Workshop: "bg-purple-600",
    Pengumuman: "bg-orange-600",
    Beasiswa: "bg-yellow-600",
  };
  return colors[categoryName] || "bg-gray-600";
};

// Computed page title
const pageTitle = computed(() => {
  return article.value
    ? article.value.judul
    : "Detail Berita - Keluarga Alumni Sekolah Vokasi UNS";
});

// Fetch article on component mount
onMounted(() => {
  fetchArticle();
});
</script>

<template>
  <Head :title="pageTitle" />

  <LandingLayout>
    <!-- Main Content -->
    <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-12 2xl:px-16 py-8">
      <!-- Loading State -->
      <div v-if="isLoading" class="animate-pulse max-w-7xl mx-auto">
        <div class="h-8 bg-gray-300 dark:bg-gray-600 rounded w-3/4 mb-4"></div>
        <div class="h-64 bg-gray-300 dark:bg-gray-600 rounded-xl mb-6"></div>
        <div class="space-y-4">
          <div class="h-4 bg-gray-300 dark:bg-gray-600 rounded w-full"></div>
          <div class="h-4 bg-gray-300 dark:bg-gray-600 rounded w-5/6"></div>
          <div class="h-4 bg-gray-300 dark:bg-gray-600 rounded w-4/6"></div>
        </div>
      </div>

      <!-- Article Content -->
      <div v-else-if="article" class="max-w-7xl mx-auto grid lg:grid-cols-4 gap-8">
        <!-- Main Article -->
        <article class="lg:col-span-3">
          <!-- Breadcrumb -->
          <nav class="flex mb-6 text-sm" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
              <li class="inline-flex items-center">
                <Link
                  href="/"
                  class="text-gray-500 hover:text-blue-600 dark:text-gray-400"
                >
                  Beranda
                </Link>
              </li>
              <li>
                <div class="flex items-center">
                  <svg
                    class="w-3 h-3 text-gray-400 mx-1"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                  >
                    <path
                      fill-rule="evenodd"
                      d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                      clip-rule="evenodd"
                    ></path>
                  </svg>
                  <Link
                    href="/berita"
                    class="text-gray-500 hover:text-blue-600 dark:text-gray-400"
                  >
                    Berita
                  </Link>
                </div>
              </li>
              <li>
                <div class="flex items-center">
                  <svg
                    class="w-3 h-3 text-gray-400 mx-1"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                  >
                    <path
                      fill-rule="evenodd"
                      d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                      clip-rule="evenodd"
                    ></path>
                  </svg>
                  <span
                    class="text-gray-700 dark:text-gray-300"
                    >{{ article.judul }}</span
                  >
                </div>
              </li>
            </ol>
          </nav>

          <!-- Article Header -->
          <header class="mb-8">
            <div class="flex items-center gap-3 mb-4">
              <span
                :class="[
                  'px-3 py-1 rounded-full text-white text-sm font-medium',
                  getCategoryColor(article.kategori?.nama),
                ]"
              >
                {{ article.kategori?.nama || "Berita" }}
              </span>
            </div>

            <h1
              class="text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white mb-4 leading-tight"
            >
              {{ article.judul }}
            </h1>

            <div class="flex items-center gap-6 text-sm text-gray-600 dark:text-gray-400">
              <div class="flex items-center gap-2">
                <svg
                  class="w-4 h-4"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                  />
                </svg>
                <span>{{ article.user?.name || "Admin" }}</span>
              </div>
              <div class="flex items-center gap-2">
                <svg
                  class="w-4 h-4"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                  />
                </svg>
                <span>
                  {{ formatDate(article.published_at || article.created_at) }}
                </span>
              </div>
            </div>
          </header>

          <!-- Featured Image -->
          <div class="mb-8">
            <img
              :src="
                article.thumbnail ||
                'https://images.unsplash.com/photo-1585829365295-ab7cd400c167?w=800&h=400&fit=crop&crop=center'
              "
              :alt="article.judul"
              class="w-full h-80 object-cover rounded-xl shadow-lg"
              @error="
                $event.target.src =
                  'https://images.unsplash.com/photo-1585829365295-ab7cd400c167?w=800&h=400&fit=crop&crop=center'
              "
            />
          </div>

          <!-- Article Content -->
          <div class="prose prose-lg dark:prose-invert max-w-none">
            <div
              v-html="article.content"
              class="text-gray-700 dark:text-gray-300 leading-relaxed"
            ></div>
          </div>

          <!-- Share Section -->
          <div class="mt-12 pt-8 border-t border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
              Bagikan Artikel
            </h3>
            <div class="flex items-center space-x-4">
              <button
                class="flex items-center space-x-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors"
              >
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                  <path
                    d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"
                  />
                </svg>
                <span>Twitter</span>
              </button>
              <button
                class="flex items-center space-x-2 bg-blue-800 hover:bg-blue-900 text-white px-4 py-2 rounded-lg transition-colors"
              >
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                  <path
                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"
                  />
                </svg>
                <span>Facebook</span>
              </button>
              <button
                class="flex items-center space-x-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors"
              >
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                  <path
                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"
                  />
                </svg>
                <span>WhatsApp</span>
              </button>
            </div>
          </div>
        </article>

        <!-- Sidebar -->
        <aside class="lg:col-span-1 space-y-6">
          <!-- Related News -->
          <div
            v-if="relatedNews.length > 0"
            class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg"
          >
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
              <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              Berita Terkait
            </h3>
            <div class="space-y-4">
              <article v-for="item in relatedNews" :key="item.id" class="group">
                <Link :href="`/berita/${item.slug}`" class="block">
                  <div class="flex gap-3">
                    <img
                      :src="
                        item.thumbnail ||
                        'https://images.unsplash.com/photo-1585829365295-ab7cd400c167?w=100&h=80&fit=crop&crop=center'
                      "
                      :alt="item.judul"
                      class="w-20 h-16 object-cover rounded-lg"
                    />
                    <div class="flex-1">
                      <span
                        :class="[
                          'inline-block px-2 py-1 rounded text-xs font-medium text-white mb-1',
                          getCategoryColor(item.kategori?.nama),
                        ]"
                      >
                        {{ item.kategori?.nama || "Berita" }}
                      </span>
                      <h4
                        class="text-sm font-medium text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 line-clamp-2 transition-colors"
                      >
                        {{ item.judul }}
                      </h4>
                      <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        {{ formatDate(item.published_at || item.created_at) }}
                      </p>
                    </div>
                  </div>
                </Link>
              </article>
            </div>
          </div>

          <!-- Recommended News -->
          <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
              <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
              </svg>
              Rekomendasi Berita
            </h3>
            <div class="space-y-4">
              <article v-for="(item, index) in recommendedNews" :key="item.id" class="group">
                <Link :href="`/berita/${item.slug}`" class="block">
                  <div class="flex gap-3">
                    <div class="relative">
                      <img
                        :src="
                          item.thumbnail ||
                          'https://images.unsplash.com/photo-1585829365295-ab7cd400c167?w=100&h=80&fit=crop&crop=center'
                        "
                        :alt="item.judul"
                        class="w-20 h-16 object-cover rounded-lg"
                      />
                      <div class="absolute -top-2 -left-2 bg-green-600 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center">
                        {{ index + 1 }}
                      </div>
                    </div>
                    <div class="flex-1">
                      <span
                        :class="[
                          'inline-block px-2 py-1 rounded text-xs font-medium text-white mb-1',
                          getCategoryColor(item.kategori?.nama),
                        ]"
                      >
                        {{ item.kategori?.nama || "Berita" }}
                      </span>
                      <h4
                        class="text-sm font-medium text-gray-900 dark:text-white group-hover:text-green-600 dark:group-hover:text-green-400 line-clamp-2 transition-colors"
                      >
                        {{ item.judul }}
                      </h4>
                      <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        {{ formatDate(item.published_at || item.created_at) }}
                      </p>
                    </div>
                  </div>
                </Link>
              </article>
            </div>
          </div>

          <!-- Back to News -->
          <div>
            <Link
              href="/berita"
              class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-3 px-4 rounded-lg font-medium transition-colors"
            >
              ← Kembali ke Semua Berita
            </Link>
          </div>
        </aside>
      </div>

      <!-- Error State -->
      <div v-else class="text-center py-12 max-w-7xl mx-auto">
        <svg
          class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
          />
        </svg>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
          Berita Tidak Ditemukan
        </h3>
        <p class="text-gray-600 dark:text-gray-400 mb-4">
          Maaf, berita yang Anda cari tidak dapat ditemukan.
        </p>
        <Link
          href="/berita"
          class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors"
        >
          ← Kembali ke Semua Berita
        </Link>
      </div>
    </div>
  </LandingLayout>
</template>

<style scoped>
.prose h3 {
  @apply text-xl font-bold text-gray-900 dark:text-white mt-8 mb-4;
}

.prose ul {
  @apply list-disc pl-6 mb-4;
}

.prose li {
  @apply mb-2;
}

.prose p {
  @apply mb-4;
}
</style>
