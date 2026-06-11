<template>
  <AdminLayout>
    <div class="max-w-4xl mx-auto p-4 md:p-8">
      <h1 class="font-headline-lg text-[32px] font-bold text-on-surface mb-2">Settings</h1>
      <p class="text-on-surface-variant font-body-lg mb-8">Manage your admin profile and account settings.</p>

      <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 shadow-sm">
        <h2 class="text-xl font-bold text-on-surface mb-6 border-b border-outline-variant pb-3">Admin Profile</h2>
        
        <div v-if="successMsg" class="bg-[#dcfce7] border border-[#bbf7d0] text-[#166534] p-4 rounded-lg mb-6 flex items-start gap-3">
          <span class="material-symbols-outlined">check_circle</span>
          <span class="font-medium">{{ successMsg }}</span>
        </div>
        
        <div v-if="errorMsg" class="bg-[#fee2e2] border border-[#fecaca] text-[#991b1b] p-4 rounded-lg mb-6 flex items-start gap-3">
          <span class="material-symbols-outlined">error</span>
          <span class="font-medium">{{ errorMsg }}</span>
        </div>

        <form @submit.prevent="updateProfile" class="space-y-5 max-w-xl">
          <div class="flex items-center gap-6 mb-6">
            <div class="w-20 h-20 rounded-full bg-surface-container border border-outline-variant flex items-center justify-center overflow-hidden shrink-0">
              <img v-if="avatarPreview" :src="avatarPreview" class="w-full h-full object-cover" />
              <span v-else class="material-symbols-outlined text-[32px] text-on-surface-variant">person</span>
            </div>
            <div class="flex-1">
              <label class="block text-sm font-bold text-on-surface mb-1.5">Profile Photo</label>
              <input type="file" @change="handleFileUpload" accept="image/*" class="block w-full text-sm text-on-surface-variant file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition-colors cursor-pointer" />
              <p class="text-xs text-on-surface-variant mt-1">JPG, PNG or GIF. Max size 2MB.</p>
            </div>
          </div>

          <div>
            <label class="block text-sm font-bold text-on-surface mb-1.5">Full Name</label>
            <input v-model="form.name" type="text" class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring focus:ring-primary/20 bg-surface-container-lowest text-on-surface p-3" required />
          </div>
          
          <div>
            <label class="block text-sm font-bold text-on-surface mb-1.5">Email Address</label>
            <input v-model="form.email" type="email" class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring focus:ring-primary/20 bg-surface-container-lowest text-on-surface p-3" required />
          </div>

          <div class="pt-4 border-t border-outline-variant/50">
            <h3 class="text-sm font-bold text-on-surface mb-1.5">Change Password <span class="text-on-surface-variant font-normal">(Optional)</span></h3>
            <p class="text-xs text-on-surface-variant mb-4">Leave blank if you don't want to change your password.</p>
            
            <div class="space-y-4">
              <div>
                <label class="block text-xs font-bold text-on-surface mb-1">Current Password</label>
                <div class="relative">
                  <input v-model="form.current_password" :type="showCurrentPassword ? 'text' : 'password'" autocomplete="new-password" placeholder="Enter your current password" class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring focus:ring-primary/20 bg-surface-container-lowest text-on-surface p-3 pr-10" />
                  <button type="button" @click="showCurrentPassword = !showCurrentPassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-on-surface focus:outline-none">
                    <span class="material-symbols-outlined text-[20px]">{{ showCurrentPassword ? 'visibility_off' : 'visibility' }}</span>
                  </button>
                </div>
              </div>
              
              <div>
                <label class="block text-xs font-bold text-on-surface mb-1">New Password</label>
                <div class="relative">
                  <input v-model="form.new_password" :type="showNewPassword ? 'text' : 'password'" autocomplete="new-password" placeholder="Enter new password" class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring focus:ring-primary/20 bg-surface-container-lowest text-on-surface p-3 pr-10" />
                  <button type="button" @click="showNewPassword = !showNewPassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-on-surface focus:outline-none">
                    <span class="material-symbols-outlined text-[20px]">{{ showNewPassword ? 'visibility_off' : 'visibility' }}</span>
                  </button>
                </div>
              </div>
            </div>
          </div>

          <div class="pt-2">
            <button type="submit" class="px-6 py-2.5 bg-primary text-on-primary font-bold rounded-lg hover:bg-primary/90 transition-colors shadow-sm disabled:opacity-70 flex items-center gap-2" :disabled="loading">
               <span v-if="loading" class="material-symbols-outlined animate-spin text-[18px]">progress_activity</span>
               Save Changes
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

const form = ref({
  name: '',
  email: '',
  current_password: '',
  new_password: '',
  avatar: null
});

const loading = ref(false);
const successMsg = ref('');
const errorMsg = ref('');
const showCurrentPassword = ref(false);
const showNewPassword = ref(false);
const avatarPreview = ref('');

const handleFileUpload = (e) => {
  const file = e.target.files[0];
  if (!file) return;
  form.value.avatar = file;
  const reader = new FileReader();
  reader.onload = (e) => {
    avatarPreview.value = e.target.result;
  };
  reader.readAsDataURL(file);
};

const fetchProfile = async () => {
  try {
    const res = await axios.get('/api/auth/check');
    if (res.data.authenticated && res.data.user) {
      form.value.name = res.data.user.name;
      form.value.email = res.data.user.email;
      if (res.data.user.avatar) {
        avatarPreview.value = res.data.user.avatar;
      }
    }
  } catch (e) {
    console.error(e);
  }
};

const updateProfile = async () => {
  loading.value = true;
  successMsg.value = '';
  errorMsg.value = '';
  try {
    const formData = new FormData();
    formData.append('name', form.value.name);
    formData.append('email', form.value.email);
    if (form.value.current_password) formData.append('current_password', form.value.current_password);
    if (form.value.new_password) formData.append('new_password', form.value.new_password);
    if (form.value.avatar) formData.append('avatar', form.value.avatar);
    formData.append('_method', 'PUT'); // Laravel spoofing for PUT request with FormData

    const res = await axios.post('/api/profile', formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });
    
    successMsg.value = res.data.message;
    form.value.current_password = '';
    form.value.new_password = '';
    showCurrentPassword.value = false;
    showNewPassword.value = false;
    if (res.data.user && res.data.user.avatar) {
       avatarPreview.value = res.data.user.avatar;
    }
    // Refresh the page to update AdminLayout avatar if we used a global store it would be better
    window.location.reload();
  } catch (e) {
    console.error(e);
    if (e.response && e.response.data && e.response.data.message) {
      errorMsg.value = e.response.data.message;
    } else {
      errorMsg.value = 'Failed to update profile.';
    }
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchProfile();
});
</script>
