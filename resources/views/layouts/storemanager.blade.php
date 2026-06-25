<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('head')

    <!-- Scripts & Styles -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
<div id="app">

<div id="menu" class="menu">
    <div class="menu-container">
        <div class="menu-content">
            <div class="menu-header">
                <p>Store Manager</p>
            </div>

            <ul class="list-group mb-5 rounded-0 accordion p-1" id="accordionExample">
            

            <div class="accordion" id="accordionExample">

                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <a href="/administrator" class="btn accordion-button collapsed no-caret {{ (request()->is('administrator')) ? 'active-link' : '' }}" data-bs-target="#collapseZero" aria-expanded="true" aria-controls="collapseZero">
                            <i class="fas fa-fw fa-tachometer-alt mr-2"></i>
                            Dashboard
                        </a>
                    </h2>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOrderssManager">
                        <button class="accordion-button {{ (request()->is('administrator/orders*')) ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOrderssManager" aria-expanded="true" aria-controls="collapseOrderssManager">
                            <i class="fas fa-fw fa-shopping-cart mr-2"></i>
                            Order Manager
                        </button>
                    </h2>
                    <div id="collapseOrderssManager" class="accordion-collapse collapse {{ (request()->is('administrator/orders*')) ? 'show' : '' }}" aria-labelledby="headingOrderssManager" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <ul class="list-group list-group-flush d-grid p-0">
                                <li class="list-group-item"><a class="btn btn-sm btn-default btn-full border-0 {{ (request()->is('administrator/orders/pending')) ? 'fw-bold' : '' }}" href="/administrator/orders/pending">Pending</a></li>
                                <li class="list-group-item"><a class="btn btn-sm btn-default btn-full border-0 {{ (request()->is('administrator/orders/accepted')) ? 'fw-bold' : '' }}" href="/administrator/orders/accepted">Accepted</a></li>
                                <li class="list-group-item"><a class="btn btn-sm btn-default btn-full border-0 {{ (request()->is('administrator/orders/packed')) ? 'fw-bold' : '' }}" href="/administrator/orders/packed">Packed</a></li>
                                <li class="list-group-item"><a class="btn btn-sm btn-default btn-full border-0 {{ (request()->is('administrator/orders/shipped')) ? 'fw-bold' : '' }}" href="/administrator/orders/shipped">Shipped</a></li>
                                <li class="list-group-item"><a class="btn btn-sm btn-default btn-full border-0 {{ (request()->is('administrator/orders/delivered')) ? 'fw-bold' : '' }}" href="/administrator/orders/delivered">Delivered</a></li>
                                <li class="list-group-item"><a class="btn btn-sm btn-default btn-full border-0 {{ (request()->is('administrator/orders/cancelled')) ? 'fw-bold' : '' }}" href="/administrator/orders/cancelled">Cancelled & Pending</a></li>
                                <li class="list-group-item"><a class="btn btn-sm btn-default btn-full border-0 {{ (request()->is('administrator/orders/search')) ? 'fw-bold' : '' }}" href="/administrator/orders/search">Search Order</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>

        </ul>
        </div> <!-- End of menu-content -->

        <div class="menu-footer">
            <a href="{{ route('logout') }}" 
               onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();"
               class="btn btn-logout"
            >
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
            <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>

    </div>
</div>

<div id="panel" class="panel">
    <nav class="navbar-custom">
        <!-- Left: Sidebar Toggle Button -->
        <a class="sidebar-toggle-btn" id="handel" href="javascript:void(0);">
            <i class="fas fa-bars"></i>
        </a>
        
        <!-- Right: User Profile Badge -->
        <div class="user-profile">
            <div class="avatar">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <span class="username d-none d-sm-inline">{{ Auth::user()->name }}</span>
        </div>
    </nav>

    @yield('content')

</div>



</div>

@include('sweetalert::alert')


@yield('scripts')

</body>
</html>
