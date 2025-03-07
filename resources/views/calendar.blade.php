<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <title>Appointment Calendar</title>
  <style>
    h1 {
      text-align: center;
      color: #333;
    }

    .nav-buttons {
      text-align: center;
      margin-bottom: 10px;
      font-size: 18px;
    }
    .nav-buttons a {
      text-decoration: none;
      color: #2c3e50;
      margin: 0 15px;
      font-weight: bold;
    }
    .calendar-container {
    width: calc(100% - 250px);
    max-width: 1200px;
    margin: auto;
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.table-container {
    height: 600px; /* Fixed height */
    overflow-y: auto; /* Enables vertical scrolling */
    overflow-x: auto; /* Enables horizontal scrolling */
    border: 1px solid #ddd;
    display: block;
}

table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed; /* Ensures proper column sizing */
}

thead th {
    position: sticky;
    top: 0;
    background: #2c3e50;
    color: white;
    padding: 10px;
    z-index: 2;
}

th, td {
    border: 1px solid #ccc;
    padding: 10px;
    text-align: center;
    white-space: nowrap; /* Prevents text from wrapping */
}

.day-header {
    background: #f8f9fa;
    font-weight: bold;
    text-align: left;
    padding-left: 10px;
}

.appointment {
    background: #28a745;
    color: white;
    padding: 4px 6px;
    border-radius: 4px;
    cursor: pointer;
    margin: 2px 0;
}
.cancel{
    background:rgb(189, 42, 57);
    color: white;
}
.pending {
    background-color:rgb(210, 129, 14);
    color:  white;
}
.passed{
    background-color:rgb(8, 152, 15);
    color: white;
}



  </style>

  <div class="calendar-container">
    <h1>Appointment Calendar</h1>
    <div class="nav-buttons">
      <a href="{{ route('calendar.calendar', ['week' => $prevWeekStart]) }}">&#171; Previous Week</a>
      <span>
        {{ \Carbon\Carbon::parse($currentWeekStart)->format('M d, Y') }} -
        {{ \Carbon\Carbon::parse($currentWeekStart)->addDays(5)->format('M d, Y') }}
      </span>
      <a href="{{ route('calendar.calendar', ['week' => $nextWeekStart]) }}">Next Week &#187;</a>
    </div>
    <div class="table-container">
      <table>
        <thead>
          <tr>
            <th>Day / Coach</th>
            @foreach ($timeSlots as $slot)
              <th>{{ $slot['start'] }} - {{ $slot['end'] }}</th>
            @endforeach
          </tr>
        </thead>
        <tbody>
          @foreach ($days as $day)
            <tr>
              <td colspan="{{ count($timeSlots) + 1 }}" class="day-header">
                {{ \Carbon\Carbon::parse($day)->format('l, M d, Y') }}
              </td>
            </tr>
            @foreach ($coaches as $coach)
              <tr>
                <td class="text-wrap">{{ $coach->full_name }}</td>
                @foreach ($timeSlots as $slot)
                  <td>
                    @php
                      $slotStart = strtotime($day . ' ' . $slot['start']);
                      $slotEnd   = strtotime($day . ' ' . $slot['end']);
                      $cellAppointments = [];
                      if(isset($organizedAppointments[$day][$coach->id])) {
                          foreach ($organizedAppointments[$day][$coach->id] as $appointment) {
                              $apptStart = strtotime($day . ' ' . $appointment['startTime']);
                              if ($apptStart >= $slotStart && $apptStart < $slotEnd) {
                                  $cellAppointments[] = $appointment;
                              }
                          }
                      }
                    @endphp
                    @if(count($cellAppointments) > 0)
                      @foreach ($cellAppointments as $appointment)
                        @php
                         $class ='';
                         if($appointment['status'] == 'passed'){
                         $class = 'passed';
                         }
                         elseif ($appointment['status'] == 'cancel'){
                         $class = 'cancel';
                         }
                         elseif ($appointment['status'] == 'pending'){
                         $class = 'pending';
                         }
                     
                        @endphp
                     
                        <div class="appointment {{ $class }}" onclick="{{ route('appointment_details',$appointment['id']) }}">
                          {{ $appointment['patient'] }}
                        </div>
                      @endforeach
                    @else
                      &ndash;
                    @endif
                  </td>
                @endforeach
              </tr>
            @endforeach
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>
