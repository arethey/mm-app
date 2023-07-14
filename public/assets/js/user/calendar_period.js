// $(function () {
//     var curYear = moment().format('YYYY');
//     var curMonth = moment().format('MM');

//     $.ajax({
//         url: '../user/calendar/menstruation-periods',
//         type: 'GET',
//         dataType: 'json',
//         success: function (data) {

//             if (data.menstruation_period_list.length !== 0) {
//                 var event_arr = [];
//                 for (var i = 0; i < data.menstruation_period_list.length; i++) {
//                     var event = {
//                         id: data.menstruation_period_list[i].id,
//                         title: i == 0 ? 'Last Menstruation Period' : 'Previous Menstruation Period',
//                         description: 'Your recorded period: ' + data.menstruation_period_list[i].menstruation_date,
//                         start: data.menstruation_period_list[i].menstruation_date
//                     };
//                     event_arr.push(event);
//                 }

//                 var events = {
//                     backgroundColor: 'rgba(91,71,251,.2)',
//                     borderColor: '#5b47fb',
//                     events: event_arr
//                 }

//                 var estimated_period = {
//                     backgroundColor: 'rgba(253,126,20,.25)',
//                     borderColor: '#fd7e14',
//                     events: [{
//                         title: 'Estimated Next Period',
//                         start: data.estimated_next_period
//                     }]
//                 }

//                 $('#fullcalendar').fullCalendar({
//                     header: {
//                         left: 'prev,today,next',
//                         center: 'title',
//                         right: 'month,listMonth'
//                     },
//                     editable: false,
//                     droppable: false, // this allows things to be dropped onto the calendar
//                     dragRevertDuration: 0,
//                     defaultView: 'month',
//                     eventLimit: true, // allow "more" link when too many events
//                     eventSources: [events, estimated_period],
//                     eventClick: function (event, jsEvent, view) {
//                         $('#modalTitle1').html(event.title);
//                         $('#modalBody1').html(event.description);
//                         $('#fullCalModal').modal();
//                     },
//                     dayClick: function (date, jsEvent, view) {
//                         $("#createEventModal").modal("show");
//                     },
                    
//                 });
//             }
//             else {
//                 $('#calendar_card').addClass('hidden');

//                 $('#no_record').append('\
//                     <div class="alert alert-warning" role="alert">\
//                         <h4 h4 class= "alert-heading" > No record found!</h4>\
//                         <p>It seems you don\'t have any menstrual records yet. To submit a record, go to "<strong>Menstrual Data</strong>" on the sidebar or you can just simply click "<strong>Add New Menstruation Period</strong>" button on the upper right side of the dashboard.</p>\
//                     </div>\
//                 ');
//             }

//         },
//         error: function (data) {
//             console.log(data);
//         }
//     });

//     var isEventOverDiv = function (x, y) {
//         var external_events = $('#external-events');
//         var offset = external_events.offset();
//         offset.right = external_events.width() + offset.left;
//         offset.bottom = external_events.height() + offset.top;

//         // Compare
//         if (x >= offset.left
//             && y >= offset.top
//             && x <= offset.right
//             && y <= offset.bottom) { return true; }
//         return false;
//     }
// });