document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('pnw-calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        editable: true,
        selectable: true,
        events: function(fetchInfo, successCallback, failureCallback) {
            jQuery.ajax({
                url: pnw_calendar.ajax_url,
                data: {
                    action: 'pnw_get_events',
                    start: fetchInfo.startStr,
                    end: fetchInfo.endStr,
                    nonce: pnw_calendar.nonce
                },
                success: function(response) {
                    successCallback(response);
                },
                error: function() {
                    failureCallback();
                }
            });
        },
        select: function(info) {
            var title = prompt('Wprowadź tytuł rezerwacji:');
            if (title) {
                calendar.addEvent({
                    title: title,
                    start: info.startStr,
                    end: info.endStr,
                    allDay: info.allDay
                });

                jQuery.ajax({
                    url: pnw_calendar.ajax_url,
                    method: 'POST',
                    data: {
                        action: 'pnw_add_event',
                        title: title,
                        start: info.startStr,
                        end: info.endStr,
                        allDay: info.allDay,
                        nonce: pnw_calendar.nonce
                    },
                    success: function(response) {
                        console.log('Event added:', response);
                    }
                });
            }
            calendar.unselect();
        },
        eventClick: function(info) {
            if (confirm("Czy na pewno chcesz usunąć tę rezerwację?")) {
                info.event.remove();
                jQuery.ajax({
                    url: pnw_calendar.ajax_url,
                    method: 'POST',
                    data: {
                        action: 'pnw_delete_event',
                        id: info.event.id,
                        nonce: pnw_calendar.nonce
                    },
                    success: function(response) {
                        console.log('Event deleted:', response);
                    }
                });
            }
        },
        eventDrop: function(info) {
            jQuery.ajax({
                url: pnw_calendar.ajax_url,
                method: 'POST',
                data: {
                    action: 'pnw_update_event',
                    id: info.event.id,
                    start: info.event.startStr,
                    end: info.event.endStr,
                    nonce: pnw_calendar.nonce
                },
                success: function(response) {
                    console.log('Event updated:', response);
                }
            });
        }
    });
    calendar.render();
});
