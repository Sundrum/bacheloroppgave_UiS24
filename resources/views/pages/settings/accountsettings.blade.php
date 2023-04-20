<div class="row mt-4 mb-4 justify-content-center">
    <h3 class="text-center">@lang('settings.users')</h3>
</div>

<div class="row">
    <div class="col-12 text-end">
        <button class="btn-7g" onclick="removeValues()" type="button" data-toggle="modal" data-target="#newUser">
            + @lang('settings.newuser')
        </button>
    </div>
</div>

<div id="userslist">
    {{-- Foreach user --}}
    <div class="col-12">
        @foreach ($users as $user)
            <div id="object{{$user->user_id}}" onclick="rotateImg('arrow{{$user->user_id}}')" class="card-rounded bg-white p-3" data-toggle="collapse" data-target="#collapseuser{{$user->user_id}}" aria-expanded="true" aria-controls="collapseuser">
                <input type="hidden" id="user_name{{$user->user_id}}" value="{{$user->user_name}}">
                <input type="hidden" id="user_email{{$user->user_id}}" value="{{$user->user_email}}">
                <input type="hidden" id="user_phone_work{{$user->user_id}}" value="{{$user->user_phone_work}}">
                <input type="hidden" id="roletype_id_ref{{$user->user_id}}" value="{{$user->roletype_id_ref}}">

                <div class="row">
                    <div class="col-10">
                        <div class="row">
                            <h4 class="v-align"><strong>{{$user->user_name ?? ''}}</strong></h4>
                        </div>
                        <div class="row">
                            <span>{{$user->user_email ?? ''}}</span>
                        </div>
                    </div>
                    <div class="col-2 align-self-center text-end">
                        <img id="arrow{{$user->user_id}}" data-toggle="collapse" data-target="#collapseuser{{$user->user_id}}" aria-expanded="true" aria-controls="collapseuser{{$user->user_id}}" src="{{ asset('img/expand.svg') }}">
                    </div>
                </div>
            </div>
            <div id="collapseuser{{$user->user_id}}" class="collapse bg-white col-10 offset-1" aria-labelledby="headingOne" data-parent="#object{{$user->user_id}}">
                <div class="row justify-content-center">
                    <div class="col text-center">
                        <button type="button" id="editUserDetails" onclick="userEdit({{$user->user_id}});" class="btn btn-primary card-rounded m-3" data-toggle="modal" data-target="#newUser">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                                <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"></path>
                            </svg>
                            @lang('settings.edituser')
                        </button>
                    </div>
                </div>
                <div class="m-1">
                    <div class="col-12 mt-4">
                        @foreach($user->units as $unit)
                            <div class="row m-1 card card-rounded" id="access{{$unit->sensoraccess_id}}">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-12 col-md-6 col-lg-5">
                                            {{$unit->serialnumber ?? ''}} -> {{$unit->sensorunit_location ?? ''}}
                                        </div>
                                        <div class="col-12 col-md-6 col-lg-5">
                                            @if($unit->changeallowed == '1')
                                                @lang('settings.writeandread')
                                            @else 
                                                @lang('settings.readyonly')
                                            @endif
                                        </div>
                                        <div class="col-12 col-lg-2">
                                            <button type="button" class="btn btn-outline-danger card-rounded" onclick="deleteAccess({{$unit->sensoraccess_id}})">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"></path>
                                                    <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"></path>
                                                </svg>
                                                @lang('settings.deleteaccess')
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col text-center">
                        <button type="button" class="btn btn-outline-danger card-rounded m-3" onclick="deleteUser({{$user->user_id}})">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"></path>
                                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"></path>
                            </svg>
                            @lang('settings.deleteuser')
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@include('pages.settings.newuser')

<script>
function userEdit(id){
    $('#user_email').val($('#user_email'+id).val());
    $('#user_name').val($('#user_name'+id).val());
    $('#user_phone_work').val($('#user_phone_work'+id).val());
    $('#user_id').val(id);
    $('#roletype_id_ref').val($('#roletype_id_ref'+id).val()).change();
}

function removeValues(){
    $('#user_email').val(null);
    $('#user_name').val(null);
    $('#user_phone_work').val(null);
    $('#user_id').val(null);
    $('#roletype_id_ref').val(1).change();
}

function rotateImg(obj) {
    img = document.getElementById(obj);
    if (img.style.cssText) img.style.cssText = "";
    else img.style.cssText = "transform: rotate(-90deg);";
}

function deleteUser(id) {
    console.log(id);
    var confirmed = confirm('Er du sikker på at du ønsker å slette denne brukeren?');
    if (confirmed) {
        $.ajax({
            url: "/customeradmin/user/delete",
            type: 'POST',
            data: { 
                "id": id,
                "_token": token,
            },
            success: function(data) {
                console.log(data);
                $('#object'+id).remove();
                $('#collapseuser'+id).remove();
            },   
            error: function(msg) {
                console.log(msg);
                alert("Failed - Please try again")
            }
        });

    }
}

function deleteAccess(id) {
    console.log(id);
    var confirmed = confirm('Er du sikker på at du ønsker å fjerne tilgangen for av denne sensorenheten for denne brukeren?');
    if (confirmed) {
        $.ajax({
            url: "/customeradmin/access/delete",
            type: 'POST',
            data: { 
                "id": id,
                "_token": token,
            },
            success: function(data) {
                console.log(data);
                $('#access'+id).remove();
            },   
            error: function(msg) {
                console.log(msg);
                alert("Failed - Please try again")
            }
        });

    }
}

</script>