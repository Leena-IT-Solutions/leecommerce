<template>
<div class="crud-wrapper animate-fade-in">

    <!-- Title and Header Panel -->
    <div class="header-panel mb-4">
        <div class="row align-items-center justify-content-between g-3">
            <div class="col-12 col-sm-auto">
                <div class="title-container">
                    <h5 class="page-title text-uppercase m-0">User Manager</h5>
                    <span v-if="totalUsers !== null" class="count-badge">
                        <i class="fas fa-users mr-1"></i> {{ totalUsers }} Total
                    </span>
                </div>
            </div>
            <div class="col-12 col-sm-auto text-sm-right">
                <button v-if="!isFormOpen" @click="openCreateForm" class="btn btn-add text-uppercase">
                    <i class="fas fa-user-plus mr-1"></i> Add User
                </button>
                <button v-if="isFormOpen" @click="closeForm" class="btn btn-close-form text-uppercase">
                    <i class="fas fa-times mr-1"></i> Close Form
                </button>
            </div>
        </div>
    </div>

    <!-- Add/Edit Form Card -->
    <div v-if="isFormOpen" class="crud-card mb-4 animate-fade-in">
        <div class="card-header-sub mb-3">
            <h6 class="text-uppercase m-0 font-weight-bold text-slate-800">
                {{ id === 0 ? 'Create New User' : 'Edit User #' + id }}
            </h6>
        </div>
        
        <form @submit.prevent="saveUser">
            <div v-if="successMessage" class="p-3 alert alert-success rounded mb-4 text-capitalize d-flex align-items-center">
                <i class="fas fa-check-circle mr-2"></i> {{ successMessage }}
            </div>
            <div v-if="errorMessage" class="p-3 alert alert-danger rounded mb-4 text-capitalize d-flex align-items-center">
                <i class="fas fa-exclamation-circle mr-2"></i> {{ errorMessage }}
            </div>

            <div class="row g-3">
                <!-- Full Name -->
                <div class="col-12 col-md-6">
                    <label class="disable-select form-label" for="name">Full Name</label>
                    <input class="form-control" type="text" id="name" v-model="name" placeholder="Enter Full Name" @input="clearFieldError('name')">
                    <span v-if="validationErrors.name" class="disable-select form-error">{{ validationErrors.name }}</span>
                </div>

                <!-- Mobile -->
                <div class="col-12 col-md-6">
                    <label class="disable-select form-label" for="mobile">Mobile</label>
                    <input class="form-control" type="text" id="mobile" v-model="mobile" placeholder="Enter Mobile Number" @input="clearFieldError('mobile')">
                    <span v-if="validationErrors.mobile" class="disable-select form-error">{{ validationErrors.mobile }}</span>
                </div>

                <!-- Email -->
                <div class="col-12 col-md-6">
                    <label class="disable-select form-label" for="email">Email Address</label>
                    <input class="form-control" type="email" id="email" v-model="email" placeholder="Enter Email Address" @input="clearFieldError('email')">
                    <span v-if="validationErrors.email" class="disable-select form-error">{{ validationErrors.email }}</span>
                </div>

                <!-- Password -->
                <div class="col-12 col-md-6">
                    <label class="disable-select form-label" for="password">Password</label>
                    <input class="form-control" type="password" id="password" v-model="password" :placeholder="id === 0 ? 'Enter Password' : 'Leave blank to keep unchanged'" @input="clearFieldError('password')">
                    <span v-if="validationErrors.password" class="disable-select form-error">{{ validationErrors.password }}</span>
                    <span v-if="id !== 0" class="disable-select form-hint">Leave blank if you do not want to change the password.</span>
                </div>

                <div class="col-12 mt-4 form-actions">
                    <button class="btn btn-submit text-uppercase" type="submit">
                        <i class="fas fa-check mr-1"></i> Submit
                    </button>
                    <button @click.prevent="resetForm" class="btn btn-reset text-uppercase" type="button">
                        <i class="fas fa-undo mr-1"></i> Reset
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Advanced Search Component -->
    <data-search
        :columns="[
            { value: 'id', label: 'ID' },
            { value: 'name', label: 'Name' },
            { value: 'mobile', label: 'Mobile' },
            { value: 'email', label: 'Email' }
        ]"
        :per-page.sync="perPage"
        :search-key.sync="searchKey"
        :search-res.sync="searchRes"
        :search-val.sync="searchVal"
        @search="submitSearch"
    ></data-search>

    <!-- Table Results -->
    <div class="results-container mb-4">
        <div class="table-card">
            <div class="table-responsive">
                <table class="table" id="users-table">
                    <thead>
                        <tr>
                            <th style="width: 80px;" class="text-center">ID</th>
                            <th>Name</th>
                            <th>Mobile</th>
                            <th>Email</th>
                            <th style="width: 160px;" class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="user in users" :key="user.id" class="table-row-hover">
                            <td class="text-center font-weight-bold text-slate-500 align-middle">#{{ user.id }}</td>
                            <td class="align-middle font-medium text-slate-800">{{ user.name }}</td>
                            <td class="align-middle text-slate-600">{{ user.mobile }}</td>
                            <td class="align-middle text-slate-600">{{ user.email }}</td>
                            <td class="text-right align-middle text-nowrap">
                                <button @click="editUser(user)" class="btn btn-action-edit" title="Edit User"><i class="fas fa-pen"></i></button>
                                <button @click="deleteUser(user.id)" class="btn btn-action-delete" title="Delete User"><i class="fas fa-trash"></i></button>
                                <a class="btn btn-action-link" :href="'/administrator/user_manager/roles/'+user.id" target="_blank" title="Manage User Roles"><i class="fas fa-user-shield"></i></a>
                            </td>
                        </tr>
                        <tr v-if="users.length === 0">
                            <td colspan="5" class="text-center py-5 text-slate-400">
                                <div class="empty-state">
                                    <i class="fas fa-user-slash fa-2x mb-3 text-slate-300"></i>
                                    <p class="m-0 font-medium">No users found matching the query.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Load More & Export -->
    <div class="footer-actions-panel mb-5">
        <div class="d-flex justify-content-between align-items-center flex-wrap g-3">
            <button class="btn btn-load-more text-uppercase" @click="loadMore" :disabled="currentPage == lastPage || users.length === 0">
                <i class="fas fa-chevron-down mr-1"></i> Load More
            </button>
            <button class="btn btn-export text-uppercase" @click="exportToExcel">
                <i class="fas fa-file-excel mr-1"></i> Export to Excel
            </button>
        </div>
    </div>

