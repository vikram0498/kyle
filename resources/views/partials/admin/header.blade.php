<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
    <a class="navbar-brand brand-logo" href="javascript:void(0)"><img src="{{ asset(config('constants.default.admin_logo')) }}" class="mr-2" alt="logo"/></a>
    <a class="navbar-brand brand-logo-mini" href="javascript:void(0)"><img src="{{asset('images/logo-mini.svg')}}" alt="logo"/></a>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
    <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
        <span class="icon-menu"></span>
    </button>
    <!-- <ul class="navbar-nav mr-lg-2">
        <li class="nav-item nav-search d-none d-lg-block">
        <div class="input-group">
            <div class="input-group-prepend hover-cursor" id="navbar-search-icon">
            <span class="input-group-text" id="search">
                <i class="icon-search"></i>
            </span>
            </div>
            <input type="text" class="form-control" id="navbar-search-input" placeholder="Search now" aria-label="search" aria-describedby="search">
        </div>
        </li>
    </ul> -->
    <ul class="navbar-nav navbar-nav-right">
        <!-- <li class="nav-item dropdown">
        <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-toggle="dropdown">
            <i class="icon-bell mx-0"></i>
            <span class="count"></span>
        </a>
        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
            <p class="mb-0 font-weight-normal float-left dropdown-header">Notifications</p>
            <a class="dropdown-item preview-item">
            <div class="preview-thumbnail">
                <div class="preview-icon bg-success">
                <i class="ti-info-alt mx-0"></i>
                </div>
            </div>
            <div class="preview-item-content">
                <h6 class="preview-subject font-weight-normal">Application Error</h6>
                <p class="font-weight-light small-text mb-0 text-muted">
                Just now
                </p>
            </div>
            </a>
            <a class="dropdown-item preview-item">
            <div class="preview-thumbnail">
                <div class="preview-icon bg-warning">
                <i class="ti-settings mx-0"></i>
                </div>
            </div>
            <div class="preview-item-content">
                <h6 class="preview-subject font-weight-normal">Settings</h6>
                <p class="font-weight-light small-text mb-0 text-muted">
                Private message
                </p>
            </div>
            </a>
            <a class="dropdown-item preview-item">
            <div class="preview-thumbnail">
                <div class="preview-icon bg-info">
                <i class="ti-user mx-0"></i>
                </div>
            </div>
            <div class="preview-item-content">
                <h6 class="preview-subject font-weight-normal">New user registration</h6>
                <p class="font-weight-light small-text mb-0 text-muted">
                2 days ago
                </p>
            </div>
            </a>
        </div>
        </li> -->
        <li class="nav-item nav-profile dropdown">
            <div class="dropdown user-dropdown">
                <button class="btn dropdown-toggle ms-auto" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="dropdown-data">
                        <div class="img-user"><img src="images/avtar.png" class="img-fluid" alt=""></div>
                        <div class="welcome-user">
                            <span class="welcome">welcome</span>
                            <span class="user-name-title">John Thomsan</span>
                        </div>
                    </div>
                    <span class="arrow-icon">
                        <svg width="14" height="8" viewBox="0 0 14 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13.002 7L7.00195 0.999999L1.00195 7" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </span>
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                    <ul class="list-unstyled mb-0">
                        <li><a class="dropdown-item" href="{{route('auth.admin-profile')}}"><img src="{{ asset('admin/images/user-login.svg')}}" class="img-fluid">My Profile</a></li>
                        <li><a class="dropdown-item" href="#"><img src="{{ asset('admin/images/booksaved.svg') }}" class="img-fluid">My Buyers Data</a></li>
                        <li><a class="dropdown-item" href="#"><img src="{{ asset('admin/images/messages.svg') }}" class="img-fluid">Support</a></li>
                        @livewire('auth.admin.logout')
                    </ul>
                </div>
            </div>
        </li>
        <!-- <li class="nav-item nav-settings d-none d-lg-flex">
        <a class="nav-link" href="#">
            <i class="icon-ellipsis"></i>
        </a>
        </li> -->
    </ul>
    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
        <span class="icon-menu"></span>
    </button>
    </div>
</nav>
