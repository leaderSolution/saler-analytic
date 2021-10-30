import { Calendar } from '@fullcalendar/core';
import interactionPlugin from '@fullcalendar/interaction';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import { toMoment } from '@fullcalendar/moment'; // only for formatting
import momentTimezonePlugin from '@fullcalendar/moment-timezone';
import '../styles/app.scss'
import 'jquery-confirm'

document.addEventListener('DOMContentLoaded', function() {
   
  var calendarEl = document.getElementById('calendar')
  fetch('/user/schedule')
  .then(response => response.json())
  .then(data => {
      // Init the calendar
    var calendar = new Calendar(calendarEl, {
        plugins: [ interactionPlugin, dayGridPlugin, timeGridPlugin, listPlugin, momentTimezonePlugin ],
        themeSystem: 'bootstrap',
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
        
        postData(`/edit/${e.event.id}`, { startTime: start.format(), endTime: end.format() })

       });
       // Edit a visit's title
       calendar.on('eventClick', (calEvent, jsEvent, view) => {
           let isChecked =''
           if(calEvent.event.extendedProps.isDone){
               isChecked = 'checked'
           }
           if(! calEvent.event.extendedProps.isDone){
               isChecked = ''
           }
           console.log(calEvent.event.extendedProps.isDone)
        $.confirm({
          title: 'Edit Visit!',
          content: '' +
          '<form action="" class="formName">' +
          '<div class="form-group">' +
          '<label>New Title </label>' +
          '<input type="text" value="'+calEvent.event.title+'" class="title form-control" required />\n' +
          '<div class="form-check">\n' +
              '  <input class="isDone form-check-input" type="checkbox" id="idDone" '+isChecked+'>\n' +
              '  <label class="form-check-label" for="idDone">Done</label>\n' +
          '</div>'+
          '</div>' +
          '</form>',
          buttons: {
              formSubmit: {
                  text: 'Submit',
                  btnClass: 'btn btn-primary',
                  action: function () {
                      let title = this.$content.find('.title').val();
                      let isDone = this.$content.find('.isDone').prop("checked");
                      if(!title){
                          $.alert('provide a valid title');
                          return false;
                      }
                      console.log(isDone)
                     postData(`/edit/${calEvent.event.id}`, {title : title, isDone: isDone})
                      
                  }
              },
              cancel: function () {
                  //close
              },
          },
          onContentReady: function () {
              // bind to events
              let jc = this;
              this.$content.find('form').on('submit', function (e) {
                  // if the user submits the form by pressing enter in the field.
                  e.preventDefault();
                  jc.$$formSubmit.trigger('click'); // reference the button and click it
              });
          }
      });

       });
       

      calendar.render();
  })
  .catch((error) => {
    console.error('Error:', error);
  });

  let postData = (url, data ) => {
    $.post(url, data)
      .done(function (data) {
        $.alert('done !')
          console.log(data)
      })
      .fail(function() {
        $.alert( "error" );
      })
      .always(function() {
        setTimeout(function(){location.reload()}, 2000);
      });
  }

});
