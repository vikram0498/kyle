@extends('emails.layouts.admin')

@section('email-content')

    <h4 style="font-family: 'Barlow', sans-serif; color: #464B70; font-weight: 600; font-size: 18px;margin-top: 0;">Dear Support</h4>

    <p style="font-size: 18px; line-height: 25.5px; font-weight: 600; font-family: 'Nunito Sans', sans-serif; color: #464B70; margin-bottom: 27px;">Please click the button below to verify your email address.</p>

    <p style="font-size: 18px; line-height: 25.5px; font-weight: 600; font-family: 'Nunito Sans', sans-serif; color: #464B70; margin-bottom: 27px;">A user seeking help. Below his details:</p>
    <p style="font-size: 18px; line-height: 25.5px; font-weight: 600; font-family: 'Nunito Sans', sans-serif; color: #464B70; margin-bottom: 27px;">Name :- {{ ucwords($name) }}</p>
    <p style="font-size: 18px; line-height: 25.5px; font-weight: 600; font-family: 'Nunito Sans', sans-serif; color: #464B70; margin-bottom: 27px;">Email :- {{$email ?? ''}}</p>
    <p style="font-size: 18px; line-height: 25.5px; font-weight: 600; font-family: 'Nunito Sans', sans-serif; color: #464B70; margin-bottom: 27px;">Phone Number :- {{$phone_number ?? ''}}</p>
    <p style="font-size: 18px; line-height: 25.5px; font-weight: 600; font-family: 'Nunito Sans', sans-serif; color: #464B70; margin-bottom: 27px;">Contact Preferance :- {{ $contact_preferance ? config('constants.contact_preferances')[$contact_preferance] : ''}}</p>

    <p style="font-size: 18px; line-height: 10.5px; font-weight: 600; font-family: 'Nunito Sans', sans-serif; color: #464B70; margin-bottom: 27px;">{!! nl2br($message) !!}</p>
    
@endsection



