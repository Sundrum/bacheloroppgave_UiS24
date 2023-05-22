@extends('email.layout.mail')

@section('content')
    <tr>
        <td style= "padding-left: 10px; padding-right: 10px;">
            <table width=" 100%" style="border-spacing:0;" role="presentation">  
                <tr>
                    <td style="background-color: #E5E5E5;padding: 0 0 5px 0;">
                        <p style="font-size: 20px; color:#00265A; margin-left: 50px; margin-right: 50px;">
                            Hei {!! $name !!},
                        </p>
                    </td>
                </tr>
            </table>
        </td>  
    </tr>
    <tr>
        <td style= "padding-left: 10px; padding-right: 10px;">
            <table width=" 100%" style="border-spacing:0;" role="presentation">  
                <tr>
                    <td style="background-color: #E5E5E5;padding: 0; ">
                        <h2 style ="color:#00265A; margin-left: 50px; margin-right: 50px; height:10px;">BESTILLINGEN ER PÅ VEI!</h2>
                        <p style="font-size: 20px; color:#00265A; margin-left: 50px; margin-right: 50px;">
                            Din bestilling er nå sendt fra vårt lager, og vil bli levert så raskt som mulig.
                            Du kan følge <a href="https://www.postnord.no/pakkesporing">forsendelsen </a>, lim inn sporingsnummer på nettsiden til PostNord: {!! $tracking !!}
                        </p>
                    </td>
                </tr>
            </table>
        </td>  
    </tr>
    <tr>
        <td style= "padding-left: 10px; padding-right: 10px;">
            <table width=" 100%" style="border-spacing:0;" role="presentation">  
                <tr>
                    <td style="background-color: #E5E5E5;padding: 5px 0 0px 0; ">
                        <h2 style ="color:#00265A; margin-left: 50px; margin-right: 50px; height:10px;">GJØR DEG KLAR TIL Å BRUKE SENSOREN</h2>

                        <p style="font-size: 20px; color:#00265A; margin-left: 50px; margin-right: 50px;">
                            Vi anbefaler at du benytter ventetiden til å gjøre deg klar 😊 
                            <br>
                            For best utnyttelse på mobile enheter anbefaler vi at du laster ned appen vår «7Sense» fra App Store eller Google Play.  
                            <br>
                            Du kan også følge finne den samme informasjonen i portalen vår: <a href="https://portal.7sense.no/">https://portal.7sense.no/ </a>
                            <br>
                            <br>
                            Innloggingsdetaljer til både app og portal: 
                            <br>
                            Brukernavn: {!!$email!!}
                            <br>
                            Passord: {!!$password !!}
                            <br>
                            <br>
                            Du må gjerne endre passordet så snart du er logget inn. Dette gjør du ved å velge “Endre passord” under Min konto 
                        </p>
                    </td>
                </tr>
            </table>
        </td>  
    </tr>
    <tr>
        <td style= "padding-left: 10px; padding-right: 10px;">
            <table width=" 100%" style="border-spacing:0;" role="presentation">  
                <tr>
                    <td style="background-color: #E5E5E5;padding: 0px 0 0px 0; ">
                        <h2 style ="color:#00265A; margin-left: 50px; margin-right: 50px; height:10px;">NÅR SENSOREN KOMMER</h2>

                        <p style="font-size: 20px; color:#00265A; margin-left: 50px; margin-right: 50px;">
                            Her har vi samlet litt nyttig informasjon om hvordan du raskt kommer i gang med å bruke sensoren: <a href="https://7sense.no/no/irrigation-sensor-oppstart/">https://7sense.no/no/irrigation-sensor-oppstart/</a>
                            <br>
                            Du kan også se en enkel film om hvordan den brukes her:  <a href="https://player.vimeo.com/video/346188730"> https://player.vimeo.com/video/346188730 </a>
                        </p>
                    </td>
                </tr>
            </table>
        </td>  
    </tr>
    <tr>
        <td style= "padding-left: 10px; padding-right: 10px;">
            <table width=" 100%" style="border-spacing:0;" role="presentation">  
                <tr>
                    <td style="background-color: #E5E5E5;padding: 0px 0 0px 0; ">
                        <h2 style ="color:#00265A; margin-left: 50px; margin-right: 50px; height:10px;">LURER DU PÅ NOE?</h2>
                        <p style="font-size: 20px; color:#00265A; margin-left: 50px; margin-right: 50px;">
                            Send oss en e-post på <a href="mailto:support@7sense.no">support@7sense.no</a> eller ring 33084400 
                            <br>
                            Lykke til!
                            <br><br>
                            Med vennlig hilsen
                            <br>
                            Max J. Tangen og alle oss i 7Sense
                        </p>
                    </td>
                </tr>
            </table>
        </td>  
    </tr>
@endsection