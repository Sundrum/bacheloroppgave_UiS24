<div class="modal" tabindex="-1" id="sendOrderConfirmation">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-grey">
            <div class="modal-body p-3">
                <h4 class="modal-title text-center">Send mail</h4>
                <div class="card-rounded bg-white">
                    <div class="p-3">
                        <p>Du er på vei til å sende ut mail med følegende emnefelt: "Din vanningssensor fra 7Sense er på vei"</p>
                        <p>Mailen sendes med kopi til: sales@7sense.no og vegard@7sense.no</p>
                    </div>
                </div>
                <form method="POST" id="orderConfirmation" action="/admin/order/confirmation">
                    @csrf
                    <div class="form-group row mt-2">
                        <div class="col-sm-10 offset-sm-1">
                            <span class="mx-5">User</span>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span name="prefixproduct" value="tracking" class="input-group-text bg-7s icon-color h-100">
                                        <i class="fa fa-1x fa-at"></i>
                                    </span>
                                </div>
                                @if(isset($customer->users) && count($customer->users) > 0)

                                    <select name="order_user_id" id="order_user_id">
                                        @foreach ($customer->users as $user)
                                            <option value="{{$user->user_id ?? ''}}">{{$user->user_name ?? ''}}, {{$user->user_email ?? ''}}</option> 
                                        @endforeach
                                    </select>
                                @else
                                    Please connect a user to the customer before trying to send information.
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mt-1">
                        <div class="col-sm-10 offset-sm-1">
                            <span class="mx-5">Tracking information</span>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span name="prefixproduct" value="tracking" class="input-group-text bg-7s icon-color h-100">
                                        <i class="fa fa-1x fa-at"></i>
                                    </span>
                                </div>
                                <input placeholder="Tracking" id="tracking" class="form-control input-login" name="tracking" required>
                            </div>
                        </div>
                    </div>
                    <div class="row offset-1">
                            <button id="closeOrder" type="button" class="btn-7r col-5 text-center" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn-7g col-5 text-center"> Send e-post </button>
                    </div>
                </form>
            </div> 
        </div>
    </div>
</div>

<script>

$( "#orderConfirmation" ).on( "submit", function(e) {
    e.preventDefault();
    var dataString = $(this).serialize() 
    $.ajax({
        type: "POST",
        url: "/admin/order/confirmation",
        data: dataString,
        success: function (data) {
            console.log(data);
            $("#closeOrder").trigger('click');
            const infoMessage = document.createElement('div');
            infoMessage.className = "message-g";
            infoMessage.appendChild(document.createTextNode(data));
            document.getElementById("content-main").appendChild(infoMessage);
            $(".message-g").fadeTo(4000, 0.8).slideUp(500, function() {
                $(".message-g").remove();
            });
        },
        error: function(data) {
            console.log('ERROR ' + data);
            const infoMessage = document.createElement('div');
            infoMessage.className = "message-r";
            infoMessage.appendChild(document.createTextNode("Something went wrong, please try again"));
            document.getElementById("content-main").appendChild(infoMessage);
            $(".message-r").fadeTo(4000, 0.8).slideUp(500, function() {
                $(".message-r").remove();
            });
        }
    });
});

</script>