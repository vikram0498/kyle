<div>
<section class="login d-flex flex-wrap">
    <div class="login-left bg-white">
    <div class="login-left-inner">
        <div class="login-quote">
            <h4>Grow Your Skill With {{config('constants.app_name')  }}</h4>
            <p>it is an e-learning platform where you can learn different type of skills that will be helpful to create a better future for you.it is an e-learning platform where you can learn.</p>
        </div>
        <div class="login-img-left">
            <img src="{{ asset('images/login-left.png') }}" alt="login img">
        </div>
    </div>
    </div>
    <div class="login-right bg-light-orange">
        <div class="login-form">
            <div class="form-head">
                <h3>{{ trans('global.reset_password') }}</h3>
            </div>
            <form wire:submit.prevent="submit"  class="form">            
            <div class="form-outer">
                <div class="form-group">
                    <div class="input-form">
                        <div class="login-icon"><img src="{{ asset('images/icons/password.svg') }}" ></div>
                        <label for="password" class="form-label">Password</label>
                        <input id="password-field" type="password" wire:model.defer="password" class="form-control" placeholder="Enter Your Password" autocomplete="off"/>
                        <span toggle="#password-field" class="fa-eye field-icon toggle-password">
                        <img src="{{ asset('images/icons/view-password.svg') }}" alt="view-password" class="view-password">
                        <img src="{{ asset('images/icons/hide-password.svg') }}" alt="hide-password" class="hide-password">
                        </span>
                    </div>
                    @error('password') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <div class="input-form">
                        <div class="login-icon"><img src="{{ asset('images/icons/password.svg') }}" ></div>
                        <label for="password" class="form-label">Confirm password</label>
                        <input id="password-field" type="password" wire:model.defer="password_confirmation" class="form-control" placeholder="Enter Your Confirm Password" />
                        <span toggle="#password-field" class="fa-eye field-icon toggle-password">
                        <img src="{{ asset('images/icons/view-password.svg') }}" alt="view-password" class="view-password">
                        <img src="{{ asset('images/icons/hide-password.svg') }}" alt="hide-password" class="hide-password">
                        </span>
                    </div>
                    @error('password_confirmation') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
                </div>
                <div class="submit-btn">
                    <button type="submit"  wire:loading.attr="disabled" class="btn">
                    Reset Password
                        <span wire:loading wire:target="submit">
                            <i class="fa fa-solid fa-spinner fa-spin" aria-hidden="true"></i>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
</div>