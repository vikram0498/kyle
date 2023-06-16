<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} | @yield('title')</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="HIPL" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset(config('constants.default.admin_favicon')) }}" type="image/x-icon">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap4-toggle/3.6.1/bootstrap4-toggle.min.css" integrity="sha512-EzrsULyNzUc4xnMaqTrB4EpGvudqpetxG/WNjCpG6ZyyAGxeB6OBF9o246+mwx3l/9Cn838iLIcrxpPHTiygAA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap4-toggle/3.6.1/bootstrap4-toggle.min.js" integrity="sha512-bAjB1exAvX02w2izu+Oy4J96kEr1WOkG6nRRlCtOSQ0XujDtmAstq5ytbeIxZKuT9G+KzBmNq5d23D6bkGo8Kg==" crossorigin="anonymous" referrerpolicy="no-referrer" defer></script>
    @include('partials.admin.css')    

   {{-- @powerGridStyles --}}
    
    @livewireStyles

    @stack('styles')
</head>
<body>
    <div class="container-scroller">
    
        @include('partials.admin.header')

        <div class="container-fluid page-body-wrapper">

        @if(auth()->user()->is_super_admin || auth()->user()->is_admin)

            @include('partials.admin.admin_sidebar')

        @elseif(auth()->user()->is_user)

            @include('partials.admin.user_sidebar')

        @endif
        

        <div class="main-panel">
            <!-- content-wrapper start -->
                @yield('content')
            <!-- content-wrapper ends -->

            <!-- partial:partials/_footer -->
            <footer class="footer">
            <div class="d-sm-flex justify-content-center justify-content-sm-between">
                <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">©{{ date('Y') }} {{ config('constants.app_name') }}, LLC. All Rights Reserved. 
                 <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center"> Developed by <i class="ti-heart text-danger ml-1"></i> HIPL</span>
            </div>
            </footer>
            <!-- partial -->
        </div>
        <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
        
    </div>
    <!-- container-scroller -->
  
    @include('partials.admin.js')

   {{-- @powerGridScripts --}}
   
    @livewireScripts

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    
    <script src="{{ asset('vendor/livewire-alert/livewire-alert.js') }}"></script> 

    <x-livewire-alert::flash />
    
    @stack('scripts')

</body>
</html>
<!-- new -->

