<template>
  <AdminLayout>
    <template #header>
      <header class="bg-white/80 backdrop-blur-md w-full top-0 sticky border-b border-gray-200 flex items-center px-8 py-5 z-40 shrink-0 shadow-sm">
        <h2 class="text-[20px] font-bold text-gray-900">{{ isEditMode ? 'Edit Employee Profile' : 'Add New Employee Profile' }}</h2>
      </header>
    </template>

    <div class="p-8 max-w-4xl w-full mx-auto pb-20">
        <div class="bg-white border border-gray-200 rounded-2xl p-8 shadow-sm">
          <form @submit.prevent="submitForm" class="space-y-8">
            
            <!-- Avatar Upload Section -->
            <div>
              <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-100 pb-2 mb-5">Profile Picture</h3>
              <div class="flex items-center gap-6">
                <div class="w-28 h-28 rounded-full overflow-hidden bg-gray-100 border-[3px] border-white ring-1 ring-gray-200 flex-shrink-0 relative group shadow-sm">
                  <img v-if="previewImage" :src="previewImage" alt="Preview" class="w-full h-full object-cover"/>
                  <span v-else class="material-symbols-outlined text-gray-400 w-full h-full flex items-center justify-center text-5xl">person</span>
                  <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer" @click="$refs.fileInput.click()">
                    <span class="material-symbols-outlined text-white text-3xl">photo_camera</span>
                  </div>
                </div>
                <div>
                  <input type="file" ref="fileInput" @change="handleFileUpload" accept="image/*" class="hidden" />
                  <button type="button" @click="$refs.fileInput.click()" class="px-5 py-2.5 bg-white border border-gray-300 rounded-lg text-gray-700 text-[14px] font-medium hover:bg-gray-50 transition-colors shadow-sm focus:ring-2 focus:ring-[#054a34] focus:outline-none">
                    Choose Image
                  </button>
                  <p class="text-[13px] text-gray-500 mt-2">JPG, PNG or GIF. Max size 10MB.</p>
                </div>
              </div>
            </div>

            <!-- Identity Section -->
            <div>
              <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-100 pb-2 mb-5">Identity Information</h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                  <label class="block text-[13px] font-medium text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                  <input v-model="form.name" type="text" class="w-full border border-gray-300 rounded-lg py-2.5 px-3 focus:outline-none focus:ring-2 focus:ring-[#054a34] focus:border-transparent text-[14px]" required />
                </div>
                <div>
                  <label class="block text-[13px] font-medium text-gray-700 mb-1">Gender</label>
                  <select v-model="form.gender" class="w-full border border-gray-300 rounded-lg py-2.5 px-3 focus:outline-none focus:ring-2 focus:ring-[#054a34] focus:border-transparent text-[14px] bg-white">
                    <option value="">Select Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                  </select>
                </div>
                <div>
                  <label class="block text-[13px] font-medium text-gray-700 mb-1">Date of Birth</label>
                  <input v-model="form.dob" type="date" class="w-full border border-gray-300 rounded-lg py-2.5 px-3 focus:outline-none focus:ring-2 focus:ring-[#054a34] focus:border-transparent text-[14px]" />
                </div>
                <div>
                  <label class="block text-[13px] font-medium text-gray-700 mb-1">NIP (Employee ID) <span class="text-red-500">*</span></label>
                  <input v-model="form.nip" type="text" class="w-full border border-gray-300 rounded-lg py-2.5 px-3 focus:outline-none focus:ring-2 focus:ring-[#054a34] focus:border-transparent text-[14px]" required />
                </div>
              </div>
            </div>

            <!-- Employment Details -->
            <div>
              <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-100 pb-2 mb-5">Employment Details</h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                  <label class="block text-[13px] font-medium text-gray-700 mb-1">Position / Title <span class="text-red-500">*</span></label>
                  <input v-model="form.position" type="text" class="w-full border border-gray-300 rounded-lg py-2.5 px-3 focus:outline-none focus:ring-2 focus:ring-[#054a34] focus:border-transparent text-[14px]" required />
                </div>
                <div class="relative" ref="deptDropdownRef">
                  <label class="block text-[13px] font-medium text-gray-700 mb-1">Department <span class="text-red-500">*</span></label>
                  <div @click="showDeptDropdown = !showDeptDropdown" class="w-full border border-gray-300 rounded-lg py-2.5 px-3 focus-within:outline-none focus-within:ring-2 focus-within:ring-[#054a34] focus-within:border-transparent text-[14px] bg-white cursor-pointer flex justify-between items-center h-[42px]">
                    <span :class="form.department ? 'text-gray-900' : 'text-gray-500'" class="truncate pr-4">{{ form.department || 'Select Department...' }}</span>
                    <span class="material-symbols-outlined text-gray-400 text-[18px]">{{ showDeptDropdown ? 'expand_less' : 'expand_more' }}</span>
                  </div>
                  <div v-if="showDeptDropdown" class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-48 overflow-y-auto">
                    <div @click="form.department = ''; showDeptDropdown = false" class="px-3 py-2 text-[14px] text-gray-500 hover:bg-emerald-50 cursor-pointer">
                      Select Department...
                    </div>
                    <div v-for="dept in departments" :key="dept.id" @click="form.department = dept.name; showDeptDropdown = false" class="px-3 py-2 text-[14px] text-gray-900 hover:bg-emerald-50 cursor-pointer border-t border-gray-50">
                      {{ dept.name }}
                    </div>
                  </div>
                </div>
                <div>
                  <label class="block text-[13px] font-medium text-gray-700 mb-1">Hire Date</label>
                  <input v-model="form.hire_date" type="date" class="w-full border border-gray-300 rounded-lg py-2.5 px-3 focus:outline-none focus:ring-2 focus:ring-[#054a34] focus:border-transparent text-[14px]" />
                </div>
                <div class="grid grid-cols-2 gap-4">
                  <div>
                    <label class="block text-[13px] font-medium text-gray-700 mb-1">Status</label>
                    <select v-model="form.status" class="w-full border border-gray-300 rounded-lg py-2.5 px-3 focus:outline-none focus:ring-2 focus:ring-[#054a34] focus:border-transparent text-[14px] bg-white">
                      <option value="active">Active</option>
                      <option value="inactive">Inactive</option>
                    </select>
                  </div>
                  <div>
                    <label class="block text-[13px] font-medium text-gray-700 mb-1">Visibility</label>
                    <select v-model="form.visibility" class="w-full border border-gray-300 rounded-lg py-2.5 px-3 focus:outline-none focus:ring-2 focus:ring-[#054a34] focus:border-transparent text-[14px] bg-white">
                      <option value="public">Public</option>
                      <option value="hidden">Hidden</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>

            <!-- Contact Details -->
            <div>
              <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-100 pb-2 mb-5">Contact Details</h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                  <label class="block text-[13px] font-medium text-gray-700 mb-1">Email Address <span class="text-red-500">*</span></label>
                  <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[18px]">mail</span>
                    <input v-model="form.email" type="email" class="w-full border border-gray-300 rounded-lg py-2.5 pl-10 pr-3 focus:outline-none focus:ring-2 focus:ring-[#054a34] focus:border-transparent text-[14px]" required />
                  </div>
                </div>
                <div>
                  <label class="block text-[13px] font-medium text-gray-700 mb-1">Phone Number</label>
                  <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[18px]">call</span>
                    <input v-model="form.phone" type="tel" class="w-full border border-gray-300 rounded-lg py-2.5 pl-10 pr-3 focus:outline-none focus:ring-2 focus:ring-[#054a34] focus:border-transparent text-[14px]" />
                  </div>
                </div>
                <div class="md:col-span-2">
                  <label class="block text-[13px] font-medium text-gray-700 mb-1">Location / Office Address</label>
                  <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[18px]">location_on</span>
                    <input v-model="form.location" type="text" class="w-full border border-gray-300 rounded-lg py-2.5 pl-10 pr-3 focus:outline-none focus:ring-2 focus:ring-[#054a34] focus:border-transparent text-[14px]" />
                  </div>
                </div>
              </div>
            </div>

            <!-- Biography -->
            <div>
              <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-100 pb-2 mb-5">Biography / Summary</h3>
              <div>
                <textarea v-model="form.bio" rows="4" class="w-full border border-gray-300 rounded-lg py-3 px-4 focus:outline-none focus:ring-2 focus:ring-[#054a34] focus:border-transparent text-[14px] resize-y" placeholder="Write a brief professional summary..."></textarea>
              </div>
            </div>

            <!-- Error State -->
            <div v-if="error" class="text-red-700 bg-red-50 p-4 rounded-xl text-[14px] border border-red-200 flex items-center gap-3">
              <span class="material-symbols-outlined">error</span>
              {{ error }}
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-gray-100">
              <router-link to="/directory" class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 text-[14px] font-medium hover:bg-gray-50 transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-300">Cancel</router-link>
              <button type="submit" class="px-6 py-2.5 bg-[#054a34] text-white rounded-lg text-[14px] font-medium hover:bg-[#033323] transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-[#054a34] focus:ring-offset-2 flex items-center gap-2" :disabled="loading">
                <span v-if="loading" class="material-symbols-outlined animate-spin text-[18px]">progress_activity</span>
                <span v-else class="material-symbols-outlined text-[18px]">save</span>
                <span>{{ loading ? 'Saving...' : 'Save Profile' }}</span>
              </button>
            </div>
          </form>
        </div>
      </div>
  </AdminLayout>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import axios from 'axios';
