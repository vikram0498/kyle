<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>

	<!-- FONT FAMILY -->
	<link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600;700;800&family=Nunito+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @yield('styles')
</head>
<body style="margin: 0;">

	<div class="mail-template" style="max-width: 800px; margin: 0 auto;">
		<table cellpadding="0" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th style="text-align: center;padding: 34px 0; background-color: #F5F7F9;"><img src="{{ asset(config('constants.default.email_logo')) }}" alt="" title="" /></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td style="padding: 89px 66px 257px; border: 1px solid #F5F7F9;">
                        @yield('email-content') 
					</td>
				</tr>
			</tbody>
			<tfoot>
				<tr>
					<td>
						<p class="copyright" style="margin: 0; background-color: #121639; padding:22px 0; text-align:center; font-size: 20px; font-weight: 600; font-family: 'Nunito Sans', sans-serif; color:#fff;">© {{ date('Y') }} All Copyrights Reserved By {{config('app.name')}}</p>
					</td>
				</tr>
			</tfoot>
		</table>
	</div>
	<!--  -->

</body>
</html>