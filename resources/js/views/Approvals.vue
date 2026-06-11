<template>
  <AdminLayout>
    <div class="max-w-7xl mx-auto p-4 md:p-8">
      <!-- Header -->
      <div class="mb-8 flex flex-col md:flex-row justify-between md:items-end gap-4">
        <div>
          <h1 class="font-headline-lg text-[32px] font-bold text-on-surface mb-1">Pending Requests</h1>
          <p class="text-on-surface-variant font-body-lg">Review and approve faculty data changes or visibility toggles.</p>
        </div>
        <div class="flex flex-wrap gap-2">
          <button @click="setFilter('all')" :class="['px-4 py-2 rounded-full font-label-md transition-colors border', filter === 'all' ? 'bg-primary border-primary text-on-primary' : 'bg-surface-container-high border-transparent text-on-surface-variant hover:bg-surface-variant']">
            All ({{ pendingRequests.length }})
          </button>
          <button @click="setFilter('data_change')" :class="['px-4 py-2 rounded-full font-label-md transition-colors border', filter === 'data_change' ? 'bg-primary border-primary text-on-primary' : 'bg-surface-container-high border-transparent text-on-surface-variant hover:bg-surface-variant']">
            Data Change ({{ pendingRequests.filter(r => r.type === 'data_change').length }})
          </button>
          <button @click="setFilter('visibility')" :class="['px-4 py-2 rounded-full font-label-md transition-colors border', filter === 'visibility' ? 'bg-primary border-primary text-on-primary' : 'bg-surface-container-high border-transparent text-on-surface-variant hover:bg-surface-variant']">
            Visibility ({{ pendingRequests.filter(r => r.type === 'visibility').length }})
          </button>
        </div>
      </div>

      <!-- Main Content Grid -->
      <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 items-start">
        
        <!-- Left Column (Active Request) -->
        <div class="xl:col-span-2">
          <div v-if="activeRequest" class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 lg:p-8 flex flex-col gap-6 shadow-sm">
            <!-- Card Header -->
            <div class="flex flex-col md:flex-row justify-between items-start gap-4">
              <div class="flex gap-4 items-center">
                <img v-if="activeRequest.employee.avatar" :src="activeRequest.employee.avatar" class="w-16 h-16 rounded-full object-cover bg-surface-container-high border border-outline-variant" alt="Avatar" />
                <div v-else class="w-16 h-16 rounded-full bg-secondary-container text-on-secondary-container flex items-center justify-center font-bold text-xl border border-outline-variant">
                  {{ getInitials(activeRequest.employee.name) }}
                </div>
                <div>
                  <h2 class="font-headline-md text-on-surface text-xl md:text-2xl">{{ activeRequest.employee.name }}</h2>
                  <p class="text-on-surface-variant font-body-md">{{ activeRequest.employee.department }} • ID: {{ activeRequest.employee.nip }}</p>
                  <div class="flex gap-2 mt-2">
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-[#fff3e0] text-[#e65100] flex items-center gap-1.5 border border-[#ffe0b2]">
                      <span class="w-1.5 h-1.5 rounded-full bg-[#e65100]"></span> PENDING
                    </span>
                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-surface-container-high text-on-surface-variant border border-outline-variant/30">
                      {{ activeRequest.subtype }}
                    </span>
                  </div>
                </div>
              </div>
              <div class="text-left md:text-right">
                <p class="text-on-surface-variant text-sm font-medium mb-1">Submitted: {{ formatDate(activeRequest.created_at) }}</p>
                <router-link :to="'/directory/' + activeRequest.employee.id" class="text-primary font-label-md flex items-center md:justify-end gap-1 hover:underline">
                  View Full Profile <span class="material-symbols-outlined text-[18px]">open_in_new</span>
                </router-link>
              </div>
            </div>

            <!-- Data Comparison Section -->
            <div v-if="activeRequest.type === 'data_change' && activeRequest.current_data && Object.keys(activeRequest.current_data).length > 0" class="mt-2">
              <h3 class="text-xs font-bold tracking-[0.1em] text-on-surface-variant mb-4 uppercase">Data Comparison</h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Current -->
                <div class="border border-outline-variant rounded-lg relative overflow-hidden bg-surface flex flex-col h-full">
                  <div class="absolute top-0 right-0 bg-secondary text-white text-[10px] font-bold px-3 py-1 rounded-bl-lg tracking-wide z-10">CURRENT LIVE</div>
                  <div class="p-5 pt-8 flex flex-col gap-5 grow">
                    <div v-for="(val, key) in activeRequest.current_data" :key="key">
                      <p class="text-xs text-on-surface-variant mb-1 font-medium">{{ formatLabel(key) }}</p>
                      <p class="font-body-md text-on-surface">{{ val }}</p>
                    </div>
                  </div>
                </div>
                <!-- Requested -->
                <div class="border-2 border-primary rounded-lg relative overflow-hidden bg-surface-container-lowest shadow-sm flex flex-col h-full">
                  <div class="absolute top-0 right-0 bg-primary text-white text-[10px] font-bold px-3 py-1 rounded-bl-lg tracking-wide z-10">REQUESTED CHANGE</div>
                  <div class="p-5 pt-8 flex flex-col gap-5 grow">
                    <div v-for="(val, key) in activeRequest.requested_data" :key="key">
                      <p class="text-xs text-on-surface-variant mb-1 font-medium">{{ formatLabel(key) }}</p>
                      <div class="inline-block">
                          <p :class="['font-body-md text-on-surface', (activeRequest.current_data && activeRequest.current_data[key] !== val) ? 'bg-[#e8f5e9] px-2 py-0.5 -mx-2 rounded font-medium' : '']">
                              {{ val }}
                          </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Fallback to showing just the description for unstructured requests or visibility toggles -->
            <div v-else class="mt-2">
              <h3 class="text-xs font-bold tracking-[0.1em] text-on-surface-variant mb-4 uppercase">Request Details</h3>
              <div class="border border-outline-variant rounded-lg p-5 bg-surface">
                <p class="text-on-surface font-body-md whitespace-pre-line">{{ activeRequest.description }}</p>
              </div>
            </div>

            <!-- Faculty Comment -->
            <div v-if="activeRequest.faculty_comment" class="bg-surface-container border border-outline-variant rounded-lg p-5">
              <h3 class="flex items-center gap-2 text-sm font-semibold text-on-surface mb-3">
                <span class="material-symbols-outlined text-[18px]">chat</span> Faculty Comment
              </h3>
              <p class="italic text-on-surface-variant font-body-md text-sm leading-relaxed">"{{ activeRequest.faculty_comment }}"</p>
            </div>

            <hr class="border-outline-variant/60 my-2" />

            <!-- Admin Notes -->
            <div>
              <label for="admin-notes" class="block text-sm font-semibold text-on-surface mb-2">Admin Notes (Required for Rejection)</label>
              <textarea id="admin-notes" v-model="adminNotes" rows="2" class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring focus:ring-primary/20 bg-surface-container-lowest text-on-surface p-3 placeholder-on-surface-variant/50 text-sm transition-colors shadow-sm" placeholder="Add internal notes or reason for rejection..."></textarea>
            </div>

            <!-- Actions -->
            <div class="flex flex-wrap justify-end items-center gap-3 md:gap-4 mt-2">
              <button @click="skipRequest" :disabled="processing" class="px-5 py-2.5 rounded-lg border border-outline-variant font-label-md text-on-surface hover:bg-surface-container transition-colors bg-surface-container-lowest focus:ring focus:ring-outline-variant/30 disabled:opacity-50">
                Skip for Now
              </button>
              <button @click="rejectRequest" :disabled="processing" class="px-5 py-2.5 rounded-lg border border-error text-error font-label-md hover:bg-error/10 transition-colors flex items-center gap-2 bg-surface-container-lowest focus:ring focus:ring-error/30 disabled:opacity-50">
                <span class="material-symbols-outlined text-[20px]">close</span> Reject
              </button>
              <button @click="approveRequest" :disabled="processing" class="px-6 py-2.5 rounded-lg bg-primary text-on-primary font-label-md hover:bg-primary/90 transition-colors flex items-center gap-2 shadow-sm focus:ring focus:ring-primary/40 disabled:opacity-50">
                <span class="material-symbols-outlined text-[20px]">check</span> Approve Request
              </button>
            </div>
          </div>

          <!-- Empty State -->
          <div v-else class="bg-surface-container-lowest border border-outline-variant rounded-xl p-16 flex flex-col items-center justify-center text-center shadow-sm">
            <div class="w-20 h-20 rounded-full bg-surface-container-high flex items-center justify-center mb-6">
              <span class="material-symbols-outlined text-4xl text-outline-variant">check_circle</span>
            </div>
            <h2 class="text-2xl font-headline-md text-on-surface mb-2">You're all caught up!</h2>
            <p class="text-on-surface-variant font-body-md max-w-md">There are no pending requests matching your current filter waiting for approval.</p>
          </div>
        </div>

        <!-- Right Column (Queue & Processed) -->
        <div class="xl:col-span-1">
          <!-- Queue -->
          <div class="mb-10">
            <h3 class="font-headline-sm text-on-surface mb-4">Queue ({{ queueRemaining.length }} Remaining)</h3>
            <div class="flex flex-col gap-3">
              <transition-group name="list">
                <div v-for="item in queueRemaining" :key="item.id" @click="selectRequest(item.id)" class="cursor-pointer border border-outline-variant rounded-xl p-4 bg-surface-container-lowest hover:border-primary transition-all hover:shadow-md group relative overflow-hidden">
                  <div class="absolute inset-y-0 left-0 w-1 bg-transparent group-hover:bg-primary transition-colors"></div>
                  <div class="flex justify-between items-start mb-3">
                    <div class="flex gap-3 items-center">
                      <img v-if="item.employee.avatar" :src="item.employee.avatar" class="w-10 h-10 rounded-full object-cover border border-outline-variant/50" />
                      <div v-else class="w-10 h-10 rounded-full bg-secondary-container text-on-secondary-container flex items-center justify-center font-bold text-sm border border-outline-variant/50">
                        {{ getInitials(item.employee.name) }}
                      </div>
                      <div>
                        <h4 class="font-semibold text-on-surface text-sm group-hover:text-primary transition-colors">{{ item.employee.name }}</h4>
                        <p class="text-xs text-on-surface-variant">{{ item.employee.department }}</p>
                      </div>
                    </div>
                    <span v-if="item.type === 'visibility'" class="material-symbols-outlined text-outline-variant text-[20px]">visibility_off</span>
                    <span v-else class="material-symbols-outlined text-outline-variant text-[20px]">description</span>
                  </div>
                  <p class="text-sm text-on-surface-variant mb-4 line-clamp-1 pl-1">{{ item.description || `Update ${item.subtype.toLowerCase()} and related details` }}</p>
                  <div class="flex justify-between items-center text-xs pl-1">
                    <span class="text-on-surface-variant">{{ formatTimeAgo(item.created_at) }}</span>
                    <span class="px-2.5 py-0.5 rounded-full font-semibold bg-[#fff3e0] text-[#e65100] border border-[#ffe0b2]">Pending</span>
                  </div>
                </div>
              </transition-group>
              <div v-if="queueRemaining.length === 0" class="text-sm text-on-surface-variant italic py-6 text-center border border-dashed border-outline-variant rounded-xl bg-surface-container-low">
                  No other items in queue.
              </div>
            </div>
          </div>

          <!-- Recently Processed -->
          <div>
            <h3 class="font-label-md text-on-surface-variant uppercase tracking-[0.1em] mb-4 text-xs font-bold">Recently Processed</h3>
            <div class="flex flex-col gap-3">
              <transition-group name="list">
                <div v-for="item in processedRequests" :key="item.id" class="border border-outline-variant rounded-lg p-4 bg-surface-container-lowest flex items-center justify-between shadow-sm">
                  <div class="flex items-center gap-3 overflow-hidden">
                    <span v-if="item.status === 'approved'" class="material-symbols-outlined text-status-active shrink-0">check_circle</span>
                    <span v-else class="material-symbols-outlined text-status-error shrink-0">cancel</span>
                    <span class="text-sm text-on-surface font-medium truncate">{{ item.description || item.employee.name + ' (' + item.subtype + ')' }}</span>
                  </div>
                  <span class="text-xs text-on-surface-variant shrink-0 ml-2">{{ formatTimeAgo(item.updated_at) }}</span>
                </div>
              </transition-group>
              <div v-if="processedRequests.length === 0" class="text-sm text-on-surface-variant italic py-4 text-center">
                  No recently processed items.
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';
import AdminLayout from '../layouts/AdminLayout.vue';

const requests = ref([]);
const processing = ref(false);

const filter = ref('all');
const adminNotes = ref('');
const selectedRequestId = ref(null);

const pendingRequests = computed(() => requests.value.filter(r => r.status === 'pending'));
const processedRequests = computed(() => requests.value.filter(r => r.status !== 'pending').slice(0, 5));

const filteredRequests = computed(() => {
    let list = pendingRequests.value;
    if (filter.value === 'data_change') return list.filter(r => r.type === 'data_change');
    if (filter.value === 'visibility') return list.filter(r => r.type === 'visibility');
    return list;
});

const activeRequest = computed(() => {
    if (selectedRequestId.value) {
        const found = filteredRequests.value.find(r => r.id === selectedRequestId.value);
        if (found) return found;
    }
    return filteredRequests.value[0] || null;
});

const queueRemaining = computed(() => {
    if (!activeRequest.value) return filteredRequests.value;
    return filteredRequests.value.filter(r => r.id !== activeRequest.value.id);
});

const fetchRequests = async () => {
    try {
        const res = await axios.get('/api/approvals');
        requests.value = res.data;
    } catch (e) {
        console.error("Failed to fetch requests", e);
    }
};

const formatLabel = (key) => {
    const map = {
        'position': 'Job Title',
        'email': 'Email Address',
        'location': 'Office Location',
        'phone': 'Phone Number',
        'dob': 'Date of Birth',
        'nip': 'NIP / Employee ID'
    };
    if (map[key]) return map[key];
    const result = key.replace(/([A-Z])/g, " $1");
    return result.charAt(0).toUpperCase() + result.slice(1);
};

const getInitials = (name) => {
    if (!name) return '';
    return name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
};

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' });
};

