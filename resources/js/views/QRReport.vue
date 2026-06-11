<template>
  <div class="bg-gray-50/50 h-screen overflow-y-auto p-4 sm:p-8 text-black print:bg-white print:p-4 print:h-auto print:overflow-visible">
    <!-- Action Bar (Hidden when printing) -->
    <div class="print:hidden mb-8 flex flex-col sm:flex-row justify-between items-center bg-white/80 backdrop-blur-xl border border-white/40 shadow-lg p-6 rounded-[24px] gap-4">
      <div>
        <h1 class="text-[26px] font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-[#054a34] to-[#0a7a58] tracking-tight">Employee QR Reports</h1>
        <p class="text-gray-500 text-[14px] mt-1 font-medium">Generate and print QR codes for all employees.</p>
      </div>
      <div class="flex gap-3">
        <button @click="closeWindow" class="px-5 py-2.5 bg-white border border-gray-200 text-gray-700 font-bold rounded-xl hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm focus:ring-2 focus:ring-gray-200 focus:outline-none">
          Close
        </button>
        <button @click="printReport" class="px-5 py-2.5 bg-gradient-to-r from-[#054a34] to-[#076b4a] text-white font-bold rounded-xl hover:shadow-lg hover:shadow-emerald-900/20 hover:-translate-y-0.5 transition-all duration-300 flex items-center gap-2 border border-[#043d2b]/20 focus:ring-2 focus:ring-[#054a34] focus:outline-none">
          <span class="material-symbols-outlined text-[20px]">print</span> 
          Print Report
        </button>
      </div>
    </div>

    <!-- Report Header (Visible only when printing or as preview) -->
    <div class="text-center mb-10 pb-6 border-b-2 border-gray-800">
      <h1 class="text-[24px] font-extrabold uppercase tracking-wide mb-1 text-gray-900">Universitas Islam Internasional Darullughah Wadda'wah</h1>
      <h2 class="text-[18px] font-semibold text-gray-600 tracking-wider">Official Employee QR Directory</h2>
    </div>

    <div v-if="loading" class="flex flex-col items-center justify-center py-20 text-gray-500">
      <span class="material-symbols-outlined animate-spin text-[40px] mb-4 text-[#054a34]">progress_activity</span>
      <p class="font-medium">Generating QR Codes...</p>
    </div>
    
    <!-- QR Grid -->
    <div v-else class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 print:grid-cols-4 print:gap-4 pb-12">
      <div v-for="emp in employees" :key="emp.id" class="bg-white border border-gray-100 rounded-[20px] p-6 flex flex-col items-center text-center break-inside-avoid shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 print:border-2 print:border-gray-200 print:shadow-none print:transform-none">
        <h3 class="font-bold text-[16px] text-gray-900 leading-tight mb-1 tracking-tight">{{ emp.name }}</h3>
        <p class="text-[13.5px] text-[#054a34]/80 mb-3 font-semibold">{{ emp.position }}</p>
        <p class="text-[11px] font-bold tracking-widest text-emerald-800 uppercase bg-emerald-50 px-3 py-1 rounded-full mb-5 border border-emerald-100">{{ emp.nip }}</p>
        
        <div class="p-3 bg-white border border-gray-100 rounded-2xl shadow-sm mb-3 group relative overflow-hidden ring-1 ring-black/5 print:ring-gray-300 print:p-2">
           <img :src="getQRCodeUrl(emp.id)" alt="QR Code" class="w-32 h-32 object-contain group-hover:scale-105 transition-transform duration-300" crossorigin="anonymous" />
        </div>
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Scan for Profile</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const employees = ref([]);
const loading = ref(true);

const fetchEmployees = async () => {
  try {
    const res = await axios.get('/api/employees');
    // The API Resource returns a paginated list by default, but we need all for the report.
    // However, if the API currently returns paginated data (e.g. data array inside res.data), we handle it.
    let data = res.data.data ? res.data.data : res.data;
    employees.value = data;
  } catch (error) {
    console.error("Error fetching employees for report:", error);
  } finally {
    loading.value = false;
  }
};

const getQRCodeUrl = (id) => {
  const profileUrl = `${window.location.origin}/public/${id}`;
  // Using an external QR Code API
  return `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${encodeURIComponent(profileUrl)}&margin=10`;
};

const printReport = () => {
  window.print();
};

const closeWindow = () => {
  // If opened in a new tab, this will close it.
  window.close();
  // Fallback if window.close is blocked by browser for tabs not opened by script
  window.history.back();
};

onMounted(() => {
  fetchEmployees();
});
</script>

<style>
@media print {
  body {
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
    background-color: white !important;
  }
  @page {
    margin: 1.5cm;
  }
}
</style>
