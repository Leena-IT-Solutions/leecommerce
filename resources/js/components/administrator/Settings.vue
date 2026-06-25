<template>
<div class="settings-page container-fluid p-0">
    <!-- Page Header (Static at top) -->
    <div class="px-4 pt-4 pb-3 border-bottom bg-white">
        <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-2">
            <div>
                <h1 class="h3 font-weight-bold text-slate-800 mb-1">System Settings</h1>
                <p class="text-muted mb-0 small">Manage your store branding, policies, logistics, integrations, and communications in one place.</p>
            </div>
            <div class="header-badge bg-indigo-50 text-indigo-700 px-3 py-1.5 rounded-pill font-weight-semibold small d-flex align-items-center gap-1.5 border border-indigo-100">
                <i class="fas fa-check-circle text-indigo-500"></i> Auto-saves changes
            </div>
        </div>
    </div>

    <!-- Quick-Jump Navigation Bar (Sticky glassmorphism header) -->
    <div class="sticky-top category-nav bg-white shadow-sm border-bottom py-2">
        <div class="d-flex align-items-center gap-2 overflow-auto px-4 py-1 no-scrollbar">
            <button 
                v-for="cat in categories" 
                :key="cat.id" 
                @click="scrollToCategory(cat.id)"
                class="btn btn-sm nav-link-btn rounded-pill font-weight-semibold px-3 py-2 border-0"
                type="button"
            >
                <i :class="[cat.icon, 'me-2']"></i>
                {{ cat.name }}
            </button>
        </div>
    </div>

    <!-- Scrollable Settings Stack -->
    <div class="px-4 py-4 settings-stack-container">
        
        <!-- General & Branding Section -->
        <div id="general" class="card border-0 shadow-sm rounded-lg p-4 mb-4 settings-card">
            <div class="section-header border-bottom pb-3 mb-4 d-flex align-items-center">
                <div class="icon-box bg-indigo-50 text-indigo-600 rounded p-2.5 me-3">
                    <i class="fas fa-store fa-lg"></i>
                </div>
                <div>
                    <h2 class="h5 font-weight-bold text-slate-800 mb-1">General & Branding</h2>
                    <p class="text-muted mb-0 small">Configure your storefront identity, logo, and theme styling.</p>
                </div>
            </div>
            
            <div class="setting-item d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between py-4">
                <div class="setting-text me-md-4 mb-3 mb-md-0">
                    <h4 class="setting-label font-weight-semibold text-slate-800 mb-1">Application Name</h4>
                    <p class="setting-description text-muted mb-0 small">The name of your e-commerce storefront displayed in titles, emails, and invoices.</p>
                </div>
                <div class="setting-input flex-shrink-0">
                    <toggle-switch :datawhat="'text'" :dataoptions="[]" :datavalue="'LeeCommerce'" :datakey="'App Name'"></toggle-switch>
                </div>
            </div>

            <div class="setting-item d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between py-4">
                <div class="setting-text me-md-4 mb-3 mb-md-0">
                    <h4 class="setting-label font-weight-semibold text-slate-800 mb-1">Show App Name</h4>
                    <p class="setting-description text-muted mb-0 small">Toggle whether the application name text should be displayed in the website header.</p>
                </div>
                <div class="setting-input flex-shrink-0">
                    <toggle-switch :datawhat="'toggle'" :dataoptions="['Enable','Disable']" :datavalue="'Enable'" :datakey="'Show App Name'"></toggle-switch>
                </div>
            </div>

            <div class="setting-item d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between py-4">
                <div class="setting-text me-md-4 mb-3 mb-md-0">
                    <h4 class="setting-label font-weight-semibold text-slate-800 mb-1">Tag Line</h4>
                    <p class="setting-description text-muted mb-0 small">A brief slogan or descriptive phrase that highlights your store's focus.</p>
                </div>
                <div class="setting-input flex-shrink-0">
                    <toggle-switch :datawhat="'text'" :dataoptions="[]" :datavalue="'Tag Line'" :datakey="'Tag Line'"></toggle-switch>
                </div>
            </div>

            <div class="setting-item d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between py-4">
                <div class="setting-text me-md-4 mb-3 mb-md-0">
                    <h4 class="setting-label font-weight-semibold text-slate-800 mb-1">Show Tag Line</h4>
                    <p class="setting-description text-muted mb-0 small">Toggle whether to display the tag line text alongside or under your branding.</p>
                </div>
                <div class="setting-input flex-shrink-0">
                    <toggle-switch :datawhat="'toggle'" :dataoptions="['Enable','Disable']" :datavalue="'Disable'" :datakey="'Show Tag Line'"></toggle-switch>
                </div>
            </div>

            <div class="setting-item d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between py-4">
                <div class="setting-text me-md-4 mb-3 mb-md-0">
                    <h4 class="setting-label font-weight-semibold text-slate-800 mb-1">Logo</h4>
                    <p class="setting-description text-muted mb-0 small">Upload your store's primary logo image file (PNG, JPG, or SVG formats recommended).</p>
                </div>
                <div class="setting-input flex-shrink-0">
                    <toggle-switch :datawhat="'image'" :dataoptions="[]" :datavalue="''" :datakey="'Logo'"></toggle-switch>
                </div>
            </div>

            <div class="setting-item d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between py-4">
                <div class="setting-text me-md-4 mb-3 mb-md-0">
                    <h4 class="setting-label font-weight-semibold text-slate-800 mb-1">Show Logo</h4>
                    <p class="setting-description text-muted mb-0 small">Toggle whether the uploaded logo image is displayed in the storefront navigation bar.</p>
                </div>
                <div class="setting-input flex-shrink-0">
                    <toggle-switch :datawhat="'toggle'" :dataoptions="['Enable','Disable']" :datavalue="'Disable'" :datakey="'Show Logo'"></toggle-switch>
                </div>
            </div>

            <div class="setting-item d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between py-4">
                <div class="setting-text me-md-4 mb-3 mb-md-0">
                    <h4 class="setting-label font-weight-semibold text-slate-800 mb-1">Theme Color</h4>
                    <p class="setting-description text-muted mb-0 small">Define the primary theme color (HEX code format, e.g., #4f46e5) used on the storefront UI.</p>
                </div>
                <div class="setting-input flex-shrink-0">
                    <toggle-switch :datawhat="'text'" :dataoptions="[]" :datavalue="'#000000'" :datakey="'Theme Color'"></toggle-switch>
                </div>
            </div>
        </div>

        <!-- Store Policies & Limits Section -->
        <div id="policies" class="card border-0 shadow-sm rounded-lg p-4 mb-4 settings-card">
            <div class="section-header border-bottom pb-3 mb-4 d-flex align-items-center">
                <div class="icon-box bg-emerald-50 text-emerald-600 rounded p-2.5 me-3">
                    <i class="fas fa-shield-alt fa-lg"></i>
                </div>
                <div>
                    <h2 class="h5 font-weight-bold text-slate-800 mb-1">Store Policies & Limits</h2>
                    <p class="text-muted mb-0 small">Define customer purchasing limits, minimum thresholds, and order transaction policies.</p>
                </div>
            </div>

            <div class="setting-item d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between py-4">
                <div class="setting-text me-md-4 mb-3 mb-md-0">
                    <h4 class="setting-label font-weight-semibold text-slate-800 mb-1">Max Product Quantity to Buy</h4>
                    <p class="setting-description text-muted mb-0 small">The maximum quantity of a single product a customer is allowed to purchase in one order.</p>
                </div>
                <div class="setting-input flex-shrink-0">
                    <toggle-switch :datawhat="'text'" :dataoptions="[]" :datavalue="'10'" :datakey="'Buy Quantity'"></toggle-switch>
                </div>
            </div>

            <div class="setting-item d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between py-4">
                <div class="setting-text me-md-4 mb-3 mb-md-0">
                    <h4 class="setting-label font-weight-semibold text-slate-800 mb-1">Minimum Order Amount</h4>
                    <p class="setting-description text-muted mb-0 small">The minimum subtotal value required in the cart before a customer can proceed to checkout.</p>
                </div>
                <div class="setting-input flex-shrink-0">
                    <toggle-switch :datawhat="'text'" :dataoptions="[]" :datavalue="'100'" :datakey="'Minimum Order Amount'"></toggle-switch>
                </div>
            </div>

            <div class="setting-item d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between py-4">
                <div class="setting-text me-md-4 mb-3 mb-md-0">
                    <h4 class="setting-label font-weight-semibold text-slate-800 mb-1">Enable Order Cancellation</h4>
                    <p class="setting-description text-muted mb-0 small">Allow customers to cancel their placed orders directly from their dashboards before processing.</p>
                </div>
                <div class="setting-input flex-shrink-0">
                    <toggle-switch :datawhat="'toggle'" :dataoptions="['Enable','Disable']" :datavalue="'Disable'" :datakey="'Order Cancellation'"></toggle-switch>
                </div>
            </div>

            <div class="setting-item d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between py-4">
                <div class="setting-text me-md-4 mb-3 mb-md-0">
                    <h4 class="setting-label font-weight-semibold text-slate-800 mb-1">Enable Order Replacement</h4>
                    <p class="setting-description text-muted mb-0 small">Allow customers to request replacements for eligible products within the replacement window.</p>
                </div>
                <div class="setting-input flex-shrink-0">
                    <toggle-switch :datawhat="'toggle'" :dataoptions="['Enable','Disable']" :datavalue="'Disable'" :datakey="'Order Replacement'"></toggle-switch>
                </div>
            </div>

            <div class="setting-item d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between py-4">
                <div class="setting-text me-md-4 mb-3 mb-md-0">
                    <h4 class="setting-label font-weight-semibold text-slate-800 mb-1">Replacement Hours</h4>
                    <p class="setting-description text-muted mb-0 small">The time window (in hours) after order delivery during which a replacement request can be filed.</p>
                </div>
                <div class="setting-input flex-shrink-0">
                    <toggle-switch :datawhat="'text'" :dataoptions="[]" :datavalue="'1'" :datakey="'Replacement Hours'"></toggle-switch>
                </div>
            </div>

            <div class="setting-item d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between py-4">
                <div class="setting-text me-md-4 mb-3 mb-md-0">
                    <h4 class="setting-label font-weight-semibold text-slate-800 mb-1">Return Hours</h4>
                    <p class="setting-description text-muted mb-0 small">The time window (in hours) after order delivery during which a full return request can be submitted.</p>
                </div>
                <div class="setting-input flex-shrink-0">
                    <toggle-switch :datawhat="'text'" :dataoptions="[]" :datavalue="'1'" :datakey="'Return Hours'"></toggle-switch>
                </div>
            </div>
        </div>

        <!-- Logistics & Delivery Section -->
        <div id="logistics" class="card border-0 shadow-sm rounded-lg p-4 mb-4 settings-card">
            <div class="section-header border-bottom pb-3 mb-4 d-flex align-items-center">
                <div class="icon-box bg-amber-50 text-amber-600 rounded p-2.5 me-3">
                    <i class="fas fa-shipping-fast fa-lg"></i>
                </div>
                <div>
                    <h2 class="h5 font-weight-bold text-slate-800 mb-1">Logistics & Delivery</h2>
                    <p class="text-muted mb-0 small">Configure delivery locations, standard timings, charges, and free shipment thresholds.</p>
                </div>
            </div>

            <div class="setting-item d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between py-4">
                <div class="setting-text me-md-4 mb-3 mb-md-0">
                    <h4 class="setting-label font-weight-semibold text-slate-800 mb-1">Business Address</h4>
                    <p class="setting-description text-muted mb-0 small">Your store's physical operations address displayed on checkout pages, invoices, and receipts.</p>
                </div>
                <div class="setting-input flex-shrink-0">
                    <toggle-switch :datawhat="'text'" :dataoptions="[]" :datavalue="'Address'" :datakey="'Address'"></toggle-switch>
                </div>
            </div>

            <div class="setting-item d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between py-4">
                <div class="setting-text me-md-4 mb-3 mb-md-0">
                    <h4 class="setting-label font-weight-semibold text-slate-800 mb-1">Delivery Timing</h4>
                    <p class="setting-description text-muted mb-0 small">The standard estimated delivery timeframe shown to customers during shipping selection.</p>
                </div>
                <div class="setting-input flex-shrink-0">
                    <toggle-switch :datawhat="'text'" :dataoptions="[]" :datavalue="'5 to 7 Days'" :datakey="'Delivery Timing'"></toggle-switch>
                </div>
            </div>

            <div class="setting-item d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between py-4">
                <div class="setting-text me-md-4 mb-3 mb-md-0">
                    <h4 class="setting-label font-weight-semibold text-slate-800 mb-1">Standard Delivery Charges</h4>
                    <p class="setting-description text-muted mb-0 small">The default flat-rate shipping charge applied to orders below the free delivery threshold.</p>
                </div>
                <div class="setting-input flex-shrink-0">
                    <toggle-switch :datawhat="'text'" :dataoptions="[]" :datavalue="'100'" :datakey="'Delivery Charges'"></toggle-switch>
                </div>
            </div>

            <div class="setting-item d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between py-4">
                <div class="setting-text me-md-4 mb-3 mb-md-0">
                    <h4 class="setting-label font-weight-semibold text-slate-800 mb-1">Free Delivery Amount</h4>
                    <p class="setting-description text-muted mb-0 small">The minimum order subtotal value required to waive the standard delivery charges entirely.</p>
                </div>
                <div class="setting-input flex-shrink-0">
                    <toggle-switch :datawhat="'text'" :dataoptions="[]" :datavalue="'500'" :datakey="'Free Delivery Amount'"></toggle-switch>
                </div>
            </div>
        </div>

        <!-- Payments & Integrations Section -->
        <div id="payments" class="card border-0 shadow-sm rounded-lg p-4 mb-4 settings-card">
            <div class="section-header border-bottom pb-3 mb-4 d-flex align-items-center">
                <div class="icon-box bg-info-50 text-info-600 rounded p-2.5 me-3">
                    <i class="fas fa-credit-card fa-lg"></i>
                </div>
                <div>
                    <h2 class="h5 font-weight-bold text-slate-800 mb-1">Payments & Integrations</h2>
                    <p class="text-muted mb-0 small">Enable merchant gateways, offline payment methods, and configure external API keys.</p>
                </div>
            </div>

            <div class="setting-item d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between py-4">
                <div class="setting-text me-md-4 mb-3 mb-md-0">
                    <h4 class="setting-label font-weight-semibold text-slate-800 mb-1">Enable Cash on Delivery (COD)</h4>
                    <p class="setting-description text-muted mb-0 small">Toggle whether customers can complete purchases using physical currency upon package delivery.</p>
                </div>
                <div class="setting-input flex-shrink-0">
                    <toggle-switch :datawhat="'toggle'" :dataoptions="['Enable','Disable']" :datavalue="'Disable'" :datakey="'COD'"></toggle-switch>
                </div>
            </div>

            <div class="setting-item d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between py-4">
                <div class="setting-text me-md-4 mb-3 mb-md-0">
                    <h4 class="setting-label font-weight-semibold text-slate-800 mb-1">Enable Online Payment</h4>
                    <p class="setting-description text-muted mb-0 small">Toggle whether customers can make instant digital payments using integrated secure online gateways.</p>
                </div>
                <div class="setting-input flex-shrink-0">
                    <toggle-switch :datawhat="'toggle'" :dataoptions="['Enable','Disable']" :datavalue="'Disable'" :datakey="'Online payment'"></toggle-switch>
                </div>
            </div>

            <div class="setting-item d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between py-4">
                <div class="setting-text me-md-4 mb-3 mb-md-0">
                    <h4 class="setting-label font-weight-semibold text-slate-800 mb-1">Razorpay API Key</h4>
                    <p class="setting-description text-muted mb-0 small">The public key provided by Razorpay, used to initialize the digital checkout modal on the client side.</p>
                </div>
                <div class="setting-input flex-shrink-0">
                    <toggle-switch :datawhat="'text'" :dataoptions="[]" :datavalue="'000'" :datakey="'Razorpay API Key'"></toggle-switch>
                </div>
            </div>

            <div class="setting-item d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between py-4">
                <div class="setting-text me-md-4 mb-3 mb-md-0">
                    <h4 class="setting-label font-weight-semibold text-slate-800 mb-1">Razorpay API Secret</h4>
                    <p class="setting-description text-muted mb-0 small">The private secret token provided by Razorpay, used for secure server-to-server transaction verification.</p>
                </div>
                <div class="setting-input flex-shrink-0">
                    <toggle-switch :datawhat="'text'" :dataoptions="[]" :datavalue="'000'" :datakey="'Razorpay API Secret'"></toggle-switch>
                </div>
            </div>

            <div class="setting-item d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between py-4">
                <div class="setting-text me-md-4 mb-3 mb-md-0">
                    <h4 class="setting-label font-weight-semibold text-slate-800 mb-1">Google Maps API Key</h4>
                    <p class="setting-description text-muted mb-0 small">API Key used to load maps, auto-complete shipping addresses, and calculate precise delivery distances.</p>
                </div>
                <div class="setting-input flex-shrink-0">
                    <toggle-switch :datawhat="'text'" :dataoptions="[]" :datavalue="'000'" :datakey="'Google Map API Key'"></toggle-switch>
                </div>
            </div>
        </div>

        <!-- Contact & Social Links Section -->
        <div id="contact" class="card border-0 shadow-sm rounded-lg p-4 mb-4 settings-card">
            <div class="section-header border-bottom pb-3 mb-4 d-flex align-items-center">
                <div class="icon-box bg-purple-50 text-purple-600 rounded p-2.5 me-3">
                    <i class="fas fa-share-alt fa-lg"></i>
                </div>
                <div>
                    <h2 class="h5 font-weight-bold text-slate-800 mb-1">Contact & Social Links</h2>
                    <p class="text-muted mb-0 small">Manage support communication channels and direct links to your official social media profiles.</p>
                </div>
            </div>

            <div class="setting-item d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between py-4">
                <div class="setting-text me-md-4 mb-3 mb-md-0">
                    <h4 class="setting-label font-weight-semibold text-slate-800 mb-1">Support Email Address</h4>
                    <p class="setting-description text-muted mb-0 small">The primary customer support email address listed on contact pages, headers, and transactional emails.</p>
                </div>
                <div class="setting-input flex-shrink-0">
                    <toggle-switch :datawhat="'text'" :dataoptions="[]" :datavalue="'support@example.com'" :datakey="'Support Email'"></toggle-switch>
                </div>
            </div>

            <div class="setting-item d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between py-4">
                <div class="setting-text me-md-4 mb-3 mb-md-0">
                    <h4 class="setting-label font-weight-semibold text-slate-800 mb-1">Support Mobile Number</h4>
                    <p class="setting-description text-muted mb-0 small">The telephone hotline number displayed on the storefront for direct customer support inquiries.</p>
                </div>
                <div class="setting-input flex-shrink-0">
                    <toggle-switch :datawhat="'text'" :dataoptions="[]" :datavalue="'0000000000'" :datakey="'Support Mobile'"></toggle-switch>
                </div>
            </div>

            <div class="setting-item d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between py-4">
                <div class="setting-text me-md-4 mb-3 mb-md-0">
                    <h4 class="setting-label font-weight-semibold text-slate-800 mb-1">WhatsApp Mobile Number</h4>
                    <p class="setting-description text-muted mb-0 small">The WhatsApp-enabled phone number used to power direct click-to-chat support widgets on the storefront.</p>
                </div>
                <div class="setting-input flex-shrink-0">
                    <toggle-switch :datawhat="'text'" :dataoptions="[]" :datavalue="'0000000000'" :datakey="'WhatsApp'"></toggle-switch>
                </div>
            </div>

            <div class="setting-item d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between py-4">
                <div class="setting-text me-md-4 mb-3 mb-md-0">
                    <h4 class="setting-label font-weight-semibold text-slate-800 mb-1">Facebook Page URL</h4>
                    <p class="setting-description text-muted mb-0 small">Complete URL path to your official Facebook page, linked in the storefront footer and contact sections.</p>
                </div>
                <div class="setting-input flex-shrink-0">
                    <toggle-switch :datawhat="'text'" :dataoptions="[]" :datavalue="'https://facebook.com'" :datakey="'Facebook'"></toggle-switch>
                </div>
            </div>

            <div class="setting-item d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between py-4">
                <div class="setting-text me-md-4 mb-3 mb-md-0">
                    <h4 class="setting-label font-weight-semibold text-slate-800 mb-1">Instagram Profile URL</h4>
                    <p class="setting-description text-muted mb-0 small">Complete URL path to your official Instagram business profile, linked in the storefront footer and socials.</p>
                </div>
                <div class="setting-input flex-shrink-0">
                    <toggle-switch :datawhat="'text'" :dataoptions="[]" :datavalue="'https://instagram.com'" :datakey="'Instagram'"></toggle-switch>
                </div>
            </div>

            <div class="setting-item d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between py-4">
                <div class="setting-text me-md-4 mb-3 mb-md-0">
                    <h4 class="setting-label font-weight-semibold text-slate-800 mb-1">YouTube Channel URL</h4>
                    <p class="setting-description text-muted mb-0 small">Complete URL path to your official YouTube branding channel, linked in footer icons and promotional areas.</p>
                </div>
                <div class="setting-input flex-shrink-0">
                    <toggle-switch :datawhat="'text'" :dataoptions="[]" :datavalue="'https://youtube.com'" :datakey="'Youtube'"></toggle-switch>
                </div>
            </div>
        </div>

        <!-- Welcome Content Section -->
        <div id="welcome" class="card border-0 shadow-sm rounded-lg p-4 mb-4 settings-card">
            <div class="section-header border-bottom pb-3 mb-4 d-flex align-items-center">
                <div class="icon-box bg-rose-50 text-rose-600 rounded p-2.5 me-3">
                    <i class="fas fa-door-open fa-lg"></i>
                </div>
                <div>
                    <h2 class="h5 font-weight-bold text-slate-800 mb-1">Welcome Content</h2>
                    <p class="text-muted mb-0 small">Customize introductory text blocks, marketing subtitles, and call-to-actions shown to new visitors.</p>
                </div>
            </div>

            <div class="setting-item d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between py-4">
                <div class="setting-text me-md-4 mb-3 mb-md-0">
                    <h4 class="setting-label font-weight-semibold text-slate-800 mb-1">Welcome Title</h4>
                    <p class="setting-description text-muted mb-0 small">The main bold heading title displayed inside the hero banner or welcome section of the home page.</p>
                </div>
                <div class="setting-input flex-shrink-0">
                    <toggle-switch :datawhat="'text'" :dataoptions="[]" :datavalue="'Welcome'" :datakey="'Welcome Title'"></toggle-switch>
                </div>
            </div>

            <div class="setting-item d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between py-4">
                <div class="setting-text me-md-4 mb-3 mb-md-0">
                    <h4 class="setting-label font-weight-semibold text-slate-800 mb-1">Welcome Call to Action</h4>
                    <p class="setting-description text-muted mb-0 small">A short, persuasive sub-heading or tagline that encourages visitors to explore your products.</p>
                </div>
                <div class="setting-input flex-shrink-0">
                    <toggle-switch :datawhat="'text'" :dataoptions="[]" :datavalue="'Welcome Call'" :datakey="'Welcome Call'"></toggle-switch>
                </div>
            </div>

            <div class="setting-item d-flex flex-column align-items-start justify-content-between py-4">
                <div class="setting-text mb-3">
                    <h4 class="setting-label font-weight-semibold text-slate-800 mb-1">Welcome Note (Detailed Description)</h4>
                    <p class="setting-description text-muted mb-0 small">A comprehensive introductory description or welcome statement displayed to visitors when they land on your store.</p>
                </div>
                <div class="setting-input w-100 mt-2">
                    <toggle-switch :datawhat="'textarea'" :dataoptions="[]" :datavalue="'Welcome Note'" :datakey="'Welcome Note'"></toggle-switch>
                </div>
            </div>
        </div>

    </div>
</div>
</template>

<style scoped>
.settings-page {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    color: #1e293b; /* Slate 800 */
}

/* Category Navigation (Sticky glassmorphism header) */
.category-nav {
    top: 0;
    z-index: 1020;
    background: rgba(255, 255, 255, 0.9) !important;
    backdrop-filter: blur(12px) saturate(180%);
    -webkit-backdrop-filter: blur(12px) saturate(180%);
    border-bottom: 1px solid rgba(226, 232, 240, 0.8) !important;
}

/* Glassmorphism nav buttons styling */
.nav-link-btn {
    color: #64748b; /* Slate 500 */
    background: transparent;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    white-space: nowrap;
    font-size: 0.85rem;
    border: 1px solid transparent !important;
    
    &:hover {
        color: #4f46e5; /* Indigo 600 */
        background: rgba(79, 70, 229, 0.05);
        border-color: rgba(79, 70, 229, 0.1) !important;
    }
    
    i {
        font-size: 0.95rem;
    }
}

/* Hide scrollbar utility */
.no-scrollbar {
    scrollbar-width: none; /* Firefox */
    &::-webkit-scrollbar {
        display: none; /* Chrome, Safari, Opera */
    }
}

/* Settings Cards Styling */
.settings-card {
    background: #ffffff;
    border: 1px solid rgba(226, 232, 240, 0.8); /* Slate 200 */
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03) !important;
    scroll-margin-top: 72px; /* Anchor offset for sticky header */
}

