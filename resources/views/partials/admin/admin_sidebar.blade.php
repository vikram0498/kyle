<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item {{ request()->is('admin/index') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                <i class="icon-grid menu-icon"></i>
                <span class="menu-title">{{__('global.dashboard')}}</span>
            </a>
        </li>

        @if(auth()->user()->is_admin)            

            @can('user_access')
            <li class="nav-item {{ request()->is('admin/users') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.seller') }}">
                    <i class="icon-grid menu-icon fa-solid fa-users"></i>
                    <span class="menu-title"> {{ __('cruds.user.title') }} </span>
                </a>
            </li>

            <li class="nav-item {{ request()->is('admin/deleted') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.deleted-users') }}">
                    <i class="icon-grid menu-icon fas fa-users-slash"></i>
                    <span class="menu-title"> {{__('global.deleted')}} {{ __('cruds.user.title') }} </span>
                </a>
            </li>
            @endcan
            
            <li class="nav-item {{ request()->is('admin/search-log') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.search-log') }}">
                    <i class="icon-grid menu-icon fa-solid fa-search"></i>
                    <span class="menu-title"> {{ __('cruds.search_log.title') }} </span>
                </a>
            </li>
            
            @can('transaction_access')
            <li class="nav-item {{ request()->is('admin/transactions') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.transactions') }}">
                    <i class="icon-grid menu-icon fas fa-wallet"></i>
                    <span class="menu-title"> {{ __('cruds.transaction.title') }} </span>
                </a>
            </li>
            @endcan

            @can('buyer_access')            
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#buyer-menu" aria-expanded="false" aria-controls="buyer-menu">
                    <i class="icon-grid menu-icon fas fa-house-user"></i>
                    <span class="menu-title"> {{ __('cruds.buyer.title') }} </span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="buyer-menu">
                    <ul class="nav flex-column sub-menu">
                        @can('buyer_access')
                        <li class="nav-item {{ (request()->is('admin/buyer') || request()->is('admin/buyer/import')) ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.buyer') }}">
                                <span class="menu-title"> {{ __('cruds.buyer.sub_menu_list_title') }} </span>
                            </a>
                        </li>
                        @endcan

                        @can('buyer_plan_access')
                        <li class="nav-item {{ request()->segment(2) == 'buyer-plans' ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.buyer-plans') }}">
                                <span class="menu-title"> {{ __('cruds.buyer_plan.title') }} </span>
                            </a>
                        </li>
                        @endcan

                        @can('buyer_transaction_access')
                        <li class="nav-item {{ (request()->is('admin/buyer-transactions')) ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.buyer-transactions') }}">
                                <span class="menu-title"> {{ __('cruds.buyer_transaction.title') }} </span>
                            </a>
                        </li>
                        @endcan

                        @can('buyer_flag_access')
                        <li class="nav-item {{ request()->is('admin/buyer') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.buyer') }}">
                                <span class="menu-title"> {{ __('cruds.buyer.sub_menu_flaged_title') }} </span>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </div>
            </li> 
            @endcan

            

            {{-- Masters --}}
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#master-menu"  aria-expanded="false" aria-controls="master-menu">
                    <i class="menu-icon fas fa-money-check-alt"></i>
                    <span class="menu-title">Subscription Plans</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="master-menu">
                    <ul class="nav flex-column sub-menu">
                        @can('plan_access')
                        <li class="nav-item {{ request()->segment(2) == 'plan' ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.plan') }}">
                                <span class="menu-title">{{__('cruds.plan.title')}}</span>
                            </a>
                        </li>
                        @endcan
                        @can('addon_access')
                        <li class="nav-item {{ request()->is('admin/addon') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.addon') }}">
                                <span class="menu-title">{{__('cruds.addon.title')}}</span>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </div>
            </li> 

            {{-- Settings --}}
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#setting-menu" aria-expanded="false" aria-controls="setting-menu">
                    <i class="menu-icon fas fa-cog"></i>
                    <span class="menu-title"> {{ __('cruds.setting.title') }} </span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="setting-menu">
                    <ul class="nav flex-column sub-menu">
                        @can('video_access')
                        <li class="nav-item {{ request()->is('admin/video') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.video') }}">
                                <span class="menu-title">{{__('cruds.video.title')}}</span>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </div>
            </li> 


            @can('support_access')
            <li class="nav-item {{ request()->is('admin/supports') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.supports') }}">
                    <i class="icon-grid menu-icon fas fa-headset"></i>
                    <span class="menu-title"> {{ __('cruds.support.title') }} </span>
                </a>
            </li>
            @endcan

        @endif
    </ul>
</nav>