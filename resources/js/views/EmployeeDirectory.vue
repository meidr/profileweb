<template>
  <AdminLayout>
    <div class="p-container-margin-mobile md:p-container-margin-desktop flex-1 flex flex-col gap-gutter">
      <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
          <div>
            <h1 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface">Employee Directory</h1>
            <p class="font-body-md text-body-md text-on-surface-variant mt-1">Manage institutional staff profiles, visibility, and access credentials.</p>
          </div>
          <div class="flex gap-3">
            <button v-if="selectedEmployees.length > 0" @click="bulkDelete" class="h-touch-target px-4 bg-red-50 text-red-600 font-bold text-[14px] border border-red-200 rounded-xl hover:bg-red-600 hover:text-white transition-all flex items-center gap-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500/50">
              <span class="material-symbols-outlined text-[20px]">delete</span>
              Delete Selected ({{ selectedEmployees.length }})
            </button>
            <button @click="exportCSV" class="h-touch-target px-4 bg-white text-[#054a34] font-bold text-[14px] border border-[#054a34]/20 rounded-xl hover:bg-emerald-50 transition-colors flex items-center gap-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#054a34]/50">
              <span class="material-symbols-outlined text-[20px]">download</span>
              Export List
            </button>
          </div>
        </div>

        <!-- Toolbar (Filters & Actions) -->
        <div class="bg-surface-container-lowest border border-subtle rounded-xl p-4 flex flex-col md:flex-row gap-4 justify-between items-center shadow-sm">
          <div class="flex flex-col md:flex-row gap-3 w-full md:w-auto flex-1">
            <!-- Local Search -->
            <div class="relative w-full md:max-w-xs">
              <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
              <input v-model="searchQuery" type="text" class="w-full bg-surface border border-subtle rounded-lg py-2 pl-10 pr-4 font-body-sm text-body-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all h-touch-target" placeholder="Search by Name or NIP..."/>
            </div>
            <!-- Department Filter -->
            <div class="relative w-full md:w-56" ref="deptDropdownRef">
              <div @click="showDeptDropdown = !showDeptDropdown" class="w-full bg-surface border border-subtle rounded-lg py-2 pl-3 pr-8 font-body-sm text-body-sm focus-within:outline-none focus-within:border-primary focus-within:ring-1 focus-within:ring-primary transition-all cursor-pointer flex justify-between items-center h-touch-target">
                <span class="truncate pr-2">{{ filterDepartment || 'All Departments' }}</span>
                <span class="material-symbols-outlined absolute right-2 text-on-surface-variant text-[18px] pointer-events-none">{{ showDeptDropdown ? 'expand_less' : 'expand_more' }}</span>
              </div>
              <div v-if="showDeptDropdown" class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                <div @click="filterDepartment = ''; showDeptDropdown = false" class="px-4 py-2.5 text-[14px] text-gray-700 hover:bg-emerald-50 cursor-pointer">
                  All Departments
                </div>
                <div v-for="dept in departments" :key="dept.id" @click="filterDepartment = dept.name; showDeptDropdown = false" class="px-4 py-2.5 text-[14px] text-gray-900 hover:bg-emerald-50 cursor-pointer border-t border-gray-50">
                  {{ dept.name }}
                </div>
              </div>
            </div>
            <!-- Status Filter -->
            <div class="relative w-full md:w-40">
              <select v-model="filterStatus" class="w-full bg-surface border border-subtle rounded-lg py-2 pl-3 pr-8 font-body-sm text-body-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all appearance-none h-touch-target cursor-pointer bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%2349454f%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%226%209%2012%2015%2018%209%22%3E%3C%2Fpolyline%3E%3C%2Fsvg%3E')] bg-no-repeat bg-[position:right_8px_center] bg-[length:16px_16px]">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>
            </div>
          </div>
          <div class="flex items-center gap-1 border border-gray-200 rounded-xl p-1.5 bg-white shadow-sm">
            <button @click="viewMode = 'list'" :class="viewMode === 'list' ? 'bg-[#054a34] text-white shadow' : 'text-gray-400 hover:text-gray-600 hover:bg-gray-50'" class="w-9 h-9 rounded-lg flex items-center justify-center transition-all focus:outline-none">
              <span class="material-symbols-outlined text-[20px]">view_list</span>
            </button>
            <button @click="viewMode = 'grid'" :class="viewMode === 'grid' ? 'bg-[#054a34] text-white shadow' : 'text-gray-400 hover:text-gray-600 hover:bg-gray-50'" class="w-9 h-9 rounded-lg flex items-center justify-center transition-all focus:outline-none">
              <span class="material-symbols-outlined text-[20px]">grid_view</span>
            </button>
          </div>
        </div>

        <!-- Data Table -->
        <div class="bg-surface-container-lowest border border-subtle rounded-xl shadow-sm overflow-hidden flex flex-col">
          <!-- List View -->
          <div v-if="viewMode === 'list'" class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[800px]">
              <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                  <th class="py-4 px-6 text-[13px] font-bold text-gray-500 uppercase tracking-wider w-12 text-center">
                    <input type="checkbox" v-model="selectAll" class="rounded border-gray-300 text-[#054a34] focus:ring-[#054a34] w-4 h-4 cursor-pointer">
                  </th>
                  <th class="py-4 px-6 text-[13px] font-bold text-gray-500 uppercase tracking-wider">Employee</th>
                  <th class="py-4 px-6 text-[13px] font-bold text-gray-500 uppercase tracking-wider">NIP</th>
                  <th class="py-4 px-6 text-[13px] font-bold text-gray-500 uppercase tracking-wider">Position</th>
                  <th class="py-4 px-6 text-[13px] font-bold text-gray-500 uppercase tracking-wider">Department</th>
                  <th class="py-4 px-6 text-[13px] font-bold text-gray-500 uppercase tracking-wider">Status</th>
                  <th class="py-4 px-6 text-[13px] font-bold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100 bg-white">
                <tr v-if="loading" class="text-center p-4">
                  <td colspan="6" class="py-10 text-gray-500">
                    <span class="material-symbols-outlined animate-spin text-[32px] text-[#054a34]">progress_activity</span>
                  </td>
                </tr>
                <tr v-else-if="paginatedEmployees.length === 0" class="text-center p-4">
                  <td colspan="6" class="py-10 text-gray-500 font-medium">No employees found.</td>
                </tr>
                <tr v-for="emp in paginatedEmployees" :key="emp.id" class="hover:bg-emerald-50/50 transition-colors group">
                  <td class="py-4 px-6 text-center">
                    <input type="checkbox" v-model="selectedEmployees" :value="emp.id" class="rounded border-gray-300 text-[#054a34] focus:ring-[#054a34] w-4 h-4 cursor-pointer">
                  </td>
                  <td class="py-4 px-6">
                    <div class="flex items-center gap-4">
                      <div class="w-11 h-11 rounded-full overflow-hidden bg-gray-100 border border-gray-200 flex-shrink-0">
                        <img v-if="emp.avatar" :src="emp.avatar" alt="Profile" class="w-full h-full object-cover"/>
                        <span v-else class="material-symbols-outlined text-gray-300 w-full h-full flex items-center justify-center bg-gray-50">person</span>
                      </div>
                      <div>
                        <p class="text-[15px] text-gray-900 font-bold">{{ emp.name }}</p>
                        <p class="text-[13px] text-gray-500">{{ emp.email }}</p>
                      </div>
                    </div>
                  </td>
                  <td class="py-4 px-6 text-[14px] text-gray-700 font-medium">{{ emp.nip }}</td>
                  <td class="py-4 px-6 text-[14px] text-gray-700">{{ emp.position }}</td>
                  <td class="py-4 px-6 text-[14px] text-gray-700">{{ emp.department }}</td>
                  <td class="py-4 px-6">
                    <span :class="emp.status === 'active' ? 'bg-[#dcfce7] text-[#166534] border-emerald-200' : 'bg-gray-100 text-gray-600 border-gray-200'" class="inline-flex items-center px-3 py-1 rounded-full text-[12px] font-bold border capitalize">{{ emp.status }}</span>
                  </td>
                  <td class="py-4 px-6 text-right">
                    <div class="flex justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                      <router-link :to="'/directory/' + emp.id" class="w-9 h-9 rounded-lg hover:bg-white hover:shadow text-gray-500 hover:text-[#054a34] flex items-center justify-center transition-all" title="View Detail">
                        <span class="material-symbols-outlined text-[20px]">visibility</span>
                      </router-link>
                      <router-link :to="'/directory/' + emp.id + '/edit'" class="w-9 h-9 rounded-lg hover:bg-white hover:shadow text-gray-500 hover:text-[#054a34] flex items-center justify-center transition-all" title="Edit Profile">
                        <span class="material-symbols-outlined text-[20px]">edit</span>
                      </router-link>
                      <button @click="deleteEmployee(emp.id)" class="w-9 h-9 rounded-lg hover:bg-red-50 hover:shadow text-gray-500 hover:text-red-600 flex items-center justify-center transition-all" title="Delete Profile">
                        <span class="material-symbols-outlined text-[20px]">delete</span>
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Grid View -->
          <div v-if="viewMode === 'grid'" class="p-6 bg-gray-50">
            <div v-if="loading" class="text-center py-10">
              <span class="material-symbols-outlined animate-spin text-[32px] text-[#054a34]">progress_activity</span>
            </div>
            <div v-else-if="paginatedEmployees.length === 0" class="text-center py-10 text-gray-500 font-medium">
              No employees found.
            </div>
            <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
              <div v-for="emp in paginatedEmployees" :key="emp.id" class="bg-white border border-gray-200 rounded-[20px] p-6 hover:shadow-lg hover:border-emerald-200 transition-all group flex flex-col items-center text-center relative">
                <!-- Grid Checkbox -->
                <div class="absolute top-4 left-4 z-10">
                  <input type="checkbox" v-model="selectedEmployees" :value="emp.id" class="rounded border-gray-300 text-[#054a34] focus:ring-[#054a34] w-5 h-5 cursor-pointer shadow-sm">
                </div>

                <div class="relative w-20 h-20 rounded-full overflow-hidden mb-4 border-[3px] border-emerald-50 mt-2">
                  <img v-if="emp.avatar" :src="emp.avatar" alt="Profile" class="w-full h-full object-cover"/>
                  <span v-else class="material-symbols-outlined text-[40px] text-gray-300 w-full h-full flex items-center justify-center bg-gray-50">person</span>
                  <div v-if="emp.status === 'active'" class="absolute bottom-0 right-0 w-4 h-4 bg-emerald-500 border-2 border-white rounded-full"></div>
                </div>
                <h3 class="font-bold text-[16px] text-gray-900 mb-1 leading-tight">{{ emp.name }}</h3>
                <p class="text-[13px] text-emerald-800/80 mb-2 font-medium">{{ emp.position }}</p>
                <span class="text-[11px] font-bold text-gray-400 bg-gray-100 px-2 py-0.5 rounded uppercase tracking-widest mb-4">{{ emp.nip }}</span>
                <p class="text-[13px] text-gray-500 mb-4 line-clamp-1">{{ emp.department }}</p>
                <div class="flex gap-2 w-full mt-auto border-t border-gray-100 pt-5 justify-center">
                  <router-link :to="'/directory/' + emp.id" class="px-4 py-2 bg-emerald-50 text-[#054a34] font-bold text-[13px] rounded-lg hover:bg-[#054a34] hover:text-white transition-colors flex-1">
                    View
                  </router-link>
                  <router-link :to="'/directory/' + emp.id + '/edit'" class="px-4 py-2 bg-gray-50 text-gray-600 font-bold text-[13px] rounded-lg hover:bg-gray-200 transition-colors flex-1">
                    Edit
                  </router-link>
                  <button @click="deleteEmployee(emp.id)" class="px-3 py-2 bg-red-50 text-red-600 font-bold rounded-lg hover:bg-red-600 hover:text-white transition-colors" title="Delete">
                    <span class="material-symbols-outlined text-[18px]">delete</span>
                  </button>
                </div>
              </div>
            </div>
          </div>
          <!-- Pagination -->
          <div class="bg-gray-50 border-t border-gray-200 px-6 py-4 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-[14px] text-gray-500 font-medium">
              Showing <span class="font-bold text-gray-900">{{ paginationStart }}</span> to <span class="font-bold text-gray-900">{{ paginationEnd }}</span> of <span class="font-bold text-gray-900">{{ filteredEmployees.length }}</span> entries
            </p>
            <div class="flex flex-col sm:flex-row items-center gap-4">
              <div class="flex items-center gap-1.5">
                <button @click="currentPage--" :disabled="currentPage === 1" class="w-9 h-9 rounded-lg border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-gray-100 disabled:opacity-50 transition-colors bg-white shadow-sm">
                  <span class="material-symbols-outlined text-[20px]">chevron_left</span>
                </button>
                
                <div class="flex gap-1 overflow-x-auto max-w-[200px] md:max-w-none no-scrollbar">
                  <button v-for="page in totalPages" :key="page" @click="currentPage = page" :class="currentPage === page ? 'bg-[#054a34] text-white border-[#054a34] shadow-md' : 'bg-white border-gray-200 text-gray-600 hover:bg-gray-50'" class="min-w-[36px] h-9 px-2 rounded-lg border font-bold text-[14px] flex items-center justify-center transition-all">
                    {{ page }}
                  </button>
                </div>

                <button @click="currentPage++" :disabled="currentPage === totalPages || totalPages === 0" class="w-9 h-9 rounded-lg border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-gray-100 disabled:opacity-50 transition-colors bg-white shadow-sm">
                  <span class="material-symbols-outlined text-[20px]">chevron_right</span>
                </button>
              </div>

              <!-- Jump to Page Input -->
              <div v-if="totalPages > 1" class="flex items-center gap-2 sm:ml-2 sm:border-l sm:border-gray-300 sm:pl-4">
                <span class="text-[13px] text-gray-500 font-medium whitespace-nowrap">Go to:</span>
                <input type="number" min="1" :max="totalPages" v-model.number="jumpPage" @keyup.enter="goToPage" class="w-14 h-9 border border-gray-200 rounded-lg text-center text-[14px] font-bold focus:ring-[#054a34] focus:border-[#054a34] bg-white shadow-sm appearance-none m-0">
              </div>
            </div>
          </div>
        </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import AdminLayout from '../layouts/AdminLayout.vue';

const employees = ref([]);
const loading = ref(true);
const router = useRouter();

// Filters & Pagination State
const searchQuery = ref('');
const filterDepartment = ref('');
const filterStatus = ref('');
const currentPage = ref(1);
const itemsPerPage = ref(10);
const viewMode = ref('list'); // 'list' or 'grid'

const showDeptDropdown = ref(false);
const deptDropdownRef = ref(null);

const selectedEmployees = ref([]);
const departments = ref([]);

const fetchDepartments = async () => {
  try {
    const res = await axios.get('/api/departments');
    departments.value = res.data;
  } catch (e) {
    console.error("Failed to fetch departments", e);
  }
};

const fetchEmployees = async () => {
  try {
    const res = await axios.get('/api/employees');
    employees.value = res.data;
  } catch(e) {
    console.error(e);
  } finally {
    loading.value = false;
  }
};

const filteredEmployees = computed(() => {
  return employees.value.filter(emp => {
    const matchesSearch = emp.name.toLowerCase().includes(searchQuery.value.toLowerCase()) || 
                          emp.nip.toLowerCase().includes(searchQuery.value.toLowerCase());
    const matchesDept = filterDepartment.value === '' || emp.department === filterDepartment.value;
    const matchesStatus = filterStatus.value === '' || emp.status === filterStatus.value;
    return matchesSearch && matchesDept && matchesStatus;
  });
});

const totalPages = computed(() => Math.ceil(filteredEmployees.value.length / itemsPerPage.value));

const jumpPage = ref(1);

const goToPage = () => {
  if (jumpPage.value >= 1 && jumpPage.value <= totalPages.value) {
    currentPage.value = jumpPage.value;
  }
};

const paginatedEmployees = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value;
  const end = start + itemsPerPage.value;
  return filteredEmployees.value.slice(start, end);
});