/* Icons box accent styling */
.icon-box {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 42px;
    height: 42px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.02);
}

/* Individual setting rows */
.setting-item {
    border-bottom: 1px solid #f1f5f9 !important; /* Slate 100 */
    
    &:last-child {
        border-bottom: none !important;
    }
    
    .setting-label {
        font-size: 0.95rem;
        color: #1e293b;
    }
    
    .setting-description {
        font-size: 0.85rem;
        color: #64748b;
        max-width: 580px;
        line-height: 1.4;
    }
}

/* Header badge auto-save style */
.header-badge {
    font-size: 0.8rem;
    letter-spacing: 0.02em;
}
</style>

<script>
import ToggleSwitch from '../elements/ToggleSwitch.vue'

export default {
    props: [],

    components: {
        ToggleSwitch,
    },

    data: function () {
        return {
            categories: [
                { id: 'general', name: 'General & Branding', icon: 'fas fa-store' },
                { id: 'policies', name: 'Store Policies & Limits', icon: 'fas fa-shield-alt' },
                { id: 'logistics', name: 'Logistics & Delivery', icon: 'fas fa-shipping-fast' },
                { id: 'payments', name: 'Payments & Integrations', icon: 'fas fa-credit-card' },
                { id: 'contact', name: 'Contact & Social Links', icon: 'fas fa-share-alt' },
                { id: 'welcome', name: 'Welcome Content', icon: 'fas fa-door-open' }
            ],
            key: "Value",
        }
    },

    methods: {
        scrollToCategory(id) {
            const element = document.getElementById(id);
            if (element) {
                element.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        },
        change(e) {
            /* console.log(e.target.value); */
        }
    },

    created() {
    },

    mounted() {
    }
}
</script>
