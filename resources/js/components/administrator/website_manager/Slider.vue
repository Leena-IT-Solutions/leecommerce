<template>
<div class="crud-wrapper animate-fade-in">

    <!-- Title and Header Panel -->
    <div class="header-panel mb-4">
        <div class="row align-items-center justify-content-between g-3">
            <div class="col-12 col-sm-auto">
                <div class="title-container">
                    <h5 class="page-title text-uppercase m-0">Slider Manager</h5>
                    <span v-if="totalSlides !== null" class="count-badge">
                        <i class="fas fa-images mr-1"></i> {{ totalSlides }} Total Slides
                    </span>
                </div>
            </div>
            <div class="col-12 col-sm-auto text-sm-right">
                <button v-if="!isFormOpen" @click="openCreateForm" class="btn btn-add text-uppercase">
                    <i class="fas fa-plus mr-1"></i> Add Slide
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
                {{ id === 0 ? 'Create New Slide' : 'Edit Slide #' + id }}
            </h6>
        </div>
        
        <form @submit.prevent="saveSlide">
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

                <!-- Title -->
                <div class="col-12 col-sm-6 col-md-8">
                    <label class="disable-select form-label" for="title">Title</label>
                    <input class="form-control" type="text" id="title" v-model="title" placeholder="Enter Slide Title" @input="clearFieldError('title')">
                    <span v-if="validationErrors.title" class="disable-select form-error">{{ validationErrors.title }}</span>
                </div>

                <!-- Link -->
                <div class="col-12">
                    <label class="disable-select form-label" for="link">Link / Action URL</label>
                    <input class="form-control" type="text" id="link" v-model="link" placeholder="Enter link redirection URL" @input="clearFieldError('link')">
                    <span v-if="validationErrors.link" class="disable-select form-error">{{ validationErrors.link }}</span>
                </div>

                <!-- Desktop Slide Media -->
                <div class="col-12">
                    <label class="disable-select form-label">Desktop Slide Image (2000x700px)</label>
                    <div class="d-flex align-items-center flex-wrap gap-3 mb-2">
                        <div class="media-preview-container" v-if="mediaPreview || media" style="width: 120px; height: 50px;">
                            <img :src="mediaPreview || media" class="media-preview-img" alt="Desktop Preview">
                        </div>
                        <div class="flex-grow-1">
                            <div class="custom-file-wrapper">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="media" ref="mediaInput" @change="handleFileChange($event, 'desktop')">
                                    <label class="custom-file-label text-truncate" for="media">{{ mediaFilename || 'Choose desktop image...' }}</label>
                                </div>
                            </div>
                            <span v-if="validationErrors.media" class="disable-select form-error">{{ validationErrors.media }}</span>
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
            { value: 'title', label: 'Title' },
            { value: 'link', label: 'Link' },
            { value: 'display', label: 'Display Status' }
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
                <table class="table" id="slides-table">
                    <thead>
                        <tr>
                            <th style="width: 80px;" class="text-center">ID</th>
                            <th>Slide Title</th>
                            <th>Redirection Link</th>
                            <th class="text-center" style="width: 180px;">Desktop Banner</th>
                            <th style="width: 120px;">Display</th>
                            <th style="width: 140px;" class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="slide in slides" :key="slide.id" class="table-row-hover">
                            <td class="text-center font-weight-bold text-slate-500 align-middle">#{{ slide.id }}</td>
                            <td class="align-middle font-medium text-slate-800">{{ slide.title }}</td>
                            <td class="align-middle text-slate-600 font-monospace small" style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                <a :href="slide.link" target="_blank" title="Open Link">{{ slide.link }}</a>
                            </td>
                            <td class="text-center align-middle">
                                <div class="table-media-container" v-if="slide.media" style="width: 120px; height: 42px; border-radius: 4px;">
                                    <img :src="slide.media" class="table-cell-image" alt="Desktop Slide">
                                </div>
                                <span v-else class="text-slate-400 font-italic small">No image</span>
                            </td>
                            <td class="align-middle">
                                <span class="badge" :class="slide.display === 'Show' ? 'bg-success-light text-success-dark' : 'bg-slate-light text-slate-dark'">
                                    {{ slide.display }}
                                </span>
                            </td>
                            <td class="text-right align-middle text-nowrap">
                                <button @click="editSlide(slide)" class="btn btn-action-edit" title="Edit Slide"><i class="fas fa-pen"></i></button>
                                <button @click="deleteSlide(slide.id)" class="btn btn-action-delete" title="Delete Slide"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr v-if="slides.length === 0">
                            <td colspan="7" class="text-center py-5 text-slate-400">
                                <div class="empty-state">
                                    <i class="fas fa-images fa-2x mb-3 text-slate-300"></i>
                                    <p class="m-0 font-medium">No slides found matching the query.</p>
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
            <button class="btn btn-load-more text-uppercase" @click="loadMore" :disabled="currentPage == lastPage || slides.length === 0">
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
            slides: [],
            id: 0,
            display: '',
            title: '',
            link: '',
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
            totalSlides: 0,
            
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
            this.title = '';
            this.link = '';
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

        handleFileChange(e, type) {
            const file = e.target.files[0];
            if (file) {
                if (type === 'desktop') {
                    this.mediaFile = file;
                    this.mediaFilename = file.name;
                    this.mediaPreview = URL.createObjectURL(file);
                    this.clearFieldError('media');
                }
            }
        },

        fetchSlides(page = 1) {
            if (page === 1) {
                this.slides = [];
            }
            
            const params = {
                model: 'Slider',
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
                    this.totalSlides = res.data.total;
                    
                    res.data.data.forEach(slide => {
                        this.slides.push(slide);
                    });
                })
                .catch(err => {
                    console.error('Error fetching slides:', err);
                });
        },

        loadMore() {
            if (this.currentPage < this.lastPage) {
                this.fetchSlides(this.currentPage + 1);
            }
        },

        submitSearch() {
            this.fetchSlides(1);
        },

        editSlide(slide) {
            this.isFormOpen = true;
            window.scroll(0, 0);
            this.id = slide.id;
            this.display = slide.display;
            this.title = slide.title;
            this.link = slide.link;
            this.media = slide.media;
            
            this.mediaFile = null;
            this.mediaFilename = slide.media ? slide.media.split('/').pop() : '';
            this.mediaPreview = '';

            this.validationErrors = {};
            this.successMessage = '';
            this.errorMessage = '';
        },

        saveSlide() {
            this.successMessage = '';
            this.errorMessage = '';
            this.validationErrors = {};

            const fd = new FormData();
            fd.append('model', 'Slider');
            fd.append('id', this.id);
            fd.append('form_action', 'CRUD');
            fd.append('display', this.display);
            fd.append('title', this.title);
            fd.append('link', this.link);

            let crudnames = ['display', 'title', 'link'];
            let crudtypes = ['select', 'text', 'text'];
            let crudvalidation = ['required', 'required|string', 'required|string'];

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
                    this.successMessage = this.id === 0 ? 'Slide created successfully.' : 'Slide updated successfully.';
                    this.fetchSlides(1);
                    
                    if (this.id === 0) {
                        this.resetForm();
                        setTimeout(() => {
                            this.isFormOpen = false;
                        }, 1200);
                    } else {
                        this.mediaFile = null;
                        if (res.data && typeof res.data === 'object') {
                            if (res.data.media) this.media = res.data.media;
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
                        this.errorMessage = 'An error occurred while saving the slide.';
                        console.error('Error saving slide:', error);
                    }
                });
        },

        deleteSlide(id) {
            const c = confirm('Are you sure you want to delete this slide?');
            if (c) {
                window.axios.delete('/crud/' + id, {
                    params: {
                        model: 'Slider',
                        files: 'media'
                    }
                })
                .then(res => {
                    if (res.data === 'ok') {
                        this.fetchSlides(1);
                    }
                })
                .catch(err => {
                    console.error('Error deleting slide:', err);
                });
            }
        },

        exportToExcel() {
            const tableSelect = document.getElementById('slides-table');
            const tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
            const filename = 'slides_list.xls';
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
        this.fetchSlides(1);
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

/* Table Media Preview */
.table-media-container {
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
