<div class="modal" id="updateUnit">
    <div class="modal-dialog modal-lg">
      <div class="modal-content bg-a-grey">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title text-center">Add a sensorunit</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body mt-3 mb-3">
            <form method="POST" action="/admin/sensorunit/updatecustomer">
                @csrf
                <input type="hidden" name="customer_id_ref" value="{{$customer->customer_id}}">
                <div class="row justify-content-center">
                    <div id="sensorTable" class="input-group col">
                        
                    </div>

                </div>
                <div class="row mb-2">
                    <div id="customerinfo" class="input-group col">
                        
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary-filled"> Upload </button>
                    </div>
                </div>
            </form>
        </div> 
    </div>
</div>

<script>
var code2 = '<option disabled selected value="">Choose Serialnumber</option>';
getData();

function getData(){
    $.ajax({
        url: '/admin/sensorunitall',
        dataType: 'json',      
        success: function(data) {
            var code = '<select class="seriallist" onchange="checkCustomer()" id="sensorunit_id" name="sensorunit_id">';
            for (var i in data) {
                code2 += '<option value="'+data[i][1].trim()+'">'+data[i][0].trim()+'</option>';
            }
            code += code2;
            code += '</select>';
            document.getElementById('sensorTable').innerHTML = code;
            makeSearchable();

        }
    });
}

function checkCustomer() {
    var unit = document.getElementById('sensorunit_id').value;
    console.log(unit + 'Unit');
    $.ajax({
        url: '/admin/customerunit/'+unit,
        dataType: 'json',      
        success: function(data) {
            document.getElementById('customerinfo').innerHTML = data;
        },
        error: function(data) {
            console.log(data);
        }
    });
}

function makeSearchable(){
    $('.seriallist').select2();
}

</script>