</div>
</template>

<script>
export default {
    props: [],

    data() {
        return {
            users: [],
            id: 0,
            name: '',
            mobile: '',
            email: '',
            password: '',
            
            searchKey: '',
            searchVal: '',
            searchRes: 'Anywhere',
            perPage: 10,
            
            currentPage: 1,
            lastPage: 1,
            totalUsers: 0,
            
            isFormOpen: false,
            successMessage: '',
            errorMessage: '',
            validationErrors: {}
        };
    },

    methods: {
        openCreateForm() {
            this.resetForm();
            this.id = 0;
            this.isFormOpen = true;
            this.successMessage = '';
            this.errorMessage = '';
        },

        closeForm() {
            this.isFormOpen = false;
            this.resetForm();
        },

        resetForm() {
            this.name = '';
            this.mobile = '';
            this.email = '';
            this.password = '';
            this.validationErrors = {};
        },

        clearFieldError(field) {
            if (this.validationErrors[field]) {
                this.$set(this.validationErrors, field, '');
            }
            this.errorMessage = '';
        },

        fetchUsers(page = 1) {
            if (page === 1) {
                this.users = [];
            }
            
            const params = {
                model: 'User',
                key: this.searchKey,
                val: this.searchVal,
                res: this.searchRes,
                ds: 'No',
                r: this.perPage,
                page: page
            };

            window.axios.get('/crud', { params })
                .then(res => {
                    this.currentPage = res.data.current_page;
                    this.lastPage = res.data.last_page;
                    this.totalUsers = res.data.total;
                    
                    res.data.data.forEach(user => {
                        this.users.push(user);
                    });
                })
                .catch(err => {
                    console.error('Error fetching users:', err);
                });
        },

        loadMore() {
            if (this.currentPage < this.lastPage) {
                this.fetchUsers(this.currentPage + 1);
            }
        },

        submitSearch() {
            this.fetchUsers(1);
        },

        editUser(user) {
            this.isFormOpen = true;
            window.scroll(0, 0);
            this.id = user.id;
            this.name = user.name;
            this.mobile = user.mobile;
            this.email = user.email;
            this.password = ''; // Leave password empty for security, making it optional on edit!
            this.validationErrors = {};
            this.successMessage = '';
            this.errorMessage = '';
        },

        saveUser() {
            this.successMessage = '';
            this.errorMessage = '';
            this.validationErrors = {};

            const fd = new FormData();
            fd.append('model', 'User');
            fd.append('id', this.id);
            fd.append('form_action', 'CRUD');
            fd.append('name', this.name);
            fd.append('mobile', this.mobile);
            fd.append('email', this.email);

            let crudnames = ['name', 'mobile', 'email'];
            let crudtypes = ['text', 'text', 'text'];
            let crudvalidation = ['required|string', 'required|string', 'required|string'];

            // Only send password if we are creating a new user, or if we are editing and a new password was provided
            if (this.id === 0 || this.password !== '') {
                fd.append('password', this.password);
                crudnames.push('password');
                crudtypes.push('password');
                
                if (this.id === 0) {
                    crudvalidation.push('required|string');
                } else {
                    crudvalidation.push('nullable|string');
                }
            }

            fd.append('crudnames', crudnames.join(','));
            fd.append('crudtypes', crudtypes.join(','));
            fd.append('crudvalidation', crudvalidation.join(','));

            window.axios.post('/crud', fd)
                .then(res => {
                    this.successMessage = this.id === 0 ? 'User created successfully.' : 'User updated successfully.';
                    this.fetchUsers(1);
                    
                    if (this.id === 0) {
                        this.resetForm();
                        // Close form after a short delay so they can see success
                        setTimeout(() => {
                            this.isFormOpen = false;
                        }, 1200);
                    } else {
                        // For edit, clear password field and errors
                        this.password = '';
                    }
                })
                .catch(error => {
                    if (error.response && error.response.status === 422) {
                        this.errorMessage = error.response.data.message;
                        const errors = error.response.data.errors;
                        
                        Object.keys(errors).forEach(key => {
                            this.$set(this.validationErrors, key, errors[key].join(', '));
                        });
                    } else {
                        this.errorMessage = 'An error occurred while saving the user.';
                        console.error('Error saving user:', error);
                    }
                });
        },

        deleteUser(id) {
            const c = confirm('Are you sure you want to delete this user?');
            if (c) {
                window.axios.delete('/crud/' + id, {
                    params: {
                        model: 'User',
                        files: ''
                    }
                })
                .then(res => {
                    if (res.data === 'ok') {
                        this.fetchUsers(1);
                    }
                })
                .catch(err => {
                    console.error('Error deleting user:', err);
                });
            }
        },

        exportToExcel() {
            const tableSelect = document.getElementById('users-table');
            const tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
            const filename = 'users_list.xls';
            const dataType = 'application/vnd.ms-excel';

            const downloadLink = document.createElement('a');
            document.body.appendChild(downloadLink);

            if (navigator.msSaveOrOpenBlob) {
                const blob = new Blob(['\ufeff', tableHTML], { type: dataType });
                navigator.msSaveOrOpenBlob(blob, filename);
            } else {
                downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
                downloadLink.download = filename;
                downloadLink.click();
            }
        }
    },

    created() {
        this.fetchUsers(1);
    }
};
</script>

