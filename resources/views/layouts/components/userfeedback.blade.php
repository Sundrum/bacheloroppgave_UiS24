@if (isset($message) || Session::has('message'))
    <div id="info-message" class="message-g">{{ $message ?? Session::get('message') }}</div>

    <script>
        $("#info-message").fadeTo(5000, 500).slideUp(1000, function() {
            $("#info-message").slideUp(1000);
        });
    </script>
@endif 
@if (isset($errormessage) || Session::has('errormessage'))
    <div id="danger-message" class="message-r">{{ $errormessage ?? Session::get('errormessage')}}</div>

    <script>
        $("#danger-message").fadeTo(5000, 500).slideUp(1000, function() {
            $("#danger-message").slideUp(1000);
        });
    </script>
@endif 
