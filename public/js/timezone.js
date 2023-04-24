var timezone = moment.tz.guess();
$.ajax({
    url: "/timezone",
    type: 'POST',
    data: { 
        "timezone": timezone,
        "_token": token,
    },
    success: function(msg) {
        // console.log(msg);
    },   
    error:function(msg) {
        console.log("Not able to determine local timezone");
    }
});