@extends('emails.layouts.admin')

@section('email-content')

    <p style="font-size: 18px; line-height: 25.5px; font-family: 'Nunito Sans', sans-serif; margin-bottom: 27px;">{{ $name ?? '' }} welcome to {{ config('app.name') }}! We’re thrilled to have you join our innovative platform to streamline and amplify the number of deals you close in the future. As a member of our growing community, you’re about to experience the most efficient, targeted way to grow your business and profits.</p>

<p style="font-size: 18px; line-height: 25.5px; font-weight: 600; font-family: 'Nunito Sans', sans-serif; margin-bottom: 27px; margin-top:27px;">We need to verify your email address before you can begin using our platform.</p>

<p style="font-size: 18px; line-height: 25.5px; font-weight: 600; font-family: 'Nunito Sans', sans-serif; margin-bottom: 27px; margin-top:27px;">Please click the link below to verify your email and activate your {{ config('app.name') }} account:</p>


<p> <a href="{{ $url }}" style="font-size:18px; display: inline-block; text-decoration: none;">**Verify Email Now**</a></p>
@endsection