<style scoped>
.disable-select {
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

/* Color System & General Variables */
.crud-wrapper {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    color: #1e293b;
    padding: 1rem !important;
}

@media (min-width: 576px) {
    .crud-wrapper {
        padding: 1.5rem !important;
    }
}

/* Header Panel Styles */
.header-panel {
    background: transparent;
    padding: 1rem 0;
}
.title-container {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-wrap: wrap;
}
.page-title {
    font-size: 1.35rem;
    font-weight: 800;
    color: #0f172a;
    letter-spacing: -0.02em;
}
.count-badge {
    background: rgba(99, 102, 241, 0.08);
    color: #4f46e5;
    border: 1px solid rgba(99, 102, 241, 0.15);
    padding: 0.25rem 0.65rem;
    border-radius: 30px;
    font-size: 0.78rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
}

/* Base Button Styles */
.btn {
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.85rem;
    padding: 0.5rem 1.15rem;
    transition: all 0.2s ease;
    border: none;
    box-shadow: none;
}
.btn:focus {
    outline: none;
    box-shadow: none;
}

/* Header Action Buttons */
.btn-add {
    background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%) !important;
    color: #ffffff !important;
    box-shadow: 0 4px 10px rgba(79, 70, 229, 0.18) !important;
}
.btn-add:hover {
    background: linear-gradient(135deg, #4338ca 0%, #4f46e5 100%) !important;
    transform: translateY(-1px);
    box-shadow: 0 6px 14px rgba(79, 70, 229, 0.24) !important;
}
.btn-close-form {
    background: #fff1f2 !important;
    color: #e11d48 !important;
    border: 1px solid rgba(244, 63, 94, 0.2) !important;
    white-space: nowrap !important;
    display: inline-flex !important;
    align-items: center;
    gap: 0.35rem;
}
.btn-close-form:hover {
    background: #ffe4e6 !important;
    color: #be123c !important;
    transform: translateY(-1px);
}

/* Form Card Styling */
.crud-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    box-shadow: 0 10px 15px -3px rgba(15, 23, 42, 0.04), 0 4px 6px -2px rgba(15, 23, 42, 0.02);
    padding: 2rem;
    transition: all 0.3s ease;
}
.crud-card .row > [class*="col-"] {
    margin-bottom: 0 !important;
    padding-top: 0.35rem !important;
    padding-bottom: 0.35rem !important;
}
.card-header-sub {
    border-bottom: 1px solid #e2e8f0;
    padding-bottom: 1rem;
}
.card-header-sub h6 {
    font-size: 1rem;
    font-weight: 700;
    letter-spacing: -0.01em;
    color: #0f172a;
}

