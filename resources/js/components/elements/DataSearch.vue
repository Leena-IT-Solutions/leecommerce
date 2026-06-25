<template>
<div class="advanced-search-container mb-4">
    <div class="d-flex flex-column flex-lg-row align-items-stretch align-items-lg-center gap-3">
        <!-- Unified Search Bar -->
        <div class="flex-grow-1 position-relative">
            <div class="modern-search-bar d-flex align-items-stretch">
                
                <!-- Column Selector Dropdown -->
                <div class="dropdown-wrapper position-relative d-flex align-items-center">
                    <button 
                        @click.stop="toggleColumnDropdown" 
                        type="button" 
                        class="btn dropdown-trigger-btn d-flex align-items-center gap-2 px-3 h-100"
                    >
                        <i class="fas fa-filter text-slate-400"></i>
                        <span class="trigger-label text-slate-700 font-weight-semibold small">
                            {{ activeColumnLabel }}
                        </span>
                        <i class="fas fa-chevron-down text-slate-400 xs-icon"></i>
                    </button>
                    
                    <transition name="fade-slide">
                        <div v-if="isColumnDropdownOpen" class="custom-dropdown-menu shadow-lg rounded-lg border py-2" v-click-outside="closeColumnDropdown">
                            <button 
                                @click="selectColumn('')" 
                                type="button" 
                                :class="['dropdown-item d-flex align-items-center justify-content-between py-2 px-3 small', localSearchKey === '' ? 'active-item font-weight-bold' : '']"
                            >
                                <span>All Columns</span>
                                <i v-if="localSearchKey === ''" class="fas fa-check text-indigo-600"></i>
                            </button>
                            <div class="dropdown-divider my-1"></div>
                            <button 
                                v-for="col in columns" 
                                :key="col.value"
                                @click="selectColumn(col.value)" 
                                type="button"
                                :class="['dropdown-item d-flex align-items-center justify-content-between py-2 px-3 small', localSearchKey === col.value ? 'active-item font-weight-bold' : '']"
                            >
                                <span>{{ col.label }}</span>
                                <i v-if="localSearchKey === col.value" class="fas fa-check text-indigo-600"></i>
                            </button>
                        </div>
                    </transition>
                </div>
                
                <div class="vertical-divider"></div>
                
                <!-- Query Input Field with Search Icon & Clear Button -->
                <div class="input-wrapper flex-grow-1 position-relative d-flex align-items-center">
                    <i class="fas fa-search search-inside-icon text-slate-400 position-absolute"></i>
                    <input 
                        type="text" 
                        class="form-control query-input border-0 shadow-none h-100" 
                        placeholder="Search anything..."
                        v-model="localSearchVal"
                        @keyup.enter="triggerSearch"
                    >
                    <button 
                        v-if="localSearchVal" 
                        @click="clearQuery" 
                        type="button" 
                        class="btn clear-query-btn p-0 position-absolute d-flex align-items-center justify-content-center"
                    >
                        <i class="fas fa-times-circle text-slate-400"></i>
                    </button>
                </div>
                
                <div class="vertical-divider d-none d-sm-block"></div>
                
                <!-- Match Mode Dropdown -->
                <div class="dropdown-wrapper position-relative d-flex align-items-center d-none d-sm-block">
                    <button 
                        @click.stop="toggleResDropdown" 
                        type="button" 
                        class="btn dropdown-trigger-btn d-flex align-items-center gap-2 px-3 h-100"
                    >
                        <span class="trigger-label text-slate-500 small font-weight-medium">
                            Mode: <strong class="text-slate-800">{{ localSearchRes }}</strong>
                        </span>
                        <i class="fas fa-chevron-down text-slate-400 xs-icon"></i>
                    </button>
                    
                    <transition name="fade-slide">
                        <div v-if="isResDropdownOpen" class="custom-dropdown-menu dropdown-right shadow-lg rounded-lg border py-2" v-click-outside="closeResDropdown">
                            <button 
                                v-for="mode in ['Anywhere', 'Start', 'Exact']" 
                                :key="mode"
                                @click="selectRes(mode)" 
                                type="button"
                                :class="['dropdown-item d-flex align-items-center justify-content-between py-2 px-3 small', localSearchRes === mode ? 'active-item font-weight-bold' : '']"
                            >
                                <span>{{ mode }}</span>
                                <i v-if="localSearchRes === mode" class="fas fa-check text-indigo-600"></i>
                            </button>
                        </div>
                    </transition>
                </div>
                
                <!-- Search Commit Button -->
                <button @click="triggerSearch" type="button" class="btn search-commit-btn px-4 d-flex align-items-center gap-2 font-weight-bold text-uppercase">
                    <i class="fas fa-search"></i>
                    <span class="d-none d-md-inline">Search</span>
                </button>
            </div>
        </div>
        
        <!-- Entries Limit Selector -->
        <div class="per-page-wrapper flex-shrink-0 d-flex align-items-center gap-2.5 bg-white px-3 py-2 border rounded-lg shadow-sm">
            <span class="text-muted small font-weight-semibold text-uppercase tracking-wider">Show</span>
            <select v-model="localPerPage" class="form-select border-0 shadow-none p-0 pe-4 font-weight-bold text-slate-800 cursor-pointer small">
                <option :value="1">1</option>
                <option :value="10">10</option>
                <option :value="25">25</option>
                <option :value="50">50</option>
                <option :value="100">100</option>
            </select>
            <span class="text-muted small font-weight-semibold text-uppercase tracking-wider">Entries</span>
        </div>
    </div>
    
    <!-- Active Filter Badge Row -->
    <transition name="fade-slide">
        <div v-if="localSearchVal" class="active-filters-row d-flex align-items-center gap-2 mt-3">
            <span class="small text-slate-400 font-weight-semibold text-uppercase tracking-wide">Active Filter:</span>
            <div class="filter-badge bg-indigo-50 text-indigo-700 border border-indigo-100 rounded-pill px-3 py-1.5 small d-inline-flex align-items-center gap-2 shadow-sm">
                <i class="fas fa-filter text-indigo-400 xs-icon"></i>
                <span class="font-weight-bold text-indigo-800">{{ activeColumnLabel }}</span>
                <span class="text-indigo-400 font-weight-medium">contains</span>
                <span class="font-weight-bold text-indigo-900">"{{ localSearchVal }}"</span>
                <span class="badge bg-indigo-200 text-indigo-800 rounded-pill font-weight-bold tracking-wider text-uppercase px-2 py-0.5" style="font-size: 0.65rem;">
                    {{ localSearchRes }}
                </span>
                <button @click="clearAllFilters" type="button" class="btn clear-filter-pill-btn p-0 border-0 bg-transparent text-indigo-500 d-flex align-items-center justify-content-center" title="Clear Filter">
                    <i class="fas fa-times-circle"></i>
                </button>
            </div>
        </div>
    </transition>
