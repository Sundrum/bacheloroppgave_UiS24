@extends('layouts.app')

@section('content')

<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>

<div class="">
  <div class="bg-white card-rounded p-3">
    <div id="calendar"></div>
  </div>
</div>
    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">
              &times;
            </button>
            <h4 class="modal-title">Create Event</h4>
          </div>
          <div class="modal-body">
            <input type="text" class="form-control" />
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">
              Close
            </button>
          </div>
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
      success: function (data) {
        let irrigationlog;
        for(let i in data) {
          console.log(data[i]);
          // irrigationlog[i] = {title: data[i].serialnumber, start: data[i].irrigation_starttime, end: data[i].irrigation_endtime};
        }

        initCalendar(irrigationlog);
      },
      error: function (data) {
        errorMessage('Failed');
      }
    })
  };

  function initCalendar() {
      $("#calendar").fullCalendar({
        selectable: true,
        selectHelper: true,
        select: function () {
          $("#myModal").modal("toggle");
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
        events: [
          {
            title: "Vanning",
            start: "2023-06-03T09:00",
            end: "2023-06-07T10:00",
            color: "blue",
            textColor: "white",
          },
          {
            title: "Vanning",
            start: "2023-04-18T13:00",
            end: "2023-04-22T15:00",
            color: "blue",
            textColor: "white",
          },
        ],
        // dayRender: function (date, cell) {
        //   let newDate = $.fullCalendar.formatDate(date, "DD-MM-YYYY");
        //   if (newDate == "XX-XX-XXXX") {
        //     cell.css("background", "lightgrey");
        //   } else if (newDate == "20-04-2023") {
        //     cell.css("background", "grey");
        //   }
        // },
      });
  }
</script>

@endsection