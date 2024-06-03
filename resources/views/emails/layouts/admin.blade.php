<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="x-apple-disable-message-reformatting" />
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="color-scheme" content="light dark" />
  <meta name="supported-color-schemes" content="light dark" />
  <title></title>
   <style>
     body {
      width: 100% !important;
      height: 100%;
      margin: 0;
      -webkit-text-size-adjust: none;
    }
    body,
    td,
    th {
      font-family: "Nunito Sans", Helvetica, Arial, sans-serif;
    }
    .email-footer {
      /* width: 570px; */
      margin: 0 auto;
      padding: 0;
      -premailer-width: 570px;
      -premailer-cellpadding: 0;
      -premailer-cellspacing: 0;
      text-align: center;
	  background: #121639;
    }
    .email-wrapper {
      width: 100%;
      margin: 0;
      padding: 0;
      -premailer-width: 100%;
      -premailer-cellpadding: 0;
      -premailer-cellspacing: 0;
      background-color: #F2F4F6;
    }
    
    .email-content {
      width: 100%;
      margin: 0;
      padding: 0;
      -premailer-width: 100%;
      -premailer-cellpadding: 0;
      -premailer-cellspacing: 0;
    }
    .email-footer p {
      color: #A8AAAF;
    }

 .email-body {
      width: 100%;
      margin: 0;
      padding: 0;
      -premailer-width: 100%;
      -premailer-cellpadding: 0;
      -premailer-cellspacing: 0;
    }
    
    .email-body_inner {
      /* width: 570px; */
      margin: 0 auto;
      padding: 0;
      -premailer-width: 570px;
      -premailer-cellpadding: 0;
      -premailer-cellspacing: 0;
      background-color: #FFFFFF;
    }
   .email-masthead {
      padding: 25px 0;
      text-align: center;
    }
   .email-masthead_logo {
      width: 94px;
    }
 /*Media Queries ------------------------------ */
    
    @media only screen and (max-width: 600px) {
      .email-body_inner,
      .email-footer {
        width: 100% !important;
      }
    }
    
    @media (prefers-color-scheme: dark) {
      body,
      .email-body,
      .email-body_inner,
      .email-content,
      .email-wrapper,
      .email-masthead,
      .email-footer {
        background-color: #333333 !important;
        color: #FFF !important;
      }
      p,
      ul,
      ol,
      blockquote,
      h1,
      h2,
      h3,
      span,
      .purchase_item {
        color: #FFF !important;
      }
      .attributes_content,
      .discount {
        background-color: #222 !important;
      }
      .email-masthead_name {
        text-shadow: none !important;
      }
    }
    
    :root {
      color-scheme: light dark;
      supported-color-schemes: light dark;
    }    
   </style>  
  </head>
  <body>
   <table class="email-wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation">
    <tr>
      <td align="center">
        <table class="email-content" width="100%" cellpadding="0" cellspacing="0" role="presentation">

          <!-- Email Header -->
        
          <!-- Header -->
            <tr>
              <td class="email-masthead" style="padding-top: 34px;padding-bottom: 34px;">
		 <img src="{{ asset(config('constants.default.email_logo')) }}" alt="" title="" />
              </td>
            </tr>

          <!-- Email Body -->
          <tr>
            <td class="email-body" width="100%" cellpadding="0" cellspacing="0">
              <table class="email-body_inner" align="center" width="100%" cellpadding="0" cellspacing="0" role="presentation">

                <!-- Body content -->
                <tr>
                  <td class="content-cell">
                    <div class="f-fallback">
	                @yield('email-content')
                    </div>
                  </td>
                </tr>
              </table>
            </td>
          </tr>

         
         <!-- Email Footer -->
          <tr class="email-footer">
            <td class="content-cell" align="center" style="padding: 20px;">
              <p class="f-fallback sub align-center" style="text-align:center; font-size: 20px;font-weight: 600;margin: 0;color: #fff;">
               © {{ date('Y') }} All Copyrights Reserved By {{config('app.name')}}             
		 </p>
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>
 
  </body>
</html>
