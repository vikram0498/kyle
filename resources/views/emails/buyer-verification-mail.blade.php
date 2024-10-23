@extends('emails.layouts.admin')

@section('email-content')
		<p class="mail-title">
			<b>Hello {{ ucwords($name) }},</b>
		</p>
		<div class="mail-desc">
            <p style="margin-bottom: 0;font-weight: normal;">
                I hope this message finds you well.
            </p>
            <p style="margin-bottom: 0;font-weight: normal;">
                We have a new buyer who has recently registered on our platform and requires your verification. Below are the details of the buyer awaiting approval:
            </p>
            <p style="margin-bottom: 0;font-weight: normal;">
                Full Name: {{ $user ? $user->name : null }}<br>
                Email Address: {{ $user ? $user->email : null }}<br>
            </p>
		</div>
@endsection
