<template>
<div class="crud-wrapper animate-fade-in">

    <!-- Title and Header Panel -->
    <div class="header-panel mb-4">
        <div class="row align-items-center justify-content-between g-3">
            <div class="col-12 col-sm-auto">
                <div class="title-container">
                    <h5 class="page-title text-uppercase m-0">Product Group Manager</h5>
                    <span v-if="totalProductGroups !== null" class="count-badge">
                        <i class="fas fa-layer-group mr-1"></i> {{ totalProductGroups }} Total
                    </span>
                </div>
            </div>
            <div class="col-12 col-sm-auto text-sm-right">
                <button v-if="!isFormOpen" @click="openCreateForm" class="btn btn-add text-uppercase">
                    <i class="fas fa-plus mr-1"></i> Add Product Group
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
                {{ id === 0 ? 'Create New Product Group' : 'Edit Product Group #' + id }}
            </h6>
        </div>
        
        <form @submit.prevent="saveProductGroup">
            <div v-if="successMessage" class="p-3 alert alert-success rounded mb-4 text-capitalize d-flex align-items-center">
                <i class="fas fa-check-circle mr-2"></i> {{ successMessage }}
            </div>
            <div v-if="errorMessage" class="p-3 alert alert-danger rounded mb-4 text-capitalize d-flex align-items-center">
                <i class="fas fa-exclamation-circle mr-2"></i> {{ errorMessage }}
            </div>

            <div class="row g-3">
                <!-- Display Status -->
                <div class="col-12 col-sm-6 col-md-4">
                    <label class="disable-select form-label" for="display">Display Status</label>
                    <select class="form-control form-select-custom" id="display" v-model="display" @change="clearFieldError('display')">
                        <option value="">Select Display</option>
                        <option value="Show">Show</option>
                        <option value="Hide">Hide</option>
                    </select>
                    <span v-if="validationErrors.display" class="disable-select form-error">{{ validationErrors.display }}</span>
                </div>

                <!-- Category -->
                <div class="col-12 col-sm-6 col-md-4">
                    <label class="disable-select form-label" for="category_id">Category</label>
                    <select class="form-control form-select-custom" id="category_id" v-model="category_id" @change="onCategoryChange">
                        <option value="">Select Category</option>
                        <option v-for="cat in categories" :key="cat.value" :value="cat.value">{{ cat.key }}</option>
                    </select>
                    <span v-if="validationErrors.category_id" class="disable-select form-error">{{ validationErrors.category_id }}</span>
                </div>

                <!-- Sub Category -->
                <div class="col-12 col-sm-6 col-md-4">
                    <label class="disable-select form-label" for="sub_category_id">Sub Category</label>
                    <select class="form-control form-select-custom" id="sub_category_id" v-model="sub_category_id" @change="clearFieldError('sub_category_id')">
                        <option value="">Select Sub Category</option>
                        <option v-for="sub in filteredSubCategories" :key="sub.value" :value="sub.value">{{ sub.key }}</option>
                    </select>
                    <span v-if="validationErrors.sub_category_id" class="disable-select form-error">{{ validationErrors.sub_category_id }}</span>
                </div>

                <!-- Group Name -->
                <div class="col-12">
                    <label class="disable-select form-label" for="group_name">Group Name</label>
                    <input class="form-control" type="text" id="group_name" v-model="group_name" placeholder="Enter Group Name" @input="clearFieldError('group_name')">
                    <span v-if="validationErrors.group_name" class="disable-select form-error">{{ validationErrors.group_name }}</span>
                </div>

                <!-- Brand -->
                <div class="col-12 col-sm-6 col-md-3">
                    <label class="disable-select form-label" for="brand">Brand</label>
                    <input class="form-control" type="text" id="brand" v-model="brand" placeholder="Enter Brand" @input="clearFieldError('brand')">
                    <span v-if="validationErrors.brand" class="disable-select form-error">{{ validationErrors.brand }}</span>
                </div>

                <!-- HSN -->
                <div class="col-12 col-sm-6 col-md-3">
                    <label class="disable-select form-label" for="hsn">HSN</label>
                    <input class="form-control" type="text" id="hsn" v-model="hsn" placeholder="Enter HSN" @input="clearFieldError('hsn')">
                    <span v-if="validationErrors.hsn" class="disable-select form-error">{{ validationErrors.hsn }}</span>
                </div>

                <!-- Tax Name -->
                <div class="col-12 col-sm-6 col-md-3">
                    <label class="disable-select form-label" for="tax_name">Tax Name</label>
                    <input class="form-control" type="text" id="tax_name" v-model="tax_name" placeholder="Enter Tax Name" @input="clearFieldError('tax_name')">
                    <span v-if="validationErrors.tax_name" class="disable-select form-error">{{ validationErrors.tax_name }}</span>
                </div>

                <!-- Tax Percentage -->
                <div class="col-12 col-sm-6 col-md-3">
                    <label class="disable-select form-label" for="tax_percentage">Tax Percentage</label>
                    <input class="form-control" type="text" id="tax_percentage" v-model="tax_percentage" placeholder="Enter Tax Percentage" @input="clearFieldError('tax_percentage')">
                    <span v-if="validationErrors.tax_percentage" class="disable-select form-error">{{ validationErrors.tax_percentage }}</span>
                </div>

                <!-- Attributes Checks -->
                <div class="col-12 col-sm-6 col-md-3">
                    <label class="disable-select form-label" for="weight">Weight Required?</label>
                    <select class="form-control form-select-custom" id="weight" v-model="weight" @change="clearFieldError('weight')">
                        <option value="">Select Weight</option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
                    <span v-if="validationErrors.weight" class="disable-select form-error">{{ validationErrors.weight }}</span>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <label class="disable-select form-label" for="volume">Volume Required?</label>
                    <select class="form-control form-select-custom" id="volume" v-model="volume" @change="clearFieldError('volume')">
                        <option value="">Select Volume</option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
                    <span v-if="validationErrors.volume" class="disable-select form-error">{{ validationErrors.volume }}</span>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <label class="disable-select form-label" for="color">Color Required?</label>
                    <select class="form-control form-select-custom" id="color" v-model="color" @change="clearFieldError('color')">
                        <option value="">Select Color</option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
                    <span v-if="validationErrors.color" class="disable-select form-error">{{ validationErrors.color }}</span>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <label class="disable-select form-label" for="size">Size Required?</label>
                    <select class="form-control form-select-custom" id="size" v-model="size" @change="clearFieldError('size')">
                        <option value="">Select Size</option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
                    <span v-if="validationErrors.size" class="disable-select form-error">{{ validationErrors.size }}</span>
                </div>

                <!-- Details -->
                <div class="col-12">
                    <label class="disable-select form-label" for="details">Details</label>
                    <textarea class="form-control" id="details" v-model="details" placeholder="Enter Details" rows="3" @input="clearFieldError('details')"></textarea>
                    <span v-if="validationErrors.details" class="disable-select form-error">{{ validationErrors.details }}</span>
                </div>

                <!-- Tags -->
                <div class="col-12">
                    <label class="disable-select form-label" for="tags">Tags</label>
                    <textarea class="form-control" id="tags" v-model="tags" placeholder="Enter Tags" rows="2" @input="clearFieldError('tags')"></textarea>
                    <span v-if="validationErrors.tags" class="disable-select form-error">{{ validationErrors.tags }}</span>
                </div>

                <!-- Page Title -->
                <div class="col-12">
                    <label class="disable-select form-label" for="title">Title for the Page</label>
                    <input class="form-control" type="text" id="title" v-model="title" placeholder="Enter Title for the Page" @input="clearFieldError('title')">
                    <span v-if="validationErrors.title" class="disable-select form-error">{{ validationErrors.title }}</span>
                </div>

                <!-- Keywords -->
                <div class="col-12">
                    <label class="disable-select form-label" for="keywords">Keywords (SEO)</label>
                    <textarea class="form-control" id="keywords" v-model="keywords" placeholder="Enter Keywords" rows="2" @input="clearFieldError('keywords')"></textarea>
                    <span v-if="validationErrors.keywords" class="disable-select form-error">{{ validationErrors.keywords }}</span>
                </div>

                <!-- Page Description -->
                <div class="col-12">
                    <label class="disable-select form-label" for="description">Page Description (SEO)</label>
                    <textarea class="form-control" id="description" v-model="description" placeholder="Enter Page Description" rows="2" @input="clearFieldError('description')"></textarea>
                    <span v-if="validationErrors.description" class="disable-select form-error">{{ validationErrors.description }}</span>
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
            { value: 'group_name', label: 'Group Name' },
            { value: 'brand', label: 'Brand' },
            { value: 'hsn', label: 'HSN' },
            { value: 'tax_name', label: 'Tax' },
            { value: 'display', label: 'Display' },
            { value: 'title', label: 'Title' }
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
                <table class="table" id="productgroups-table">
                    <thead>
                        <tr>
                            <th style="width: 80px;" class="text-center">ID</th>
                            <th>Category</th>
                            <th>Sub Category</th>
                            <th>Group Name</th>
                            <th>Brand</th>
                            <th>HSN</th>
                            <th>Tax</th>
                            <th class="text-center">W / V / C / S</th>
                            <th style="width: 120px;">Display</th>
                            <th style="width: 140px;" class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="group in productgroups" :key="group.id" class="table-row-hover">
                            <td class="text-center font-weight-bold text-slate-500 align-middle">#{{ group.id }}</td>
                            <td class="align-middle font-medium text-slate-800">{{ group.data_category }}</td>
                            <td class="align-middle text-slate-700">{{ group.data_sub_category }}</td>
                            <td class="align-middle font-weight-bold text-slate-800">{{ group.group_name }}</td>
                            <td class="align-middle text-slate-600">{{ group.brand }}</td>
                            <td class="align-middle text-slate-600 font-monospace small">{{ group.hsn }}</td>
                            <td class="align-middle text-slate-600">{{ group.tax_name }} ({{ group.tax_percentage }}%)</td>
                            <td class="align-middle text-center text-nowrap">
                                <span class="badge" :class="group.weight === 'Yes' ? 'bg-indigo-light text-indigo-dark' : 'bg-slate-light text-slate-dark'" title="Weight">W</span>
                                <span class="badge" :class="group.volume === 'Yes' ? 'bg-indigo-light text-indigo-dark' : 'bg-slate-light text-slate-dark'" title="Volume">V</span>
                                <span class="badge" :class="group.color === 'Yes' ? 'bg-indigo-light text-indigo-dark' : 'bg-slate-light text-slate-dark'" title="Color">C</span>
                                <span class="badge" :class="group.size === 'Yes' ? 'bg-indigo-light text-indigo-dark' : 'bg-slate-light text-slate-dark'" title="Size">S</span>
                            </td>
                            <td class="align-middle">
                                <span class="badge" :class="group.display === 'Show' ? 'bg-success-light text-success-dark' : 'bg-slate-light text-slate-dark'">
                                    {{ group.display }}
                                </span>
                            </td>
                            <td class="text-right align-middle text-nowrap">
                                <a :href="'/administrator/products/products/' + group.id" class="btn btn-action-view" title="Manage Products in Group"><i class="fas fa-box"></i></a>
                                <button @click="editProductGroup(group)" class="btn btn-action-edit" title="Edit Product Group"><i class="fas fa-pen"></i></button>
                                <button @click="deleteProductGroup(group.id)" class="btn btn-action-delete" title="Delete Product Group"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr v-if="productgroups.length === 0">
                            <td colspan="10" class="text-center py-5 text-slate-400">
                                <div class="empty-state">
                                    <i class="fas fa-layer-group fa-2x mb-3 text-slate-300"></i>
                                    <p class="m-0 font-medium">No product groups found matching the query.</p>
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
            <button class="btn btn-load-more text-uppercase" @click="loadMore" :disabled="currentPage == lastPage || productgroups.length === 0">
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
    props: ['categories', 'sub_categories'],

    data() {
        return {
            productgroups: [],
            id: 0,
            display: '',
            category_id: '',
            sub_category_id: '',
            group_name: '',
            brand: '',
            hsn: '',
            tax_name: '',
            tax_percentage: '',
            weight: '',
            volume: '',
            color: '',
            size: '',
            details: '',
            tags: '',
            title: '',
            keywords: '',
            description: '',

            filteredSubCategories: [],
            
            searchKey: '',
            searchVal: '',
            searchRes: 'Anywhere',
            perPage: 10,
            
            currentPage: 1,
            lastPage: 1,
            totalProductGroups: 0,
            
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
            this.display = '';
            this.category_id = '';
            this.sub_category_id = '';
            this.group_name = '';
            this.brand = '';
            this.hsn = '';
            this.tax_name = '';
            this.tax_percentage = '';
            this.weight = '';
            this.volume = '';
            this.color = '';
            this.size = '';
            this.details = '';
            this.tags = '';
            this.title = '';
            this.keywords = '';
            this.description = '';
            this.filteredSubCategories = [];
            this.validationErrors = {};
        },

        clearFieldError(field) {
            if (this.validationErrors[field]) {
                this.$set(this.validationErrors, field, '');
            }
            this.errorMessage = '';
        },

        onCategoryChange() {
            this.clearFieldError('category_id');
            this.sub_category_id = '';
            this.filterSubCategories(this.category_id);
        },

        filterSubCategories(catId, selectedSub = '') {
            if (!catId) {
                this.filteredSubCategories = [];
                return;
            }
            this.filteredSubCategories = this.sub_categories.filter(sub => sub.category_id == catId);
            this.sub_category_id = selectedSub;
        },

        fetchProductGroups(page = 1) {
            if (page === 1) {
                this.productgroups = [];
            }
            
            const params = {
                model: 'ProductGroup',
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
                    this.totalProductGroups = res.data.total;
                    
                    res.data.data.forEach(group => {
                        this.productgroups.push(group);
                    });
                })
                .catch(err => {
                    console.error('Error fetching product groups:', err);
                });
        },

        loadMore() {
            if (this.currentPage < this.lastPage) {
                this.fetchProductGroups(this.currentPage + 1);
            }
        },

        submitSearch() {
            this.fetchProductGroups(1);
        },

        editProductGroup(group) {
            this.isFormOpen = true;
            window.scroll(0, 0);
            this.id = group.id;
            this.display = group.display;
            this.category_id = group.category_id;
            this.group_name = group.group_name;
            this.brand = group.brand;
            this.hsn = group.hsn;
            this.tax_name = group.tax_name;
            this.tax_percentage = group.tax_percentage;
            this.weight = group.weight;
            this.volume = group.volume;
            this.color = group.color;
            this.size = group.size;
            this.details = group.details;
            this.tags = group.tags;
            this.title = group.title;
            this.keywords = group.keywords;
            this.description = group.description;
            
            this.filterSubCategories(group.category_id, group.sub_category_id);
            
            this.validationErrors = {};
            this.successMessage = '';
            this.errorMessage = '';
        },

        saveProductGroup() {
            this.successMessage = '';
            this.errorMessage = '';
            this.validationErrors = {};

            const fd = new FormData();
            fd.append('model', 'ProductGroup');
            fd.append('id', this.id);
            fd.append('form_action', 'CRUD');
            fd.append('display', this.display);
            fd.append('category_id', this.category_id);
            fd.append('sub_category_id', this.sub_category_id);
            fd.append('group_name', this.group_name);
            fd.append('brand', this.brand);
            fd.append('hsn', this.hsn);
            fd.append('tax_name', this.tax_name);
            fd.append('tax_percentage', this.tax_percentage);
            fd.append('weight', this.weight);
            fd.append('volume', this.volume);
            fd.append('color', this.color);
            fd.append('size', this.size);
            fd.append('details', this.details);
            fd.append('tags', this.tags);
            fd.append('title', this.title);
            fd.append('keywords', this.keywords);
            fd.append('description', this.description);

            let crudnames = ['display', 'category_id', 'sub_category_id', 'group_name', 'brand', 'hsn', 'tax_name', 'tax_percentage', 'weight', 'volume', 'color', 'size', 'details', 'tags', 'title', 'keywords', 'description'];
            let crudtypes = ['select', 'select', 'select', 'text', 'text', 'text', 'text', 'text', 'select', 'select', 'select', 'select', 'textarea', 'textarea', 'text', 'textarea', 'textarea'];
            let crudvalidation = ['required', 'required', 'required', 'required|string', 'required|string', 'required|string', 'required|string', 'required|string', 'required', 'required', 'required', 'required', 'required|string', 'required|string', 'required|string', 'required|string', 'required|string'];

            fd.append('crudnames', crudnames.join(','));
            fd.append('crudtypes', crudtypes.join(','));
            fd.append('crudvalidation', crudvalidation.join(','));

            window.axios.post('/crud', fd)
                .then(res => {
                    this.successMessage = this.id === 0 ? 'Product Group created successfully.' : 'Product Group updated successfully.';
                    this.fetchProductGroups(1);
                    
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
                        this.errorMessage = 'An error occurred while saving the product group.';
                        console.error('Error saving product group:', error);
                    }
                });
        },

        deleteProductGroup(id) {
            const c = confirm('Are you sure you want to delete this product group?');
            if (c) {
                window.axios.delete('/crud/' + id, {
                    params: {
                        model: 'ProductGroup'
                    }
                })
                .then(res => {
                    if (res.data === 'ok') {
                        this.fetchProductGroups(1);
                    }
                })
                .catch(err => {
                    console.error('Error deleting product group:', err);
                });
            }
        },

        exportToExcel() {
            const tableSelect = document.getElementById('productgroups-table');
            const tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
            const filename = 'productgroups_list.xls';
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
        this.fetchProductGroups(1);
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

/* Select Form Dropdown */
.form-select-custom {
    appearance: none;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23475569' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 16px 12px;
    padding-right: 2.25rem;
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

/* Custom Badges */
.badge {
    font-size: 0.72rem;
    font-weight: 700;
    padding: 0.3rem 0.6rem;
    border-radius: 6px;
    letter-spacing: 0.01em;
    text-transform: capitalize;
}
.bg-success-light {
    background-color: #dcfce7;
}
.text-success-dark {
    color: #15803d;
}
.bg-slate-light {
    background-color: #f1f5f9;
}
.text-slate-dark {
    color: #475569;
}
.bg-indigo-light {
    background-color: #e0e7ff;
}
.text-indigo-dark {
    color: #4338ca;
}

/* Action Buttons */
.btn-action-view, .btn-action-edit, .btn-action-delete {
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
.btn-action-view {
    background-color: #e0e7ff !important;
    color: #4338ca !important;
}
.btn-action-view:hover {
    background-color: #c7d2fe !important;
    color: #3730a3 !important;
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
