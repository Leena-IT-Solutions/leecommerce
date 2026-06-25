@extends('layouts.administrator')

@section('head')
<title>Theme Branding Manager</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    .theme-manager-page {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        color: #1e293b; /* Slate 800 */
    }

    .color-card {
        background: #ffffff;
        border: 1px solid rgba(226, 232, 240, 0.8);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        border-radius: 12px;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .color-row {
        border-bottom: 1px solid #f1f5f9;
        padding: 1.25rem 0;
    }

    .color-row:last-child {
        border-bottom: none;
    }

    .color-input-group {
        display: flex;
        align-items: center;
        gap: 8px;
        width: 210px;
    }

    .color-picker-input {
        width: 46px;
        height: 38px;
        padding: 0;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        cursor: pointer;
        background: transparent;
    }

    .color-text-input {
        flex-grow: 1;
        font-family: monospace;
        font-size: 0.85rem;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.05em;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        height: 38px;
        padding: 0.375rem 0.75rem;
        transition: all 0.15s ease;
    }

    .color-text-input:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
    }

    /* Live Preview Mockup styling */
    .preview-card {
        position: sticky;
        top: 24px;
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        border-radius: 16px;
        padding: 24px;
    }

    .mockup-container {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        --theme-primary: #2d208d;
        --theme-secondary: #2f6a60;
        --theme-success: #00b315;
        --theme-info: #00d5ff;
        --theme-warning: #f1b83b;
        --theme-danger: #db0000;
        --theme-light: #ededed;
        --theme-dark: #000000;
    }

    /* Mockup Elements using dynamic variables */
    .mock-header {
        background-color: var(--theme-primary);
        color: #ffffff;
        padding: 12px 16px;
        font-size: 0.85rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .mock-body {
        padding: 18px;
    }

    .mock-btn-primary {
        background-color: var(--theme-primary);
        color: #ffffff;
        border: none;
        border-radius: 6px;
        padding: 6px 12px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .mock-btn-secondary {
        background-color: var(--theme-secondary);
        color: #ffffff;
        border: none;
        border-radius: 6px;
        padding: 6px 12px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .mock-badge-success {
        background-color: var(--theme-success);
        color: #ffffff;
        font-size: 0.65rem;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 12px;
        text-transform: uppercase;
    }

    .mock-alert-warning {
        background-color: #fffbeb;
        border-left: 4px solid var(--theme-warning);
        color: #92400e;
        padding: 10px 12px;
        font-size: 0.75rem;
        border-radius: 0 6px 6px 0;
    }

    .mock-danger-box {
        border: 1px solid var(--theme-danger);
        background-color: #fef2f2;
        color: var(--theme-danger);
        padding: 8px 12px;
        font-size: 0.75rem;
        border-radius: 6px;
        font-weight: 600;
    }

    .save-theme-btn {
        background-color: #4f46e5;
        border: 1px solid #4f46e5;
        color: #ffffff;
        border-radius: 8px;
        padding: 0.75rem 1.5rem;
        font-size: 0.95rem;
        font-weight: 600;
        transition: all 0.15s ease;
        box-shadow: 0 2px 4px rgba(79, 70, 229, 0.15);
    }

    .save-theme-btn:hover {
        background-color: #4338ca;
        border-color: #4338ca;
        box-shadow: 0 4px 6px rgba(79, 70, 229, 0.2);
    }
</style>
@endsection

@section('content')
<div class="theme-manager-page container-fluid p-0">
    <!-- Header -->
    <div class="px-4 pt-4 pb-3 border-bottom bg-white mb-4">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h1 class="h3 font-weight-bold text-slate-800 mb-1">Theme Branding Manager</h1>
                <p class="text-muted mb-0 small">Customize the global color system of your storefront and administration dashboards.</p>
            </div>
            <div class="d-flex align-items-center gap-1.5 text-indigo-700 bg-indigo-50 border border-indigo-100 px-3 py-1.5 rounded-pill font-weight-semibold small">
                <i class="fas fa-palette"></i> CSS Variable Sync
            </div>
        </div>
    </div>

    <div class="px-4 pb-5">
        <!-- Success Alert -->
        @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-lg p-3.5 d-flex align-items-center gap-2 mb-4 animate-fade-in" role="alert">
            <i class="fas fa-check-circle fs-5 text-success"></i>
            <div class="font-weight-semibold small text-success">{{ session('success') }}</div>
        </div>
        @endif

        <form action="/administrator/theme" method="get">
            <div class="row g-4">
                <!-- Color Configuration Column -->
                <div class="col-12 col-lg-7">
                    <div class="color-card p-4">
                        <div class="border-bottom pb-3 mb-3">
                            <h2 class="h5 font-weight-bold text-slate-800 mb-1">Theme Palette Settings</h2>
                            <p class="text-muted mb-0 small">Adjust color definitions below. Colors will compile dynamically into your UI components.</p>
                        </div>

                        <!-- Primary -->
                        <div class="color-row d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="small font-weight-bold mb-1 text-slate-800">Primary Color</h4>
                                <p class="text-muted mb-0 small" style="max-width: 320px;">Primary branding, main navigation, major buttons, and active status indicators.</p>
                            </div>
                            <div class="color-input-group">
                                <input type="color" class="color-picker-input" value="{{ $theme->primary }}" name="primary">
                                <input type="text" class="form-control color-text-input" value="{{ $theme->primary }}" name="primary_text" placeholder="#HEX">
                            </div>
                        </div>

                        <!-- Secondary -->
                        <div class="color-row d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="small font-weight-bold mb-1 text-slate-800">Secondary Color</h4>
                                <p class="text-muted mb-0 small" style="max-width: 320px;">Secondary buttons, auxiliary badges, border highlights, and muted accents.</p>
                            </div>
                            <div class="color-input-group">
                                <input type="color" class="color-picker-input" value="{{ $theme->secondary }}" name="secondary">
                                <input type="text" class="form-control color-text-input" value="{{ $theme->secondary }}" name="secondary_text" placeholder="#HEX">
                            </div>
                        </div>

                        <!-- Success -->
                        <div class="color-row d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="small font-weight-bold mb-1 text-slate-800">Success Color</h4>
                                <p class="text-muted mb-0 small" style="max-width: 320px;">Successful actions, completed order badges, checkmark confirmations, and active toggles.</p>
                            </div>
                            <div class="color-input-group">
                                <input type="color" class="color-picker-input" value="{{ $theme->success }}" name="success">
                                <input type="text" class="form-control color-text-input" value="{{ $theme->success }}" name="success_text" placeholder="#HEX">
                            </div>
                        </div>

                        <!-- Info -->
                        <div class="color-row d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="small font-weight-bold mb-1 text-slate-800">Information Color</h4>
                                <p class="text-muted mb-0 small" style="max-width: 320px;">System announcements, descriptive tooltips, analytics links, and neutral notices.</p>
                            </div>
                            <div class="color-input-group">
                                <input type="color" class="color-picker-input" value="{{ $theme->info }}" name="info">
                                <input type="text" class="form-control color-text-input" value="{{ $theme->info }}" name="info_text" placeholder="#HEX">
                            </div>
                        </div>

                        <!-- Warning -->
                        <div class="color-row d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="small font-weight-bold mb-1 text-slate-800">Warning Color</h4>
                                <p class="text-muted mb-0 small" style="max-width: 320px;">Pending transaction statuses, configuration notifications, and unsaved fields.</p>
                            </div>
                            <div class="color-input-group">
                                <input type="color" class="color-picker-input" value="{{ $theme->warning }}" name="warning">
                                <input type="text" class="form-control color-text-input" value="{{ $theme->warning }}" name="warning_text" placeholder="#HEX">
                            </div>
                        </div>

                        <!-- Danger -->
                        <div class="color-row d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="small font-weight-bold mb-1 text-slate-800">Danger Color</h4>
                                <p class="text-muted mb-0 small" style="max-width: 320px;">Error validation alerts, cancellation indicators, critical warnings, and delete operations.</p>
                            </div>
                            <div class="color-input-group">
                                <input type="color" class="color-picker-input" value="{{ $theme->danger }}" name="danger">
                                <input type="text" class="form-control color-text-input" value="{{ $theme->danger }}" name="danger_text" placeholder="#HEX">
                            </div>
                        </div>

                        <!-- Light -->
                        <div class="color-row d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="small font-weight-bold mb-1 text-slate-800">Light Background</h4>
                                <p class="text-muted mb-0 small" style="max-width: 320px;">Faint backgrounds, card backgrounds, empty list placeholders, and form container blocks.</p>
                            </div>
                            <div class="color-input-group">
                                <input type="color" class="color-picker-input" value="{{ $theme->light }}" name="light">
                                <input type="text" class="form-control color-text-input" value="{{ $theme->light }}" name="light_text" placeholder="#HEX">
                            </div>
                        </div>

                        <!-- Dark -->
                        <div class="color-row d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="small font-weight-bold mb-1 text-slate-800">Dark Accent</h4>
                                <p class="text-muted mb-0 small" style="max-width: 320px;">Primary dark headings, footer blocks, table borders, and high contrast typography.</p>
                            </div>
                            <div class="color-input-group">
                                <input type="color" class="color-picker-input" value="{{ $theme->dark }}" name="dark">
                                <input type="text" class="form-control color-text-input" value="{{ $theme->dark }}" name="dark_text" placeholder="#HEX">
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                            <button class="btn save-theme-btn px-4 py-2.5 d-flex align-items-center gap-2" type="submit">
                                <i class="fas fa-save"></i> Save Theme System
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Live Preview Mockup Column -->
                <div class="col-12 col-lg-5">
                    <div class="preview-card">
                        <div class="border-bottom border-dashed pb-3 mb-3">
                            <h3 class="h6 font-weight-bold text-slate-800 mb-1"><i class="fas fa-eye me-1.5"></i> Live Storefront Mockup</h3>
                            <p class="text-muted mb-0 small">Displays real-time component updates as you modify custom color palettes.</p>
                        </div>

                        <div class="mockup-container" id="theme-preview-card">
                            <!-- Mock Navigation -->
                            <div class="mock-header">
                                <div><i class="fas fa-store me-1.5"></i> Storefront Logo</div>
                                <div class="d-flex gap-2">
                                    <span class="small font-weight-medium">Home</span>
                                    <span class="small font-weight-medium">Catalog</span>
                                </div>
                            </div>

                            <!-- Mock Content -->
                            <div class="mock-body">
                                <h4 class="font-weight-bold text-slate-900 mb-1" style="font-size: 0.95rem;">Product Details</h4>
                                <p class="text-muted small mb-3">Below is a live preview showing how different colors will render dynamically across buttons, badges, notifications, and indicators.</p>
                                
                                <!-- Buttons -->
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <button class="mock-btn-primary" type="button">Primary Button</button>
                                    <button class="mock-btn-secondary" type="button">Secondary Button</button>
                                    <span class="mock-badge-success">Active Badge</span>
                                </div>

                                <!-- Alerts & Warnings -->
                                <div class="mock-alert-warning mb-3">
                                    <i class="fas fa-exclamation-triangle me-1.5 text-warning-glow"></i>
                                    <strong>Warning Notice:</strong> Changes here will re-compile styles.
                                </div>

                                <div class="mock-danger-box">
                                    <i class="fas fa-times-circle me-1.5"></i> Critical Error Indicator
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<!-- Real-time dynamic JS color sync -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputs = document.querySelectorAll('input[type="color"]');
        const textInputs = document.querySelectorAll('input[type="text"]');
        const preview = document.getElementById('theme-preview-card');

        function updatePreview() {
            inputs.forEach(input => {
                const name = input.name;
                const val = input.value;
                preview.style.setProperty('--theme-' + name, val);
                
                // Sync hex code inside text input
                const textInput = document.querySelector('input[name="' + name + '_text"]');
                if (textInput) {
                    textInput.value = val.toUpperCase();
                }
            });
        }

        inputs.forEach(input => {
            input.addEventListener('input', updatePreview);
        });

        textInputs.forEach(textInput => {
            textInput.addEventListener('input', function() {
                const name = this.name.replace('_text', '');
                const val = this.value;
                
                // If typed text is a valid hex color, sync it to the picker and preview
                if (/^#[0-9A-F]{6}$/i.test(val)) {
                    const colorInput = document.querySelector('input[name="' + name + '"]');
                    if (colorInput) {
                        colorInput.value = val;
                        updatePreview();
                    }
                }
            });
        });

        // Initialize preview colors on page load
        updatePreview();
    });
</script>
@endsection