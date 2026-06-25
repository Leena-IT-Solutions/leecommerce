<template>
<div>
    <!-- Sliding Toggle Switch -->
    <div v-if="what == 'toggle'" class="d-flex align-items-center gap-3">
        <label class="modern-switch mb-0">
            <input type="checkbox" :checked="val == options[0]" @change="toggleValue()">
            <span class="slider"></span>
        </label>
        <span :class="[val == options[0] ? 'text-indigo-600 font-weight-bold' : 'text-slate-400']" class="small font-weight-semibold text-uppercase tracking-wider me-1">
            {{ val }}
        </span>
        <span 
            v-if="showSavedSuccess"
            class="saved-success-badge d-inline-flex align-items-center gap-1"
        >
            <i class="fas fa-check"></i> Saved
        </span>
    </div>

    <!-- Text Input with Save button only when changed -->
    <div v-if="what == 'text'" class="modern-input-group d-flex align-items-center gap-3">
        <input 
            @input="makeTampered()" 
            type="text" 
            :class="['form-control modern-input', isTampered ? 'border-warning-glow' : 'border-clean']" 
            placeholder="Enter configuration value..." 
            v-model="val"
        >
        <button 
            v-if="isTampered"
            @click="updateValueToServer()" 
            class="btn modern-btn btn-indigo-save animate-fade-in" 
            type="button"
        >
            <i class="fas fa-save"></i>
            <span>Save</span>
        </button>
        <span 
            v-if="showSavedSuccess && !isTampered"
            class="saved-success-badge d-inline-flex align-items-center gap-1"
        >
            <i class="fas fa-check"></i> Saved
        </span>
    </div>

    <!-- Textarea Input with Save button only when changed -->
    <div v-if="what == 'textarea'" class="w-100">
        <textarea 
            @input="makeTampered()" 
            :class="['form-control modern-textarea mb-2', isTampered ? 'border-warning-glow' : 'border-clean']" 
            rows="4" 
            v-model="val"
            placeholder="Enter detailed description note..."
        ></textarea>
        <div class="d-flex justify-content-end align-items-center gap-3">
            <span 
                v-if="showSavedSuccess && !isTampered"
                class="saved-success-badge d-inline-flex align-items-center gap-1 me-2"
            >
                <i class="fas fa-check"></i> Saved
            </span>
            <button 
                v-if="isTampered"
                @click="updateValueToServer()" 
                class="btn modern-btn btn-indigo-save animate-fade-in" 
                type="button"
            >
                <i class="fas fa-save"></i>
                <span>Save Changes</span>
            </button>
        </div>
    </div>

    <!-- Image Uploader with Real-time Thumbnail Preview -->
    <form enctype="multipart/form-data" action="POST" @submit.prevent="submitForm($event)">
        <div v-if="what == 'image'" class="d-flex align-items-center gap-3">
            <div class="image-preview-box">
                <img v-if="localPreviewUrl || val" :src="localPreviewUrl || val" class="preview-img rounded border shadow-sm">
                <div v-else class="no-img rounded border shadow-sm bg-light d-flex align-items-center justify-content-center">
                    <i class="fas fa-image text-slate-400"></i>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <!-- Hidden file input -->
                <input 
                    type="file" 
                    class="d-none" 
                    ref="fileInput"
                    @change="onFileChange"
                    accept="image/*"
                >
                <!-- Styled select trigger button -->
                <button 
                    type="button" 
                    @click="triggerFileInput" 
                    class="btn btn-sm btn-outline-secondary px-3 py-2 font-weight-semibold rounded-lg d-flex align-items-center gap-1.5"
                >
                    <i class="fas fa-upload"></i>
                    <span>{{ hasNewFile ? 'Change File' : 'Choose Logo' }}</span>
                </button>
                <!-- Styled submit button -->
                <button 
                    v-if="hasNewFile"
                    class="btn btn-sm btn-indigo-save px-3 py-2 font-weight-semibold rounded-lg d-flex align-items-center gap-1.5 animate-fade-in" 
                    type="submit"
                >
                    <i class="fas fa-save"></i>
                    <span>Save Logo</span>
                </button>
                <span 
                    v-if="showSavedSuccess && !hasNewFile"
                    class="saved-success-badge d-inline-flex align-items-center gap-1"
                >
                    <i class="fas fa-check"></i> Saved
                </span>
            </div>
        </div>
    </form>
</div>
</template>

<style scoped>
/* Modern sliding toggle switch */
.modern-switch {
    position: relative;
    display: inline-block;
    width: 44px;
    height: 24px;
}

.modern-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #cbd5e1; /* Slate 300 */
    transition: .25s cubic-bezier(0.4, 0, 0.2, 1);
    border-radius: 24px;
}

.slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .25s cubic-bezier(0.4, 0, 0.2, 1);
    border-radius: 50%;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15);
}

input:checked + .slider {
    background-color: #4f46e5; /* Indigo 600 */
}

input:checked + .slider:before {
    transform: translateX(20px);
}

