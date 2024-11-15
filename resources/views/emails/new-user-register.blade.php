@extends('emails.layouts.admin')

@section('email-content')
	<p class="mail-title">
		<b>Hello {{ ucwords($name) }},</b>
	</p>
	<div class="mail-desc">
		
		<p style="margin-bottom: 0;font-weight: normal;">
			We’re excited to inform you that a new user has just registered on the platform. Below are the details of the user:
		</p>
		<ul>
			<li><b>Name :</b> {{ $user->name ?? ''}}</li>
			<li><b>Email :</b> {{ $user->email ?? ''}}</li>
			<li><b>Phone :</b> {{ $user->phone ?? ''}}</li>
			<li><b>Role : </b>{{ $user->roles()->first()->title }}</li>
			<li><b>Registered On :</b> {{ convertDateTimeFormat($user->created_at,'datetime') }}</li>
		</ul>

		<p style="margin-bottom: 0;font-weight: normal;">
			You can log in to your admin dashboard to view more details about this user or manage their account.
		</p>
		
	</div>
@endsection