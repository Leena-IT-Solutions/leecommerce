<template>
<div class="crud-wrapper animate-fade-in">

    <!-- Title and Header Panel -->
    <div class="header-panel mb-4">
        <div class="row align-items-center justify-content-between g-3">
            <div class="col-12 col-sm-auto">
                <div class="title-container">
                    <h5 class="page-title text-uppercase m-0">Purchase Manager</h5>
                    <span v-if="totalPurchases !== null" class="count-badge">
                        <i class="fas fa-shopping-cart mr-1"></i> {{ totalPurchases }} Total Purchases
                    </span>
                </div>
            </div>
            <div class="col-12 col-sm-auto text-sm-right">
                <button v-if="!isFormOpen" @click="openCreateForm" class="btn btn-add text-uppercase">
                    <i class="fas fa-plus mr-1"></i> Add Purchase
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
                {{ id === 0 ? 'Create New Purchase Record' : 'Edit Purchase Record #' + id }}
            </h6>
        </div>
        
        <form @submit.prevent="savePurchase">
            <div v-if="successMessage" class="p-3 alert alert-success rounded mb-4 text-capitalize d-flex align-items-center">
                <i class="fas fa-check-circle mr-2"></i> {{ successMessage }}
            </div>
            <div v-if="errorMessage" class="p-3 alert alert-danger rounded mb-4 text-capitalize d-flex align-items-center">
                <i class="fas fa-exclamation-circle mr-2"></i> {{ errorMessage }}
            </div>

            <div class="row g-3">
                <!-- Product ID -->
                <div class="col-12 col-sm-6 col-md-3">
                    <label class="disable-select form-label" for="product_id">Product ID</label>
                    <input class="form-control" type="text" id="product_id" v-model="product_id" placeholder="Enter Product ID" @input="clearFieldError('product_id')">
                    <span v-if="validationErrors.product_id" class="disable-select form-error">{{ validationErrors.product_id }}</span>
                </div>

                <!-- Date -->
                <div class="col-12 col-sm-6 col-md-3">
                    <label class="disable-select form-label" for="date">Date</label>
                    <input class="form-control" type="date" id="date" v-model="date" @change="clearFieldError('date')">
                    <span v-if="validationErrors.date" class="disable-select form-error">{{ validationErrors.date }}</span>
                </div>

                <!-- Bill No -->
                <div class="col-12 col-sm-6 col-md-3">
                    <label class="disable-select form-label" for="bill_no">Bill No</label>
                    <input class="form-control" type="text" id="bill_no" v-model="bill_no" placeholder="Bill No" @input="clearFieldError('bill_no')">
                    <span v-if="validationErrors.bill_no" class="disable-select form-error">{{ validationErrors.bill_no }}</span>
                </div>

                <!-- Supplier -->
                <div class="col-12 col-sm-6 col-md-3">
                    <label class="disable-select form-label" for="supplier">Supplier</label>
                    <input class="form-control" type="text" id="supplier" v-model="supplier" placeholder="Supplier Name" @input="clearFieldError('supplier')">
                    <span v-if="validationErrors.supplier" class="disable-select form-error">{{ validationErrors.supplier }}</span>
                </div>

                <!-- Quantity -->
                <div class="col-12 col-sm-6 col-md-4">
                    <label class="disable-select form-label" for="quantity">Quantity</label>
                    <input class="form-control" type="text" id="quantity" v-model="quantity" placeholder="Enter Quantity" @input="onQtyOrRateChange">
                    <span v-if="validationErrors.quantity" class="disable-select form-error">{{ validationErrors.quantity }}</span>
                </div>

                <!-- Rate -->
                <div class="col-12 col-sm-6 col-md-4">
                    <label class="disable-select form-label" for="rate">Rate</label>
                    <input class="form-control" type="text" id="rate" v-model="rate" placeholder="Enter Rate" @input="onQtyOrRateChange">
                    <span v-if="validationErrors.rate" class="disable-select form-error">{{ validationErrors.rate }}</span>
                </div>

                <!-- Amount -->
                <div class="col-12 col-sm-12 col-md-4">
                    <label class="disable-select form-label" for="amount">Amount (Calculated)</label>
                    <input class="form-control" type="text" id="amount" v-model="amount" placeholder="Calculated Amount" @input="clearFieldError('amount')">
                    <span v-if="validationErrors.amount" class="disable-select form-error">{{ validationErrors.amount }}</span>
                </div>

                <!-- Narration -->
                <div class="col-12">
                    <label class="disable-select form-label" for="narration">Narration</label>
                    <input class="form-control" type="text" id="narration" v-model="narration" placeholder="Enter Narration" @input="clearFieldError('narration')">
                    <span v-if="validationErrors.narration" class="disable-select form-error">{{ validationErrors.narration }}</span>
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
            { value: 'product_id', label: 'Product ID' },
            { value: 'bill_no', label: 'Bill No' },
            { value: 'supplier', label: 'Supplier' },
            { value: 'date', label: 'Date' }
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
                <table class="table" id="purchases-table">
                    <thead>
                        <tr>
                            <th style="width: 80px;" class="text-center">ID</th>
                            <th>Product ID</th>
                            <th>Product Details</th>
                            <th>Date</th>
                            <th class="text-right">Qty</th>
                            <th class="text-right">Rate</th>
                            <th class="text-right">Amount</th>
                            <th>Supplier</th>
                            <th>Bill No</th>
                            <th>Narration</th>
                            <th style="width: 140px;" class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="purchase in purchases" :key="purchase.id" class="table-row-hover">
                            <td class="text-center font-weight-bold text-slate-500 align-middle">#{{ purchase.id }}</td>
                            <td class="align-middle font-weight-bold text-slate-700">#{{ purchase.product_id }}</td>
                            <td class="align-middle text-slate-800 font-medium">{{ purchase.data_product || 'N/A' }}</td>
                            <td class="align-middle text-slate-600 text-nowrap">{{ purchase.date }}</td>
                            <td class="align-middle text-right font-weight-bold text-slate-700">{{ purchase.quantity }}</td>
                            <td class="align-middle text-right text-slate-600">₹{{ parseFloat(purchase.rate).toFixed(2) }}</td>
                            <td class="align-middle text-right font-weight-bold text-indigo-dark">₹{{ parseFloat(purchase.amount).toFixed(2) }}</td>
                            <td class="align-middle text-slate-700">{{ purchase.supplier }}</td>
                            <td class="align-middle text-slate-600 font-monospace small">{{ purchase.bill_no }}</td>
                            <td class="align-middle text-slate-600 text-truncate" style="max-width: 150px;">{{ purchase.narration }}</td>
                            <td class="text-right align-middle text-nowrap">
                                <button @click="editPurchase(purchase)" class="btn btn-action-edit" title="Edit Purchase"><i class="fas fa-pen"></i></button>
                                <button @click="deletePurchase(purchase.id)" class="btn btn-action-delete" title="Delete Purchase"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr v-if="purchases.length === 0">
                            <td colspan="11" class="text-center py-5 text-slate-400">
                                <div class="empty-state">
                                    <i class="fas fa-shopping-cart fa-2x mb-3 text-slate-300"></i>
                                    <p class="m-0 font-medium">No purchase records found matching the query.</p>
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
            <button class="btn btn-load-more text-uppercase" @click="loadMore" :disabled="currentPage == lastPage || purchases.length === 0">
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
            purchases: [],
            id: 0,
            product_id: '',
            date: '',
            bill_no: '',
            supplier: '',
            quantity: '',
            rate: '',
            amount: '',
            narration: '',
            
            searchKey: '',
            searchVal: '',
            searchRes: 'Anywhere',
            perPage: 10,
            
            currentPage: 1,
            lastPage: 1,
            totalPurchases: 0,
            
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
            // Default date to today
            const today = new Date().toISOString().split('T')[0];
            this.date = today;
        },

        closeForm() {
            this.isFormOpen = false;
            this.resetForm();
        },

        resetForm() {
            this.product_id = '';
            this.date = '';
            this.bill_no = '';
            this.supplier = '';
            this.quantity = '';
            this.rate = '';
            this.amount = '';
            this.narration = '';
            this.validationErrors = {};
        },

        clearFieldError(field) {
            if (this.validationErrors[field]) {
                this.$set(this.validationErrors, field, '');
            }
            this.errorMessage = '';
        },

        onQtyOrRateChange() {
            this.clearFieldError('quantity');
            this.clearFieldError('rate');
            
            const q = parseFloat(this.quantity);
            const r = parseFloat(this.rate);
            if (!isNaN(q) && !isNaN(r)) {
                this.amount = (q * r).toFixed(2);
                this.clearFieldError('amount');
            }
        },

        fetchPurchases(page = 1) {
            if (page === 1) {
                this.purchases = [];
            }
            
            const params = {
                model: 'Purchase',
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
                    this.totalPurchases = res.data.total;
                    
                    res.data.data.forEach(purchase => {
                        this.purchases.push(purchase);
                    });
                })
                .catch(err => {
                    console.error('Error fetching purchases:', err);
                });
        },

        loadMore() {
            if (this.currentPage < this.lastPage) {
                this.fetchPurchases(this.currentPage + 1);
            }
        },

        submitSearch() {
            this.fetchPurchases(1);
        },

        editPurchase(purchase) {
            this.isFormOpen = true;
            window.scroll(0, 0);
            this.id = purchase.id;
            this.product_id = purchase.product_id;
            this.date = purchase.date;
            this.bill_no = purchase.bill_no;
            this.supplier = purchase.supplier;
            this.quantity = purchase.quantity;
            this.rate = purchase.rate;
            this.amount = purchase.amount;
            this.narration = purchase.narration;
            
            this.validationErrors = {};
            this.successMessage = '';
            this.errorMessage = '';
        },

        savePurchase() {
            this.successMessage = '';
            this.errorMessage = '';
            this.validationErrors = {};

            const fd = new FormData();
            fd.append('model', 'Purchase');
            fd.append('id', this.id);
            fd.append('form_action', 'CRUD');
            fd.append('product_id', this.product_id);
            fd.append('date', this.date);
            fd.append('bill_no', this.bill_no);
            fd.append('supplier', this.supplier);
            fd.append('quantity', this.quantity);
            fd.append('rate', this.rate);
            fd.append('amount', this.amount);
            fd.append('narration', this.narration);

            let crudnames = ['product_id', 'date', 'bill_no', 'supplier', 'quantity', 'rate', 'amount', 'narration'];
            let crudtypes = ['text', 'date', 'text', 'text', 'text', 'text', 'text', 'text'];
            let crudvalidation = ['required|string', 'required|string', 'required|string', 'required|string', 'required|string', 'required|string', 'required|string', 'required|string'];

            fd.append('crudnames', crudnames.join(','));
            fd.append('crudtypes', crudtypes.join(','));
            fd.append('crudvalidation', crudvalidation.join(','));

            window.axios.post('/crud', fd)
                .then(res => {
                    this.successMessage = this.id === 0 ? 'Purchase record created successfully.' : 'Purchase record updated successfully.';
                    this.fetchPurchases(1);
                    
                    if (this.id === 0) {
                        this.resetForm();
                        setTimeout(() => {
                            this.isFormOpen = false;
                        }, 1200);
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
                        this.errorMessage = 'An error occurred while saving the purchase record.';
                        console.error('Error saving purchase:', error);
                    }
                });
        },

        deletePurchase(id) {
            const c = confirm('Are you sure you want to delete this purchase record?');
            if (c) {
                window.axios.delete('/crud/' + id, {
                    params: {
                        model: 'Purchase'
                    }
                })
                .then(res => {
                    if (res.data === 'ok') {
                        this.fetchPurchases(1);
                    }
                })
                .catch(err => {
                    console.error('Error deleting purchase record:', err);
                });
            }
        },

        exportToExcel() {
            const tableSelect = document.getElementById('purchases-table');
            const tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
            const filename = 'purchases_list.xls';
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
        this.fetchPurchases(1);
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

/* Results Table Card */
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
.table {
    margin-bottom: 0;
    width: 100%;
}
.table th {
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    color: #475569;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 1rem 1.25rem;
    border-top: none;
}
.table td {
    padding: 1rem 1.25rem;
    border-top: none;
    border-bottom: 1px solid #f1f5f9;
    font-size: 0.88rem;
}
.table-row-hover {
    transition: background-color 0.15s ease;
}
.table-row-hover:hover {
    background-color: #f8fafc;
}

/* Action Buttons */
.btn-action-edit, .btn-action-delete {
    width: 32px;
    height: 32px;
    padding: 0 !important;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    font-size: 0.82rem;
    margin-left: 0.25rem;
    border: none;
    transition: all 0.2s ease;
}
.btn-action-edit {
    background-color: #fef9c3 !important;
    color: #a16207 !important;
}
.btn-action-edit:hover {
    background-color: #fef08a !important;
    color: #854d0e !important;
}
.btn-action-delete {
    background-color: #fee2e2 !important;
    color: #b91c1c !important;
}
.btn-action-delete:hover {
    background-color: #fecaca !important;
    color: #991b1b !important;
}

/* Empty State */
.empty-state {
    padding: 2rem;
}
.font-medium {
    font-weight: 500;
}
.text-slate-300 {
    color: #cbd5e1;
}
.text-slate-400 {
    color: #94a3b8;
}
.text-slate-500 {
    color: #64748b;
}
.text-slate-600 {
    color: #475569;
}
.text-slate-700 {
    color: #334155;
}
.text-slate-800 {
    color: #1e293b;
}

/* Footer Panel */
.footer-actions-panel {
    background: transparent;
}
.btn-load-more {
    background: #ffffff !important;
    color: #475569 !important;
    border: 1px solid #cbd5e1 !important;
}
.btn-load-more:hover:not(:disabled) {
    background: #f8fafc !important;
    color: #1e293b !important;
}
.btn-load-more:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}
.btn-export {
    background: #ecfdf5 !important;
    color: #047857 !important;
    border: 1px solid rgba(16, 185, 129, 0.2) !important;
}
.btn-export:hover {
    background: #d1fae5 !important;
    color: #065f46 !important;
}

/* Animations */
.animate-fade-in {
    animation: fadeIn 0.25s ease-out forwards;
}
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(4px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
