@extends('emails.layouts.admin')

@section('email-content')
<tr>
	<td>
		<p class="mail-title">
			<b>Hello</b> {{ $name ?? "" }},
		</p>
		<div class="mail-desc">
			<p>Please click the button below to verify your email address.</p>
		</div>
	</td>
    <tr>
        <td style="box-sizing:border-box;text-align: center;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol'">

        <a href="{{ $url }}" style="border-radius:4px;color:#fff;display:inline-block;overflow:hidden;text-decoration:none;background-color:#3f53fe;padding: 10px 20px;" target="_blank">Verify Email</a>

        </td>
    </tr>
    
    <tr>
        <td>
            <p >If you did not create an account, no further action is required.</p>
        </td>
    </tr>
</tr>
@endsection
