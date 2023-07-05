<div class="content-wrapper">
    <div wire:loading wire:target="openEditSection,closedEditSection" class="loader" role="status" aria-hidden="true"></div>
    <div class="card">
        <div class="card-body">
            <div class="top-box-set">
                <h4>Profile</h4>
            </div>
        </div>
    </div>
    <div class="row profile-page">
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-body">
                    <div class="card-title top-box-set">
                        <h4>Edit Profile</h4>
                    </div>            
                    <div class="card-block">
                        @include('livewire.auth.profile.profile-image')
                    </div>
                    @include('livewire.auth.profile.edit')              
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-body">
                    <div class="card-title top-box-set">
                        <h4>Change Password</h4>
                    </div>
                    @include('livewire.auth.profile.change-password')
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