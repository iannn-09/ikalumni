<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import DonutChart from '@/Components/ui/chart-donut/DonutChart.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import axios from 'axios';

const breadcrumbs = [
  { title: 'Dashboard' }
];

// Dashboard statistics data
const stats = ref({
  totalNews: 0,
  totalUsers: 0,
  totalAlumni: 0,
  totalCategories: 0
});

const newsByCategory = ref([]);
const loading = ref(true);
const chartLoading = ref(true);

// Fetch dashboard statistics
const fetchDashboardData = async () => {
  try {
    loading.value = true;
    const response = await axios.get('/api/dashboard/stats');
    stats.value = response.data;
  } catch (error) {
    console.error('Error fetching dashboard stats:', error);
    // Fallback to mock data
    stats.value = {
      totalNews: 45,
      totalUsers: 128,
      totalAlumni: 324,
      totalCategories: 8
    };
  } finally {
    loading.value = false;
  }
};

// Fetch news by category for chart
const fetchNewsByCategory = async () => {
  try {
    chartLoading.value = true;
    console.log('Fetching news by category...');

    const response = await axios.get('/api/dashboard/news-by-category');
    console.log('API Response:', response.data);

    // Ensure the data has the correct structure for shadcn-vue chart
    if (Array.isArray(response.data)) {
      newsByCategory.value = response.data.map(item => ({
        name: item.name || 'Unknown',
        value: parseInt(item.value) || 0,
        color: item.color || '#6B7280'
      }));
    } else {
      console.warn('Invalid data format received:', response.data);
      newsByCategory.value = [];
    }

    console.log('Processed data:', newsByCategory.value);
  } catch (error) {
    console.error('Error fetching news by category:', error);

    // Check if it's a network error or API error
    if (error.response) {
      console.error('API Error:', error.response.status, error.response.data);
    }

    // Fallback to mock data only if API fails
    newsByCategory.value = [
      { name: 'Teknologi', value: 15, color: '#3B82F6' },
      { name: 'Pendidikan', value: 12, color: '#10B981' },
      { name: 'Karir', value: 8, color: '#8B5CF6' },
      { name: 'Event', value: 6, color: '#F59E0B' },
      { name: 'Alumni', value: 4, color: '#EF4444' }
    ];
  } finally {
    chartLoading.value = false;
  }
};

onMounted(() => {
  fetchDashboardData();
  fetchNewsByCategory();
});
</script>

<template>
    <Head title="Dashboard" />

    <DashboardLayout :breadcrumbs="breadcrumbs">
        <div class="py-6">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- Welcome Section -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Dashboard</h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">Selamat datang di panel admin IK Alumni</p>
                </div>

                <!-- Loading State -->
                <div v-if="loading" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    <div v-for="i in 4" :key="i" class="animate-pulse">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                            <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-3/4 mb-4"></div>
                            <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-1/2"></div>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div v-else class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
                    <!-- Total Berita -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Berita</h3>
                                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.totalNews }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Users -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-.5a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Users</h3>
                                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.totalUsers }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Alumni -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Alumni</h3>
                                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.totalAlumni }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Categories -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Kategori</h3>
                                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.totalCategories }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chart Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <DonutChart
                        :data="newsByCategory"
                        title="Distribusi Berita per Kategori"
                        :show-legend="true"
                        :show-tooltip="false"
                        type="donut"
                        v-if="!chartLoading && newsByCategory.length > 0"
                    />

                    <!-- Chart Loading State -->
                    <div v-if="chartLoading" class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <div class="animate-pulse">
                            <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-1/2 mb-4"></div>
                            <div class="h-64 bg-gray-200 dark:bg-gray-700 rounded"></div>
                        </div>
                    </div>

                    <!-- No Data State -->
                    <DonutChart
                        :data="[]"
                        title="Distribusi Berita per Kategori"
                        v-if="!chartLoading && newsByCategory.length === 0"
                    />

                    <!-- Additional chart placeholder -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Chart Lainnya</h3>
                        <div class="h-64 flex items-center justify-center border-2 border-dashed border-gray-300 dark:border-gray-600 rounded">
                            <p class="text-gray-500 dark:text-gray-400">Chart akan ditambahkan di sini</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>
