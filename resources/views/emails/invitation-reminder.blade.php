@extends('emails.layouts.admin')

@section('email-content')


			@if($reminderNo == 1)

				@php
					$mailContent  = getSetting('reminder_one_mail_content');
					$mailContent  = str_replace('[INVITATION_LINK]',$invitationLink,$mailContent);
					$mailContent  = str_replace('[APP_NAME]',config('app.name'),$mailContent);
				@endphp

				@if($mailContent)
					{!! $mailContent !!}
				@else

					<b>Hello</b><br>
					<p>We are excited to invite you to join {{ config('app.name') }}! As a valued member of our community, we are extending this exclusive invitation to you to register.</p>
					<p style="margin-bottom: 0;font-weight: normal;">To register, simply  <a href="{{ $invitationLink }}">Click Here</a>
					</p>

					<br>
					<p>
						Regards<br>
						{{ config('app.name') }}
					</p>

					
				@endif

			@elseif($reminderNo == 2)

				@php
					$mailContent  = getSetting('reminder_two_mail_content');
					$mailContent  = str_replace('[INVITATION_LINK]',$invitationLink,$mailContent);
					$mailContent  = str_replace('[APP_NAME]',config('app.name'),$mailContent);
				@endphp

				@if($mailContent)
					{!! $mailContent !!}
				@else

					<b>Hello</b><br>
					<p>We are excited to invite you to join {{ config('app.name') }}! As a valued member of our community, we are extending this exclusive invitation to you to register.</p>
					<p style="margin-bottom: 0;font-weight: normal;">To register, simply  <a href="{{ $invitationLink }}">Click Here</a>
					</p>

					<br>
					<p>
						Regards<br>
						{{ config('app.name') }}
					</p>

				@endif


			@elseif($reminderNo == 3)

				@php
					$mailContent  = getSetting('reminder_three_mail_content');
					$mailContent  = str_replace('[INVITATION_LINK]',$invitationLink,$mailContent);
					$mailContent  = str_replace('[APP_NAME]',config('app.name'),$mailContent);
				@endphp

				@if($mailContent)
					{!! $mailContent !!}
				@else

					<b>Hello</b><br>
					<p>We are excited to invite you to join {{ config('app.name') }}! As a valued member of our community, we are extending this exclusive invitation to you to register.</p>
					<p style="margin-bottom: 0;font-weight: normal;">To register, simply  <a href="{{ $invitationLink }}">Click Here</a>
					</p>

					<br>
					<p>
						Regards<br>
						{{ config('app.name') }}
					</p>

				@endif


			@endif

{{--	@if($reminderNo == 1)

		@php
			$mailContent  = getSetting('reminder_one_mail_content');
			$mailContent  = str_replace('[INVITATION_LINK]',$invitationLink,$mailContent);
			$mailContent  = str_replace('[APP_NAME]',config('app.name'),$mailContent);
		@endphp

		@if($mailContent)
				{!! $mailContent !!}
		@else

			<p class="mail-title">
				<b>Hello</b>
			</p>
			
			<p>We are excited to invite you to join {{ config('app.name') }}! As a valued member of our community, we are extending this exclusive invitation to you to register.</p><br>

			<p style="margin-bottom: 0;font-weight: normal;">To register, simply  <a href="{{ $invitationLink }}">Click Here</a>
			</p><br>
			
			
			<p>
				Regards<br>
				{{ config('app.name') }}
			</p>

		@endif
	@elseif($reminderNo == 2)

		@php
			$mailContent  = getSetting('reminder_two_mail_content');
			$mailContent  = str_replace('[INVITATION_LINK]',$invitationLink,$mailContent);
			$mailContent  = str_replace('[APP_NAME]',config('app.name'),$mailContent);
		@endphp

		@if($mailContent)
				{!! $mailContent !!}
		@else

			<p class="mail-title">
				<b>Hello</b>
			</p>
			
			<p>We are excited to invite you to join {{ config('app.name') }}! As a valued member of our community, we are extending this exclusive invitation to you to register.</p><br>

			<p style="margin-bottom: 0;font-weight: normal;">To register, simply  <a href="{{ $invitationLink }}" >Click Here</a>
			</p><br>
			
			
			<p>
				Regards<br>
				{{ config('app.name') }}
			</p>
			
		@endif

	@elseif($reminderNo == 3)

		@php
			$mailContent  = getSetting('reminder_three_mail_content');
			$mailContent  = str_replace('[INVITATION_LINK]',$invitationLink,$mailContent);
			$mailContent  = str_replace('[APP_NAME]',config('app.name'),$mailContent);
		@endphp

		@if($mailContent)
				{!! $mailContent !!}
		@else

			<p class="mail-title">
				<b>Hello</b>
			</p>
			
			<p>We are excited to invite you to join {{ config('app.name') }}! As a valued member of our community, we are extending this exclusive invitation to you to register.</p><br>

			<p style="margin-bottom: 0;font-weight: normal;">To register, simply <a href="{{ $invitationLink }}" >Click Here</a>
			</p><br>
			
			
			<p>
				Regards<br>
				{{ config('app.name') }}
			</p>
			
		@endif

	@endif
		--}}
@endsection
