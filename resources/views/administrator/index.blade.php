@extends('layouts.administrator')

@section('head')
<title>Dashboard</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    .dashboard-page {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        color: #1e293b;
    }

    /* Welcome Hero Banner */
    .welcome-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
        border-radius: 16px;
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.1), 0 8px 10px -6px rgba(15, 23, 42, 0.05);
        position: relative;
        overflow: hidden;
    }
    .welcome-title {
        font-size: 1.85rem;
        font-weight: 800;
        letter-spacing: -0.02em;
        color: #ffffff;
    }
    .welcome-subtitle {
        font-size: 0.95rem;
        color: #94a3b8;
        max-width: 700px;
    }
    .welcome-banner .banner-glow {
        position: absolute;
        top: -50%;
        right: -10%;
        width: 350px;
        height: 350px;
        background: radial-gradient(circle, rgba(99, 102, 241, 0.15) 0%, rgba(99, 102, 241, 0) 70%);
        border-radius: 50%;
        pointer-events: none;
    }
    .banner-date {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #818cf8;
        display: inline-block;
    }

    /* Section Titles */
    .section-title-container {
        border-left: 4px solid #4f46e5;
        padding-left: 0.75rem;
        margin-bottom: 1.5rem;
    }
    .section-title {
        font-size: 1.1rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        color: #0f172a;
        margin: 0;
    }

    /* Dashboard Cards */
    .dashboard-card-link {
        text-decoration: none !important;
        display: block;
        height: 100%;
    }
    .dashboard-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.03), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .dashboard-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(15, 23, 42, 0.06), 0 10px 10px -5px rgba(15, 23, 42, 0.03);
        border-color: var(--card-hover-border, #cbd5e1);
    }
    .dashboard-card .card-main {
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.25rem;
    }
    .dashboard-card .card-icon {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        background: var(--icon-bg, #f1f5f9);
        color: var(--icon-color, #475569);
        transition: all 0.2s ease;
        flex-shrink: 0;
    }
    .dashboard-card:hover .card-icon {
        transform: scale(1.08);
    }
    .dashboard-card .card-info {
        display: flex;
        flex-direction: column;
    }
    .dashboard-card .card-title {
        font-size: 0.8rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .dashboard-card .card-value {
        font-size: 2rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
        margin-top: 0.15rem;
    }
    .dashboard-card .card-footer {
        background: #f8fafc;
        border-top: 1px solid #f1f5f9;
        padding: 0.75rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.78rem;
        font-weight: 600;
        color: #64748b;
        transition: all 0.2s ease;
    }
    .dashboard-card:hover .card-footer {
        background: var(--footer-hover-bg, #f1f5f9);
        color: var(--footer-hover-color, #0f172a);
    }

    /* Card Themes */
    .card-pending {
        --icon-bg: #fffbeb;
        --icon-color: #d97706;
        --card-hover-border: #fde68a;
        --footer-hover-bg: #fffbeb;
        --footer-hover-color: #b45309;
    }
    .card-accepted {
        --icon-bg: #e0e7ff;
        --icon-color: #4f46e5;
        --card-hover-border: #c7d2fe;
        --footer-hover-bg: #e0e7ff;
        --footer-hover-color: #3730a3;
    }
    .card-packed {
        --icon-bg: #e0f2fe;
        --icon-color: #0284c7;
        --card-hover-border: #bae6fd;
        --footer-hover-bg: #e0f2fe;
        --footer-hover-color: #0369a1;
    }
    .card-shipped {
        --icon-bg: #faf5ff;
        --icon-color: #9333ea;
        --card-hover-border: #f3e8ff;
        --footer-hover-bg: #faf5ff;
        --footer-hover-color: #6b21a8;
    }
    .card-delivered {
        --icon-bg: #ecfdf5;
        --icon-color: #059669;
        --card-hover-border: #a7f3d0;
        --footer-hover-bg: #ecfdf5;
        --footer-hover-color: #047857;
    }
    .card-customers {
        --icon-bg: #fff1f2;
        --icon-color: #e11d48;
        --card-hover-border: #fecdd3;
        --footer-hover-bg: #fff1f2;
        --footer-hover-color: #be123c;
    }
    .card-catalog {
        --icon-bg: #f8fafc;
        --icon-color: #475569;
        --card-hover-border: #e2e8f0;
        --footer-hover-bg: #f1f5f9;
        --footer-hover-color: #1e293b;
    }

    /* Quick Actions */
    .quick-action-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1.25rem;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 1rem;
        height: 100%;
        text-decoration: none !important;
    }
    .quick-action-card:hover {
        background: #f8fafc;
        border-color: #6366f1;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.06);
    }
    .quick-action-card .action-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: #e0e7ff;
        color: #4f46e5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
        transition: all 0.2s ease;
    }
    .quick-action-card:hover .action-icon {
        background: #4f46e5;
        color: #ffffff;
    }
    .quick-action-card .action-title {
        font-size: 0.9rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }
    .quick-action-card .action-desc {
        font-size: 0.75rem;
        color: #64748b;
        margin: 0.15rem 0 0 0;
    }
</style>
@endsection

@section('content')

<div class="container-fluid p-4 dashboard-page">

    <!-- Welcome Hero Banner -->
    <div class="welcome-banner mb-5 p-4 rounded-4 position-relative overflow-hidden">
        <div class="position-relative z-index-2">
            <span class="banner-date mb-2"><i class="far fa-calendar-alt me-1.5"></i> {{ date('F d, Y') }}</span>
            <h1 class="welcome-title mb-2">Welcome Back, {{ Auth::user()->name }}</h1>
            <p class="welcome-subtitle mb-0">Here is the current state of your e-commerce ecosystem. You currently have <span class="text-white font-weight-bold">{{ $dashboard["pending"] }} pending orders</span> waiting to be processed in the shipping queue.</p>
        </div>
        <div class="banner-glow"></div>
    </div>

    <!-- Section: Order Lifecycle Pipeline -->
    <div class="section-title-container">
        <h2 class="section-title">Order Lifecycle Pipeline</h2>
    </div>

    <div class="row g-4 mb-5">
        <!-- Pending Orders -->
        <div class="col-12 col-sm-6 col-xl-2.4 col-lg-3 col-md-4">
            <a class="dashboard-card-link" href="/administrator/orders/pending">
                <div class="dashboard-card card-pending">
                    <div class="card-main">
                        <div class="card-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="card-info">
                            <span class="card-title">Pending</span>
                            <span class="card-value">{{ $dashboard["pending"] }}</span>
                        </div>
                    </div>
                    <div class="card-footer">
                        <span>Process queue</span>
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>
            </a>
        </div>

        <!-- Accepted Orders -->
        <div class="col-12 col-sm-6 col-xl-2.4 col-lg-3 col-md-4">
            <a class="dashboard-card-link" href="/administrator/orders/accepted">
                <div class="dashboard-card card-accepted">
                    <div class="card-main">
                        <div class="card-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="card-info">
                            <span class="card-title">Accepted</span>
                            <span class="card-value">{{ $dashboard["accepted"] }}</span>
                        </div>
                    </div>
                    <div class="card-footer">
                        <span>Pack items</span>
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>
            </a>
        </div>

        <!-- Packed Orders -->
        <div class="col-12 col-sm-6 col-xl-2.4 col-lg-3 col-md-4">
            <a class="dashboard-card-link" href="/administrator/orders/packed">
                <div class="dashboard-card card-packed">
                    <div class="card-main">
                        <div class="card-icon">
                            <i class="fas fa-box"></i>
                        </div>
                        <div class="card-info">
                            <span class="card-title">Packed</span>
                            <span class="card-value">{{ $dashboard["packed"] }}</span>
                        </div>
                    </div>
                    <div class="card-footer">
                        <span>Ready to ship</span>
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>
            </a>
        </div>

        <!-- Shipped Orders -->
        <div class="col-12 col-sm-6 col-xl-2.4 col-lg-3 col-md-4">
            <a class="dashboard-card-link" href="/administrator/orders/shipped">
                <div class="dashboard-card card-shipped">
                    <div class="card-main">
                        <div class="card-icon">
                            <i class="fas fa-truck"></i>
                        </div>
                        <div class="card-info">
                            <span class="card-title">Shipped</span>
                            <span class="card-value">{{ $dashboard["shipped"] }}</span>
                        </div>
                    </div>
                    <div class="card-footer">
                        <span>Track transit</span>
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>
            </a>
        </div>

        <!-- Delivered Orders -->
        <div class="col-12 col-sm-6 col-xl-2.4 col-lg-3 col-md-4">
            <a class="dashboard-card-link" href="/administrator/orders/delivered">
                <div class="dashboard-card card-delivered">
                    <div class="card-main">
                        <div class="card-icon">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <div class="card-info">
                            <span class="card-title">Delivered</span>
                            <span class="card-value">{{ $dashboard["delivered"] }}</span>
                        </div>
                    </div>
                    <div class="card-footer">
                        <span>View records</span>
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Section: Catalog & Audience Summary -->
    <div class="section-title-container">
        <h2 class="section-title">Catalog & Audience Summary</h2>
    </div>

    <div class="row g-4 mb-5">
        <!-- Customers -->
        <div class="col-12 col-sm-6 col-lg-3">
            <a class="dashboard-card-link" href="/administrator/user_manager">
                <div class="dashboard-card card-customers">
                    <div class="card-main">
                        <div class="card-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="card-info">
                            <span class="card-title">Customers</span>
                            <span class="card-value">{{ $dashboard["customers"] }}</span>
                        </div>
                    </div>
                    <div class="card-footer">
                        <span>Manage accounts</span>
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>
            </a>
        </div>

        <!-- Categories -->
        <div class="col-12 col-sm-6 col-lg-3">
            <a class="dashboard-card-link" href="/administrator/products/category">
                <div class="dashboard-card card-catalog">
                    <div class="card-main">
                        <div class="card-icon">
                            <i class="fas fa-tags"></i>
                        </div>
                        <div class="card-info">
                            <span class="card-title">Categories</span>
                            <span class="card-value">{{ $dashboard["categories"] }}</span>
                        </div>
                    </div>
                    <div class="card-footer">
                        <span>Manage structure</span>
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>
            </a>
        </div>

        <!-- Sub Categories -->
        <div class="col-12 col-sm-6 col-lg-3">
            <a class="dashboard-card-link" href="/administrator/products/sub_category">
                <div class="dashboard-card card-catalog">
                    <div class="card-main">
                        <div class="card-icon">
                            <i class="fas fa-tag"></i>
                        </div>
                        <div class="card-info">
                            <span class="card-title">Sub Categories</span>
                            <span class="card-value">{{ $dashboard["sub_categories"] }}</span>
                        </div>
                    </div>
                    <div class="card-footer">
                        <span>Manage mapping</span>
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>
            </a>
        </div>

        <!-- Products -->
        <div class="col-12 col-sm-6 col-lg-3">
            <a class="dashboard-card-link" href="/administrator/products/product_group">
                <div class="dashboard-card card-catalog">
                    <div class="card-main">
                        <div class="card-icon">
                            <i class="fas fa-boxes"></i>
                        </div>
                        <div class="card-info">
                            <span class="card-title">Products</span>
                            <span class="card-value">{{ $dashboard["products"] }}</span>
                        </div>
                    </div>
                    <div class="card-footer">
                        <span>Manage inventory</span>
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Section: Quick Actions -->
    <div class="section-title-container">
        <h2 class="section-title">Quick Administration Actions</h2>
    </div>

    <div class="row g-4 mb-4">
        <!-- Theme Manager -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2.4">
            <a class="quick-action-card" href="/administrator/theme">
                <div class="action-icon">
                    <i class="fas fa-palette"></i>
                </div>
                <div>
                    <h3 class="action-title">Theme Manager</h3>
                    <p class="action-desc">Branding colors & styles</p>
                </div>
            </a>
        </div>

        <!-- System Settings -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2.4">
            <a class="quick-action-card" href="/administrator/settings">
                <div class="action-icon">
                    <i class="fas fa-cog"></i>
                </div>
                <div>
                    <h3 class="action-title">System Settings</h3>
                    <p class="action-desc">App name, logo & timing</p>
                </div>
            </a>
        </div>

        <!-- Manage Offers -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2.4">
            <a class="quick-action-card" href="/administrator/offers">
                <div class="action-icon">
                    <i class="fas fa-gift"></i>
                </div>
                <div>
                    <h3 class="action-title">Offers & Coupons</h3>
                    <p class="action-desc">Manage promotional discounts</p>
                </div>
            </a>
        </div>

        <!-- Purchase Manager -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2.4">
            <a class="quick-action-card" href="/administrator/purchase">
                <div class="action-icon">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <div>
                    <h3 class="action-title">Purchase Ledger</h3>
                    <p class="action-desc">Supplier rates & quantity logs</p>
                </div>
            </a>
        </div>

        <!-- Sales Analytics -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2.4">
            <a class="quick-action-card" href="/administrator/analytics/sale">
                <div class="action-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div>
                    <h3 class="action-title">Sales Analytics</h3>
                    <p class="action-desc">Revenue reports & trends</p>
                </div>
            </a>
        </div>
    </div>

</div>

@endsection
