<template>
  <AdminLayout>

      <div class="p-6 md:p-8 max-w-[1100px] w-full mx-auto pb-10">
        <div v-if="loading" class="flex justify-center items-center py-20">
          <div class="animate-spin rounded-full h-10 w-10 border-4 border-[#054a34] border-t-transparent"></div>
        </div>
        
        <div v-else-if="!employee" class="text-center py-20 text-gray-500">Employee not found</div>
        
        <div v-else class="animate-fade-in space-y-6">
          <!-- Breadcrumbs & Header -->
          <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
              <div class="flex items-center gap-1.5 text-[12px] text-gray-500 mb-1.5 font-medium">
                <router-link to="/directory" class="hover:text-gray-800">Directory</router-link>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-gray-800">Employee Detail</span>
              </div>
              <h1 class="text-[28px] font-bold text-gray-900 tracking-tight leading-none">{{ employee.name }}</h1>
            </div>
            <div class="flex items-center gap-3">
              <router-link :to="`/directory/${employee.id}/edit`" class="flex items-center gap-2 px-4 py-2 bg-[#f8f9f8] border border-gray-200 rounded-lg text-gray-700 text-[13px] font-medium hover:bg-gray-100 transition-colors shadow-sm">
                <span class="material-symbols-outlined text-[16px]">edit</span> Edit Profile
              </router-link>
              <button @click="shareProfile" class="flex items-center gap-2 px-4 py-2 bg-[#054a34] text-white rounded-lg text-[13px] font-medium hover:bg-[#033323] transition-colors shadow-sm">
                <span class="material-symbols-outlined text-[16px]">share</span> Share
              </button>
            </div>
          </div>

          <!-- Main Content Grid -->
          <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-6">
              
              <!-- Hero Card -->
              <div class="bg-white border border-gray-200 rounded-[16px] p-6 shadow-sm flex flex-col md:flex-row gap-6">
                <div class="w-[120px] h-[120px] rounded-full overflow-hidden shrink-0 bg-gray-100 border-[3px] border-white ring-1 ring-gray-200 shadow-sm mx-auto md:mx-0">
                   <img v-if="employee.avatar" :src="employee.avatar" alt="Avatar" class="w-full h-full object-cover">
                   <span v-else class="material-symbols-outlined text-6xl text-gray-400 mt-4 ml-4">person</span>
                </div>
                <div class="flex-1">
                  <div class="flex justify-between items-start mb-1">
                    <h2 class="text-[20px] font-semibold text-gray-900">{{ employee.name }}{{ employee.position && employee.position.toLowerCase().includes('professor') ? ', Ph.D.' : '' }}</h2>
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium border" :class="employee.status === 'active' ? 'bg-[#dcfce7] text-[#166534] border-[#bbf7d0]' : 'bg-gray-100 text-gray-600 border-gray-200'">
                      <span class="w-1.5 h-1.5 rounded-full" :class="employee.status === 'active' ? 'bg-[#166534]' : 'bg-gray-400'"></span>
                      {{ employee.status === 'active' ? 'Active' : 'Inactive' }}
                    </div>
                  </div>
                  <p class="text-[15px] text-[#546e7a] mb-3">{{ employee.position }}, {{ employee.department }}</p>
                  <p class="text-[13.5px] text-gray-600 leading-relaxed mb-4">{{ employee.bio || 'No biography provided.' }}</p>
                  
                  <div class="flex flex-wrap gap-2">
                    <span class="px-2.5 py-1 bg-[#f1f3f2] text-[#455a64] rounded text-[11px] font-medium border border-gray-200">Tenured</span>
                    <span class="px-2.5 py-1 bg-[#f1f3f2] text-[#455a64] rounded text-[11px] font-medium border border-gray-200">Dept. Head</span>
                    <span class="px-2.5 py-1 bg-[#f1f3f2] text-[#455a64] rounded text-[11px] font-medium border border-gray-200">Research Council</span>
                  </div>
                </div>
              </div>

              <!-- Tabs Card -->
              <div class="bg-white border border-gray-200 rounded-[16px] shadow-sm overflow-hidden">
                <div class="flex border-b border-gray-200 px-2 bg-[#fdfdfd]">
                  <button @click="activeTab = 'personal'" class="flex-1 py-3.5 text-[13px] font-medium text-center border-b-2 transition-colors" :class="activeTab === 'personal' ? 'border-[#054a34] text-[#054a34]' : 'border-transparent text-gray-500 hover:text-gray-700'">Personal Info</button>
                  <button @click="activeTab = 'employment'" class="flex-1 py-3.5 text-[13px] font-medium text-center border-b-2 transition-colors" :class="activeTab === 'employment' ? 'border-[#054a34] text-[#054a34]' : 'border-transparent text-gray-500 hover:text-gray-700'">Employment</button>
                  <button @click="activeTab = 'contact'" class="flex-1 py-3.5 text-[13px] font-medium text-center border-b-2 transition-colors" :class="activeTab === 'contact' ? 'border-[#054a34] text-[#054a34]' : 'border-transparent text-gray-500 hover:text-gray-700'">Contact</button>
                </div>
                
                <div class="p-6">
                  <div v-if="activeTab === 'personal'" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-4">
                      <div>
                        <p class="text-[10px] font-semibold text-gray-500 uppercase tracking-widest mb-1.5">Full Legal Name</p>
                        <p class="text-[14px] text-gray-900">{{ employee.name }} {{ employee.gender === 'male' ? 'bin Hassan' : '' }}</p>
                      </div>
                      <div>
                        <p class="text-[10px] font-semibold text-gray-500 uppercase tracking-widest mb-1.5">Preferred Name</p>
                        <p class="text-[14px] text-gray-900">{{ employee.name.split(' ')[0] }}</p>
                      </div>
                      <div>
                        <p class="text-[10px] font-semibold text-gray-500 uppercase tracking-widest mb-1.5">Date of Birth</p>
                        <p class="text-[14px] text-gray-900">{{ employee.dob || '15 October 1982' }}</p>
                      </div>
                      <div>
                        <p class="text-[10px] font-semibold text-gray-500 uppercase tracking-widest mb-1.5">Gender</p>
                        <p class="text-[14px] text-gray-900 capitalize">{{ employee.gender || 'Male' }}</p>
                      </div>
                      <div class="md:col-span-2 bg-gray-50 border border-gray-100 rounded-lg p-4 mt-2 flex items-center justify-between">
                        <div>
                          <p class="text-[10px] font-semibold text-gray-500 uppercase tracking-widest mb-1.5 flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">vpn_key</span> Profile Security Code</p>
                          <p class="text-[12px] text-gray-500">This 5-digit code is required by the employee to submit data change requests.</p>
                        </div>
                        <p class="text-[18px] font-mono text-[#054a34] font-bold bg-[#dcfce7] px-3 py-1 rounded border border-[#bbf7d0] tracking-[0.2em] shadow-sm">{{ employee.unique_code }}</p>
                      </div>
                    </div>
                    <hr class="border-gray-100">
                    <div>
                        <p class="text-[10px] font-semibold text-gray-500 uppercase tracking-widest mb-2">Biography Highlights</p>
                        <p class="text-[14px] text-gray-900 leading-relaxed">{{ employee.bio || 'Ph.D. in Computer Science, University of Technology. M.Sc. in Human-Computer Interaction.' }}</p>
                    </div>
                  </div>
                  
                  <div v-if="activeTab === 'employment'" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                      <div>
                        <p class="text-[10px] font-semibold text-gray-500 uppercase tracking-widest mb-1.5">NIP / Employee ID</p>
                        <p class="text-[14px] text-gray-900">{{ employee.nip }}</p>
                      </div>
                      <div>
                        <p class="text-[10px] font-semibold text-gray-500 uppercase tracking-widest mb-1.5">Hire Date</p>
                        <p class="text-[14px] text-gray-900">{{ employee.hire_date || '-' }}</p>
                      </div>
                      <div>
                        <p class="text-[10px] font-semibold text-gray-500 uppercase tracking-widest mb-1.5">Department</p>
                        <p class="text-[14px] text-gray-900">{{ employee.department }}</p>
                      </div>
                      <div>
                        <p class="text-[10px] font-semibold text-gray-500 uppercase tracking-widest mb-1.5">Position</p>
                        <p class="text-[14px] text-gray-900">{{ employee.position }}</p>
                      </div>
                  </div>

                  <div v-if="activeTab === 'contact'" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                      <div>
                        <p class="text-[10px] font-semibold text-gray-500 uppercase tracking-widest mb-1.5">Email Address</p>
                        <p class="text-[14px] text-gray-900">{{ employee.email }}</p>
                      </div>
                      <div>
                        <p class="text-[10px] font-semibold text-gray-500 uppercase tracking-widest mb-1.5">Phone Number</p>
                        <p class="text-[14px] text-gray-900">{{ employee.phone || '-' }}</p>
                      </div>
                      <div class="md:col-span-2">
                        <p class="text-[10px] font-semibold text-gray-500 uppercase tracking-widest mb-1.5">Location / Office</p>
                        <p class="text-[14px] text-gray-900">{{ employee.location || '-' }}</p>
                      </div>
                  </div>
                </div>
              </div>

              <!-- Request History Table Mock -->
              <div class="bg-white border border-gray-200 rounded-[16px] shadow-sm p-6">
                <div class="flex justify-between items-center mb-5">
                  <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-gray-600 text-[20px]">history</span>
                    <h3 class="text-[16px] font-semibold text-gray-900">Request History & Updates</h3>
                  </div>
                  <a href="#" class="text-[12px] font-medium text-gray-600 hover:text-gray-900">View All</a>
                </div>

                <div class="overflow-x-auto">
                  <table class="w-full text-left border-collapse min-w-[600px]">
                    <thead>
                      <tr class="border-b border-gray-200 text-[12px] text-gray-500">
                        <th class="py-2.5 font-medium w-1/5">Date</th>
                        <th class="py-2.5 font-medium w-1/4">Type</th>
                        <th class="py-2.5 font-medium w-2/5">Description</th>
                        <th class="py-2.5 font-medium w-auto">Status</th>
                      </tr>
                    </thead>
                    <tbody class="text-[13px]">
                      <tr class="border-b border-gray-100">
                        <td class="py-3.5 text-gray-700">Oct 12, 2024</td>
                        <td class="py-3.5 text-gray-700">Profile Update</td>
                        <td class="py-3.5 text-gray-700 pr-2">Updated biography and office location.</td>
                        <td class="py-3.5"><span class="px-2.5 py-0.5 bg-[#dcfce7] text-[#166534] rounded-full text-[11px] font-medium border border-[#bbf7d0]">Approved</span></td>
                      </tr>
                      <tr class="border-b border-gray-100">
                        <td class="py-3.5 text-gray-700">Sep 05, 2024</td>
                        <td class="py-3.5 text-gray-700">Visibility Change</td>
                        <td class="py-3.5 text-gray-700 pr-2">Set mobile phone number to private.</td>
                        <td class="py-3.5"><span class="px-2.5 py-0.5 bg-[#dcfce7] text-[#166534] rounded-full text-[11px] font-medium border border-[#bbf7d0]">Approved</span></td>
                      </tr>
                      <tr>
                        <td class="py-3.5 text-gray-700">Aug 20, 2024</td>
                        <td class="py-3.5 text-gray-700">Title Change</td>
                        <td class="py-3.5 text-gray-700 pr-2">Promoted to Associate Professor.</td>
                        <td class="py-3.5"><span class="px-2.5 py-0.5 bg-[#f1f3f2] text-[#455a64] rounded-full text-[11px] font-medium border border-gray-200">System Automated</span></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
              
              <!-- Public Profile QR Card -->
              <div class="bg-white border border-gray-200 rounded-[16px] shadow-sm p-6">
                <div class="flex items-center gap-2 mb-3">
                  <span class="material-symbols-outlined text-gray-800 text-[20px]">qr_code_scanner</span>
                  <h3 class="text-[16px] font-semibold text-gray-900">Public Profile QR</h3>
                </div>
                <p class="text-[13px] text-gray-600 mb-5 leading-relaxed">Scan this code to view the public-facing faculty directory profile for {{ employee.name }}.</p>
                
                <div class="bg-white border border-gray-200 rounded-xl p-6 flex justify-center mb-5 shadow-sm">
                  <img :src="`https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(publicLink)}`" alt="QR Code" class="w-36 h-36" />
                </div>

                <div class="mb-5">
                  <label class="block text-[11px] font-medium text-gray-500 mb-1.5">Direct Link</label>
                  <div class="flex">
                    <input type="text" readonly :value="publicLink" class="flex-1 bg-white border border-gray-200 border-r-0 rounded-l-md py-1.5 px-2.5 text-[12px] text-gray-700 focus:outline-none truncate">
                    <button @click="copyToClipboard" class="bg-[#f8f9f8] border border-gray-200 rounded-r-md px-2.5 flex items-center justify-center hover:bg-gray-100 transition-colors">
                      <span class="material-symbols-outlined text-[16px] text-gray-500">content_copy</span>
                    </button>
                  </div>
                  <span v-if="copied" class="text-[11px] text-[#054a34] mt-1 block font-medium">Copied to clipboard!</span>
                </div>

                <div class="space-y-2.5">
                  <button @click="downloadQR('png')" :disabled="downloading" class="w-full flex items-center justify-center gap-2 bg-[#f8f9f8] border border-gray-200 text-gray-800 py-2 px-4 rounded-lg text-[13px] font-medium hover:bg-gray-100 transition-colors shadow-sm disabled:opacity-50">
                    <span class="material-symbols-outlined text-[16px]">{{ downloading ? 'hourglass_empty' : 'image' }}</span>
                    {{ downloading ? 'Downloading...' : 'Download PNG' }}
                  </button>
                  <button @click="downloadQR('svg')" :disabled="downloading" class="w-full flex items-center justify-center gap-2 bg-[#f8f9f8] border border-gray-200 text-gray-800 py-2 px-4 rounded-lg text-[13px] font-medium hover:bg-gray-100 transition-colors shadow-sm disabled:opacity-50">
                    <span class="material-symbols-outlined text-[16px]">polyline</span>
                    Download SVG
                  </button>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
      
      <!-- Footer -->
      <footer class="py-6 px-8 border-t border-gray-200 flex flex-col items-center justify-center text-[12px] text-gray-500 mt-auto shrink-0 bg-[#f8f9f8]">
        <p>© {{ new Date().getFullYear() }} Universitas Islam Internasional Darullughah Wadda'wah</p>
      </footer>
  </AdminLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';
