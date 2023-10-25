@extends('emails.layouts.admin')

@section('email-content')

    <h4 style="font-family: 'Barlow', sans-serif; color: #464B70; font-weight: 700; font-size: 24px;margin-top: 0;">Hello {{ $name ?? "" }}</h4>
    <p style="font-size: 24px; line-height: 39.5px; font-weight: 600; font-family: 'Nunito Sans', sans-serif; color: #464B70; margin-bottom: 36px;">You are receiving this email because we received a password reset request for your account</p>
    <p style="font-size: 24px; line-height: 39.5px; font-weight: 600; font-family: 'Nunito Sans', sans-serif; color: #464B70; margin-bottom: 36px;">Please click on the link below to reset your password and get access to your account :</p>
    <a href="{{ $reset_password_url }}" style="font-family: 'Barlow', sans-serif; color:#fff; text-transform: uppercase; font-size:18px; line-height: 13px; border-radius: 5px; background-color: #3F53FE; padding: 21px 28px; display: inline-block; text-decoration: none;">Reset Password</a>
    <p style="font-size: 24px; line-height: 39.5px; font-weight: 600; font-family: 'Nunito Sans', sans-serif; color: #464B70; margin-bottom: 36px;"> If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser: {{ $reset_password_url }}</p>

    <div class="regards" style="font-family: 'Barlow', sans-serif; color: #464B70; font-weight: 700; font-size: 22px;">Regards,<br><br><br> {{ config('app.name') }}</div>

@endsection
