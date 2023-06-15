
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

    @if(!$verifyMailComponent)
    <div class="login-right bg-light-orange">
        <div class="login-form">
            <div class="form-head">
            <h3>Nice to see you again!</h3>
            </div>
            <form wire:submit.prevent="submitLogin" class="form">            
            <div class="form-outer">
                <div class="form-group">
                    <div class="input-form">
                        <div class="login-icon"><img src="{{ asset('images/icons/user.svg') }}" alt="User"></div>
                        <label for="email" class="form-label">Email</label>
                        <input type="text" class="form-control"  wire:model="email" wire:keyup="checkEmail" placeholder="Enter Your Email" />
                    </div>
                    @error('email') <span class="error text-danger ">{{ $message }}</span>@enderror
                </div>
                
                <div class="form-group">
                    <div class="input-form">
                        <div class="login-icon"><img src="{{ asset('images/icons/password.svg') }}" ></div>
                        <label for="password" class="form-label">Password</label>
                        <input id="password-field" type="password" class="form-control" placeholder="Enter Your Password" wire:model.defer="password" autocomplete="off"/>
                        <span toggle="#password-field" class="fa-eye field-icon toggle-password">
                            <img src="{{ asset('images/icons/view-password.svg') }}" alt="view-password" class="view-password">
                            <img src="{{ asset('images/icons/hide-password.svg') }}" alt="hide-password" class="hide-password">
                        </span>
                    </div>
                    @error('password') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <div class="login-forgot">
                    <label class="toggle-switch" for="checkbox">
                        <input type="checkbox" id="checkbox" wire:model.defer="remember_me" />
                        <div class="toggle-slider round"></div>
                        <div class="can-toggle__label-text">Remember me</div>
                    </label>
                    <a href="{{ route('auth.forget-password') }}">Forgot Password?</a>
                    </div>
                </div>
                </div>
                <div class="submit-btn">
                    <button type="submit"  wire:loading.attr="disabled" class="btn">
                        Login Now!
                        <span wire:loading wire:target="submitLogin">
                            <i class="fa fa-solid fa-spinner fa-spin" aria-hidden="true"></i>
                        </span>
                    </button>
                </div>
                {{-- <div class="have-account">
                <p>Don’t Have an account? <a href="{{ route('auth.register') }}">Sign up Now!</a></p>
                </div> --}}
            </form>
        </div>
    </div>
    @else
    <div class="login-right bg-light-orange">
        <div class="login-form">
            <div class="form-head">
            <div class="icon-head">
                <img src="{{ asset('images/icons/send-icon.svg') }}">
            </div>
            <h3>Please Verify the Mail</h3>
            <p>We have send the verification mail on you mail id</p>
            </div>
            <div class="go-back">
            <a href="javascript:void(0)" wire:click.prevent="backToLogin">
                <svg width="16" height="13" viewBox="0 0 16 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M14.9993 6.5H0.999268" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                <path d="M5.89927 11.5L0.999268 6.5L5.89927 1.5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
                Back To Login
            </a>
            </div>
        </div>
    </div>
    
    @endif


</section>
</div>
