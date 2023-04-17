@extends('email.layout.mail')

@section('content')
    <tr>
        <td style= "padding-left: 30px; padding-right: 30px;">
            <table width=" 100%" style="border-spacing:0;" role="presentation">  
            <tr>
                <td style="background-color: #E5E5E5;padding: 5px 0 15px 0;">
                    <p style="font-style: normal; font-weight:400;font-size:24px; line-height: 25px; color:#00265A;height:40px; margin-left: 150px; margin-right: 150px;">
                        Verify your email
                    </p> 
                </td>
            </tr>
        </table>
        </td>  
    </tr>
    
    <!-- END Forgot password" -->
    
    <!-- START You recently requested to reset your password  -->
    
    <tr>
        <td style= "padding-left: 30px; padding-right: 30px;">
            <table width=" 100%" style="border-spacing:0;" role="presentation">  
                <tr>
                    <td style="background-color: #E5E5E5;padding: 5px 0 0px 0; ">
                        <p style="font-size: 20px; color:#00265A; margin-left: 150px; margin-right: 150px;">
                            Have you recently been requested to verify your email. 
                            Use the button below to verify you email. The button or link below are valid 1 hour.
                        </p>
                    </td>
                </tr>
            </table>
        </td>  
    </tr>
    
    <!-- END You recently osv -->
    
    <!-- START Knapp Reset password  -->
    <tr>
        <td style= "padding-left: 30px; padding-right: 30px;">
            <table width=" 100%" style="border-spacing:0;"
            role="presentation">  
            <tr>
                <td style="background-color: #E5E5E5;padding: 10px 0 10px 0;text-align: center;">
                    <a rel="noopener" target="_blank" href="https://7sense.no" style="background-color: #00265A; font-size: 16px; font-weight: bold; text-decoration: none; padding: 14px 20px; color: #ffffff; border-radius:50px; display: inline-block; mso-padding-alt: 0;">
                        <!--[if mso]>
                        <i style="letter-spacing: 25px; mso-font-width: -100%; mso-text-raise: 30pt; border-radius:50px;">&nbsp;</i>
                        <![endif]-->
                        <span style="mso-text-raise: 15pt;">Verify Email</span>
                        <!--[if mso]>
                        <i style="letter-spacing: 25px; mso-font-width: -100%;">&nbsp;</i>
                        <![endif]-->
                    </a>
                </td>
            </tr>
        </table>
        </td>  
    </tr>
    
    <!-- END Reset password-->
    
    <!-- START "if you did not request a password reset " -->
    <tr>
        <td style= "padding-left: 30px; padding-right: 30px;">
            <table width=" 100%" style="border-spacing:0;"
            role="presentation">  
            <tr>
                <td style="background-color: #E5E5E5;padding: 10px 0 10px 0;">
                    <p style="font-size: 20px; font-weight: 400; line-height: 24px; margin-left: 150px; margin-right: 150px;">
                    If you did not request a password reset, please ignore this email or contact us at 
                    <a href="mailto:support@7sense.no" style="color:#00265A">support@7sense.no</a> if you have any questions </p>
                </td>
            </tr>
        </table>
        </td>  
    </tr>
    
    <tr>
        <td style= "padding-left: 30px; padding-right: 30px;">
            <table width=" 100%" style="border-spacing:0;"
                role="presentation">  
                <tr>
                    <td style= "padding:0;"> 
                        <table width=" 100%" style="border-spacing:0;"
                        role="presentation">  
                        <tr>
                            <td style="background-color: #E5E5E5;padding: 10px 0 10px 0;text-align: center;">
                                <img src="{{asset('img/troubleshoot.png')}}" alt="Troubleshoot Picture" width:65px; height:65px; border="0">   
                            </td>
                        </tr>
                        </table>
                    </td>  
                </tr>
            </table>
        </td>  
    </tr>

    <tr>
        <td style= "padding-left: 30px; padding-right: 30px;">
            <table width=" 100%" style="border-spacing:0;" role="presentation">  
                <tr>
                    <td style="background-color: #E5E5E5;padding: 10px 0 10px 0; ">
                        <p style="font-size: 20px; color:#00265A; margin-left: 150px; margin-right: 150px;">   
                            If you’re having trouble with the button above, copy and paste the URL below into your web browser.
                        </p>
                    </td>
                </tr>
            </table>
        </td>  
    </tr>

    <tr>
        <td style= "padding-left: 30px; padding-right: 30px;">
            <table width=" 100%" style="border-spacing:0;"
            role="presentation">  
            <tr>
                <td style="background-color: #E5E5E5;padding: 0px 0 20px 0;text-align: center;">
                    <p style="font-weight:400;font-size: 18px;line-height:24px; height:24px;text-decoration: underline; margin-left: 50px; margin-right: 50px;">
                        <a href="http://portal.7sense.no/password/reset/" target="blank" style="color: #00265A;">
                            http://portal.7sense.no/password/reset/esqmdTEPlsm49170dfmhnDpmgFSEdq2
                        </a>
                    </p>
                </td>
            </tr>
        </table>
        </td>  
    </tr>
@endsection