import AdminLayout from '../layouts/AdminLayout.vue';

const router = useRouter();
const route = useRoute();
const loading = ref(false);
const error = ref('');
const previewImage = ref(null);
const fileInput = ref(null);
const selectedFile = ref(null);

const showDeptDropdown = ref(false);
const deptDropdownRef = ref(null);

const isEditMode = computed(() => !!route.params.id);

const departments = ref([]);

const fetchDepartments = async () => {
  try {
    const res = await axios.get('/api/departments');
    departments.value = res.data;
  } catch (err) {
    console.error('Failed to fetch departments:', err);
  }
};

const form = ref({
  name: '',
  email: '',
  nip: '',
  position: '',
  department: '',
  status: 'active',
  visibility: 'public',
  phone: '',
  gender: '',
  dob: '',
  location: '',
  hire_date: '',
  bio: ''
});

const handleFileUpload = (event) => {
  const file = event.target.files[0];
  if (file) {
    selectedFile.value = file;
    // Create preview URL
    previewImage.value = URL.createObjectURL(file);
  }
};

const fetchEmployeeData = async () => {
  if (isEditMode.value) {
    loading.value = true;
    try {
      const res = await axios.get(`/api/employees/${route.params.id}`);
      const data = res.data;
      form.value = {
        name: data.name || '',
        email: data.email || '',
        nip: data.nip || '',
        position: data.position || '',
        department: data.department || '',
        status: data.status || 'active',
        visibility: data.visibility || 'public',
        phone: data.phone || '',
        gender: data.gender || '',
        dob: data.dob || '',
        location: data.location || '',
        hire_date: data.hire_date || '',
        bio: data.bio || ''
      };
      if (data.avatar) {
        previewImage.value = data.avatar;
      }
    } catch (err) {
      error.value = "Failed to load employee data";
    } finally {
      loading.value = false;
    }
  }
};

const submitForm = async () => {
  loading.value = true;
  error.value = '';
  
  const formData = new FormData();
  Object.keys(form.value).forEach(key => {
    formData.append(key, form.value[key]);
  });
  
  if (selectedFile.value) {
    formData.append('avatar', selectedFile.value);
  }

  try {
    if (isEditMode.value) {
      formData.append('_method', 'PUT'); // Method spoofing for Laravel
      await axios.post(`/api/employees/${route.params.id}`, formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      });
    } else {
      await axios.post('/api/employees', formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      });
    }
    router.push('/directory');
  } catch (err) {
    error.value = err.response?.data?.message || 'Failed to save profile';
  } finally {
    loading.value = false;
  }
};

const handleClickOutside = (event) => {
  if (deptDropdownRef.value && !deptDropdownRef.value.contains(event.target)) {
    showDeptDropdown.value = false;
  }
};

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
  fetchDepartments();
  fetchEmployeeData();
});

import { onUnmounted } from 'vue';
onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});
</script>

<style scoped>
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
