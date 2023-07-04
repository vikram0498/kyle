

<div class="content-wrapper">

   <div wire:loading wire:target="openEditSection,closedEditSection" class="loader" role="status" aria-hidden="true"></div>
   
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
                            <i class="fa fa-edit pr-1"></i>{{__('global.update')}}
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


    @livewire('auth.profile.change-password')
    <div class="row profile-page">
      <div class="col-12 col-lg-6">
        <div class="card">
          <div class="card-body">
            <div class="card-title top-box-set">
              <h4>Edit Profile</h4>
            </div>
            <form id="form_submit" enctype="multipart/form-data">
                  <div class="card-block">
                      <div class="profile-image-change">
                        <div class="left-imag-col">
                            @if ($profile_image)  
                            <img src="{{ $profile_image->temporaryUrl() }}" alt="avatar"
                            class="rounded-circle img-fluid">
                            @else
                            <img src="{{ ($authUser->profileImage()->first()) ? $authUser->profileImage()->first()->file_url : asset(config('constants.default.profile_image')) }}" alt="avatar"
                            class="rounded-circle img-fluid">
                            @endif   
                        </div>

                        <div class="right-content-profile">
                          <h5 class="my-3">{{ ucfirst($authUser->name) }}</h5>
                          <p class="text-muted mb-1">{{ $authUser->my_referral_code ?? ''  }}</p>
                          <p class="text-muted mb-1">{{ $authUser->profile->profession ?? ''  }}</p>
                          <div class="d-flex mb-2">

                          @if($showConfirmCancel)
                              <button class="btn btn-outline-success ms-1 mr-1" wire:key="action-{{generateRandomString(5)}}" wire:click.prevent="$emitSelf('confirmUpdateProfileImage')"><i class="fa fa-check"></i></button>

                              <button class="btn btn-outline-danger ms-1" wire:key="action-{{generateRandomString(5)}}"  wire:click.prevent="$emitSelf('cancelUpdateProfileImage')"><i class="fa fa-close"></i></button>
                          @else
                              <input id="profile-image-upload" wire:model.defer="profile_image" class="d-none" type="file" wire:change="validateProfileImage" accept="image/*">
                              <button type="button" class="btn change ms-1" onclick="document.getElementById('profile-image-upload').click();" >Change</button>
                          @endif
                        </div>
                        </div>
                      </div>
                      <div class="row mt-5">
                          <div class="col-12 col-md-6 col-lg-12 col-xl-6">
                              <div class="form-group">
                                  <label for="first_name">First Name</label>
                                  <div class="input-set">
                                      <span class="icon-left"><img src="images/user-login.svg" alt="Img"></span>
                                      <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Enter First Name"/>
                                  </div>
                                  <span class="error_first_name error text-danger"></span>
                              </div>
                          </div>
                          <div class="col-12 col-md-6 col-lg-12 col-xl-6">
                              <div class="form-group">
                                  <label for="last_name">Last Name</label>
                                  <div class="input-set">
                                      <span class="icon-left"><img src="images/user-login.svg" alt="Img"></span>
                                      <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Enter Last Name"/>
                                  </div>
                                  <span class="error_last_name text-danger"></span>
                              </div>
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-12 col-md-6 col-lg-12 col-xl-6">
                              <div class="form-group">
                                  <label for="email">Email</label>
                                  <div class="input-set">
                                      <span class="icon-left"><img src="images/mail.svg" alt="Img"></span>
                                      <input type="email" class="form-control" placeholder=" Enter Email" />
                                  </div>
                              </div>
                          </div>
                          <div class="col-12 col-md-6 col-lg-12 col-xl-6">
                              <div class="form-group">
                                  <label for="Mobile">Phone Number</label>
                                  <div class="input-set">
                                      <span class="icon-left"><img src="images/phone.svg" alt="Img"></span>
                                      <input type="text" class="form-control only_integer" id="mobile" placeholder="Enter Number" name="mobile_no"/>
                                  </div>
                                  <span class="error_mobile_no error text-danger"></span>
                              </div>
                          </div>
                      </div>
                  </div>
                  <button type="submit" class="btn btn-fill btn-blue">Update </button>
              </form>
          </div>
        </div>
      </div>
       <div class="col-12 col-lg-6">
        <div class="card changepassword-block">
          <div class="card-body">
            <div class="card-title top-box-set">
              <h4>Change Password</h4>
            </div>
            <form id="form_submit"  enctype="multipart/form-data">
                  <div class="card-block">
                      <div class="row">
                          <div class="col-12 col-lg-12">
                              <div class="form-group">
                                  <label>Current Password</label>
                                  <div class="input-set">
                                      <span class="icon-left"><img src="images/password.svg" alt="Img"></span>
                                      <input id="currentpass_log_id" type="password" class="form-control"  value="" name="" placeholder="Current Password"/>
                                      <span toggle="#password-field" class="form-icon-password toggle-password"><img src="images/eye.svg" class="img-fluid" alt=""></span>
                                  </div>
                              </div>
                          </div>
                          <div class="col-12 col-lg-12">
                              <div class="form-group">
                                  <label>New Password</label>
                                  <div class="input-set">
                                      <span class="icon-left"><img src="images/password.svg" alt="Img"></span>
                                      <input id="newpass_log_id" type="password" class="form-control"  value="" name="" placeholder="New Password"/>
                                      <span toggle="#password-field" class="form-icon-password toggle-password1"><img src="images/eye.svg" class="img-fluid" alt=""></span>
                                  </div>
                              </div>
                          </div>
                          <div class="col-12 col-lg-12">
                              <div class="form-group">
                                  <label>Confirm New Password</label>
                                  <div class="input-set">
                                      <span class="icon-left"><img src="images/password.svg" alt="Img"></span>
                                      <input id="connewpass_log_id" type="password" class="form-control"  value="" name="" placeholder="Confirm New Password"/>
                                      <span toggle="#password-field" class="form-icon-password toggle-password2"><img src="images/eye.svg" class="img-fluid" alt=""></span>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
                  <button type="submit" class="btn btn-fill btn-blue">Update </button>
              </form>
          </div>
        </div>
      </div>
    </div>
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


    $(document).on('click', '.toggle-password', function() {

        $(this).toggleClass("eye-open");
        
        var input = $("#currentpass_log_id");
        input.attr('type') === 'password' ? input.attr('type','text') : input.attr('type','password')
    });
    $(document).on('click', '.toggle-password1', function() {

        $(this).toggleClass("eye-open");
        
        var input = $("#newpass_log_id");
        input.attr('type') === 'password' ? input.attr('type','text') : input.attr('type','password')
    });
    $(document).on('click', '.toggle-password2', function() {

        $(this).toggleClass("eye-open");
        
        var input = $("#connewpass_log_id");
        input.attr('type') === 'password' ? input.attr('type','text') : input.attr('type','password')
    });
  });
</script>
@endpush