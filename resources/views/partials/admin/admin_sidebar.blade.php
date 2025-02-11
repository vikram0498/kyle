<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                <i class="icon-grid menu-icon"></i>
                <span class="menu-title">{{__('global.dashboard')}}</span>
            </a>
        </li>

        @if(auth()->user()->is_admin)            

            {{-- Made All Users Menu on 08-02-2025 --}}
            @can('user_access')
            <li class="nav-item {{ request()->is('all-users') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.users') }}">
                    <i class="icon-grid menu-icon fa-solid fa-users"></i>
                    <span class="menu-title"> All {{ __('cruds.user.title') }} </span>
                </a>
            </li>
            @endcan
            {{-- End All Users Menu on 08-02-2025 --}}

            @can('user_access')
            <li class="nav-item {{ (request()->is('buyer-verification')) ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.buyer-verification') }}">
                    <i class="icon-grid menu-icon fa-solid fa-users"></i>
                    <span class="menu-title">{{__('cruds.buyer_verification.title')}} &nbsp;<span class="badge badge-light kyc-buyer-count"></span></span>
                </a>
            </li>

            <li class="nav-item {{ request()->is('users') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.seller') }}">
                    <i class="icon-grid menu-icon fa-solid fa-users"></i>
                    <span class="menu-title"> {{ __('cruds.user.title') }} </span>
                </a>
            </li>

            <li class="nav-item {{ request()->is('deleted-users') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.deleted-users') }}">
                    <i class="icon-grid menu-icon fas fa-users-slash"></i>
                    <span class="menu-title"> {{__('global.deleted')}} {{ __('cruds.user.title') }} </span>
                </a>
            </li>
            @endcan
            
            <li class="nav-item {{ request()->is('search-log') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.search-log') }}">
                    <i class="icon-grid menu-icon fa-solid fa-search"></i>
                    <span class="menu-title"> {{ __('cruds.search_log.title') }} </span>
                </a>
            </li>
            
            @can('transaction_access')
            <li class="nav-item {{ request()->is('transactions') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.transactions') }}">
                    <i class="icon-grid menu-icon fas fa-wallet"></i>
                    <span class="menu-title"> {{ __('cruds.transaction.title') }} </span>
                </a>
            </li>
            @endcan

            @can('buyer_access')  
            @php
              $buyerCallapse = (
                request()->is('buyer') || 
                request()->is('deleted-buyers') || 
                request()->is('buyer/import') || 
                request()->is('profile-tags')  ||
                request()->is('buyer-transactions') ||
                request()->is('invited-list')
              ); 
            @endphp     

            <li class="nav-item {{ $buyerCallapse ? 'active' : '' }}">
                <a class="nav-link" data-toggle="collapse" href="#buyer-menu" aria-expanded="false" aria-controls="buyer-menu">
                    <i class="icon-grid menu-icon fas fa-house-user"></i>
                    <span class="menu-title"> {{ __('cruds.buyer.title') }} </span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse {{ $buyerCallapse ? 'show' : 'hide' }}" id="buyer-menu">
                    <ul class="nav flex-column sub-menu">
                        @can('buyer_access')
                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('buyer') || request()->is('buyer/import')) ? 'active' : '' }}" href="{{ route('admin.buyer') }}">
                                <span class="menu-title"> {{ __('cruds.buyer.sub_menu_list_title') }} </span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('deleted-buyers')) ? 'active' : '' }}" href="{{ route('admin.deleted-buyers') }}">
                                <span class="menu-title"> Deleted Buyers </span>
                            </a>
                        </li>
                        @endcan

                        @can('buyer_invitation_access')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('invited-list') ? 'active' : '' }}" href="{{ route('admin.buyer-invited-list') }}">
                                <span class="menu-title"> {{ __('cruds.buyer_invitation.title') }} </span>
                            </a>
                        </li>
                        @endcan

                        @can('buyer_plan_access')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('profile-tags') ? 'active' : '' }}" href="{{ route('admin.buyer-plans') }}">
                                <span class="menu-title"> {{ __('cruds.buyer_plan.title') }} </span>
                            </a>
                        </li>
                        @endcan

                        @can('buyer_transaction_access')
                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('buyer-transactions')) ? 'active' : '' }}" href="{{ route('admin.buyer-transactions') }}">
                                <span class="menu-title"> {{ __('cruds.buyer_transaction.title') }} </span>
                            </a>
                        </li>
                        @endcan

                        @can('buyer_flag_access')
                        <li class="nav-item {{ request()->is('buyer') ? 'active' : '' }}">
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
            @php
              $subscriptionCallapse = in_array(request()->segment(1),array('plan','addon')); 
            @endphp
            <li class="nav-item {{ $subscriptionCallapse ? 'active' : '' }}">
                <a class="nav-link" data-toggle="collapse" href="#master-menu"  aria-expanded="false" aria-controls="master-menu">
                    <i class="menu-icon fas fa-money-check-alt"></i>
                    <span class="menu-title">Subscription Plans</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse {{ $subscriptionCallapse ? 'show' : 'hide' }}" id="master-menu">
                    <ul class="nav flex-column sub-menu">
                        @can('plan_access')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('plan') ? 'active' : '' }}" href="{{ route('admin.plan') }}">
                                <span class="menu-title">{{__('cruds.plan.title')}}</span>
                            </a>
                        </li>
                        @endcan
                        @can('addon_access')
                        <li class="nav-item ">
                            <a class="nav-link {{ request()->is('addon') ? 'active' : '' }}" href="{{ route('admin.addon') }}">
                                <span class="menu-title">{{__('cruds.addon.title')}}</span>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </div>
            </li> 

            @can('ad_banner_access')
            <li class="nav-item {{ request()->is('ad-banner') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.ad-banner') }}">
                    <i class="icon-grid menu-icon fas fa-wallet"></i>
                    <span class="menu-title"> {{ __('cruds.adBanner.title') }} </span>
                </a>
            </li>
            @endcan

            {{-- Settings --}}
            @can('setting_access')
            <li class="nav-item {{ request()->is('settings') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.settings') }}">
                    <i class="menu-icon fas fa-cog"></i>
                    <span class="menu-title"> {{ __('cruds.setting.title') }} </span>
                </a>
            </li>
            @endcan

            @can('chat_report_access')
            <li class="nav-item {{ request()->is('chat-reports') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.chat-reports') }}">
                    <i class="icon-grid menu-icon fas fa-wallet"></i>
                    <span class="menu-title"> {{ __('cruds.chat_report.title') }} </span>
                </a>
            </li>
            @endcan

            @can('support_access')
            <li class="nav-item {{ request()->is('supports') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.supports') }}">
                    <i class="icon-grid menu-icon fas fa-headset"></i>
                    <span class="menu-title"> {{ __('cruds.support.title') }} </span>
                </a>
            </li>
            @endcan

        @endif
    </ul>
</nav>