/* Form Inputs & Labels */
.form-label {
    font-size: 0.85rem;
    font-weight: 600;
    color: #334155;
    margin-bottom: 0.4rem;
    letter-spacing: 0.01em;
}
.form-control {
    border-radius: 8px;
    border: 1px solid #cbd5e1;
    padding: 0.65rem 0.9rem;
    font-size: 0.92rem;
    color: #0f172a;
    background: #ffffff;
    transition: all 0.2s ease;
}
.form-control::placeholder {
    color: #94a3b8;
}
.form-control:focus {
    border-color: #6366f1 !important;
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1) !important;
    outline: none;
}
.form-hint {
    font-size: 0.72rem;
    color: #94a3b8;
    margin-top: 0.25rem;
    display: block;
    font-weight: 400;
}
.form-error {
    font-size: 0.75rem;
    color: #ef4444;
    font-weight: 600;
    margin-top: 0.25rem;
    display: block;
}

/* Form Action Buttons */
.form-actions {
    display: flex;
    gap: 0.75rem;
}
.btn-submit {
    background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%) !important;
    color: #ffffff !important;
    box-shadow: 0 4px 10px rgba(79, 70, 229, 0.18) !important;
    padding: 0.6rem 1.5rem !important;
}
.btn-submit:hover {
    background: linear-gradient(135deg, #4338ca 0%, #4f46e5 100%) !important;
    transform: translateY(-1px);
    box-shadow: 0 6px 14px rgba(79, 70, 229, 0.24) !important;
}
.btn-reset {
    background: #f1f5f9 !important;
    color: #475569 !important;
    border: 1px solid #cbd5e1 !important;
    padding: 0.6rem 1.5rem !important;
}
.btn-reset:hover {
    background: #e2e8f0 !important;
    color: #1e293b !important;
}

/* Search Filters Card */
.search-card {
    background: #ffffff;
    border: 1px solid rgba(226, 232, 240, 0.8);
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.02);
    padding: 1.25rem 1.5rem;
}
.filter-label {
    font-size: 0.78rem;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    margin-bottom: 0.35rem;
    letter-spacing: 0.03em;
    display: block;
}
.filter-control, .filter-select {
    border-radius: 8px;
    border: 1px solid #cbd5e1;
    font-size: 0.88rem;
    padding: 0.5rem 0.75rem;
    color: #334155;
    background: #ffffff;
    height: auto;
}
.filter-select {
    max-width: 100%;
}
.btn-search {
    background: #0f172a !important;
    color: #ffffff !important;
    border-radius: 0 8px 8px 0 !important;
    padding: 0.5rem 1.25rem !important;
    border: none;
}
.btn-search:hover {
    background: #1e293b !important;
}

