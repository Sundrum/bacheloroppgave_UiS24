<div class="card">
    <div class="card-header">
        <div class="float-right">
            <label class="switch">
            <input type="checkbox" data-toggle="collapse" data-target="#collapseSettings" onclick="enableSortable()" id="enableSorting">
                <span class="slider round"></span>
            </label>
        </div>
        <h5>@lang('dashboard.groupenable')</h5>
    </div>
    <div class="collapse" id="collapseSettings">
        <div class="card-body">
            <p>@lang('dashboard.groupinfo')</p>
            <button  class="btn btn-secondary float-right"
                data-toggle="modal" data-target="#changeGroup">@lang('dashboard.changegroup')
            </button>
            <button class="btn btn-primary float-right mb-3"
                style="margin-right: 5px;"
                data-toggle="modal" data-target="#addGroup">@lang('dashboard.addgroup')
            </button>
            <br>
        </div>
    </div>
</div>

<script type="text/javascript">
    
    function enableSortable() {
        var numberOfGroups = getNumberOfGroups();
        var deleteButtons = document.getElementsByClassName("deleteGroup");
        var state = document.getElementById("enableSorting");
        if (state.checked) {
            for (i = 0; i < numberOfGroups; i++) {
                deleteButtons.item(i).style.display = "block";
            }
            sortingState = true;
            sorting(sortingState);
        } else {
            for (i = 0; i < numberOfGroups; i++) {
                deleteButtons.item(i).style.display = "none";
            }
            sortingState = false;
            sorting(sortingState);
            setTimeout(location.reload.bind(location), 25);
        }        
    }
</script>