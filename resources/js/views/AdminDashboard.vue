<template>
  <AdminLayout>
    <!-- Dashboard Canvas -->
    <div class="flex-1 overflow-y-auto no-scrollbar bg-[#f4f7f6] p-6 md:p-10 relative">
      <div class="max-w-7xl mx-auto space-y-8 relative z-10">
        <!-- Page Header & Quick Actions -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-2">
          <div>
            <h2 class="text-[28px] font-extrabold text-gray-900 tracking-tight">System Overview</h2>
            <p class="text-[15px] text-gray-500 mt-1 font-medium">Real-time statistics for UII Dalwa staff directories.</p>
          </div>
          <div class="flex flex-wrap gap-3 mt-4 md:mt-0">
            <button @click="generateReports" class="px-6 py-2.5 bg-white border border-[#054a34]/20 hover:bg-emerald-50 text-[#054a34] font-bold text-[14px] rounded-xl flex items-center justify-center gap-2 transition-all shadow-sm focus:outline-none focus:ring-2 focus:ring-[#054a34]/50">
              <span class="material-symbols-outlined text-[20px]">qr_code_2</span>
              Generate QR Reports
            </button>
            <router-link to="/directory/new" class="px-6 py-2.5 bg-gradient-to-r from-[#054a34] to-[#076b4a] hover:from-[#033323] hover:to-[#054a34] text-white font-bold text-[14px] rounded-xl flex items-center justify-center gap-2 transition-all shadow-md shadow-[#054a34]/20 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-[#054a34]/50">
              <span class="material-symbols-outlined text-[20px]">person_add</span>
              Add Employee
            </router-link>
          </div>
        </div>

        <!-- Key Statistics Bento Grid -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
          <!-- Total Employees (Large Hero Card) -->
          <div class="md:col-span-8 bg-gradient-to-br from-[#054a34] to-[#076b4a] rounded-[24px] p-8 md:p-10 flex flex-col justify-between shadow-lg shadow-emerald-900/10 relative overflow-hidden group">
            <!-- Decorative Backgrounds -->
            <div class="absolute top-0 right-0 w-80 h-80 bg-white/10 rounded-full blur-2xl -translate-y-1/2 translate-x-1/4 group-hover:scale-110 transition-transform duration-700"></div>
            <div class="absolute bottom-0 right-12 w-40 h-40 bg-white/5 rounded-full blur-xl translate-y-1/2 group-hover:scale-110 transition-transform duration-500 delay-100"></div>
            
            <div class="relative z-10">
              <div class="flex items-center gap-2 text-emerald-100/80 mb-3">
                <span class="material-symbols-outlined text-[20px]">badge</span>
                <h3 class="text-[12px] font-bold uppercase tracking-widest">Total Employees</h3>
              </div>
              <div class="text-[64px] font-extrabold text-white leading-none tracking-tight" v-if="!loading">{{ stats.total }}</div>
              <div class="text-[64px] font-extrabold text-white leading-none tracking-tight animate-pulse" v-else>...</div>
            </div>
            
            <div class="relative z-10 mt-10 flex gap-10 border-t border-white/20 pt-8">
              <div>
                <p class="text-[12px] font-semibold text-emerald-100/70 uppercase tracking-wider">Active</p>
                <div class="flex items-center gap-2 mt-1.5">
                  <div class="w-2.5 h-2.5 rounded-full bg-[#10b981] shadow-[0_0_10px_rgba(16,185,129,0.5)]"></div>
                  <p class="text-[24px] font-bold text-white leading-none">{{ stats.active }}</p>
                </div>
              </div>
              <div>
                <p class="text-[12px] font-semibold text-emerald-100/70 uppercase tracking-wider">Inactive</p>
                <div class="flex items-center gap-2 mt-1.5">
                  <div class="w-2.5 h-2.5 rounded-full bg-red-400"></div>
                  <p class="text-[24px] font-bold text-white leading-none">{{ stats.inactive }}</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Visibility Stats (Side Cards) -->
          <div class="md:col-span-4 flex flex-col gap-6">
            <div class="bg-white rounded-[24px] p-7 border border-gray-100 shadow-sm flex-1 flex flex-col justify-center hover:shadow-md transition-shadow">
              <div class="flex justify-between items-start mb-4">
                <div class="p-3 rounded-2xl bg-emerald-50 text-[#054a34]">
                  <span class="material-symbols-outlined text-[28px]">public</span>
                </div>
              </div>
              <h3 class="text-[14px] font-bold text-gray-500 uppercase tracking-widest">Public Profiles</h3>
              <p class="text-[36px] font-extrabold text-gray-900 mt-1 leading-none">{{ stats.public }}</p>
              <div class="mt-5 w-full bg-gray-100 h-2 rounded-full overflow-hidden">
                <div class="bg-[#054a34] h-full rounded-full" :style="{ width: publicPercentage + '%' }"></div>
              </div>
            </div>

            <div class="bg-white rounded-[24px] p-7 border border-gray-100 shadow-sm flex-1 flex flex-col justify-center hover:shadow-md transition-shadow">
              <div class="flex justify-between items-start mb-4">
                <div class="p-3 rounded-2xl bg-gray-50 text-gray-500">
                  <span class="material-symbols-outlined text-[28px]">visibility_off</span>
                </div>
              </div>
              <h3 class="text-[14px] font-bold text-gray-500 uppercase tracking-widest">Hidden Profiles</h3>
              <p class="text-[36px] font-extrabold text-gray-900 mt-1 leading-none">{{ stats.hidden }}</p>
              <div class="mt-5 w-full bg-gray-100 h-2 rounded-full overflow-hidden">
                <div class="bg-gray-400 h-full rounded-full" :style="{ width: hiddenPercentage + '%' }"></div>
              </div>
            </div>
          </div>
        </div>

        <!-- Actionable Queues -->
        <div class="mt-10">
          <h3 class="text-[20px] font-bold text-gray-900 mb-5">Action Center</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Pending Data Changes -->
            <router-link to="/approvals" class="group block bg-white rounded-[20px] border border-gray-100 hover:border-emerald-200 hover:shadow-lg transition-all p-6 md:p-8 relative overflow-hidden">
              <div class="absolute right-0 top-0 h-full w-2 bg-yellow-400"></div>
              <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-4">
                  <div class="w-12 h-12 rounded-full bg-yellow-50 text-yellow-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[24px]">edit_document</span>
                  </div>
                  <h4 class="text-[18px] font-bold text-gray-900">Data Update Approvals</h4>
                </div>
                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-50 text-gray-400 group-hover:bg-[#054a34] group-hover:text-white transition-colors">
                  <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
                </div>
              </div>
              <div class="flex items-end gap-3">
                <!-- Using a placeholder '2' since we don't have pending count in stats yet, but we'll show 0 dynamically if not available -->
                <span class="text-[48px] font-extrabold text-yellow-500 leading-none">0</span>
                <span class="text-[14px] font-medium text-gray-500 pb-2">profiles require review</span>
              </div>
            </router-link>

            <!-- Pending Visibility Requests -->
            <router-link to="/approvals" class="group block bg-white rounded-[20px] border border-gray-100 hover:border-emerald-200 hover:shadow-lg transition-all p-6 md:p-8 relative overflow-hidden">
              <div class="absolute right-0 top-0 h-full w-2 bg-blue-500"></div>
              <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-4">
                  <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[24px]">security</span>
                  </div>
                  <h4 class="text-[18px] font-bold text-gray-900">Visibility Requests</h4>
                </div>
                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-50 text-gray-400 group-hover:bg-[#054a34] group-hover:text-white transition-colors">
                  <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
                </div>
              </div>
              <div class="flex items-end gap-3">
                <span class="text-[48px] font-extrabold text-blue-500 leading-none">0</span>
                <span class="text-[14px] font-medium text-gray-500 pb-2">requests pending</span>
              </div>
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import AdminLayout from '../layouts/AdminLayout.vue';

const router = useRouter();
const stats = ref({
  total: 0,
  active: 0,
  inactive: 0,
  public: 0,
  hidden: 0
});
const loading = ref(true);

const publicPercentage = computed(() => stats.value.total > 0 ? (stats.value.public / stats.value.total) * 100 : 0);
const hiddenPercentage = computed(() => stats.value.total > 0 ? (stats.value.hidden / stats.value.total) * 100 : 0);

const fetchStats = async () => {
  try {
    const response = await axios.get('/api/employees/stats');
    stats.value = response.data;
  } catch (error) {
    console.error("Failed to fetch stats:", error);
  } finally {
    loading.value = false;
  }
};

const generateReports = () => {
  window.open('/reports/qr', '_blank');
};

const handleLogout = async () => {
  try {
    await axios.post('/api/logout');
    router.push('/login');
  } catch (error) {
    console.error("Logout failed:", error);
  }
};

onMounted(() => {
  fetchStats();
});
</script>
