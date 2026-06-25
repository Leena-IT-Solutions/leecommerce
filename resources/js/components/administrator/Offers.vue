<template>
<div class="crud-wrapper animate-fade-in">

    <!-- Title and Header Panel -->
    <div class="header-panel mb-4">
        <div class="row align-items-center justify-content-between g-3">
            <div class="col-12 col-sm-auto">
                <div class="title-container">
                    <h5 class="page-title text-uppercase m-0">Offer Manager</h5>
                    <span v-if="totalOffers !== null" class="count-badge">
                        <i class="fas fa-percentage mr-1"></i> {{ totalOffers }} Total Offers
                    </span>
                </div>
            </div>
            <div class="col-12 col-sm-auto text-sm-right">
                <button v-if="!isFormOpen" @click="openCreateForm" class="btn btn-add text-uppercase">
                    <i class="fas fa-plus mr-1"></i> Add Offer
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
                {{ id === 0 ? 'Create New Offer / Coupon' : 'Edit Offer #' + id }}
            </h6>
        </div>
        
        <form @submit.prevent="saveOffer">
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

                <!-- Offer Name -->
                <div class="col-12 col-sm-6 col-md-4">
                    <label class="disable-select form-label" for="offer_name">Offer Name</label>
                    <input class="form-control" type="text" id="offer_name" v-model="offer_name" placeholder="Enter Offer Name" @input="clearFieldError('offer_name')">
                    <span v-if="validationErrors.offer_name" class="disable-select form-error">{{ validationErrors.offer_name }}</span>
                </div>

                <!-- Coupon Code -->
                <div class="col-12 col-sm-6 col-md-4">
                    <label class="disable-select form-label" for="coupon_code">Coupon Code</label>
                    <input class="form-control" type="text" id="coupon_code" v-model="coupon_code" placeholder="e.g. FESTIVE50" @input="clearFieldError('coupon_code')">
                    <span v-if="validationErrors.coupon_code" class="disable-select form-error">{{ validationErrors.coupon_code }}</span>
                </div>

                <!-- Offer Types and Targets -->
                <div class="col-12 col-sm-6 col-md-3">
                    <label class="disable-select form-label" for="offer_type">Offer Type</label>
                    <select class="form-control form-select-custom" id="offer_type" v-model="offer_type" @change="clearFieldError('offer_type')">
                        <option value="">Select Offer Type</option>
                        <option value="Flat Discount">Flat Discount</option>
                        <option value="Percentile Discount">Percentile Discount</option>
                    </select>
                    <span v-if="validationErrors.offer_type" class="disable-select form-error">{{ validationErrors.offer_type }}</span>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <label class="disable-select form-label" for="offer_value">Offer Value (Flat / %)</label>
                    <input class="form-control" type="text" id="offer_value" v-model="offer_value" placeholder="Offer Value" @input="clearFieldError('offer_value')">
                    <span v-if="validationErrors.offer_value" class="disable-select form-error">{{ validationErrors.offer_value }}</span>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <label class="disable-select form-label" for="offer_for">Offer For</label>
                    <select class="form-control form-select-custom" id="offer_for" v-model="offer_for" @change="clearFieldError('offer_for')">
                        <option value="">Select Offer For</option>
                        <option value="All Orders">All Orders</option>
                        <option value="First Order">First Order</option>
                    </select>
                    <span v-if="validationErrors.offer_for" class="disable-select form-error">{{ validationErrors.offer_for }}</span>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <label class="disable-select form-label" for="ticket_size">Total Coupons Pool (Size)</label>
                    <input class="form-control" type="text" id="ticket_size" v-model="ticket_size" placeholder="Total Coupons Pool" @input="clearFieldError('ticket_size')">
                    <span v-if="validationErrors.ticket_size" class="disable-select form-error">{{ validationErrors.ticket_size }}</span>
                </div>

                <!-- Limits & Purchase thresholds -->
                <div class="col-12 col-sm-6 col-md-3">
                    <label class="disable-select form-label" for="minimum_purchase_amount">Min Purchase Amount (₹)</label>
                    <input class="form-control" type="text" id="minimum_purchase_amount" v-model="minimum_purchase_amount" placeholder="Min Purchase" @input="clearFieldError('minimum_purchase_amount')">
                    <span v-if="validationErrors.minimum_purchase_amount" class="disable-select form-error">{{ validationErrors.minimum_purchase_amount }}</span>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <label class="disable-select form-label" for="maximum_offer_amount">Max Discount Amount (₹)</label>
                    <input class="form-control" type="text" id="maximum_offer_amount" v-model="maximum_offer_amount" placeholder="Max Discount" @input="clearFieldError('maximum_offer_amount')">
                    <span v-if="validationErrors.maximum_offer_amount" class="disable-select form-error">{{ validationErrors.maximum_offer_amount }}</span>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <label class="disable-select form-label" for="start">Start Date</label>
                    <input class="form-control" type="date" id="start" v-model="start" @change="clearFieldError('start')">
                    <span v-if="validationErrors.start" class="disable-select form-error">{{ validationErrors.start }}</span>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <label class="disable-select form-label" for="end">End Date</label>
                    <input class="form-control" type="date" id="end" v-model="end" @change="clearFieldError('end')">
                    <span v-if="validationErrors.end" class="disable-select form-error">{{ validationErrors.end }}</span>
                </div>

                <!-- Daily & Customer limits -->
                <div class="col-12 col-sm-6 col-md-4">
                    <label class="disable-select form-label" for="ticket_per_day">Total Tickets Per Day</label>
                    <input class="form-control" type="text" id="ticket_per_day" v-model="ticket_per_day" placeholder="Tickets Per Day" @input="clearFieldError('ticket_per_day')">
                    <span v-if="validationErrors.ticket_per_day" class="disable-select form-error">{{ validationErrors.ticket_per_day }}</span>
                </div>

                <div class="col-12 col-sm-6 col-md-4">
                    <label class="disable-select form-label" for="ticket_per_customer">Total Tickets Per Customer</label>
                    <input class="form-control" type="text" id="ticket_per_customer" v-model="ticket_per_customer" placeholder="Tickets Per Customer" @input="clearFieldError('ticket_per_customer')">
                    <span v-if="validationErrors.ticket_per_customer" class="disable-select form-error">{{ validationErrors.ticket_per_customer }}</span>
                </div>

                <div class="col-12 col-sm-6 col-md-4">
                    <label class="disable-select form-label" for="ticket_per_customer_per_day">Tickets Per Customer Per Day</label>
                    <input class="form-control" type="text" id="ticket_per_customer_per_day" v-model="ticket_per_customer_per_day" placeholder="Tickets Per Customer Per Day" @input="clearFieldError('ticket_per_customer_per_day')">
                    <span v-if="validationErrors.ticket_per_customer_per_day" class="disable-select form-error">{{ validationErrors.ticket_per_customer_per_day }}</span>
                </div>

                <!-- Description -->
                <div class="col-12">
                    <label class="disable-select form-label" for="description">Description</label>
                    <input class="form-control" type="text" id="description" v-model="description" placeholder="Enter Description / Offer Text" @input="clearFieldError('description')">
                    <span v-if="validationErrors.description" class="disable-select form-error">{{ validationErrors.description }}</span>
                </div>

                <!-- Offer Media Image -->
                <div class="col-12">
                    <label class="disable-select form-label">Offer Banner Image</label>
                    <div class="d-flex align-items-center flex-wrap gap-3 mb-2">
                        <div class="media-preview-container" v-if="mediaPreview || media">
                            <img :src="mediaPreview || media" class="media-preview-img" alt="Preview">
                        </div>
                        <div class="flex-grow-1">
                            <div class="custom-file-wrapper">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="media" ref="mediaInput" @change="handleFileChange">
                                    <label class="custom-file-label text-truncate" for="media">{{ mediaFilename || 'Choose offer banner image...' }}</label>
                                </div>
                            </div>
                            <span v-if="validationErrors.media" class="disable-select form-error">{{ validationErrors.media }}</span>
                            <span v-else class="disable-select form-hint">Upload a promotional offer thumbnail / banner.</span>
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

    <!-- Search Filters Card -->
    <!-- Advanced Search Component -->
    <data-search
        :columns="[
            { value: 'id', label: 'ID' },
            { value: 'offer_name', label: 'Offer Name' },
            { value: 'coupon_code', label: 'Coupon Code' },
            { value: 'offer_type', label: 'Offer Type' },
            { value: 'offer_for', label: 'Offer For' },
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
                <table class="table" id="offers-table">
                    <thead>
                        <tr>
                            <th style="width: 80px;" class="text-center">ID</th>
                            <th>Offer Name</th>
                            <th>Coupon</th>
                            <th>Type</th>
                            <th>Targets</th>
                            <th class="text-right">Min Purchase</th>
                            <th class="text-right">Max Discount</th>
                            <th class="text-right">Value</th>
                            <th>Validity</th>
                            <th class="text-center">Pool / Limits (D/C/DC)</th>
                            <th class="text-center">Banner</th>
                            <th style="width: 120px;">Display</th>
                            <th style="width: 140px;" class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="offer in offers" :key="offer.id" class="table-row-hover">
                            <td class="text-center font-weight-bold text-slate-500 align-middle">#{{ offer.id }}</td>
                            <td class="align-middle font-medium text-slate-800">{{ offer.offer_name }}</td>
                            <td class="align-middle font-monospace font-weight-bold text-indigo-dark">{{ offer.coupon_code }}</td>
                            <td class="align-middle text-slate-600 text-nowrap">{{ offer.offer_type }}</td>
                            <td class="align-middle text-slate-600 text-nowrap"><span class="badge bg-slate-light text-slate-dark">{{ offer.offer_for }}</span></td>
                            <td class="align-middle text-right text-slate-700">₹{{ parseFloat(offer.minimum_purchase_amount).toFixed(2) }}</td>
                            <td class="align-middle text-right text-slate-700">₹{{ parseFloat(offer.maximum_offer_amount).toFixed(2) }}</td>
                            <td class="align-middle text-right font-weight-bold text-success-dark">
                                {{ offer.offer_type === 'Flat Discount' ? '₹' : '' }}{{ offer.offer_value }}{{ offer.offer_type === 'Percentile Discount' ? '%' : '' }}
                            </td>
                            <td class="align-middle text-slate-600 text-nowrap small">
                                <div><strong>S:</strong> {{ offer.start }}</div>
                                <div><strong>E:</strong> {{ offer.end }}</div>
                            </td>
                            <td class="align-middle text-center small text-slate-600">
                                <div>Pool: {{ offer.ticket_size }}</div>
                                <div class="text-muted font-8">Per Day: {{ offer.ticket_per_day }} | Cust: {{ offer.ticket_per_customer }} | Cust/Day: {{ offer.ticket_per_customer_per_day }}</div>
                            </td>
                            <td class="text-center align-middle">
                                <div class="table-media-container" v-if="offer.media">
                                    <img :src="offer.media" class="table-cell-image" alt="Offer">
                                </div>
                                <span v-else class="text-slate-400 font-italic small">No image</span>
                            </td>
                            <td class="align-middle">
                                <span class="badge" :class="offer.display === 'Show' ? 'bg-success-light text-success-dark' : 'bg-slate-light text-slate-dark'">
                                    {{ offer.display }}
                                </span>
                            </td>
                            <td class="text-right align-middle text-nowrap">
                                <button @click="editOffer(offer)" class="btn btn-action-edit" title="Edit Offer"><i class="fas fa-pen"></i></button>
                                <button @click="deleteOffer(offer.id)" class="btn btn-action-delete" title="Delete Offer"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr v-if="offers.length === 0">
                            <td colspan="13" class="text-center py-5 text-slate-400">
                                <div class="empty-state">
                                    <i class="fas fa-percentage fa-2x mb-3 text-slate-300"></i>
                                    <p class="m-0 font-medium">No offers or coupons found matching the query.</p>
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
            <button class="btn btn-load-more text-uppercase" @click="loadMore" :disabled="currentPage == lastPage || offers.length === 0">
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
            offers: [],
            id: 0,
            display: '',
            offer_name: '',
            coupon_code: '',
            minimum_purchase_amount: '',
            maximum_offer_amount: '',
            offer_value: '',
            description: '',
            start: '',
            end: '',
            offer_type: '',
            offer_for: '',
            ticket_size: '',
            ticket_per_day: '',
            ticket_per_customer: '',
            ticket_per_customer_per_day: '',
            media: '',

            mediaFile: null,
            mediaFilename: '',
            mediaPreview: '',
            
            searchKey: '',
            searchVal: '',
            searchRes: 'Anywhere',
            perPage: 10,
            
            currentPage: 1,
            lastPage: 1,
            totalOffers: 0,
            
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
            // Default dates
            const today = new Date().toISOString().split('T')[0];
            this.start = today;
            
            const thirtyDaysLater = new Date();
            thirtyDaysLater.setDate(thirtyDaysLater.getDate() + 30);
            this.end = thirtyDaysLater.toISOString().split('T')[0];
        },

        closeForm() {
            this.isFormOpen = false;
            this.resetForm();
        },

        resetForm() {
            this.display = '';
            this.offer_name = '';
            this.coupon_code = '';
            this.minimum_purchase_amount = '';
            this.maximum_offer_amount = '';
            this.offer_value = '';
            this.description = '';
            this.start = '';
            this.end = '';
            this.offer_type = '';
            this.offer_for = '';
            this.ticket_size = '';
            this.ticket_per_day = '';
            this.ticket_per_customer = '';
            this.ticket_per_customer_per_day = '';
            this.media = '';
            this.mediaFile = null;
            this.mediaFilename = '';
            this.mediaPreview = '';
            this.validationErrors = {};
            if (this.$refs.mediaInput) {
                this.$refs.mediaInput.value = '';
            }
        },

        clearFieldError(field) {
            if (this.validationErrors[field]) {
                this.$set(this.validationErrors, field, '');
            }
            this.errorMessage = '';
        },

        handleFileChange(e) {
            const file = e.target.files[0];
            if (file) {
                this.mediaFile = file;
                this.mediaFilename = file.name;
                this.mediaPreview = URL.createObjectURL(file);
                this.clearFieldError('media');
            }
        },

        fetchOffers(page = 1) {
            if (page === 1) {
                this.offers = [];
            }
            
            const params = {
                model: 'Offer',
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
                    this.totalOffers = res.data.total;
                    
                    res.data.data.forEach(offer => {
                        this.offers.push(offer);
                    });
                })
                .catch(err => {
                    console.error('Error fetching offers:', err);
                });
        },

        loadMore() {
            if (this.currentPage < this.lastPage) {
                this.fetchOffers(this.currentPage + 1);
            }
        },

        submitSearch() {
            this.fetchOffers(1);
        },

        editOffer(offer) {
            this.isFormOpen = true;
            window.scroll(0, 0);
            this.id = offer.id;
            this.display = offer.display;
            this.offer_name = offer.offer_name;
            this.coupon_code = offer.coupon_code;
            this.minimum_purchase_amount = offer.minimum_purchase_amount;
            this.maximum_offer_amount = offer.maximum_offer_amount;
            this.offer_value = offer.offer_value;
            this.description = offer.description;
            this.start = offer.start;
            this.end = offer.end;
            this.offer_type = offer.offer_type;
            this.offer_for = offer.offer_for;
            this.ticket_size = offer.ticket_size;
            this.ticket_per_day = offer.ticket_per_day;
            this.ticket_per_customer = offer.ticket_per_customer;
            this.ticket_per_customer_per_day = offer.ticket_per_customer_per_day;
            this.media = offer.media;
            
            this.mediaFile = null;
            this.mediaFilename = offer.media ? offer.media.split('/').pop() : '';
            this.mediaPreview = '';
            this.validationErrors = {};
            this.successMessage = '';
            this.errorMessage = '';
        },

        saveOffer() {
            this.successMessage = '';
            this.errorMessage = '';
            this.validationErrors = {};

            const fd = new FormData();
            fd.append('model', 'Offer');
            fd.append('id', this.id);
            fd.append('form_action', 'CRUD');
            fd.append('display', this.display);
            fd.append('offer_name', this.offer_name);
            fd.append('coupon_code', this.coupon_code);
            fd.append('minimum_purchase_amount', this.minimum_purchase_amount);
            fd.append('maximum_offer_amount', this.maximum_offer_amount);
            fd.append('offer_value', this.offer_value);
            fd.append('description', this.description);
            fd.append('start', this.start);
            fd.append('end', this.end);
            fd.append('offer_type', this.offer_type);
            fd.append('offer_for', this.offer_for);
            fd.append('ticket_size', this.ticket_size);
            fd.append('ticket_per_day', this.ticket_per_day);
            fd.append('ticket_per_customer', this.ticket_per_customer);
            fd.append('ticket_per_customer_per_day', this.ticket_per_customer_per_day);

            let crudnames = ['display', 'offer_name', 'coupon_code', 'minimum_purchase_amount', 'maximum_offer_amount', 'offer_value', 'description', 'start', 'end', 'offer_type', 'offer_for', 'ticket_size', 'ticket_per_day', 'ticket_per_customer', 'ticket_per_customer_per_day'];
            let crudtypes = ['select', 'text', 'text', 'text', 'text', 'text', 'text', 'date', 'date', 'select', 'select', 'text', 'text', 'text', 'text'];
            let crudvalidation = ['required', 'required|string', 'required|string', 'required|string', 'required|string', 'required|string', 'required|string', 'required|string', 'required|string', 'required', 'required', 'required|string', 'required|string', 'required|string', 'required|string'];

            if (this.mediaFile) {
                fd.append('media', this.mediaFile);
                crudnames.push('media');
                crudtypes.push('image');
                crudvalidation.push('nullable');
            }

            fd.append('crudnames', crudnames.join(','));
            fd.append('crudtypes', crudtypes.join(','));
            fd.append('crudvalidation', crudvalidation.join(','));

            window.axios.post('/crud', fd)
                .then(res => {
                    this.successMessage = this.id === 0 ? 'Offer / Coupon created successfully.' : 'Offer / Coupon updated successfully.';
                    this.fetchOffers(1);
                    
                    if (this.id === 0) {
                        this.resetForm();
                        setTimeout(() => {
                            this.isFormOpen = false;
                        }, 1200);
                    } else {
                        this.mediaFile = null;
                        if (res.data && res.data !== 'ok') {
                            this.media = res.data;
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
                        this.errorMessage = 'An error occurred while saving the offer / coupon.';
                        console.error('Error saving offer:', error);
                    }
                });
        },

        deleteOffer(id) {
            const c = confirm('Are you sure you want to delete this offer / coupon?');
            if (c) {
                window.axios.delete('/crud/' + id, {
                    params: {
                        model: 'Offer',
                        files: 'media'
                    }
                })
                .then(res => {
                    if (res.data === 'ok') {
                        this.fetchOffers(1);
                    }
                })
                .catch(err => {
                    console.error('Error deleting offer:', err);
                });
            }
        },

        exportToExcel() {
            const tableSelect = document.getElementById('offers-table');
            const tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
            const filename = 'offers_list.xls';
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
        this.fetchOffers(1);
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
    padding: 0.6rem 0.85rem;
    background: #f8fafc;
    color: #475569;
    font-size: 0.9rem;
    cursor: pointer;
    margin: 0;
    transition: all 0.2s ease;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    padding-right: 5rem;
}
.custom-file-label::after {
    content: "Browse";
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    z-index: 3;
    display: flex;
    align-items: center;
    padding: 0 0.85rem;
    line-height: 1.5;
    color: #334155;
    background-color: #e2e8f0;
    border-left: 1px solid #cbd5e1;
    border-radius: 0 7px 7px 0;
    font-weight: 600;
    font-size: 0.8rem;
    text-transform: uppercase;
}

/* Media Preview Thumbnail inside Form */
.media-preview-container {
    width: 80px;
    height: 60px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
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
    width: 60px;
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
