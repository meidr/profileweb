<template>
  <div class="flex h-screen bg-surface dark:bg-inverse-surface">
    <!-- SideNavBar (Desktop) -->
    <aside class="bg-[#054a34] h-screen w-64 fixed left-0 top-0 hidden md:flex flex-col z-40 text-white shadow-xl">
      <div class="flex flex-col h-full py-8 space-y-4">
        <!-- Header -->
        <div class="px-6 mb-2">
          <div class="flex items-center gap-4 mb-8">
            <div class="w-12 h-12 rounded-xl bg-white/15 flex items-center justify-center text-white border border-white/20 shadow-inner backdrop-blur-sm">
              <span class="material-symbols-outlined text-[26px]">shield_person</span>
            </div>
            <div>
              <h2 class="text-lg font-bold text-white tracking-wide leading-tight">Admin<br/>Central</h2>
            </div>
          </div>
          <router-link to="/directory/new" class="w-full py-3 bg-white text-[#054a34] font-bold rounded-xl flex items-center justify-center gap-2 transition-all hover:bg-emerald-50 hover:shadow-lg hover:-translate-y-0.5">
            <span class="material-symbols-outlined text-[20px]">person_add</span>
            New Profile
          </router-link>
        </div>
        <!-- Navigation Links -->
        <nav class="flex-1 px-4 space-y-2 mt-4">
          <router-link to="/" active-class="bg-white/15 text-white font-bold border-l-4 border-emerald-400" class="text-emerald-50/80 hover:bg-white/10 hover:text-white rounded-lg px-4 py-3 flex items-center gap-3 text-[14.5px] font-medium transition-all border-l-4 border-transparent">
            <span class="material-symbols-outlined text-[22px]">dashboard</span>
            Dashboard
          </router-link>
          <router-link to="/directory" active-class="bg-white/15 text-white font-bold border-l-4 border-emerald-400" class="text-emerald-50/80 hover:bg-white/10 hover:text-white rounded-lg px-4 py-3 flex items-center gap-3 text-[14.5px] font-medium transition-all border-l-4 border-transparent">
            <span class="material-symbols-outlined text-[22px]">groups</span>
            Directory
          </router-link>
          <router-link to="/approvals" active-class="bg-white/15 text-white font-bold border-l-4 border-emerald-400" class="text-emerald-50/80 hover:bg-white/10 hover:text-white rounded-lg px-4 py-3 flex items-center gap-3 text-[14.5px] font-medium transition-all border-l-4 border-transparent relative">
            <span class="material-symbols-outlined text-[22px]">fact_check</span>
            Approvals
            <span v-if="pendingCount > 0" class="absolute right-3 top-1/2 -translate-y-1/2 w-5 h-5 rounded-full bg-red-500 text-white flex items-center justify-center text-[10px] font-bold shadow-sm">{{ pendingCount }}</span>
          </router-link>

          <router-link to="/departments" active-class="bg-white/15 text-white font-bold border-l-4 border-emerald-400" class="text-emerald-50/80 hover:bg-white/10 hover:text-white rounded-lg px-4 py-3 flex items-center gap-3 text-[14.5px] font-medium transition-all border-l-4 border-transparent">
            <span class="material-symbols-outlined text-[22px]">domain</span>
            Departments
          </router-link>

          <router-link to="/settings" active-class="bg-white/15 text-white font-bold border-l-4 border-emerald-400" class="text-emerald-50/80 hover:bg-white/10 hover:text-white rounded-lg px-4 py-3 flex items-center gap-3 text-[14.5px] font-medium transition-all border-l-4 border-transparent">
            <span class="material-symbols-outlined text-[22px]">settings</span>
            Settings
          </router-link>
        </nav>
        <!-- Footer Links -->
        <div class="px-4 mt-auto pb-4">
          <div class="border-t border-white/10 pt-4 space-y-2">
            <button @click="handleLogout" class="w-full text-left text-emerald-50/80 hover:bg-white/10 hover:text-white rounded-lg px-4 py-3 flex items-center gap-3 text-[14.5px] font-medium transition-all">
              <span class="material-symbols-outlined text-[22px]">logout</span>
              Log Out
            </button>
          </div>
        </div>
      </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col md:ml-64 w-full h-screen overflow-hidden bg-gray-50">
      <!-- TopAppBar (Optional Header Slot or Default) -->
      <slot name="header">
        <header class="bg-surface dark:bg-inverse-surface w-full top-0 sticky border-b border-subtle dark:border-outline-variant flex justify-between items-center px-container-margin-mobile md:px-container-margin-desktop h-touch-target z-30 shrink-0">
          <div class="flex items-center gap-4 w-1/3">
            <img src="/images/logo dalwa.ico" alt="Logo" class="h-9 w-auto md:hidden ml-2" />
          </div>
          <div class="flex-1 flex justify-center w-1/3 max-w-md">
            <!-- Search placeholder -->
          </div>
          <div class="flex items-center justify-end gap-2 w-1/3 relative">
            <router-link to="/approvals" class="w-touch-target h-touch-target rounded-full hover:bg-surface-container-low transition-colors flex items-center justify-center text-on-surface-variant relative">
              <span class="material-symbols-outlined">notifications</span>
              <span v-if="pendingCount > 0" class="absolute top-1 right-1 w-4 h-4 bg-red-500 text-white text-[9px] font-bold rounded-full flex items-center justify-center border-2 border-surface shadow-sm">{{ pendingCount }}</span>
            </router-link>
            <div class="relative ml-2">
              <div @click="showDropdown = !showDropdown" class="w-8 h-8 rounded-full bg-surface-container-high overflow-hidden border border-subtle shadow-sm cursor-pointer flex items-center justify-center">
                <img v-if="adminAvatar" :src="adminAvatar" alt="Administrator profile avatar" class="w-full h-full object-cover"/>
                <span v-else class="material-symbols-outlined text-on-surface-variant text-[20px]">person</span>
              </div>
              <div v-if="showDropdown" class="absolute right-0 top-full mt-2 w-48 bg-white rounded-xl shadow-lg border border-subtle overflow-hidden z-50">
                <div class="py-1">
                  <router-link to="/settings" @click="showDropdown = false" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">person</span> Profile
                  </router-link>
                  <button @click="handleLogout" class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">logout</span> Logout
                  </button>
                </div>
              </div>
            </div>
          </div>
        </header>
      </slot>

      <!-- Page Content -->
      <div class="flex-1 overflow-y-auto no-scrollbar bg-surface-muted relative">
        <slot></slot>
      </div>
    </main>

    <!-- BottomNavBar (Mobile Only) -->
    <nav class="bg-surface dark:bg-inverse-surface fixed bottom-0 w-full z-50 md:hidden border-t border-subtle dark:border-outline-variant bg-surface-container-highest shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] h-[60px] px-2 pb-safe flex justify-around items-center">
      <router-link to="/" active-class="text-primary font-bold bg-primary/10" class="flex flex-col items-center justify-center text-on-surface-variant flex-1 h-full rounded-lg mx-1 transition-colors">
        <span class="material-symbols-outlined text-[24px]">home</span>
        <span class="text-[10px] mt-0.5">Home</span>
      </router-link>
      <router-link to="/directory" active-class="text-primary font-bold bg-primary/10" class="flex flex-col items-center justify-center text-on-surface-variant flex-1 h-full rounded-lg mx-1 transition-colors">
        <span class="material-symbols-outlined text-[24px]">search</span>
        <span class="text-[10px] mt-0.5">Directory</span>
      </router-link>
      <router-link to="/departments" active-class="text-primary font-bold bg-primary/10" class="flex flex-col items-center justify-center text-on-surface-variant flex-1 h-full rounded-lg mx-1 transition-colors">
        <span class="material-symbols-outlined text-[24px]">domain</span>
        <span class="text-[10px] mt-0.5">Depts</span>
      </router-link>
      <router-link to="/approvals" active-class="text-primary font-bold bg-primary/10" class="flex flex-col items-center justify-center text-on-surface-variant flex-1 h-full rounded-lg mx-1 transition-colors relative">
        <span class="material-symbols-outlined text-[24px]">all_inbox</span>
        <span class="text-[10px] mt-0.5">Inbox</span>
        <span v-if="pendingCount > 0" class="absolute top-1 right-3 w-3.5 h-3.5 bg-error text-white text-[8px] font-bold rounded-full flex items-center justify-center border border-white">{{ pendingCount }}</span>
      </router-link>
    </nav>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

const router = useRouter();
const pendingCount = ref(0);
const showDropdown = ref(false);
const adminAvatar = ref(null);

// Close dropdown when clicking outside
const closeDropdown = (e) => {
  if (!e.target.closest('.relative.ml-2')) {
    showDropdown.value = false;
  }
};

const fetchPendingCount = async () => {
    try {
        const response = await axios.get('/api/approvals');
        const count = response.data.filter(r => r.status === 'pending').length;
        pendingCount.value = count;
    } catch (e) {
        console.error('Failed to fetch pending count', e);
    }
};

const handleLogout = async () => {
  try {
    await axios.post('/api/logout');
    router.push('/login');
  } catch (error) {
    console.error("Logout failed:", error);
  }
};

const fetchAdminData = async () => {
    try {
        const response = await axios.get('/api/auth/check');
        if (response.data.authenticated && response.data.user && response.data.user.avatar) {
            adminAvatar.value = response.data.user.avatar;
        }
    } catch (e) {
        console.error('Failed to fetch admin data', e);
    }
};

onMounted(() => {
    fetchPendingCount();
    fetchAdminData();
    document.addEventListener('click', closeDropdown);
});

onUnmounted(() => {
    document.removeEventListener('click', closeDropdown);
});
</script>