import AdminLayout from '../layouts/AdminLayout.vue';

const route = useRoute();
const employee = ref(null);
const loading = ref(true);
const activeTab = ref('personal');
const copied = ref(false);
const downloading = ref(false);

const fetchEmployeeData = async () => {
  try {
    const res = await axios.get(`/api/employees/${route.params.id}`);
    employee.value = res.data;
  } catch (err) {
    console.error("Error fetching data:", err);
  } finally {
    loading.value = false;
  }
};

const publicLink = computed(() => {
  if (!employee.value) return '';
  return `${window.location.origin}/public/${employee.value.id}`;
});

const copyToClipboard = () => {
  navigator.clipboard.writeText(publicLink.value);
  copied.value = true;
  setTimeout(() => { copied.value = false; }, 2000);
};

const shareProfile = async () => {
  if (navigator.share) {
    try {
      await navigator.share({
        title: `${employee.value.name}'s Public Profile`,
        url: publicLink.value
      });
    } catch (err) {
      console.error("Error sharing:", err);
    }
  } else {
    copyToClipboard();
  }
};

const downloadQR = async (format) => {
  if (!employee.value) return;
  downloading.value = true;
  try {
    const url = `https://api.qrserver.com/v1/create-qr-code/?size=500x500&data=${encodeURIComponent(publicLink.value)}&format=${format}`;
    const response = await fetch(url);
    const blob = await response.blob();
    const blobUrl = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = blobUrl;
    a.download = `${employee.value.name.replace(/\s+/g, '_')}_QR.${format}`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(blobUrl);
  } catch (err) {
    console.error("Error downloading QR:", err);
  } finally {
    downloading.value = false;
  }
};

onMounted(() => {
  fetchEmployeeData();
});
</script>

<style scoped>
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in {
  animation: fadeIn 0.4s ease-out forwards;
}

/* Hide scrollbar for Chrome, Safari and Opera */
.no-scrollbar::-webkit-scrollbar {
  display: none;
}

/* Hide scrollbar for IE, Edge and Firefox */
.no-scrollbar {
  -ms-overflow-style: none;  /* IE and Edge */
  scrollbar-width: none;  /* Firefox */
}
</style>
