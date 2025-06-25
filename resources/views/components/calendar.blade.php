<div>
    <div id='calendar'></div>
</div>
@assets
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endassets

@script
    <script> 
        $(document).ready(function(){
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: @json($events),
            dayMaxEventRows: true,
            moreLinkClick: 'popover',
            moreLinkClassNames: 'calendar-more-link-btn',
            // eventDidMount: function(info) {
            //     var eventElement = info.el;
            //     var event = info.event;
            //     var popoverContent = `
            //         <div><strong>${event.title}</strong></div>
            //         <div>${event.extendedProps.description || ''}</div>
            //     `;
            //     eventElement.setAttribute('data-bs-toggle', 'popover');
            //     eventElement.setAttribute('data-bs-content', popoverContent);
            //     eventElement.setAttribute('data-bs-html', 'true');
            //     var popover = new bootstrap.Popover(eventElement, {
            //         trigger: 'hover',
            //         placement: 'top',
            //     });
            // },
        });
        calendar.render();
        });
    </script>
@endscript