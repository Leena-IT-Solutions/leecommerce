<template>
<div class="crud-wrapper animate-fade-in">

    <!-- Title and Header Panel -->
    <div class="header-panel mb-4">
        <div class="row align-items-center justify-content-between g-3">
            <div class="col-12 col-sm-auto">
                <div class="title-container">
                    <h5 class="page-title text-uppercase m-0">Product Manager</h5>
                    <span class="count-badge mr-2">
                        <i class="fas fa-layer-group mr-1"></i> Group: {{ product_group.group_name }}
                    </span>
                    <span v-if="totalProducts !== null" class="count-badge">
                        <i class="fas fa-box mr-1"></i> {{ totalProducts }} Total Products
                    </span>
                </div>
            </div>
            <div class="col-12 col-sm-auto text-sm-right">
                <a href="/administrator/products/product-group" class="btn btn-back text-uppercase mr-2">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Groups
                </a>
                <button v-if="!isFormOpen" @click="openCreateForm" class="btn btn-add text-uppercase">
                    <i class="fas fa-plus mr-1"></i> Add Product
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
                {{ id === 0 ? 'Create New Product variant' : 'Edit Product #' + id }}
            </h6>
        </div>
        
        <form @submit.prevent="saveProduct">
            <div v-if="successMessage" class="p-3 alert alert-success rounded mb-4 text-capitalize d-flex align-items-center">
                <i class="fas fa-check-circle mr-2"></i> {{ successMessage }}
            </div>
            <div v-if="errorMessage" class="p-3 alert alert-danger rounded mb-4 text-capitalize d-flex align-items-center">
                <i class="fas fa-exclamation-circle mr-2"></i> {{ errorMessage }}
            </div>

            <div class="row g-3">
                <!-- Group Info (Read-only/Disabled) -->
                <div class="col-12 col-sm-6 col-md-3">
                    <label class="disable-select form-label">Category</label>
                    <input class="form-control bg-light" type="text" :value="product_group.data_category || 'Category'" readonly disabled>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <label class="disable-select form-label">Sub Category</label>
                    <input class="form-control bg-light" type="text" :value="product_group.data_sub_category || 'Sub Category'" readonly disabled>
                </div>
                <div class="col-12 col-sm-12 col-md-6">
                    <label class="disable-select form-label">Product Group</label>
                    <input class="form-control bg-light" type="text" :value="product_group.group_name" readonly disabled>
                </div>

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

                <!-- Pricing Fields -->
                <div class="col-12 col-sm-6 col-md-2">
                    <label class="disable-select form-label" for="mrp">MRP</label>
                    <input class="form-control" type="text" id="mrp" v-model="mrp" placeholder="MRP" @input="clearFieldError('mrp')">
                    <span v-if="validationErrors.mrp" class="disable-select form-error">{{ validationErrors.mrp }}</span>
                </div>

                <div class="col-12 col-sm-6 col-md-2">
                    <label class="disable-select form-label" for="rate">Selling Rate</label>
                    <input class="form-control" type="text" id="rate" v-model="rate" placeholder="Selling Rate" @input="clearFieldError('rate')">
                    <span v-if="validationErrors.rate" class="disable-select form-error">{{ validationErrors.rate }}</span>
                </div>

                <div class="col-12 col-sm-6 col-md-2">
                    <label class="disable-select form-label" for="cost">Cost Price</label>
                    <input class="form-control" type="text" id="cost" v-model="cost" placeholder="Cost Price" @input="clearFieldError('cost')">
                    <span v-if="validationErrors.cost" class="disable-select form-error">{{ validationErrors.cost }}</span>
                </div>

                <div class="col-12 col-sm-6 col-md-2">
                    <label class="disable-select form-label" for="tax">Tax %</label>
                    <input class="form-control" type="text" id="tax" v-model="tax" placeholder="Tax %" @input="clearFieldError('tax')">
                    <span v-if="validationErrors.tax" class="disable-select form-error">{{ validationErrors.tax }}</span>
                </div>

                <!-- Logistics & Identification -->
                <div class="col-12 col-sm-6 col-md-3">
                    <label class="disable-select form-label" for="itemcode">Item Code</label>
                    <input class="form-control" type="text" id="itemcode" v-model="itemcode" placeholder="Item Code" @input="clearFieldError('itemcode')">
                    <span v-if="validationErrors.itemcode" class="disable-select form-error">{{ validationErrors.itemcode }}</span>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <label class="disable-select form-label" for="barcode">Barcode</label>
                    <input class="form-control" type="text" id="barcode" v-model="barcode" placeholder="Barcode" @input="clearFieldError('barcode')">
                    <span v-if="validationErrors.barcode" class="disable-select form-error">{{ validationErrors.barcode }}</span>
                </div>

                <!-- Attribute Details (Conditional Visual Highlights) -->
                <div class="col-12 col-sm-6 col-md-3" :class="{'attribute-highlight': product_group.weight === 'Yes'}">
                    <label class="disable-select form-label" for="weight">
                        Weight in Grams
                        <span v-if="product_group.weight === 'Yes'" class="badge bg-indigo-light text-indigo-dark ml-1 font-8">Required by Group</span>
                    </label>
                    <input class="form-control" type="text" id="weight" v-model="weight" placeholder="Weight" @input="clearFieldError('weight')">
                    <span v-if="validationErrors.weight" class="disable-select form-error">{{ validationErrors.weight }}</span>
                </div>

                <div class="col-12 col-sm-6 col-md-3" :class="{'attribute-highlight': product_group.volume === 'Yes'}">
                    <label class="disable-select form-label" for="volume">
                        Volume in mg
                        <span v-if="product_group.volume === 'Yes'" class="badge bg-indigo-light text-indigo-dark ml-1 font-8">Required by Group</span>
                    </label>
                    <input class="form-control" type="text" id="volume" v-model="volume" placeholder="Volume" @input="clearFieldError('volume')">
                    <span v-if="validationErrors.volume" class="disable-select form-error">{{ validationErrors.volume }}</span>
                </div>

                <div class="col-12 col-sm-6 col-md-3" :class="{'attribute-highlight': product_group.color === 'Yes'}">
                    <label class="disable-select form-label" for="color">
                        Color Hex Code
                        <span v-if="product_group.color === 'Yes'" class="badge bg-indigo-light text-indigo-dark ml-1 font-8">Required by Group</span>
                    </label>
                    <div class="input-group">
                        <input class="form-control" type="text" id="color" v-model="color" placeholder="#ffffff" @input="clearFieldError('color')">
                        <span class="input-group-text p-1 d-flex align-items-center justify-content-center" style="width: 42px; border: 1px solid #cbd5e1; border-radius: 0 8px 8px 0; background: #fff;">
                            <input type="color" v-model="colorPickerValue" @input="onColorPickerInput" style="width: 30px; height: 30px; border: none; padding: 0; cursor: pointer; border-radius: 4px;">
                        </span>
                    </div>
                    <span v-if="validationErrors.color" class="disable-select form-error">{{ validationErrors.color }}</span>
                </div>

                <div class="col-12 col-sm-6 col-md-3" :class="{'attribute-highlight': product_group.size === 'Yes'}">
                    <label class="disable-select form-label" for="size">
                        Size
                        <span v-if="product_group.size === 'Yes'" class="badge bg-indigo-light text-indigo-dark ml-1 font-8">Required by Group</span>
                    </label>
                    <input class="form-control" type="text" id="size" v-model="size" placeholder="Size (e.g. S, M, L, XL)" @input="clearFieldError('size')">
                    <span v-if="validationErrors.size" class="disable-select form-error">{{ validationErrors.size }}</span>
                </div>

                <!-- Sub Media Galleries -->
                <div class="col-12 mt-4">
                    <h6 class="text-uppercase m-0 font-weight-bold text-slate-700 border-bottom pb-2 mb-3">Product Media Sub Gallery</h6>
                    <div class="row g-3">
                        <div v-for="slot in ['media_a', 'media_b', 'media_c', 'media_d', 'media_e', 'media_f']" :key="slot" class="col-12 col-sm-6 col-md-4">
                            <div class="media-uploader-card p-3 border rounded">
                                <label class="disable-select form-label text-uppercase font-weight-bold small">{{ slot.replace('_', ' ') }}</label>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="media-preview-container flex-shrink-0" v-if="mediaPreviews[slot] || mediaUrls[slot]">
                                        <img :src="mediaPreviews[slot] || mediaUrls[slot]" class="media-preview-img" alt="Preview">
                                    </div>
                                    <div class="media-preview-container flex-shrink-0 bg-light d-flex align-items-center justify-content-center" v-else>
                                        <i class="fas fa-image text-slate-300 fa-lg"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="custom-file-wrapper">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" :id="slot" @change="handleFileChange($event, slot)">
                                                <label class="custom-file-label text-truncate text-muted font-8" :for="slot">{{ mediaFilenames[slot] || 'Choose image...' }}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
            { value: 'mrp', label: 'MRP' },
            { value: 'rate', label: 'Rate' },
            { value: 'cost', label: 'Cost' },
            { value: 'itemcode', label: 'Item Code' },
            { value: 'barcode', label: 'Barcode' },
            { value: 'weight', label: 'Weight' },
            { value: 'volume', label: 'Volume' },
            { value: 'color', label: 'Color' },
            { value: 'size', label: 'Size' },
            { value: 'display', label: 'Display' }
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
                <table class="table" id="products-table">
                    <thead>
                        <tr>
                            <th style="width: 80px;" class="text-center">ID</th>
                            <th>Item Code</th>
                            <th>Barcode</th>
                            <th class="text-right">MRP</th>
                            <th class="text-right">Rate</th>
                            <th class="text-right">Cost</th>
                            <th class="text-right">Tax</th>
                            <th>Attributes</th>
                            <th class="text-center">Main Media</th>
                            <th style="width: 120px;">Display</th>
                            <th style="width: 140px;" class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="product in products" :key="product.id" class="table-row-hover">
                            <td class="text-center font-weight-bold text-slate-500 align-middle">#{{ product.id }}</td>
                            <td class="align-middle font-medium text-slate-800">{{ product.itemcode || '-' }}</td>
                            <td class="align-middle text-slate-600 font-monospace small">{{ product.barcode || '-' }}</td>
                            <td class="align-middle text-right text-slate-800">₹{{ parseFloat(product.mrp).toFixed(2) }}</td>
                            <td class="align-middle text-right font-weight-bold text-indigo-dark">₹{{ parseFloat(product.rate).toFixed(2) }}</td>
                            <td class="align-middle text-right text-slate-600">₹{{ parseFloat(product.cost).toFixed(2) }}</td>
                            <td class="align-middle text-right text-slate-600">{{ product.tax }}%</td>
                            <td class="align-middle text-slate-700">
                                <span class="d-block small" v-if="product.weight"><strong>W:</strong> {{ product.weight }}g</span>
                                <span class="d-block small" v-if="product.volume"><strong>V:</strong> {{ product.volume }}mg</span>
                                <span class="d-block small d-inline-flex align-items-center" v-if="product.color">
                                    <strong>C:</strong> 
                                    <span class="d-inline-block rounded-circle border ml-1" :style="{ backgroundColor: product.color, width: '12px', height: '12px' }" :title="product.color"></span>
                                    <span class="ml-1 font-monospace small">{{ product.color }}</span>
                                </span>
                                <span class="d-block small" v-if="product.size"><strong>S:</strong> {{ product.size }}</span>
                                <span class="text-slate-400 font-italic small" v-if="!product.weight && !product.volume && !product.color && !product.size">None</span>
                            </td>
                            <td class="text-center align-middle">
                                <div class="table-media-container" v-if="product.media_a">
                                    <img :src="product.media_a" class="table-cell-image" alt="Product">
                                </div>
                                <span v-else class="text-slate-400 font-italic small">No image</span>
                            </td>
                            <td class="align-middle">
                                <span class="badge" :class="product.display === 'Show' ? 'bg-success-light text-success-dark' : 'bg-slate-light text-slate-dark'">
                                    {{ product.display }}
                                </span>
                            </td>
                            <td class="text-right align-middle text-nowrap">
                                <button @click="editProduct(product)" class="btn btn-action-edit" title="Edit Product variant"><i class="fas fa-pen"></i></button>
                                <button @click="deleteProduct(product.id)" class="btn btn-action-delete" title="Delete Product variant"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr v-if="products.length === 0">
                            <td colspan="11" class="text-center py-5 text-slate-400">
                                <div class="empty-state">
                                    <i class="fas fa-box fa-2x mb-3 text-slate-300"></i>
                                    <p class="m-0 font-medium">No product variants found in this group.</p>
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
            <button class="btn btn-load-more text-uppercase" @click="loadMore" :disabled="currentPage == lastPage || products.length === 0">
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
    props: ['product_group'],

    data() {
        return {
            products: [],
            id: 0,
            display: '',
            mrp: '',
            rate: '',
            cost: '',
            tax: '',
            itemcode: '',
            barcode: '',
            weight: '',
            volume: '',
            color: '',
            size: '',

            colorPickerValue: '#ffffff',

            // Media structures
            mediaFiles: {
                media_a: null, media_b: null, media_c: null, media_d: null, media_e: null, media_f: null
            },
            mediaFilenames: {
                media_a: '', media_b: '', media_c: '', media_d: '', media_e: '', media_f: ''
            },
            mediaPreviews: {
                media_a: '', media_b: '', media_c: '', media_d: '', media_e: '', media_f: ''
            },
            mediaUrls: {
                media_a: '', media_b: '', media_c: '', media_d: '', media_e: '', media_f: ''
            },
            
            searchKey: '',
            searchVal: '',
            searchRes: 'Anywhere',
            perPage: 10,
            
            currentPage: 1,
            lastPage: 1,
            totalProducts: 0,
            
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

            // Prefill tax from product group if available
            if (this.product_group.tax_percentage) {
                this.tax = this.product_group.tax_percentage;
            }
        },

        closeForm() {
            this.isFormOpen = false;
            this.resetForm();
        },

        resetForm() {
            this.display = '';
            this.mrp = '';
            this.rate = '';
            this.cost = '';
            this.tax = '';
            this.itemcode = '';
            this.barcode = '';
            this.weight = '';
            this.volume = '';
            this.color = '';
            this.size = '';
            this.colorPickerValue = '#ffffff';

            const slots = ['media_a', 'media_b', 'media_c', 'media_d', 'media_e', 'media_f'];
            slots.forEach(slot => {
                this.mediaFiles[slot] = null;
                this.mediaFilenames[slot] = '';
                this.mediaPreviews[slot] = '';
                this.mediaUrls[slot] = '';
            });

            this.validationErrors = {};
        },

        clearFieldError(field) {
            if (this.validationErrors[field]) {
                this.$set(this.validationErrors, field, '');
            }
            this.errorMessage = '';
        },

        onColorPickerInput(e) {
            this.color = e.target.value;
            this.clearFieldError('color');
        },

        handleFileChange(e, slot) {
            const file = e.target.files[0];
            if (file) {
                this.mediaFiles[slot] = file;
                this.mediaFilenames[slot] = file.name;
                this.mediaPreviews[slot] = URL.createObjectURL(file);
                this.clearFieldError(slot);
            }
        },

        fetchProducts(page = 1) {
            if (page === 1) {
                this.products = [];
            }
            
            const params = {
                model: 'Product',
                key: this.searchKey,
                val: this.searchVal,
                res: this.searchRes,
                ds: 'No',
                r: this.perPage,
                page: page,
                // Restrict results to the active product group
                q: 'product_group_id,==,' + this.product_group.id
            };

            window.axios.get('/crud', { params })
                .then(res => {
                    this.currentPage = res.data.current_page;
                    this.lastPage = res.data.last_page;
                    this.totalProducts = res.data.total;
                    
                    res.data.data.forEach(product => {
                        this.products.push(product);
                    });
                })
                .catch(err => {
                    console.error('Error fetching products:', err);
                });
        },

        loadMore() {
            if (this.currentPage < this.lastPage) {
                this.fetchProducts(this.currentPage + 1);
            }
        },

        submitSearch() {
            this.fetchProducts(1);
        },

        editProduct(product) {
            this.isFormOpen = true;
            window.scroll(0, 0);
            this.id = product.id;
            this.display = product.display;
            this.mrp = product.mrp;
            this.rate = product.rate;
            this.cost = product.cost;
            this.tax = product.tax;
            this.itemcode = product.itemcode;
            this.barcode = product.barcode;
            this.weight = product.weight;
            this.volume = product.volume;
            this.color = product.color;
            this.size = product.size;

            if (product.color && product.color.startsWith('#')) {
                this.colorPickerValue = product.color;
            } else {
                this.colorPickerValue = '#ffffff';
            }

            const slots = ['media_a', 'media_b', 'media_c', 'media_d', 'media_e', 'media_f'];
            slots.forEach(slot => {
                this.mediaFiles[slot] = null;
                this.mediaPreviews[slot] = '';
                this.mediaUrls[slot] = product[slot] || '';
                this.mediaFilenames[slot] = product[slot] ? product[slot].split('/').pop() : '';
            });

            this.validationErrors = {};
            this.successMessage = '';
            this.errorMessage = '';
        },

        saveProduct() {
            this.successMessage = '';
            this.errorMessage = '';
            this.validationErrors = {};

            const fd = new FormData();
            fd.append('model', 'Product');
            fd.append('id', this.id);
            fd.append('form_action', 'CRUD');
            fd.append('category_id', this.product_group.category_id);
            fd.append('sub_category_id', this.product_group.sub_category_id);
            fd.append('product_group_id', this.product_group.id);
            fd.append('display', this.display);
            fd.append('mrp', this.mrp);
            fd.append('rate', this.rate);
            fd.append('cost', this.cost);
            fd.append('tax', this.tax);
            fd.append('itemcode', this.itemcode || '');
            fd.append('barcode', this.barcode || '');
            fd.append('weight', this.weight || '');
            fd.append('volume', this.volume || '');
            fd.append('color', this.color || '');
            fd.append('size', this.size || '');

            let crudnames = ['category_id', 'sub_category_id', 'product_group_id', 'display', 'mrp', 'rate', 'cost', 'tax', 'itemcode', 'barcode', 'weight', 'volume', 'color', 'size'];
            let crudtypes = ['text', 'text', 'text', 'select', 'text', 'text', 'text', 'text', 'text', 'text', 'text', 'text', 'text', 'text'];
            let crudvalidation = ['required', 'required', 'required', 'required', 'required|string', 'required|string', 'required|string', 'required|string', 'nullable', 'nullable', 'nullable', 'nullable', 'nullable', 'nullable'];

            const slots = ['media_a', 'media_b', 'media_c', 'media_d', 'media_e', 'media_f'];
            slots.forEach(slot => {
                if (this.mediaFiles[slot]) {
                    fd.append(slot, this.mediaFiles[slot]);
                    crudnames.push(slot);
                    crudtypes.push('image');
                    crudvalidation.push('nullable');
                }
            });

            fd.append('crudnames', crudnames.join(','));
            fd.append('crudtypes', crudtypes.join(','));
            fd.append('crudvalidation', crudvalidation.join(','));

            window.axios.post('/crud', fd)
                .then(res => {
                    this.successMessage = this.id === 0 ? 'Product variant created successfully.' : 'Product variant updated successfully.';
                    this.fetchProducts(1);
                    
                    if (this.id === 0) {
                        this.resetForm();
                        setTimeout(() => {
                            this.isFormOpen = false;
                        }, 1200);
                    } else {
                        // Reset newly selected media files since they are saved
                        slots.forEach(slot => {
                            this.mediaFiles[slot] = null;
                        });
                        // Update URLs with new filenames from response if returned
                        if (res.data && typeof res.data === 'object') {
                            slots.forEach(slot => {
                                if (res.data[slot]) {
                                    this.mediaUrls[slot] = res.data[slot];
                                }
                            });
                        }
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
                        this.errorMessage = 'An error occurred while saving the product variant.';
                        console.error('Error saving product variant:', error);
                    }
                });
        },

        deleteProduct(id) {
            const c = confirm('Are you sure you want to delete this product variant?');
            if (c) {
                window.axios.delete('/crud/' + id, {
                    params: {
                        model: 'Product',
                        files: 'media_a,media_b,media_c,media_d,media_e,media_f'
                    }
                })
                .then(res => {
                    if (res.data === 'ok') {
                        this.fetchProducts(1);
                    }
                })
                .catch(err => {
                    console.error('Error deleting product variant:', err);
                });
            }
        },

        exportToExcel() {
            const tableSelect = document.getElementById('products-table');
            const tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
            const filename = 'products_list.xls';
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
        this.fetchProducts(1);
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
.btn-back {
    background: #f1f5f9 !important;
    color: #475569 !important;
    border: 1px solid #cbd5e1 !important;
}
.btn-back:hover {
    background: #e2e8f0 !important;
    color: #1e293b !important;
}
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
    display: flex;
    align-items: center;
    flex-wrap: wrap;
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
.font-8 {
    font-size: 0.7rem !important;
}

/* Color input adjustment */
.input-group-text input[type="color"]::-webkit-color-swatch-wrapper {
    padding: 0;
}
.input-group-text input[type="color"]::-webkit-color-swatch {
    border: 1px solid #e2e8f0;
    border-radius: 4px;
}

/* Active Highlight Border for Group Requirements */
.attribute-highlight .form-control {
    border-color: #a5b4fc !important;
    background-color: #faf5ff;
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

/* Custom File/Image upload */
.custom-file-wrapper {
    position: relative;
    width: 100%;
}
.custom-file-input {
    cursor: pointer;
    opacity: 0;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 2;
}
.custom-file-label {
    display: block;
    border-radius: 8px;
    border: 1px solid #cbd5e1;
    padding: 0.5rem 0.75rem;
    background: #f8fafc;
    color: #475569;
    font-size: 0.82rem;
    cursor: pointer;
    margin: 0;
    transition: all 0.2s ease;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    padding-right: 4.5rem;
}
.custom-file-label::after {
    content: "Upload";
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    z-index: 3;
    display: flex;
    align-items: center;
    padding: 0 0.75rem;
    line-height: 1.5;
    color: #334155;
    background-color: #e2e8f0;
    border-left: 1px solid #cbd5e1;
    border-radius: 0 7px 7px 0;
    font-weight: 700;
    font-size: 0.72rem;
    text-transform: uppercase;
}

/* Media Preview Thumbnail inside Form */
.media-preview-container {
    width: 52px;
    height: 52px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0,0,0,0.04);
    background: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
}
.media-preview-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
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

/* Table Media Preview */
.table-media-container {
    width: 44px;
    height: 44px;
    border-radius: 6px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
    background: #ffffff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}
.table-cell-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
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
