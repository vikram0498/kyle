

<div class="content-wrapper">

   <div wire:loading wire:target="openEditSection,closedEditSection" class="loader" role="status" aria-hidden="true"></div>
   
   <section style="background-color: #f5f7ff;">
      <div class="container py-1">
        <!-- Start headsection -->
       <div class="row">
            <div class="col-lg-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                      <div class="d-flex float-left">
                        <h4 class="font-weight-bold">{{($editMode) ? trans('global.edit').' Profile' : 'Profile' }} </h5>
                      </div>
                      <div class="d-flex float-right">
                        @if(!$editMode)
                        <button class="btn btn-sm btn-primary mr-1" wire:click="openEditSection">
                            <i class="fa fa-edit pr-1"></i>{{__('global.edit')}}
                        </button>
                        @endif
                        <button class="btn btn-sm btn-primary" id="changepassword"  data-bs-toggle="modal" data-bs-target="#changePasswordModal"><i class="fa fa-key pr-1"></i>{{__('global.change_password')}}</button>
                      </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End headsection -->
        @if(!$editMode)
          <!--Start row-1  -->
          <div class="row">
          
              @include('livewire.auth.profile.profile-image')

            <div class="col-lg-8">
              <div class="card mb-4 table-data">
                <div class="card-body">
                  <div class="row">
                    <div class="col-sm-3">
                      <p class="mb-0 font-weight-bold">{{ __('cruds.user.fields.first_name') }}</p>
                    </div>
                    <div class="col-sm-9">
                      <p class="text-muted mb-0">{{ $authUser->first_name }}</p>
                    </div>
                  </div>
                  <hr>

                  <div class="row">
                    <div class="col-sm-3">
                      <p class="mb-0 font-weight-bold">{{ __('cruds.user.fields.last_name') }}</p>
                    </div>
                    <div class="col-sm-9">
                      <p class="text-muted mb-0">{{ $authUser->last_name }}</p>
                    </div>
                  </div>
                  <hr>

                  <div class="row">
                    <div class="col-sm-3">
                      <p class="mb-0 font-weight-bold">{{ __('cruds.user.fields.email') }}</p>
                    </div>
                    <div class="col-sm-9">
                      <p class="text-muted mb-0">{{ $authUser->email }}</p>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-3">
                      <p class="mb-0 font-weight-bold">{{ __('cruds.user.fields.phone') }}</p>
                    </div>
                    <div class="col-sm-9">
                      <p class="text-muted mb-0">{{ $authUser->phone ?? ''}}</p>
                    </div>
                  </div>
            
                </div>
              </div>
            </div>
          </div>
          <!--End row-1  -->
        @else

          @include('livewire.auth.profile.edit')
          
        @endif

      </div>
    </section>
    @livewire('auth.profile.change-password')

</div>
@push('scripts')
<script>
  $(document).ready(function(){
    $(document).on('click','#changepassword',function(){
       $('#changePasswordModal').modal('show');
    });
    
    $(document).on('click','.close-modal',function(){
         $('#changePasswordModal').modal('hide');
    });
  });
</script>
@endpush