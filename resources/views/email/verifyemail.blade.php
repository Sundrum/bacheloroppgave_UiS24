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
                            Use the verifcation code below on: 
                            <a href="https://portal.7sense.no/myaccount" target="blank" style="color: #00265A;">
                                https://portal.7sense.no/myaccount
                            </a>
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
                <td style="background-color: #E5E5E5;padding: 10px 0 10px 0;">
                    <p style="font-style: normal; font-weight:400;font-size:24px; line-height: 25px; color:#00265A;height:40px; margin-left: 150px; margin-right: 150px;">
                        {!! $verify_token !!}
                    </p>
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
                    If you did not request a validation of your email, please ignore this email or contact us at 
                    <a href="mailto:support@7sense.no" style="color:#00265A">support@7sense.no</a> if you have any questions </p>
                </td>
            </tr>
        </table>
        </td>  
    </tr>
@endsection