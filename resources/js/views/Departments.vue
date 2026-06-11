<template>
  <AdminLayout>
    <div class="max-w-4xl mx-auto p-4 md:p-8">
      <!-- Header -->
      <div class="mb-8 flex flex-col md:flex-row justify-between md:items-center gap-4">
        <div>
          <h1 class="font-headline-lg text-[32px] font-bold text-gray-900 mb-1">Departments</h1>
          <p class="text-gray-500 font-body-lg">Manage university departments and academic units.</p>
        </div>
        <button @click="openAddModal" class="h-touch-target px-5 bg-[#054a34] text-white font-bold text-[14px] rounded-xl hover:bg-[#033323] transition-colors flex items-center gap-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#054a34]/50">
          <span class="material-symbols-outlined text-[20px]">add</span>
          Add Department
        </button>
      </div>

      <!-- Main Content -->
      <div class="bg-white border border-gray-200 rounded-[24px] shadow-sm overflow-hidden">
        <div v-if="loading" class="p-10 flex justify-center items-center">
          <span class="material-symbols-outlined animate-spin text-[32px] text-[#054a34]">progress_activity</span>
        </div>
        
        <table v-else class="w-full text-left border-collapse min-w-[500px]">
          <thead>
            <tr class="bg-gray-50 border-b border-gray-200">
              <th class="py-4 px-6 text-[13px] font-bold text-gray-500 uppercase tracking-wider w-16 text-center">ID</th>
              <th class="py-4 px-6 text-[13px] font-bold text-gray-500 uppercase tracking-wider">Department Name</th>
              <th class="py-4 px-6 text-[13px] font-bold text-gray-500 uppercase tracking-wider text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            <tr v-if="departments.length === 0">
              <td colspan="3" class="py-10 text-center text-gray-500 font-medium">No departments found.</td>
            </tr>
            <tr v-for="(dept, index) in departments" :key="dept.id" class="hover:bg-emerald-50/30 transition-colors group">
              <td class="py-4 px-6 text-center text-gray-400 font-mono text-[13px]">{{ index + 1 }}</td>
              <td class="py-4 px-6 text-gray-900 font-medium text-[15px]">{{ dept.name }}</td>
              <td class="py-4 px-6 text-right">
                <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                  <button @click="openEditModal(dept)" class="w-9 h-9 rounded-lg hover:bg-white hover:shadow text-gray-500 hover:text-[#054a34] flex items-center justify-center transition-all border border-transparent hover:border-gray-200" title="Edit">
                    <span class="material-symbols-outlined text-[20px]">edit</span>
                  </button>
                  <button @click="deleteDepartment(dept.id)" class="w-9 h-9 rounded-lg hover:bg-red-50 hover:shadow text-gray-500 hover:text-red-600 flex items-center justify-center transition-all border border-transparent hover:border-red-100" title="Delete">
                    <span class="material-symbols-outlined text-[20px]">delete</span>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Modal for Add/Edit -->
    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 backdrop-blur-sm">
      <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden transform transition-all">
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
          <h3 class="text-[18px] font-bold text-gray-900">{{ isEditing ? 'Edit Department' : 'Add Department' }}</h3>
          <button @click="closeModal" class="text-gray-400 hover:text-gray-700 transition-colors">
            <span class="material-symbols-outlined text-[20px]">close</span>
          </button>
        </div>
        
        <form @submit.prevent="submitForm" class="p-6">
          <div class="mb-6">
            <label class="block text-[13px] font-bold text-gray-700 mb-2">Department Name <span class="text-red-500">*</span></label>
            <div class="relative">
              <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[18px]">domain</span>
              <input v-model="form.name" type="text" class="w-full pl-9 pr-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#054a34] focus:border-transparent text-[14px]" placeholder="e.g. Computer Science" required autofocus />
            </div>
            <p v-if="isEditing" class="text-[11px] text-amber-600 mt-2 flex items-center gap-1 bg-amber-50 p-2 rounded">
              <span class="material-symbols-outlined text-[14px]">warning</span>
              Changing this will update all employees currently in this department.
            </p>
          </div>
          
          <div class="flex justify-end gap-3 pt-2">
            <button type="button" @click="closeModal" class="px-5 py-2.5 border border-gray-300 rounded-lg text-[13px] font-bold text-gray-700 hover:bg-gray-50 transition-colors">
              Cancel
            </button>
            <button type="submit" class="px-5 py-2.5 bg-[#054a34] text-white rounded-lg text-[13px] font-bold hover:bg-[#033323] transition-colors flex items-center gap-2" :disabled="submitting">
              <span v-if="submitting" class="material-symbols-outlined animate-spin text-[16px]">progress_activity</span>
              {{ submitting ? 'Saving...' : 'Save Department' }}
            </button>
          </div>
        </form>
      </div>
    </div>

  </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import AdminLayout from '../layouts/AdminLayout.vue';

const departments = ref([]);
const loading = ref(true);
const submitting = ref(false);

const showModal = ref(false);
const isEditing = ref(false);
const editingId = ref(null);

const form = ref({
  name: ''
});

const fetchDepartments = async () => {
  loading.value = true;
  try {
    const res = await axios.get('/api/departments');
    departments.value = res.data;
  } catch (error) {
    console.error('Failed to fetch departments:', error);
  } finally {
    loading.value = false;
  }
};

const openAddModal = () => {
  isEditing.value = false;
  editingId.value = null;
  form.value.name = '';
  showModal.value = true;
};

const openEditModal = (dept) => {
  isEditing.value = true;
  editingId.value = dept.id;
  form.value.name = dept.name;
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
};

const submitForm = async () => {
  submitting.value = true;
  try {
    if (isEditing.value) {
      await axios.put(`/api/departments/${editingId.value}`, form.value);
    } else {
      await axios.post('/api/departments', form.value);
    }
    closeModal();
    fetchDepartments();
  } catch (error) {
    console.error('Failed to save department:', error);
    alert(error.response?.data?.message || 'Failed to save department.');
  } finally {
    submitting.value = false;
  }
};

const deleteDepartment = async (id) => {
  if (!confirm('Are you sure you want to delete this department? Employees in this department will remain unchanged but it will no longer appear in dropdowns.')) return;
  
  try {
    await axios.delete(`/api/departments/${id}`);
    fetchDepartments();
  } catch (error) {
    console.error('Failed to delete department:', error);
    alert('Failed to delete department.');
  }
};

onMounted(() => {
  fetchDepartments();
});
</script>
