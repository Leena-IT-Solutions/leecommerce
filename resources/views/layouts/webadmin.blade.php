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
                <p>Web-Admin</p>
            </div>

            <ul class="list-group mb-5 rounded-0 accordion p-1" id="accordionExample">
                

            <div class="accordion" id="accordionExample">

            
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <a href="/webadmin" class="btn accordion-button collapsed no-caret {{ (request()->is('webadmin')) ? 'active-link' : '' }}" data-bs-target="#collapseZero" aria-expanded="true" aria-controls="collapseZero">
                            <i class="fas fa-fw fa-tachometer-alt mr-2"></i>
                            Dashboard
                        </a>
                    </h2>
                </div>

                <!-- <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            <i class="fas fa-fw fa-tachometer-alt mr-2"></i>
                            Accordion Item #1
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <ul class="list-group list-group-flush bg-light d-grid p-0">
                                <li class="list-group-item" style="background: transparent !important;"><a class="btn btn-sm btn-default btn-full border-0" href="/course/3">Construction</a></li>
                                <li class="list-group-item" style="background: transparent !important;"><a class="btn btn-sm btn-default btn-full border-0" href="/course/1">English</a></li>
                                <li class="list-group-item" style="background: transparent !important;"><a class="btn btn-sm btn-default btn-full border-0" href="/course/2">Marathi</a></li>
                                <li class="list-group-item" style="background: transparent !important;"><a class="btn btn-sm btn-default btn-full border-0" href="/course/53">Information</a></li>
                            </ul>
                        </div>
                    </div>
                </div> -->
                
                <!-- <div class="accordion-item">
                    <h2 class="accordion-header" id="headingTwo">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            <i class="fas fa-fw fa-tachometer-alt mr-2"></i>
                            Accordion Item #2
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <ul class="list-group list-group-flush bg-light d-grid p-0">
                                <li class="list-group-item" style="background: transparent !important;"><a class="btn btn-sm btn-default btn-full border-0" href="/course/3">Construction</a></li>
                                <li class="list-group-item" style="background: transparent !important;"><a class="btn btn-sm btn-default btn-full border-0" href="/course/1">English</a></li>
                                <li class="list-group-item" style="background: transparent !important;"><a class="btn btn-sm btn-default btn-full border-0" href="/course/2">Marathi</a></li>
                                <li class="list-group-item" style="background: transparent !important;"><a class="btn btn-sm btn-default btn-full border-0" href="/course/53">Information</a></li>
                            </ul>
                        </div>
                    </div>
                </div> -->

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

</body>
</html>
