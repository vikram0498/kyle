@extends('emails.layouts.admin')

@section('email-content')
<tr>
	<td>
		<p class="mail-title">
			<b>Hello {{ $name ?? "" }} </b>,
		</p>
		<div class="mail-desc">
			<p>You are receiving this email because we received a password reset request for your account</p>
			<p>Please click on the link below to reset your password and get access to your account :</p>
		</div>
	</td>
    <tr>
        <td style="box-sizing:border-box;text-align: center;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol'">

        <a href="{{ $reset_password_url }}" style="border-radius:4px;color:#fff;display:inline-block;overflow:hidden;text-decoration:none;background-color:#3f53fe;padding: 10px 20px;" target="_blank">Reset Password</a>

        </td>
    </tr>
    <tr>
        <td style="line-break: anywhere;">
            If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser: {{ $reset_password_url }}
        </td>
    </tr>
    <tr>
        <td>
            <p style="font-size:14px;">If you did not request a password reset, no further action is required.</p>
        </td>
    </tr>
</tr>
@endsection
