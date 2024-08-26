@extends('emails.layouts.admin')

@section('email-content')

    <p style="font-size: 18px; line-height: 25.5px; font-weight: 600; font-family: 'Nunito Sans', sans-serif; color: #464B70; margin-bottom: 27px;">{{ $name ?? '' }} welcome to {{ config('app.name') }}! We’re thrilled to have you join our innovative platform to streamline and amplify the number of deals you close in the future. As a member of our growing community, you’re about to experience the most efficient, targeted way to grow your business and profits.</p>

 	<p style="font-size: 18px; line-height: 25.5px; font-weight: 600; font-family: 'Nunito Sans', sans-serif; color: #464B70; margin-bottom: 27px; margin-top:27px;">We need to verify your email address before you can begin using our platform.</p>

 	<p style="font-size: 18px; line-height: 25.5px; font-weight: 600; font-family: 'Nunito Sans', sans-serif; color: #464B70; margin-bottom: 27px; margin-top:27px;">Please click the link below to verify your email and activate your {{ config('app.name') }} account:</p>


<p> <a href="{{ $url }}" style="font-family: 'Barlow', sans-serif; color:#fff; text-transform: uppercase; font-size:18px; line-height: 13px; border-radius: 5px; background-color: #3F53FE; padding: 21px 28px; display: inline-block; text-decoration: none;">Verify Email Now</a></p>

   
    <div class="regards" style="font-family: 'Barlow', sans-serif; color: #464B70; font-weight: 700; font-size: 18px;">Regards,<br><br><br> {{ config('app.name') }}</div>
@endsection
