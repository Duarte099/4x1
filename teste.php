<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <title>Calendário - Semantic UI</title>
  <link href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.print.min.css' rel='stylesheet' media='print' />
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.css'>
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.7/semantic.min.css'>
  <style>
    /* Esconder a coluna do domingo */
    .fc-day-header[data-date="0"],
    .fc-day[data-date$="-0"] {
      display: none;
    }
  </style>
</head>
<body>
  <div class="ui container">
    <div class="ui grid">
      <div class="ui sixteen column">
        <div id="calendar"></div>
      </div>
    </div>
  </div>

  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js'></script>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.js'></script>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/locale/pt.js'></script>
  <script>
    $(document).ready(function() {
      $('#calendar').fullCalendar({
        header: {
          left: 'prev,next today',
          center: 'title',
          right: 'month,agendaWeek,agendaDay'
        },
        locale: 'pt', // Definir idioma para português
        firstDay: 1, // Iniciar a semana na segunda-feira
        hiddenDays: [0], // Esconder os domingos
        navLinks: true,
        editable: true,
        eventLimit: true,
        events: [
          // { title: 'Evento o dia todo', start: '2024-03-01' },
          // { title: 'Evento longo', start: '2024-03-07', end: '2024-03-10' },
          // { title: 'Reunião', start: '2024-03-12T10:30:00', end: '2024-03-12T12:30:00' },
          // { title: 'Almoço', start: '2024-03-12T12:00:00' },
          // { title: 'Festa de aniversário', start: '2024-03-13T07:00:00' }
        ]
      });
    });
  </script>
</body>
</html>