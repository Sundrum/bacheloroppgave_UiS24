<div id ="newUser" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">@lang('settings.user')</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form method="POST" name="userupdate" id="userupdate" action="/customeradmin/user/update">
                @csrf
                <div class="row justify-content-center">
                    <div class="col-11">
                        <div id="message"></div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="user_email" class="col-md-4 col-form-label">{{ __('E-post') }}</label>
                    <div class="col-md-8">
                        <input id="user_email" type="email" class="form-control @error('email') is-invalid @enderror" name="user_email" value="" placeholder="Brukernavn / E-post" required autocomplete="email" autofocus>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="user_name" class="col-md-4 col-form-label">{{ __('Navn') }}</label>
                    <div class="input-group col-md-8">
                        <div class="input-group">
                            <input type="text" class="form-control" id="user_name" name="user_name" placeholder="Fullt navn" value="" required>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="user_phone_work" class="col-md-4 col-form-label">{{ __('Telefon') }}</label>
                    <div class="input-group col-md-8">
                        <div class="input-group">
                            <input type="tel" class="form-control" id="user_phone_work" name="user_phone_work" placeholder="+47 000 00 000" value="" required>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <label for="roletype_id_ref" class="col-md-4 col-form-label">Brukertype</label>
                    <div class="input-group col-md-8">
                        <div class="input-group">
                            <select class="custom-select col-md-12 form-control" id="roletype_id_ref" name="roletype_id_ref" required>
                                <option value="1">Lese tilgang</option>
                                <option value="5">Skrive og lese tilgang</option>
                            </select>
                        </div>
                    </div>
                    <span class="text-muted col-11">
                        Velg om brukeren skal ha rettigheter til å endre innstillinger eller kun se sensorenhetene.
                    </span>
                </div>
                <hr>
                <div class="form-group row">
                    <label for="user_password" class="col-md-4 col-form-label">{{ __('Password') }}</label>
                    <div class="input-group col-md-8">
                        <div class="input-group">
                            <input type="text" class="form-control" id="user_password" name="user_password" minlength="8" placeholder="Passord">
                        </div>
                    </div>
                </div>
                <input type="hidden" id="user_id" name="user_id" value="">
                <input type="hidden" name="customernumber" value="{{Session::get('customernumber')}}">
                <div class="row justify-content-center">
                    <div class="col-4">
                        <button type="submit" id="userform" class="btn btn-success card-rounded"> Lagre </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$( "#userupdate" ).on( "submit", function(e) {
    e.preventDefault();
    var dataString = $(this).serialize() 
    $.ajax({
        type: "POST",
        url: "/customeradmin/user/update",
        data: dataString,
        success: function (msg) {
            console.log(msg);
            if (msg == 1) {
                $('#message').html('<div id="success-alert" class="alert alert-success fade show text-center" role="alert"><p>Oppdatert</p></div>');
                $("#success-alert").fadeTo(2000, 500).slideUp(500, function() {
                    $("#success-alert").slideUp(500);
                    location.href='https://portal.7sense.no/settings/2';
                });
            } else if (msg == 2) {
                $('#message').html('<div id="success-alert" class="alert alert-success fade show text-center" role="alert"><p>Oppdatert</p></div>');
                $("#success-alert").fadeTo(2000, 500).slideUp(500, function() {
                    $("#success-alert").slideUp(500);
                    location.href='https://portal.7sense.no/settings/2';
                });
            } else {
                $('#message').html('<div id="danger-alert" class="alert alert-danger fade show text-center" role="alert"><p>Noe gikk galt!</p></div>');
                $("#danger-alert").fadeTo(2000, 500).slideUp(500, function() {
                    $("#danger-alert").slideUp(500);
                });
            }
        }
    });
});
</script>