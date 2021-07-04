import { Calendar } from '@fullcalendar/core';
import interactionPlugin from '@fullcalendar/interaction';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import { toMoment } from '@fullcalendar/moment'; // only for formatting
import momentTimezonePlugin from '@fullcalendar/moment-timezone';
import '../styles/app.scss'

document.addEventListener('DOMContentLoaded', function() {
  
  var calendarEl = document.getElementById('calendar')
  fetch('/user/schedule')
  .then(response => response.json())
  .then(data => {
      // Init the calendar
    var calendar = new Calendar(calendarEl, {
        plugins: [ interactionPlugin, dayGridPlugin, timeGridPlugin, listPlugin, momentTimezonePlugin ],
        headerToolbar: {
          left: 'prev,next today',
          center: 'title',
          right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        navLinks: true, // can click day/week names to navigate views
        editable: true,
        dayMaxEvents: true, // allow "more" link when too many events
        events: data  ,
      });
      // Edit a visit
      calendar.on('eventChange', (e) => {
        
        let start = toMoment(e.event.start, calendar)
        let end = toMoment(e.event.end, calendar)
        console.log(start.format())
        console.log(end.format())
          $.post(`/edit/${e.event.id}`, { startTime: start.format(), endTime: end.format() })
                .done(function (data) {
                  console.log(data)
            })
              .fail(function() {
                console.log( "error" );
            })
            .always(function() {
                //window.location.reload(true);
            });
        // xhr.open('PATCH', url)
        // xhr.setRequestHeader("Content-Type", "application/merge-patch+json");
        
        
        
        // xhr.send(JSON.stringify(visit))
       });
       // Edit a visit's title
       calendar.on('eventClick', (calEvent, jsEvent, view) => {
        let xhr = new XMLHttpRequest
        let url = `/api/visits/commercial/edit/${calEvent.event.id}`
       
        var title = prompt('Event Title:', calEvent.title,{ buttons: { Ok: true, Cancel: false} });
        
        if (title){
            calEvent.title = title;
            let visitTitle = {
              'title' : title,
            }
          xhr.open('PATCH', url)
          xhr.setRequestHeader("Content-Type", "application/merge-patch+json");
          
          xhr.send(JSON.stringify(visitTitle))
        }else {
          console.log(calEvent.jsEvent.toElement)
        var i = document.createElement('i');
        i.className = 'bi bi-calendar-x';
       calEvent.jsEvent.toElement.append(i);
        }
        

       });
       

      calendar.render();
  })
  .catch((error) => {
    console.error('Error:', error);
  });

  
  

  
});