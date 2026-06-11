<template>
  <div class="h-screen bg-gradient-to-br from-[#f8fdfa] to-[#e6f4ea] flex flex-col font-sans text-gray-800 overflow-y-auto no-scrollbar items-center relative selection:bg-[#054a34] selection:text-white">
    <!-- Decorative background elements -->
    <div class="absolute top-0 left-0 w-full h-96 bg-gradient-to-b from-[#054a34]/5 to-transparent pointer-events-none"></div>
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-emerald-300/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute top-48 -left-24 w-72 h-72 bg-teal-300/20 rounded-full blur-3xl pointer-events-none"></div>

    <!-- Header -->
    <header class="w-full py-10 flex flex-col items-center justify-center shrink-0 relative z-10">
      <img src="/images/logo.png" alt="University Logo" class="h-20 w-auto mb-3 drop-shadow-md" />
      <h1 class="text-[26px] font-extrabold text-[#054a34] tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-[#054a34] to-[#0a7a58]">Profile Portal</h1>
    </header>

    <main class="flex-1 w-full max-w-[460px] px-5 flex flex-col justify-start pb-12 relative z-10">
      <div v-if="loading" class="flex justify-center items-center py-20">
        <div class="animate-spin rounded-full h-10 w-10 border-4 border-[#054a34] border-t-transparent"></div>
      </div>
      
      <div v-else-if="!employee || employee.visibility === 'hidden'" class="bg-white border border-gray-200 rounded-xl p-10 text-center shadow-[0_2px_8px_-2px_rgba(0,0,0,0.05)] mt-8">
        <span class="material-symbols-outlined text-5xl text-gray-400 mb-4">visibility_off</span>
        <h2 class="text-xl font-semibold text-gray-900 mb-2">Profile Unavailable</h2>
        <p class="text-[15px] text-gray-500">This employee profile is private or does not exist.</p>
      </div>

      <div v-else class="space-y-5 animate-fade-in">
        <!-- Top Card: Identity -->
        <div class="bg-white/80 backdrop-blur-xl border border-white/40 rounded-[24px] p-8 flex flex-col items-center text-center shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all duration-300">
          <!-- Avatar (with inner white border and outer gray ring) -->
          <div class="w-[116px] h-[116px] rounded-full border-[4px] border-white ring-2 ring-emerald-50 shadow-lg overflow-hidden bg-gradient-to-tr from-gray-100 to-gray-50 flex items-center justify-center mb-6 shrink-0 relative group">
            <div class="absolute inset-0 bg-emerald-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10"></div>
            <img v-if="employee.avatar" :src="employee.avatar" alt="Profile Avatar" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500" />
            <span v-else class="material-symbols-outlined text-6xl text-gray-300">person</span>
          </div>

          <!-- Name & Title -->
          <h2 class="text-[22px] font-bold text-gray-900 mb-1.5 tracking-tight leading-tight">{{ employee.name }}</h2>
          <p class="text-[15.5px] text-[#054a34]/80 font-medium mb-6">{{ employee.position }}</p>

          <!-- Verified Badge -->
          <div class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full bg-gradient-to-r from-emerald-50 to-[#dcfce7] border border-emerald-100 text-[#166534] text-[13px] font-bold shadow-sm">
            <span class="material-symbols-outlined text-[18px] leading-none text-emerald-500">verified</span>
            <span class="leading-none mt-0.5">Verified Profile</span>
          </div>
        </div>

        <!-- Details Card -->
        <div class="bg-white/80 backdrop-blur-xl border border-white/40 rounded-[24px] p-7 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all duration-300">
          <!-- Institutional Details -->
          <div class="flex items-center gap-2 mb-5">
            <span class="material-symbols-outlined text-[#054a34] text-[20px]">account_balance</span>
            <h3 class="text-[16px] font-bold text-gray-900">Institutional Details</h3>
          </div>
          
          <div class="grid grid-cols-2 gap-4 mb-6 bg-gray-50/50 rounded-2xl p-4 border border-gray-100">
            <div>
              <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-1">NIP</p>
              <p class="text-[15px] font-semibold text-gray-800">{{ employee.nip }}</p>
            </div>
            <div>
              <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-1">Department</p>
              <p class="text-[15px] font-semibold text-gray-800">{{ employee.department }}</p>
            </div>
          </div>

          <hr class="border-gray-100 mb-6" />

          <!-- Contact Information -->
          <div class="flex items-center gap-2 mb-5">
            <span class="material-symbols-outlined text-[#054a34] text-[20px]">contact_mail</span>
            <h3 class="text-[16px] font-bold text-gray-900">Contact Info</h3>
          </div>
          <div class="space-y-3">
            <!-- Email -->
            <div class="flex items-center justify-between border border-gray-200 rounded-lg p-3 bg-white">
              <div class="flex items-center gap-3 overflow-hidden">
                <span class="material-symbols-outlined text-gray-400 text-[20px]">mail</span>
                <a :href="`mailto:${employee.email}`" class="text-[14px] text-gray-800 truncate hover:text-[#054a34] transition-colors">{{ employee.email }}</a>
              </div>
              <button @click="copyText(employee.email)" class="text-gray-400 hover:text-[#054a34] transition-colors ml-2 shrink-0" title="Copy Email">
                <span class="material-symbols-outlined text-[18px]">content_copy</span>
              </button>
            </div>
            <!-- Phone -->
            <div class="flex items-center justify-between border border-gray-200 rounded-lg p-3 bg-white">
              <div class="flex items-center gap-3 overflow-hidden">
                <span class="material-symbols-outlined text-gray-400 text-[20px]">call</span>
                <a v-if="employee.phone" :href="`tel:${employee.phone}`" class="text-[14px] text-gray-800 hover:text-[#054a34] transition-colors">{{ employee.phone }}</a>
                <span v-else class="text-[14px] text-gray-400 italic">Not provided</span>
              </div>
              <button v-if="employee.phone" @click="copyText(employee.phone)" class="text-gray-400 hover:text-[#054a34] transition-colors ml-2 shrink-0" title="Copy Phone Number">
                <span class="material-symbols-outlined text-[18px]">content_copy</span>
              </button>
            </div>
            <!-- Location -->
            <div class="flex items-center gap-3 border border-gray-200 rounded-lg p-3 bg-white">
              <span class="material-symbols-outlined text-gray-400 text-[20px]">location_on</span>
              <span class="text-[14px] text-gray-800" :class="{'text-gray-400 italic': !employee.location}">{{ employee.location || 'Not provided' }}</span>
            </div>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="space-y-3 pt-4">
          <button @click="showDataModal = true" class="w-full flex items-center justify-center gap-2.5 bg-gradient-to-r from-[#054a34] to-[#076b4a] text-white py-4 px-4 rounded-[16px] text-[15px] font-bold hover:shadow-lg hover:shadow-emerald-900/20 hover:-translate-y-0.5 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-[#054a34] focus:ring-offset-2 border border-[#043d2b]/20">
            <span class="material-symbols-outlined text-[20px]">edit_note</span>
            Request Data Update
          </button>
          <button @click="showVisibilityModal = true" class="w-full flex items-center justify-center gap-2.5 bg-white border border-gray-200 text-gray-600 py-4 px-4 rounded-[16px] text-[15px] font-bold hover:bg-gray-50 hover:text-gray-900 transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-200 focus:ring-offset-2">
            <span class="material-symbols-outlined text-[20px]">security</span>
            Report Privacy Issue
          </button>
        </div>
      </div>
    </main>

    <!-- Footer -->
    <footer class="py-8 mt-auto flex flex-col items-center justify-center text-[13px] text-gray-500 shrink-0 relative z-10 w-full bg-white/40 backdrop-blur-sm border-t border-white/50">
      <p class="font-medium text-center px-4">© {{ new Date().getFullYear() }} Universitas Islam Internasional Darullughah Wadda'wah</p>
    </footer>

    <!-- Request Data Change Modal -->
    <div v-if="showDataModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 animate-fade-in backdrop-blur-sm">
      <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl flex flex-col max-h-[90vh] overflow-hidden transform transition-all">
        
        <!-- Sticky Header -->
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center shrink-0">
          <div>
            <h3 class="text-[18px] font-bold text-gray-900 tracking-tight">Request Data Update</h3>
            <p class="text-[13px] text-gray-500 mt-0.5">Modify the fields below to propose a change to your profile.</p>
          </div>
          <button @click="showDataModal = false" class="w-8 h-8 flex items-center justify-center rounded-full text-gray-400 hover:bg-gray-100 hover:text-gray-700 transition-colors">
            <span class="material-symbols-outlined text-[20px]">close</span>
          </button>
        </div>

        <!-- Scrollable Body -->
        <div class="p-6 overflow-y-auto no-scrollbar grow bg-gray-50/50">
          <div v-if="requestSuccess" class="bg-[#dcfce7] border border-[#bbf7d0] text-[#166534] p-5 rounded-xl mb-4 flex items-start gap-3">
            <span class="material-symbols-outlined shrink-0">check_circle</span>
            <div>
              <h4 class="font-semibold text-[14px]">Request Submitted!</h4>
              <p class="text-[13px] mt-0.5 opacity-90">Your data change request has been sent to administration. You may close this window.</p>
            </div>
          </div>
          
          <form v-else id="dataChangeForm" @submit.prevent="submitRequest('data')" class="space-y-6">
            
            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm space-y-5">
              <h4 class="text-[13px] font-bold text-gray-900 uppercase tracking-widest border-b border-gray-100 pb-2 mb-4">Core Information</h4>
              
              <div class="mb-5 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <label class="block text-[13px] font-bold text-yellow-900 mb-1.5 flex items-center gap-1.5"><span class="material-symbols-outlined text-[16px]">vpn_key</span> Security Code (5-digit) <span class="text-red-500">*</span></label>
                <p class="text-[12px] text-yellow-800 mb-2">Please enter your unique 5-digit profile code to verify your identity.</p>
                <input v-model="formData.unique_code" type="text" maxlength="5" class="w-full sm:w-1/2 px-3 py-2.5 bg-white border border-yellow-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 font-mono text-[16px] tracking-widest" required placeholder="12345" />
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                  <label class="block text-[12px] font-bold text-gray-700 mb-1.5">Email Address</label>
                  <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[18px]">mail</span>
                    <input v-model="formData.email" type="email" class="w-full pl-9 pr-3 py-2.5 bg-[#fcfcfc] border border-gray-300 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#054a34] focus:border-transparent text-[14px] transition-all" />
                  </div>
                </div>
                
                <div>
                  <label class="block text-[12px] font-bold text-gray-700 mb-1.5">Phone Number</label>
                  <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[18px]">call</span>
                    <input v-model="formData.phone" type="text" class="w-full pl-9 pr-3 py-2.5 bg-[#fcfcfc] border border-gray-300 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#054a34] focus:border-transparent text-[14px] transition-all" />
                  </div>
                </div>

                <div>
                  <label class="block text-[12px] font-bold text-gray-700 mb-1.5">Position / Title</label>
                  <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[18px]">badge</span>
                    <input v-model="formData.position" type="text" class="w-full pl-9 pr-3 py-2.5 bg-[#fcfcfc] border border-gray-300 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#054a34] focus:border-transparent text-[14px] transition-all" />
                  </div>
                </div>
                
                <div>
                  <label class="block text-[12px] font-bold text-gray-700 mb-1.5">Department</label>
                  <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[18px] z-10 pointer-events-none">domain</span>
                    <div @click="showDeptDropdown = !showDeptDropdown" class="w-full pl-9 pr-3 py-2.5 bg-[#fcfcfc] border border-gray-300 rounded-lg focus-within:bg-white focus-within:outline-none focus-within:ring-2 focus-within:ring-[#054a34] focus-within:border-transparent text-[14px] transition-all cursor-pointer flex justify-between items-center h-11">
                      <span :class="formData.department ? 'text-gray-900' : 'text-gray-500'">{{ formData.department || 'Select Department...' }}</span>
                      <span class="material-symbols-outlined text-gray-400 text-[18px]">{{ showDeptDropdown ? 'expand_less' : 'expand_more' }}</span>
                    </div>
                    <div v-if="showDeptDropdown" class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-48 overflow-y-auto">
                      <div @click="formData.department = ''; showDeptDropdown = false" class="px-4 py-2 text-[14px] text-gray-500 hover:bg-emerald-50 cursor-pointer">
                        Select Department...
                      </div>
                      <div v-for="dept in departments" :key="dept.id" @click="formData.department = dept.name; showDeptDropdown = false" class="px-4 py-2 text-[14px] text-gray-900 hover:bg-emerald-50 cursor-pointer border-t border-gray-50">
                        {{ dept.name }}
                      </div>
                    </div>
                  </div>
                </div>

                <div class="sm:col-span-2">
                  <label class="block text-[12px] font-bold text-gray-700 mb-1.5">Office Location</label>
                  <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[18px]">location_on</span>
                    <input v-model="formData.location" type="text" class="w-full pl-9 pr-3 py-2.5 bg-[#fcfcfc] border border-gray-300 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#054a34] focus:border-transparent text-[14px] transition-all" />
                  </div>
                </div>
              </div>
            </div>

            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm space-y-5">
              <h4 class="text-[13px] font-bold text-gray-900 uppercase tracking-widest border-b border-gray-100 pb-2 mb-4">Additional Details</h4>
              
              <div>
                <label class="block text-[12px] font-bold text-gray-700 mb-1.5">Biography / Notes</label>
                <textarea v-model="formData.bio" rows="3" class="w-full bg-[#fcfcfc] border border-gray-300 rounded-lg py-2.5 px-3 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#054a34] focus:border-transparent text-[14px] resize-none transition-all" placeholder="Enter biography details..."></textarea>
              </div>
              
              <div>
                <label class="block text-[12px] font-bold text-gray-700 mb-1.5">Reason for change <span class="text-gray-400 font-normal">(Optional)</span></label>
                <textarea v-model="requestReason" rows="2" class="w-full bg-[#fcfcfc] border border-gray-300 rounded-lg py-2.5 px-3 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#054a34] focus:border-transparent text-[14px] resize-none transition-all" placeholder="Explain why this needs to be updated..."></textarea>
              </div>
            </div>
            
          </form>
        </div>

        <!-- Sticky Footer -->
        <div v-if="!requestSuccess" class="px-6 py-4 border-t border-gray-200 bg-white flex justify-end gap-3 shrink-0">
          <button type="button" @click="showDataModal = false" class="px-5 py-2.5 border border-gray-300 rounded-lg text-[13px] font-bold text-gray-700 hover:bg-gray-50 transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-200">
            Cancel
          </button>
          <button form="dataChangeForm" type="submit" class="px-5 py-2.5 bg-[#054a34] text-white rounded-lg text-[13px] font-bold hover:bg-[#033323] transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-[#054a34] focus:ring-offset-2 disabled:opacity-70 flex items-center gap-2" :disabled="isSubmitting || requestSuccess">
            <span v-if="isSubmitting" class="material-symbols-outlined animate-spin text-[16px]">progress_activity</span>
            {{ isSubmitting ? 'Submitting...' : 'Submit Request' }}
          </button>
        </div>
        
      </div>
    </div>

    <!-- Report Visibility Issue Modal -->
    <div v-if="showVisibilityModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 animate-fade-in backdrop-blur-sm">
      <div class="bg-white rounded-xl shadow-xl w-full max-w-sm p-6 transform transition-all">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-semibold text-gray-900">Report Visibility Issue</h3>
          <button @click="showVisibilityModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
            <span class="material-symbols-outlined">close</span>
          </button>
        </div>
        <div v-if="requestSuccess" class="bg-[#dcfce7] text-[#166534] p-4 rounded-lg mb-4 text-[14px]">
          Your report has been received. Administration will handle this issue.
        </div>
        <form v-else @submit.prevent="submitRequest('visibility')" class="space-y-4">
          <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
            <label class="block text-[13px] font-bold text-yellow-900 mb-1.5 flex items-center gap-1.5"><span class="material-symbols-outlined text-[16px]">vpn_key</span> Security Code <span class="text-red-500">*</span></label>
            <input v-model="formData.unique_code" type="text" maxlength="5" class="w-full px-3 py-2 bg-white border border-yellow-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 font-mono text-[14px] tracking-widest" required placeholder="Enter 5-digit code" />
          </div>
          <div>
            <label class="block text-[13px] font-medium text-gray-700 mb-1">Requested Visibility <span class="text-red-500">*</span></label>
            <select v-model="requestedVisibility" class="w-full border border-gray-300 rounded-lg py-2 px-3 mb-4 focus:outline-none focus:ring-2 focus:ring-[#054a34] focus:border-transparent text-[14px]" required>
              <option value="public">Public</option>
              <option value="hidden">Hidden</option>
            </select>
          </div>
          <div>
            <label class="block text-[13px] font-medium text-gray-700 mb-1">Please describe the reason <span class="text-red-500">*</span></label>
            <textarea v-model="requestReason" rows="4" class="w-full border border-gray-300 rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-[#054a34] focus:border-transparent text-[14px] resize-none" placeholder="E.g. This profile should not be public..." required></textarea>
          </div>
          <div v-if="!requestSuccess" class="flex gap-3 justify-end pt-2">
            <button type="button" @click="showVisibilityModal = false" class="px-4 py-2 border border-gray-300 rounded-lg text-[14px] text-gray-700 hover:bg-gray-50 font-medium transition-colors">Cancel</button>
            <button type="submit" class="px-4 py-2 bg-[#054a34] text-white rounded-lg text-[14px] hover:bg-[#033323] font-medium transition-colors" :disabled="isSubmitting">
              {{ isSubmitting ? 'Submitting...' : 'Submit Report' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';

const route = useRoute();
const employee = ref(null);
const loading = ref(true);
const departments = ref([]);

// Modal states
const showDataModal = ref(false);
const showVisibilityModal = ref(false);
const showDeptDropdown = ref(false);
const requestReason = ref('');
const requestedVisibility = ref('hidden');
const isSubmitting = ref(false);
const requestSuccess = ref(false);

const formData = ref({
  email: '',
  position: '',
  department: '',
  phone: '',
  location: '',
  bio: '',
  unique_code: ''
});

const fetchDepartments = async () => {
  try {
    const res = await axios.get('/api/departments');
    departments.value = res.data;
  } catch (err) {
    console.error('Failed to fetch departments:', err);
  }
};

const fetchProfile = async () => {
  try {
    const res = await axios.get(`/api/employees/${route.params.id}`);
    employee.value = res.data;
    formData.value = {
      email: employee.value.email || '',
      position: employee.value.position || '',
      department: employee.value.department || '',
      phone: employee.value.phone || '',
      location: employee.value.location || '',
      bio: employee.value.bio || '',
      unique_code: ''
    };
  } catch(e) {
    console.error("Failed to fetch public profile", e);
  } finally {
    loading.value = false;
  }
};

const copyText = (text) => {
  if (text) {
    navigator.clipboard.writeText(text);
    alert('Copied to clipboard!');
  }
};

const submitRequest = async (type) => {
  if (!employee.value) return;
  isSubmitting.value = true;
  
  try {
    let current_data = null;
    let requested_data = null;

    if (type === 'data') {
      current_data = {};
      requested_data = {};
      
      // Include all fields from the form so the admin can see the full comparison
      for (const key in formData.value) {
        current_data[key] = employee.value[key] || '-';
        requested_data[key] = formData.value[key] || '-';
      }
    } else if (type === 'visibility') {
      current_data = { visibility: employee.value.visibility || 'public' };
      requested_data = { visibility: requestedVisibility.value };
    }

    const payload = {
      employee_id: employee.value.id,
      unique_code: formData.value.unique_code,
      type: type === 'data' ? 'data_change' : 'visibility',
      subtype: type === 'data' ? 'Data Update' : 'Visibility',
      description: requestReason.value,
      current_data: current_data,
      requested_data: requested_data
    };
    
    await axios.post('/api/approvals', payload);
    requestSuccess.value = true;
    
    setTimeout(() => {
      showDataModal.value = false;
      showVisibilityModal.value = false;
    }, 3000);
  } catch (error) {
    console.error("Failed to submit request", error);
    const msg = error.response?.data?.message || "Failed to submit request. Please try again.";
    alert(msg);
  } finally {
    isSubmitting.value = false;
  }
};

// Reset modal state when closed
watch([showDataModal, showVisibilityModal], ([newData, newVisibility]) => {
  if (!newData && !newVisibility) {
    setTimeout(() => {
      requestReason.value = '';
      requestedVisibility.value = 'hidden';
      requestSuccess.value = false;
      if (employee.value) {
        formData.value = {
          email: employee.value.email || '',
          position: employee.value.position || '',
          department: employee.value.department || '',
          phone: employee.value.phone || '',
          location: employee.value.location || '',
          bio: employee.value.bio || '',
          unique_code: ''
        };
      }
    }, 300); // Wait for transition
  }
});

onMounted(() => {
  fetchDepartments();
  fetchProfile();
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
