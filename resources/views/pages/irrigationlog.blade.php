@extends('layouts.app')

@section('content')

<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>

<div class="card">
  <div class="bg-white card-rounded p-3">
    <div id="calendar"></div>
  </div>
</div>
 
<div class="modal fade" id="updateUnit">
  <div class="modal-dialog modal-lg">
    <div class="modal-content bg-a-grey">
      <div class="modal-body">
        <div class="col-12">
          <h2 class="modal-title" id="eventtitle"></h2>
        </div>
        <p class="body" id="starttime"></p>
        <p class="body" id="endtime"></p>
        <button type="button" class="btn-7s" data-dismiss="modal"> Close </button>
      </div>
  </div>
</div>

<script>
  
setTitle('Calendar');
getIrrigationLog();
function getIrrigationLog() {    
  $.ajax({
    url: "/irrigation/run",
    type: 'GET',
    dataType: "json",
    contentType: "application/json; charset=utf-8",
    
    success: function (data) { 
      successMessage('Loading');
      let logs = Array();
      console.log(data[1]);
      for(let i in data) {
        let end
        if(data[i].irrigation_endtime) 
          end = new Date(data[i].irrigation_endtime).toDateString()
        
        else(!data[i].irrigation_endtime)
          end = data[i].irrigation_endtime
        
          logs[i] = {
          title: data[i].irrigation_run_id + ": " + data[i].serialnumber,
          start: new Date(data[i].irrigation_starttime).toDateString(),
          end: new Date(data[i].irrigation_endtime).toDateString(),
          allDay: true,
          color: 'blue',
          textcolor: 'white'
        };
      }
      initCalendar(logs);
      console.log(logs);
    },
    error: function (data) {
        errorMessage('Failed');
    }
  });
}

function initCalendar(irrigationevents) {
    $("#calendar").fullCalendar({
      selectable: true,
      timeFormat: 'h(:mm)t',
      eventClick: function (calEvent, jsEvent, view) {
        document.querySelector('#eventtitle').innerText = calEvent.title;
        document.querySelector('#starttime').innerText = view.start;
        document.querySelector('#endtime').innerText = jsEvent.end;
        $('#updateUnit').modal('show');
      },
      header: {
        left: "month, agendaWeek, agendaDay, list",
        center: "title",
        right: "prev, today, next",
      },
      buttonText: {
        today: "Today",
        month: "Month",
        week: "Week",
        day: "Day",
        list: "List",
      },
      events: irrigationevents
    });
}
</script>

@endsection