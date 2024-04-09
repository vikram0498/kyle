@extends('emails.layouts.admin')

@section('email-content')

	@if($reminderNo == 1)

		<p class="mail-title">
			<b>Hello</b>
		</p>
		<div class="mail-desc">
			<p style="margin-bottom: 0;font-weight: normal;">{{ $invitationLink }}</p>
		</div>

	@elseif($reminderNo == 2)

		<p class="mail-title">
			<b>Hello</b>
		</p>
		<div class="mail-desc">
			<p style="margin-bottom: 0;font-weight: normal;">{{ $invitationLink }}</p>
		</div>

	@elseif($reminderNo == 3)

		<p class="mail-title">
			<b>Hello</b>
		</p>
		<div class="mail-desc">
			<p style="margin-bottom: 0;font-weight: normal;">{{ $invitationLink }}</p>
		</div>

	@endif
		
@endsection
