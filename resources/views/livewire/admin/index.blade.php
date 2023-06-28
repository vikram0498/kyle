<div class="content-wrapper">
  <div class="row">
    <div class="col-md-12 grid-margin">
      <div class="row">
        <div class="col-12 col-xl-8 mb-4 mb-xl-0">
          <h3 class="font-weight-bold">Welcome {{ ucfirst(auth()->user()->first_name) }}</h3>
          <h6 class="font-weight-normal mb-0">All systems are running smoothly! You have <span class="text-primary">3 unread alerts!</span></h6>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6 grid-margin stretch-card">
      <div class="card tale-bg">
        <div class="card-people mt-auto">
          <img src="{{ asset('admin/images/dashboard/people.svg') }}" alt="people">
          <div class="weather-info">
            <div class="d-flex">
              <div>
                <h2 class="mb-0 font-weight-normal"><i class="icon-sun mr-2"></i>31<sup>C</sup></h2>
              </div>
              <div class="ml-2">
                <h4 class="location font-weight-normal">Bangalore</h4>
                <h6 class="font-weight-normal">India</h6>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6 grid-margin transparent">
      <div class="row">
        <div class="col-md-6 mb-4 stretch-card transparent">
          <div class="card card-tale">
            <div class="card-body">
              <p class="mb-4">{{ __('cruds.dashboard.total_seller') }}</p>
              <p class="fs-30 mb-2">30</p>
            </div>
          </div>
        </div>
        <div class="col-md-6 mb-4 stretch-card transparent">
          <div class="card card-dark-blue">
            <div class="card-body">
              <p class="mb-4"> {{ __('cruds.dashboard.total_buyer') }} </p>
              <p class="fs-30 mb-2">50</p>
            </div>
          </div>
        </div>
      </div>
      <!-- <div class="row">
        <div class="col-md-6 mb-4 mb-lg-0 stretch-card transparent">
          <div class="card card-light-blue">
            <div class="card-body">
              <p class="mb-4">Number of Meetings</p>
              <p class="fs-30 mb-2">34040</p>
            </div>
          </div>
        </div>
        <div class="col-md-6 stretch-card transparent">
          <div class="card card-light-danger">
            <div class="card-body">
              <p class="mb-4">Number of Clients</p>
              <p class="fs-30 mb-2">47033</p>
            </div>
          </div>
        </div>
      </div> -->
    </div>
  </div>
</div>

@push('scripts')
<!-- Custom js for this page-->
<!-- <script src="{{ asset('admin/assets/chart.js/Chart.min.js') }}"></script> -->
<!-- <script src="{{ asset('admin/js/dashboard.js') }}" type="text/javascript"></script> -->
<!-- <script src="{{ asset('admin/js/Chart.roundedBarCharts.js') }}" type="text/javascript"></script> -->
@endpush