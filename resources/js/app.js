import './bootstrap';
import '../css/app.css';

import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';
import App from './App.vue';

// Views
import AdminDashboard from './views/AdminDashboard.vue';
import EmployeeDirectory from './views/EmployeeDirectory.vue';
import EmployeeDetail from './views/EmployeeDetail.vue';
import PublicProfile from './views/PublicProfile.vue';
import Approvals from './views/Approvals.vue';
import QRReport from './views/QRReport.vue';
import Departments from './views/Departments.vue';

import Login from './views/Login.vue';
import EmployeeForm from './views/EmployeeForm.vue';
import Settings from './views/Settings.vue';
import Support from './views/Support.vue';

const routes = [
    { path: '/', component: AdminDashboard, name: 'dashboard' },
    { path: '/login', component: Login, name: 'login' },
    { path: '/directory', component: EmployeeDirectory, name: 'directory' },
    { path: '/directory/new', component: EmployeeForm, name: 'employee.create' },
    { path: '/directory/:id/edit', component: EmployeeForm, name: 'employee.edit' },
    { path: '/directory/:id', component: EmployeeDetail, name: 'employee.detail' },
    { path: '/public/:id', component: PublicProfile, name: 'public.profile' },
    { path: '/approvals', component: Approvals, name: 'approvals' },
    { path: '/reports/qr', component: QRReport, name: 'reports.qr' },
    { path: '/departments', component: Departments, name: 'departments' },
    { path: '/settings', component: Settings, name: 'settings' },
    { path: '/support', component: Support, name: 'support' },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

import axios from 'axios';
axios.interceptors.response.use(
    response => response,
    error => {
        if (error.response && error.response.status === 401) {
            // Session expired or unauthenticated
            if (router.currentRoute.value.name !== 'login') {
                router.push('/login');
            }
        }
        return Promise.reject(error);
    }
);

const app = createApp(App);
app.use(router);
app.mount('#app');