/* Results Container & Responsive Table Card */
.results-container {
    width: 100%;
}
.table-card {
    background: #ffffff;
    border: 1px solid rgba(226, 232, 240, 0.8);
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
    overflow: hidden;
}
.table-responsive {
    margin: 0;
}
.table {
    margin-bottom: 0;
    width: 100%;
}
.table thead th {
    background: #f8fafc;
    color: #475569;
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 0.95rem 1.25rem;
    border-bottom: 2px solid #e2e8f0;
    border-top: none;
    white-space: nowrap;
}
.table tbody td {
    padding: 1rem 1.25rem;
    vertical-align: middle;
    color: #334155;
    font-size: 0.88rem;
    border-top: none;
    border-bottom: 1px solid #f1f5f9;
}
.table-row-hover {
    transition: background-color 0.15s ease;
}
.table-row-hover:hover {
    background-color: #f8fafc;
}

/* Action Button Styles */
.btn-action-edit, .btn-action-delete, .btn-action-link {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: none;
    font-size: 0.85rem;
    transition: all 0.2s ease;
    text-decoration: none;
    cursor: pointer;
    margin-right: 0.35rem;
}
.btn-action-edit {
    background: #fef3c7;
    color: #d97706;
}
.btn-action-edit:hover {
    background: #fde68a;
    color: #b45309;
    transform: translateY(-1px);
}
.btn-action-delete {
    background: #fee2e2;
    color: #dc2626;
}
.btn-action-delete:hover {
    background: #fecaca;
    color: #b91c1c;
    transform: translateY(-1px);
}
.btn-action-link {
    background: #e0e7ff;
    color: #4f46e5;
}
.btn-action-link:hover {
    background: #c7d2fe;
    color: #3730a3;
    transform: translateY(-1px);
}

/* Alerts custom styling */
.alert {
    border: none;
    font-weight: 500;
    font-size: 0.9rem;
}
.alert-success {
    background-color: #ecfdf5;
    color: #065f46;
    border: 1px solid #a7f3d0;
}
.alert-danger {
    background-color: #fff1f2;
    color: #9f1239;
    border: 1px solid #fecdd3;
}

/* Footer Load More & Export Section */
.footer-actions-panel {
    width: 100%;
}
.btn-load-more {
    background: #ffffff !important;
    color: #475569 !important;
    border: 1px solid #cbd5e1 !important;
    padding: 0.6rem 1.5rem !important;
    box-shadow: 0 2px 5px rgba(0,0,0,0.02) !important;
}
.btn-load-more:hover:not(:disabled) {
    background: #f8fafc !important;
    color: #1e293b !important;
    border-color: #94a3b8 !important;
}
.btn-load-more:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
.btn-export {
    background: #ecfdf5 !important;
    color: #047857 !important;
    border: 1px solid #a7f3d0 !important;
    box-shadow: 0 2px 5px rgba(0,0,0,0.02) !important;
}
.btn-export:hover {
    background: #d1fae5 !important;
    color: #065f46 !important;
    border-color: #6ee7b7 !important;
    transform: translateY(-1px);
}

/* Grid Gap fallback for Bootstrap < 5 */
.g-3 {
    margin-top: -0.5rem;
    margin-bottom: -0.5rem;
}
.g-3 > [class*="col-"] {
    padding-top: 0.5rem;
    padding-bottom: 0.5rem;
}

/* Empty State */
.empty-state {
    padding: 2rem;
}
.empty-state p {
    font-size: 0.95rem;
    font-weight: 500;
}

.text-slate-500 { color: #64748b; }
.text-slate-600 { color: #475569; }
.text-slate-800 { color: #1e293b; }
.font-medium { font-weight: 500; }
.font-weight-bold { font-weight: 700; }

/* Micro UX animations */
.animate-fade-in {
    animation: fadeIn 0.25s ease-out;
}
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(6px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