</div>
</template>

<style scoped>
.advanced-search-container {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
}

/* Modern Unified Search Bar */
.modern-search-bar {
    background: #ffffff;
    border: 1px solid #cbd5e1; /* Slate 300 */
    border-radius: 12px;
    height: 48px;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}

.modern-search-bar:focus-within {
    border-color: #6366f1; /* Indigo 500 */
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.12);
}

/* Dropdown Triggers */
.dropdown-trigger-btn {
    border: none;
    background: transparent;
    border-radius: 0;
    white-space: nowrap;
    transition: background 0.15s ease;
}

.dropdown-trigger-btn:hover {
    background: #f8fafc; /* Slate 50 */
}

.trigger-label {
    max-width: 140px;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Custom Dropdown Menus */
.custom-dropdown-menu {
    position: absolute;
    top: calc(100% + 6px);
    left: 0;
    z-index: 1050;
    min-width: 200px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    transform-origin: top left;
}

.custom-dropdown-menu.dropdown-right {
    left: auto;
    right: 0;
    transform-origin: top right;
}

.dropdown-item {
    color: #334155;
    transition: all 0.15s ease;
    cursor: pointer;
    border: none;
    background: transparent;
    text-align: left;
    width: 100%;
}

.dropdown-item:hover {
    background: #f1f5f9;
    color: #1e293b;
}

.dropdown-item.active-item {
    color: #4f46e5; /* Indigo 600 */
    background: #e0e7ff; /* Indigo 100 */
}

/* Vertical Dividers */
.vertical-divider {
    width: 1px;
    background-color: #e2e8f0;
    margin: 10px 0;
}

/* Query Input */
.input-wrapper {
    padding-left: 40px;
    padding-right: 40px;
}

.search-inside-icon {
    left: 14px;
    font-size: 0.95rem;
}

.query-input {
    font-size: 0.95rem;
    color: #1e293b;
    background: transparent;
}

.query-input::placeholder {
    color: #94a3b8;
}

.clear-query-btn {
    right: 12px;
    font-size: 1.1rem;
    transition: color 0.15s ease;
    border: none;
    background: transparent;
}

.clear-query-btn:hover i {
    color: #64748b !important;
}

/* Search Commit Button */
.search-commit-btn {
    background-color: #4f46e5;
    color: #ffffff;
    border: none;
    border-radius: 0 11px 11px 0;
    font-size: 0.85rem;
    letter-spacing: 0.05em;
    transition: all 0.15s ease;
}

.search-commit-btn:hover {
    background-color: #4338ca;
    color: #ffffff;
}

/* Per Page Selector Wrapper */
.per-page-wrapper {
    border-color: #cbd5e1 !important;
    height: 48px;
}

.form-select {
    cursor: pointer;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%2364748b' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 0 center;
    background-size: 12px 10px;
}

/* Filter Badge Pill */
.filter-badge {
    box-shadow: 0 1px 3px rgba(79, 70, 229, 0.05);
}

.clear-filter-pill-btn {
    font-size: 1.1rem;
    transition: all 0.15s ease;
    line-height: 1;
}

.clear-filter-pill-btn:hover i {
    color: #3730a3 !important; /* Indigo 800 */
}

/* Font Size Icon Helpers */
.xs-icon {
    font-size: 0.65rem;
}

/* Animations */
.fade-slide-enter-active, .fade-slide-leave-active {
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

.fade-slide-enter, .fade-slide-leave-to {
    opacity: 0;
    transform: translateY(-8px) scale(0.98);
}
</style>

<script>
export default {
    name: 'DataSearch',
    props: {
        columns: {
            type: Array,
            required: true
        },
        perPage: {
            type: [Number, String],
            default: 10
        },
        searchKey: {
            type: String,
            default: ''
        },
        searchRes: {
            type: String,
            default: 'Anywhere'
        },
        searchVal: {
            type: String,
            default: ''
        }
    },
    data() {
        return {
            isColumnDropdownOpen: false,
            isResDropdownOpen: false
        }
    },
    computed: {
        localPerPage: {
            get() {
                return this.perPage;
            },
            set(val) {
                this.$emit('update:perPage', val);
                this.$nextTick(() => {
                    this.$emit('search');
                });
            }
        },
        localSearchKey: {
            get() {
                return this.searchKey;
            },
            set(val) {
                this.$emit('update:searchKey', val);
            }
        },
        localSearchRes: {
            get() {
                return this.searchRes;
            },
            set(val) {
                this.$emit('update:searchRes', val);
                this.$nextTick(() => {
                    if (this.localSearchVal) {
                        this.triggerSearch();
                    }
                });
            }
        },
        localSearchVal: {
            get() {
                return this.searchVal;
            },
            set(val) {
                this.$emit('update:searchVal', val);
                if (val === '') {
                    this.$nextTick(() => {
                        this.triggerSearch();
                    });
                }
            }
        },
        activeColumnLabel() {
            if (this.localSearchKey === '') {
                return 'All Columns';
            }
            const activeCol = this.columns.find(col => col.value === this.localSearchKey);
            return activeCol ? activeCol.label : 'All Columns';
        }
    },
    methods: {
        toggleColumnDropdown() {
            this.isColumnDropdownOpen = !this.isColumnDropdownOpen;
            this.isResDropdownOpen = false;
        },
        closeColumnDropdown() {
            this.isColumnDropdownOpen = false;
        },
        selectColumn(val) {
            this.localSearchKey = val;
            this.isColumnDropdownOpen = false;
            if (this.localSearchVal) {
                this.triggerSearch();
            }
        },
        toggleResDropdown() {
            this.isResDropdownOpen = !this.isResDropdownOpen;
            this.isColumnDropdownOpen = false;
        },
        closeResDropdown() {
            this.isResDropdownOpen = false;
        },
        selectRes(val) {
            this.localSearchRes = val;
            this.isResDropdownOpen = false;
        },
        triggerSearch() {
            this.$emit('search');
        },
        clearQuery() {
            this.localSearchVal = '';
        },
        clearAllFilters() {
            this.localSearchVal = '';
            this.localSearchKey = '';
            this.localSearchRes = 'Anywhere';
            this.$nextTick(() => {
                this.triggerSearch();
            });
        }
    },
    directives: {
        clickOutside: {
            bind(el, binding, vnode) {
                el.clickOutsideEvent = function(event) {
                    if (!(el == event.target || el.contains(event.target))) {
                        vnode.context[binding.expression](event);
                    }
                };
                document.body.addEventListener('click', el.clickOutsideEvent);
            },
            unbind(el) {
                document.body.removeEventListener('click', el.clickOutsideEvent);
            }
        }
    }
}
</script>