const selectAll = computed({
  get: () => {
    return paginatedEmployees.value.length > 0 && paginatedEmployees.value.every(emp => selectedEmployees.value.includes(emp.id));
  },
  set: (val) => {
    if (val) {
      paginatedEmployees.value.forEach(emp => {
        if (!selectedEmployees.value.includes(emp.id)) {
          selectedEmployees.value.push(emp.id);
        }
      });
    } else {
      paginatedEmployees.value.forEach(emp => {
        const index = selectedEmployees.value.indexOf(emp.id);
        if (index > -1) {
          selectedEmployees.value.splice(index, 1);
        }
      });
    }
  }
});

const paginationStart = computed(() => filteredEmployees.value.length === 0 ? 0 : (currentPage.value - 1) * itemsPerPage.value + 1);
const paginationEnd = computed(() => Math.min(currentPage.value * itemsPerPage.value, filteredEmployees.value.length));

const deleteEmployee = async (id) => {
  if (!confirm('Are you sure you want to delete this employee? This action cannot be undone.')) return;
  
  try {
    await axios.delete(`/api/employees/${id}`);
    fetchEmployees();
    selectedEmployees.value = selectedEmployees.value.filter(empId => empId !== id);
  } catch (error) {
    console.error('Error deleting employee:', error);
    alert('Failed to delete employee.');
  }
};

