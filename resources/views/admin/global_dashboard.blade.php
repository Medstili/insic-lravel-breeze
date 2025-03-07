
 @extends('layouts.app')
    <!-- Main Content -->
    @section('content')
    
  <style>
      /* Stats Cards */
      .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
      }
      .stat-card {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
      }
      .stat-card:hover {
        transform: translateY(-5px);
      }
      .stat-value {
        color: #2c3e50;
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 8px;
      }
      .stat-trend {
        color: #777;
        font-size: 0.9rem;
      }
      
      /* Charts */
      .charts-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
      }
      .chart-box {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      }
      .chart-header {
        margin-bottom: 20px;
      }
      .chart-header h3 {
        margin: 0;
        font-size: 1.2rem;
        color: #2c3e50;
      }
      
      /* Appointment Table */
      .appointment-table {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      }
      .appointment-table h3 {
        margin-bottom: 15px;
        font-size: 1.2rem;
        color: #2c3e50;
      }
      table {
        width: 100%;
        border-collapse: collapse;
      }
      th {
        background: #2c3e50;
        color: #fff;
        padding: 12px 15px;
        text-align: left;
      }
      td {
        padding: 12px 15px;
        border-bottom: 1px solid #e2e8f0;
        color: #333;
      }
      .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        background: #e0e7ff;
        color: #4f46e5;
        display: inline-block;
      }


    h1 {
      text-align: center;
      color: #333;
      font-size: 40px;
      font-weight: bold;
      margin: 20px;
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
    /* width: calc(100% - 250px); */
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
    padding: 5px;
    text-align: center;
    white-space: nowrap; /* Prevents text from wrapping */
}

.day-header {
    background: #f8f9fa;
    font-weight: bold;
    text-align: left;
    padding-left: 10px;
}

/* .appointment {
    background: #28a745;
    color: white;
    padding: 4px 6px;
    border-radius: 4px;
    cursor: pointer;
    margin: 2px 0;
} */
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

/* event style */

.appointment {
        border: none !important;
        border-radius: 8px !important;
        padding: 5px !important;
        font-weight: 500 !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        box-shadow: 0 3px 6px rgba(0,0,0,0.16) !important;
        position: relative;
        overflow-x: scroll;
        scrollbar-width:none ;
    }

    .appointment::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
    }
    /* cancel */
        .appointment.cancel {
            background: rgba(255, 167, 38, 0.15) !important;
            color:rgb(255, 38, 38) !important;
        }
        .appointment.cancel::before {
            background:rgb(255, 38, 38);
        }

        /* pending  */
        .appointment.pending {
            background: rgba(102, 187, 106, 0.15) !important;
            color:rgb(231, 140, 2) !important;
        }
        .appointment.pending::before {
            background: rgb(231, 140, 2);
        }

        /* passed  */
        .appointment.passed {
            background: rgba(239, 83, 80, 0.15) !important;
            color:rgb(16, 236, 0) !important;
        }
        .appointment.passed::before {
            background: rgb(16, 236, 0) ;
        }
    .cancel .appointment-time,
    .cancel .appointment-title {
      color:rgb(206, 1, 1) !important; 
    
    }

    .pending .appointment-time,
    .pending .appointment-title {
    color:rgb(251, 145, 6) !important; 
    }

    .passed .appointment-time,
    .passed .appointment-title {
    color:rgb(1, 174, 27) !important;
    }

    /* Hover Effects */
    .appointment:hover {
        transform: translateY(-2px) scale(1.02);
        box-shadow: 0 6px 12px rgba(0,0,0,0.25) !important;
    }

    /* Time Styling */
    .appointment-title {
        font-weight: 300;
        opacity: 0.8;
        margin-right: 8px;
        font-size: 12px;
        font-weight: bold;
    }
    .appointment-time{
      font-size: 10px;
    }

  </style>

    <div class="calendar-container">
      <h1>All Coach Appointment Calendar</h1>
      <div class="nav-buttons">
        <a href="{{ route('global_dashboard', ['week' => $prevWeekStart]) }}">&#171; Previous Week</a>
        <span>
          {{ \Carbon\Carbon::parse($currentWeekStart)->format('M d, Y') }} -
          {{ \Carbon\Carbon::parse($currentWeekStart)->addDays(5)->format('M d, Y') }}
        </span>
        <a href="{{ route('global_dashboard', ['week' => $nextWeekStart]) }}">Next Week &#187;</a>
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
                <td colspan="{{ count($timeSlots) + 1 }}" class="day-header text-center bg-secondary text-light">
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
                      
                          <div class="appointment {{ $class }}" onclick="window.location.href='appointment/<?php echo $appointment['id']; ?>'">
                            

                            <div class="">
                              <div class="appointment-title">{{ $appointment['patient'] }}</div>
                              <span class="appointment-time">{{ $appointment['startTime'] }}</span>
                              <span class="appointment-time">{{ $appointment['endTime'] }}</span>
                            </div>
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
  

    @endsection
