@extends('emails.layouts.admin')

@section('email-content')
<tr>
	<td>
		<p class="mail-title">
			<b>Hello {{ $name ?? "" }} </b>,
		</p>
		<div class="mail-desc">
            <p>{!! $replyMessage ?? '' !!}</p>
		</div>
	</td>
</tr>
@endsection