const bulkDelete = async () => {
  if (selectedEmployees.value.length === 0) return;
  if (!confirm(`Are you sure you want to delete ${selectedEmployees.value.length} selected employees? This action cannot be undone.`)) return;

  try {
    await axios.post('/api/employees/bulk-delete', { ids: selectedEmployees.value });
    fetchEmployees();
    selectedEmployees.value = [];
  } catch (error) {
    console.error('Error deleting employees:', error);
    alert('Failed to delete selected employees.');
  }
};

const exportCSV = () => {
  const headers = ['NIP', 'Name', 'Email', 'Position', 'Department', 'Status', 'Visibility'];
  let csvContent = "data:text/csv;charset=utf-8," + headers.join(",") + "\n";
  
  filteredEmployees.value.forEach(emp => {
    let row = [
      `"${emp.nip}"`,
      `"${emp.name}"`,
      `"${emp.email}"`,
      `"${emp.position}"`,
      `"${emp.department}"`,
      `"${emp.status}"`,
      `"${emp.visibility}"`
    ];
    csvContent += row.join(",") + "\n";
  });
  
  const encodedUri = encodeURI(csvContent);
  const link = document.createElement("a");
  link.setAttribute("href", encodedUri);
  link.setAttribute("download", "employee_directory.csv");
  document.body.appendChild(link); // Required for FF
  link.click();
  document.body.removeChild(link);
};

const handleClickOutside = (event) => {
  if (deptDropdownRef.value && !deptDropdownRef.value.contains(event.target)) {
    showDeptDropdown.value = false;
  }
};

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
  fetchDepartments();
  fetchEmployees();
});

import { onUnmounted } from 'vue';
onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});
</script>