const formatTimeAgo = (dateString) => {
    const rtf = new Intl.RelativeTimeFormat('en', { numeric: 'auto' });
    const daysDifference = Math.round((new Date(dateString).getTime() - new Date().getTime()) / (1000 * 60 * 60 * 24));
    if (daysDifference === 0) {
        const hoursDifference = Math.round((new Date(dateString).getTime() - new Date().getTime()) / (1000 * 60 * 60));
        if (hoursDifference === 0) return 'Just now';
        return rtf.format(hoursDifference, 'hour');
    }
    return rtf.format(daysDifference, 'day');
};

const setFilter = (f) => {
    filter.value = f;
    selectedRequestId.value = null;
    adminNotes.value = '';
};

const selectRequest = (id) => {
    selectedRequestId.value = id;
    adminNotes.value = '';
};

const skipRequest = () => {
    if (activeRequest.value) {
        // Move to end of pending array locally to simulate skip
        const index = requests.value.findIndex(r => r.id === activeRequest.value.id);
        if (index > -1) {
            const req = requests.value.splice(index, 1)[0];
            requests.value.push(req);
            selectedRequestId.value = null;
            adminNotes.value = '';
        }
    }
};

const processRequest = async (status) => {
    if (!activeRequest.value) return;
    
    if (status === 'rejected' && !adminNotes.value.trim()) {
        alert('Admin notes are required for rejection.');
        return;
    }
    
    processing.value = true;
    try {
        const req = activeRequest.value;
        const res = await axios.patch(`/api/approvals/${req.id}`, {
            status,
            admin_notes: adminNotes.value
        });
        
        // Update locally
        const index = requests.value.findIndex(r => r.id === req.id);
        if (index !== -1) {
            requests.value[index] = res.data;
            // Also need to put the employee relation back since it's just returned the model
            requests.value[index].employee = req.employee;
        }
        
        selectedRequestId.value = null;
        adminNotes.value = '';
    } catch (e) {
        console.error("Failed to process request", e);
        alert("Failed to process request");
    } finally {
        processing.value = false;
    }
};

const rejectRequest = () => processRequest('rejected');
const approveRequest = () => processRequest('approved');

onMounted(() => {
    fetchRequests();
});
</script>

<style scoped>
/* List transitions for smooth adding/removing from queue and processed lists */
.list-enter-active,
.list-leave-active {
  transition: all 0.3s ease;
}
.list-enter-from,
.list-leave-to {
  opacity: 0;
  transform: translateY(-15px) scale(0.98);
}
.list-leave-active {
  position: absolute;
  width: 100%;
}
</style>
