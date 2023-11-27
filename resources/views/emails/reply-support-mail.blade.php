@extends('emails.layouts.admin')

@section('email-content')
		<p class="mail-title">
			<b>Hello {{ ucwords($name) }},</b>
		</p>
		<div class="mail-desc">
            <p style="margin-bottom: 0;font-weight: normal;">{!! $replyMessage ?? '' !!}</p>
		</div>
@endsection
