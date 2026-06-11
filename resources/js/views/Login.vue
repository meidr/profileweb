<template>
  <div class="min-h-screen bg-gray-50 flex">
    <!-- Left Section: Login Form -->
    <div class="w-full lg:w-[45%] flex flex-col justify-center px-8 sm:px-16 lg:px-24 bg-white relative z-10 shadow-[10px_0_30px_rgba(0,0,0,0.03)]">
      
      <div class="max-w-sm w-full mx-auto">
        <!-- Logo Area -->
        <div class="flex items-center gap-3 mb-10 animate-fade-in-up">
          <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-[#054a34] to-emerald-600 flex items-center justify-center text-white shadow-lg shadow-emerald-900/20">
            <span class="material-symbols-outlined text-[26px]">school</span>
          </div>
          <span class="text-2xl font-bold tracking-tight text-gray-900">Profile Portal</span>
        </div>

        <h2 class="text-[32px] font-bold text-gray-900 mb-3 tracking-tight animate-fade-in-up" style="animation-delay: 100ms;">Welcome Back</h2>
        <p class="text-gray-500 mb-10 text-[15px] animate-fade-in-up" style="animation-delay: 150ms;">Sign in to access your administrative dashboard and manage university profiles.</p>
        
        <form @submit.prevent="handleLogin" class="space-y-6 animate-fade-in-up" style="animation-delay: 200ms;">
          <div class="space-y-1.5">
            <label class="block text-[13px] font-bold text-gray-700">Email Address</label>
            <div class="relative">
              <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-[20px]">mail</span>
              <input v-model="form.email" type="email" class="w-full bg-gray-50/50 border border-gray-200 rounded-xl py-3 pl-11 pr-4 text-[15px] text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#054a34] focus:border-transparent transition-all placeholder:text-gray-400" required placeholder="admin@example.com" />
            </div>
          </div>
          
          <div class="space-y-1.5">
            <label class="block text-[13px] font-bold text-gray-700">Password</label>
            <div class="relative">
              <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-[20px]">lock</span>
              <input v-model="form.password" :type="showPassword ? 'text' : 'password'" class="w-full bg-gray-50/50 border border-gray-200 rounded-xl py-3 pl-11 pr-12 text-[15px] text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#054a34] focus:border-transparent transition-all placeholder:text-gray-400" required placeholder="••••••••" />
              <button type="button" @click="showPassword = !showPassword" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none transition-colors">
                <span class="material-symbols-outlined text-[20px]">{{ showPassword ? 'visibility_off' : 'visibility' }}</span>
              </button>
            </div>
          </div>
          
          <div v-if="error" class="text-red-600 text-[14px] font-medium bg-red-50 p-4 rounded-xl border border-red-100 flex items-start gap-2">
            <span class="material-symbols-outlined text-[20px] shrink-0">error</span>
            <span>{{ error }}</span>
          </div>

          <button type="submit" class="w-full py-3.5 px-4 bg-[#054a34] text-white font-bold text-[15px] rounded-xl hover:bg-[#033323] hover:shadow-lg hover:shadow-[#054a34]/30 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#054a34] transition-all flex items-center justify-center gap-2 group overflow-hidden relative" :disabled="loading">
            <span v-if="!loading" class="absolute inset-0 w-full h-full -ml-10 bg-white opacity-0 group-hover:animate-shine rounded-xl"></span>
            <span v-if="loading" class="material-symbols-outlined animate-spin text-[20px]">progress_activity</span>
            <span class="relative">{{ loading ? 'Authenticating...' : 'Sign In' }}</span>
          </button>
        </form>
        
        <p class="mt-8 text-center text-[13px] text-gray-400 font-medium">
          &copy; {{ new Date().getFullYear() }} Profile Portal. All rights reserved.
        </p>
      </div>
    </div>

    <!-- Right Section: Decorative Image -->
    <div class="hidden lg:block lg:w-[55%] relative overflow-hidden bg-[#021f15]">
      <!-- Background Image -->
      <div class="absolute inset-0 bg-cover bg-center transition-transform duration-1000 hover:scale-105" style="background-image: url('/images/login-bg.png');"></div>
      
      <!-- Gradient Overlay -->
      <div class="absolute inset-0 bg-gradient-to-t from-[#021f15] via-[#054a34]/40 to-transparent mix-blend-multiply"></div>
      
      <!-- Content Overlay -->
      <div class="absolute inset-0 flex flex-col justify-end p-16 pb-24 text-white z-10 animate-fade-in">
        <div class="max-w-xl">
          <div class="w-16 h-1 bg-emerald-400 mb-8 rounded-full"></div>
          <h1 class="text-5xl font-bold mb-6 leading-tight">Empowering Academic Excellence</h1>
          <p class="text-lg text-emerald-50/90 font-medium leading-relaxed">
            Manage institutional staff profiles, control visibility, and streamline your university's human resources operations from a single, elegant dashboard.
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

const router = useRouter();
const form = ref({ email: 'admin@example.com', password: 'password' });
const loading = ref(false);
const error = ref('');
const showPassword = ref(false);

const handleLogin = async () => {
  loading.value = true;
  error.value = '';
  try {
    await axios.post('/api/login', form.value);
    router.push('/');
  } catch (err) {
    error.value = err.response?.data?.message || 'Invalid credentials. Please try again.';
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
@keyframes shine {
  0% { left: -100%; opacity: 0; }
  20% { left: 100%; opacity: 0.2; }
  100% { left: 100%; opacity: 0; }
}

.animate-shine {
  animation: shine 1.5s ease-out infinite;
}

.animate-fade-in-up {
  opacity: 0;
  animation: fadeInUp 0.8s ease-out forwards;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in {
  opacity: 0;
  animation: fadeIn 1.2s ease-out 0.5s forwards;
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}
</style>
