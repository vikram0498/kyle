@extends('emails.layouts.admin')

@section('email-content')
    <h4 style="font-family: 'Barlow', sans-serif; color: #464B70; font-weight: 700; font-size: 24px;margin-top: 0;">Hello {{ $name ?? "" }}</h4>
    <p style="font-size: 24px; line-height: 39.5px; font-weight: 600; font-family: 'Nunito Sans', sans-serif; color: #464B70; margin-bottom: 36px;">{{ config('constants.app_name') }} has created your profile as buyer on our platform</p>
    <p style="font-size: 24px; line-height: 39.5px; font-weight: 600; font-family: 'Nunito Sans', sans-serif; color: #464B70; margin-bottom: 36px;">Please verify email to claim your profile.</p>

    <a href="{{ $url }}" style="font-family: 'Barlow', sans-serif; color:#fff; text-transform: uppercase; font-size:18px; line-height: 13px; border-radius: 5px; background-color: #3F53FE; padding: 21px 28px; display: inline-block; text-decoration: none;">Verify Email</a>

    <p style="font-size: 24px; line-height: 39.5px; font-weight: 600; font-family: 'Nunito Sans', sans-serif; color: #464B70; margin-bottom: 36px; margin-top:30px;">If you did not create an account, no further action is required.</p>

    <div class="regards" style="font-family: 'Barlow', sans-serif; color: #464B70; font-weight: 700; font-size: 22px;">Regards,<br><br><br> {{ config('app.name') }}</div>

@endsection
