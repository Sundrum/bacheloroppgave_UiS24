<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta name="color-scheme" content="light dark">     <!-- meta tags for dark mode, line 11 is the import url for Font family Lato if we will use that one -->         
   <meta name="supported-color-schemes" content="light dark">
	<title> HTML Email 7Sense </title>
   <style type="text/css">                                      
        @import url('https://fonts.cdnfonts.com/css/poppins');
        body {
         font-family: 'Poppins', sans-serif;       
        }
        table{
        border-spacing: 0;
         }
         td {
            padding:0
         }
         p {
            font-size:15px;
         }
         img {
            border:0;
         }
		@media screen and (max-width: 599.98px) {       

         }
         @media screen and (max-width: 399.98px) {

         }

        /* media Query dark mode specific CSS */
        :root {
         color-scheme: light dark;
         supported-color-schemes: light dark;
        }

      @media (prefers-color-scheme: dark) {          /* this is for changing the color of the body of the template in da*/
         body,center {
            background: #2d2d2d!important;
         }

      }
   
	</style>

     <!--[if (gte mso 9)|(IE)]>
      <style type="text/css">
        table{border-collapse:collapse!important;}
      </style>
   <![endif]-->

</head>
<body style="Margin:0;padding:0;min-width:100%;"> 
        <!-- because of Outlook, must be Uppercase M in margin
        Next is for Outlook conditional CSS statements or comments telling Outlook how we want the email to show,
        including simple aspects with design like bgcolor og font family.
        Then using ghost tables if (gte etc.) for responsive html not collapsing, only for Outlook --> 


   <!--[if (gte mso 9)|(IE)]>
      <style type="text/css">
         body, table, td, p, a {font-family: sans-serif, Arial, Helvetica!important;}
      </style>
   <![endif]-->

   <center style="width: 100%;table-layout: fixed;">
      <div style="max-width:600px;background-color:#E5E5E5">

          <div style="font-size: 0px;color: #E5E5E5;line-height: 1px;
          mso-line-height-rule:exactly; display: none;max-width:0px;
          max-height: 0px;opacity: 0;overflow: hidden;mso-hide:all">

          <!-- the code above is to prevent that the text will never appear in the body of the email
          the color must be the same as the background. The center tag is used for html-emails it is supported and recommended. But not for websites.-->

        <!--START Preheader TEXT-->  
          7Sense Portal
         </div>
        <!-- END Preheader text-->


        <!--[if (gte mso 9)|(IE)]>
            <table width="600" align="center" style="border-spacing:0;
             color:#00265A;">
             <tr>
             <td style="padding:0;">
               

        <![endif]-->

<!-- START real table here-->

        <table align="center" style="border-spacing:0;
        color:#00265A; background-color: #E5E5E5;Margin:0; padding:0;width: 100%;max-width: fit-content
        600px;" role="presentation">

<!--role presentation for those with screenreaders, focuses on the content istead of the table tags-->


<!-- START 7 Sense logoen -->

<tr>
   <td style= "padding-left: 30px; padding-right: 30px;">
      <table width="100%" style="border-spacing: 0;" role="presentation">
         <tr>
            <td style="background-color: #E5E5E5;padding:20px 0 20px 0; text-align: center;">
               <img src="{{asset('img/7sense-logo.png')}}" alt="7Sense logo" style="width:240px; height: 103px; top: 24px;" border="0">     <!--border 0 er for Lotus Notes-->
            </td>
         </tr>
      </table>
   </td>
</tr>

<!-- END 7 Sense logoen-->

@yield('content')
<!--START Forgot password? -->



<!-- END link til portal 7Sense   -->


<!-- START FOOTER copyright-->
<tr>
   <td style="padding-left: 30px; padding-right: 30px; background-color: #00265A;">
      <table width="100%" style="border-spacing: 0;" role="presentation">

         <tr>
            <td style="padding-top: 15px; padding-bottom: 15px;">
               <p style="font-size: 16px; padding-bottom: 5px;color: #ffffff;text-align: center;">7Sense Agritech AS</p>
               <p style="mso-line-height-rule:exactly; line-height: 13px; padding-bottom: 0px; font-size: 14px;color:#ffffff;text-align: center;">Moloveien 14</p>
               <p style="mso-line-height-rule:exactly; line-height: 13px; font-size: 14px;color:#ffffff;text-align: center;">3187 Horten </p>
               <p style="mso-line-height-rule:exactly; line-height: 13px;font-size: 14px;color:#ffffff;text-align: center;">Norway</p>

            </td>
         </tr>
      </table>
   </td>
</tr>

<!-- END FOOTER copyright -->

         </table> 

<!-- next  comes the ghost table with closing tags-->

                 <!--[if (gte mso 9)|(IE)]>
                 </td>
                 </tr>
                 </table>  
        <![endif]-->
      </div>
   </center>

</body>
</html>






