@extends('emails.layouts.admin')

@section('email-content')
	<p class="mail-title">
		<b>Hello {{ ucwords($name) }},</b>
	</p>
	<div class="mail-desc">
		
		<p style="margin-bottom: 0;font-weight: normal;">
			We are pleased to inform you that the following user has successfully verified their email address:
		</p>
		<p style="margin-bottom: 0;font-weight: normal;">
            User Details:
		</p>
		<ul>
			<li><b>Name :</b> {{ $user->name ?? ''}}</li>
			<li><b>Email :</b> {{ $user->email ?? ''}}</li>
			<li><b>Phone :</b> {{ $user->phone ?? ''}}</li>
			<li><b>Role : </b>{{ $user->roles()->first()->title }}</li>
			<li><b>Date of Verification :</b> {{ convertDateTimeFormat($user->email_verified_at,'datetime') }}</li>
		</ul>

		<p style="margin-bottom: 0;font-weight: normal;">
			You can log in to your admin dashboard to view more details about this user or manage their account.
		</p>
		
	</div>
@endsection