/* Modern inputs */
.modern-input {
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    padding: 0.55rem 0.9rem;
    font-size: 0.9rem;
    color: #1e293b;
    background-color: #ffffff;
    transition: all 0.2s ease;
    box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.02);
    width: 280px; /* Consistent standard width for settings text inputs */
}

.modern-input:focus {
    outline: none;
    border-color: #6366f1; /* Indigo 500 */
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
    background-color: #ffffff;
}

.modern-textarea {
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    padding: 0.75rem 0.9rem;
    font-size: 0.9rem;
    color: #1e293b;
    background-color: #ffffff;
    transition: all 0.2s ease;
    box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.02);
    resize: vertical;
    width: 100%;
}

.modern-textarea:focus {
    outline: none;
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
    background-color: #ffffff;
}

/* Validation/tamper states */
.border-clean {
    border-color: #cbd5e1;
}

.border-warning-glow {
    border-color: #f59e0b !important; /* Amber 500 */
    box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.15) !important;
}

/* Modern buttons */
.modern-btn {
    border-radius: 8px;
    padding: 0.55rem 1rem;
    font-size: 0.85rem;
    font-weight: 600;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    height: 38px; /* Match input height */
}

.btn-indigo-save {
    background-color: #4f46e5 !important;
    border: 1px solid #4f46e5 !important;
    color: #ffffff !important;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}

.btn-indigo-save:hover {
    background-color: #4338ca !important;
    border-color: #4338ca !important;
}

/* Saved success badge styling with smooth fade-in-out */
.saved-success-badge {
    font-size: 0.85rem;
    font-weight: 600;
    color: #16a34a !important; /* Green 600 */
    animation: fadeInOut 2.0s cubic-bezier(0.4, 0, 0.2, 1) forwards;
}

@keyframes fadeInOut {
    0% { opacity: 0; transform: translateY(3px); }
    10% { opacity: 1; transform: translateY(0); }
    85% { opacity: 1; transform: translateY(0); }
    100% { opacity: 0; transform: translateY(-3px); }
}

/* Image preview box */
.image-preview-box .preview-img {
    width: 48px;
    height: 48px;
    object-fit: cover;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    background: #f8fafc;
}

.image-preview-box .no-img {
    width: 48px;
    height: 48px;
    border-radius: 8px;
    border: 1px dashed #cbd5e1;
    background: #f8fafc;
}

.image-preview-box .no-img i {
    font-size: 1.25rem;
}

/* Hide scrollbar utility */
.no-scrollbar {
    scrollbar-width: none;
}
.no-scrollbar::-webkit-scrollbar {
    display: none;
}

/* Fade in animation */
.animate-fade-in {
    animation: fadeIn 0.2s ease-out forwards;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(2px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

<script>
export default {
    props: ['dataoptions', 'datavalue', 'datakey', 'datawhat'],

    data: function () {
        return {
            options: ["On", "Off"],
            val: "On",
            key: "COD",
            what: this.datawhat,
            isTampered: false,
            hasNewFile: false,
            localPreviewUrl: null,
            showSavedSuccess: false,
            savedTimer: null,
        }
    },

    methods: {
        submitForm(e) {
            const fd = new FormData();
            fd.append('key', this.key);
            fd.append('what', 'file');
            if (this.$refs.fileInput.files.length > 0) {
                let file = this.$refs.fileInput.files[0];
                fd.append('media', file);
            }
            let url = "/administrator/save_setting";
            window.axios.post(url, fd).then(response => {
                this.hasNewFile = false;
                this.localPreviewUrl = null;
                this.triggerSavedIndicator();
                this.getSetting();
            });
        },

        onFileChange(e) {
            if (e.target.files.length > 0) {
                this.hasNewFile = true;
                this.localPreviewUrl = URL.createObjectURL(e.target.files[0]);
            }
        },

        triggerFileInput() {
            this.$refs.fileInput.click();
        },

        makeTampered() {
            this.isTampered = true;
            this.showSavedSuccess = false; // Hide success indicator if user starts typing again
        },

        toggleValue() {
            let v = this.val == this.options[0] ? this.options[1] : this.options[0];
            this.val = v;
            this.updateValueToServer();
        },

        updateValueToServer() {
            let url = "/administrator/save_setting";
            window.axios.post(url, { key: this.key, val: this.val }).then(response => {
                this.isTampered = false;
                this.triggerSavedIndicator();
            });
        },

        triggerSavedIndicator() {
            this.showSavedSuccess = true;
            if (this.savedTimer) {
                clearTimeout(this.savedTimer);
            }
            this.savedTimer = setTimeout(() => {
                this.showSavedSuccess = false;
            }, 2000); // Remove from DOM after 2 seconds (matching CSS animation)
        },

        getSetting() {
            let url = "/administrator/get_setting";
            window.axios.post(url, { key: this.key, val: this.val }).then(response => {
                this.val = response.data.val;
            });
        },

        change(e) {
            /* console.log(e.target.value); */
        }
    },

    created() {
        if (this.dataoptions.length > 0 && this.dataoptions.length == 2) {
            this.options = this.dataoptions;
        }
        this.val = this.datavalue;
        this.key = this.datakey;
        this.getSetting();
    }
}
</script>