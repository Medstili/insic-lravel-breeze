@extends('layouts.app')
@section('content')
<div class="coach-profile">
    <!-- Coach Header -->
    <div class="coach-header">
        <div class="coach-info">
            <img src="https://i.pravatar.cc/150" alt="Coach" class="coach-avatar">
            <div class="coach-details">
                <h1>{{$coach->full_name}}</h1>
                <div class="coach-tags">
                    <span class="tag speciality">{{$coach->speciality->name}} Coach</span>
                    <span class="tag availability {{$coach->is_available ? 'available' : 'busy'}}">
                        {{$coach->is_available == 1 ? 'Available' : 'Busy'}}
                    </span>
                </div>
                <div class="coach-contact">
                    <p><i class="fas fa-envelope"></i> {{$coach->email}}</p>
                    <p><i class="fas fa-phone"></i> {{$coach->phone}}</p>
                </div>
            </div>
        </div>
        <form action="{{ route('user.edit',$coach->id) }}" method="get">
            @csrf
            <button class="action-btn" >
            <i class="fas fa-edit"></i> Edit Coach Profile
        </button>
        </form>
       
    </div>
        <!-- Calendars Switcher -->
    <div class="calendar-switcher mb-4">
        <button class="action-btn" id="appointments_id" onclick="switchCalendars('appointments-calendar')">
            <i class="fas fa-calendar-check"></i> Appointments Calendar
        </button>
        <button class="action-btn" id="availabilities_id" onclick="switchCalendars('availabilities-calendar')">
            <i class="fas fa-users"></i> Disponibilities Calendar
        </button>
    </div>
    <!-- Calendar Section -->
    <div class="calendar-container availabilities-calendar">
        <div id="availabilities-calendar"></div>
    </div>  
    <div class="calendar-container appointments-calendar">
        <div id="appointments-calendar"></div>
    </div>

    <!-- Table Switcher -->
    <div class="table-switcher mb-4">
        <button class="action-btn" id="appointments_id" onclick="switchTable('appointments')">
            <i class="fas fa-calendar-check"></i> Appointments
        </button>
        <button class="action-btn" id="patients_id" onclick="switchTable('patients')">
            <i class="fas fa-users"></i> Patients
        </button>
    </div>
    <!-- Appointments Table -->
    <div id="appointments" class="table">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Date &amp; Time</th>
                        <th>Status</th>
                        <th>Report</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($coachAppointments as $appointment)
                
                    <tr>
                        <td>
                            @if ($appointment->patient->first_name==null)
                            {{ $appointment->patient->parent_first_name}} {{ $appointment->patient->parent_last_name }}
                            @else
                                {{ $appointment->patient->first_name}} {{ $appointment->patient->last_name }}
                            @endif
                        </td>
                      
                        @php
                        $appointmentDate = json_decode($appointment->appointment_planning, true);
                        @endphp
                        <td>
                            @if(is_array($appointmentDate))
                                @foreach ($appointmentDate as $date => $time)
                                    <span>{{ $date }} - </span>
                                    @foreach ($time as $slot)
                                        <span>{{ $slot }} </span>
                                    @endforeach
                                @endforeach
                            @endif
                        </td>
                        @php
                            $color = '';
                            if ($appointment->status == 'pending') {
                                $color = 'pending';
                            } elseif ($appointment->status == 'passed') {
                                $color = 'passed';
                            } elseif ($appointment->status == 'cancel') {
                                $color = 'cancel';
                            }
                        @endphp
                        <td><span class="status-badge {{$color}}">{{ $appointment->status }}</span></td>
                        @if ($appointment->report_path)
                        <td>
                            <a href="{{ route('appointments.viewReport', $appointment->id) }}" target="_blank" class="action-btn">
                                <i class="fas fa-file-alt"></i>
                            </a>
                            <a href="{{ route('appointments.downloadReport', $appointment->id) }}" class="action-btn">
                                <i class="fas fa-cloud-download-alt"></i>
                            </a>
                        </td>
                        @else
                        <td>No Report</td>
                        @endif
                        <!-- <td> -->
                            <!-- <form action="{{ route('appointment.show', $appointment->id) }}" method="get">
                                @csrf
                                <button class="action-btn"><i class="fas fa-eye"></i> Details</button>
                            </form> -->
                        <!-- </td> -->
                            
                        <td class="text-center">
                            <a href="{{ route('appointment.show', $appointment->id) }}" class="btn btn-sm btn-outline-secondary me-2">
                                <i class="fas fa-eye"></i>
                            </a>
                            
                        </td>
                    </tr>
                    @endforeach
                    <!-- More rows if needed -->
                </tbody>
            </table>
        </div>
    </div>
    <!-- patients table -->
    <div id="patients" class="table" style="display: none;">
        <div class="table-wrapper">
            <table class="patients-table">
            <thead>
                <tr>
                    <th><i class="fas fa-id-badge me-2"></i> ID</th>
                    <th><i class="fas fa-user me-2"></i> Kid/Young Full Name</th>
                    <th><i class="fas fa-phone me-2"></i> Phone</th>
                    <th>Age</th>
                    <th><i class="fas fa-venus-mars me-2"></i> Gender</th>
                    <th><i class="fa-solid fa-school-flag"></i> Ecole</th>
                    <th><i class="fa-solid fa-sitemap"></i> System</th>
                    <th><i class="fas fa-user me-2"></i> Parent/adult Full Name</th>
                    <th><i class="fa-solid fa-briefcase"></i> Profession</th>
                    <th><i class="fa-solid fa-building"></i> Etablissment</th>
                    <th><i class="bi bi-envelope-at-fill"></i> Email</th>
                    <th><i class="bi bi-geo-alt-fill"></i> Adress</th>
                    <th><i class="fa-solid fa-people-arrows"></i> Mode</th>
                    <th>Subscription</th>
                    <th><i class="fas fa-cogs me-2"></i>Actions</th>
                </tr>
            </thead>
                <tbody>
                    @foreach($coachAppointments as $appointment)
                    <tr class="hover-shadow">
                        <!-- id -->
                        <td>{{ $appointment->patient->id }}</td>
                        <!-- full name kid/young -->
                        <td>
                            @if ($appointment->patient->first_name==null)
                            <mark><i>vide</i></mark>
                            @else
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-40 me-3">
                                    <img src="https://i.pravatar.cc/150" class="rounded-circle" width="40" alt="patient Avatar">
                                </div>
                                <div>
                                    <div class="fw-bold">{{ $appointment->patient->first_name}} {{ $appointment->patient->last_name }}</div>
                                </div>
                            </div>
                            @endif


                        </td>
                        <!-- phone -->
                        <td>{{ $appointment->patient->phone }}</td>
                        <!-- age -->
                        <td>{{ $appointment->patient->age }}</td>
                        <!-- gender -->
                        <td>
                            @if ($appointment->patient->gender == 'M')
                                <span class="badge bg-primary">Male <i class="bi bi-gender-male"></i></span>
                            @else
                                <span class="badge bg-danger">Female <i class="bi bi-gender-female"></i></span>
                            @endif
                        </td>
                        <!-- ecole -->
                        <td>
                            @if ( $appointment->patient->ecole  == null)
                            <mark><i>vide</i></mark>
                            @else
                            {{ $appointment->patient->ecole }}
                            @endif
                            </td>
                        <!-- system -->
                        <td>
                        @if ( $appointment->patient->system  == null)
                                <mark><i>vide</i></mark>
                            @else
                            {{ $appointment->patient->system }}
                            @endif
                            </td>
                        <!-- parent patient full name -->
                        <td>{{ $appointment->patient->parent_first_name }}  {{ $appointment->patient->parent_last_name  }}</td>
                        <!-- profession -->
                        <td>{{ $appointment->patient->profession }}</td>
                        <!-- etablissment -->
                        <td>{{ $appointment->patient->etablissment }}</td>
                        <!-- email -->
                        <td>{{ $appointment->patient->email }}</td>
                        <!-- adress -->
                        <td>{{ $appointment->patient->address }}</td>
                        <!-- mode -->
                        <td>{{ $appointment->patient->mode }}</td>
                        <!-- subscription -->
                        <td>{{  $appointment->patient->subscription }}</td>


                        <td class="text-center">
                            <a href="{{ route('patient.show', $appointment->patient->id) }}" class="btn btn-sm btn-outline-secondary me-2">
                                <i class="fas fa-eye"></i>
                            </a>
                            
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Custom Styles -->
<style>
    :root {
        --primary-color: #3498db;
        --secondary-color: #2c3e50;
        --light-bg: #ecf0f1;
    }
    /* body {
        background-color: var(--light-bg);
        color: #333;
    } */
     
    /* Coach Profile */
    .coach-profile {
        margin: 20px;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    /* Header */
    .coach-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #ddd;
        padding-bottom: 20px;
        margin-bottom: 20px;
    }
    .coach-info {
        display: flex;
        align-items: center;
        gap: 20px;
    }
    .coach-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--primary-color);
    }
    .coach-details h1 {
        font-size: 2rem;
        margin: 0 0 10px;
        color: var(--secondary-color);
    }
    .coach-tags {
        margin-bottom: 10px;
    }
    .coach-tags .tag {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.9rem;
        margin-right: 10px;
    }
    .coach-tags .speciality {
        background-color: #f0f0f0;
        color: var(--secondary-color);
    }
    .coach-tags .availability.available {
        background-color: rgba(72, 187, 120, 0.2);
        color: #48bb78;
    }
    .coach-tags .availability.busy {
        background-color: rgba(245, 101, 101, 0.2);
        color: #f56565;
    }
    .coach-contact p {
        margin: 5px 0;
        font-size: 0.9rem;
        color: #777;
    }
    /* Action Button */
    .action-btn {
        background-color: var(--primary-color);
        color: #fff;
        border: none;
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 0.9rem;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    .action-btn:hover {
        background-color: #2980b9;
    }
    /* Calendar Container */
    .calendar-container {
        background-color: #fff;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
    /* FullCalendar Customizations */
    #appointments-calendar, #availabilities-calendar {
        color: var(--secondary-color);
        height: 600px;
    }
    .fc-event {
        border: none !important;
        border-radius: 8px !important;
        padding: 8px 12px !important;
        margin: 4px !important;
        font-weight: 500 !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        box-shadow: 0 3px 6px rgba(0,0,0,0.16) !important;
        position: relative;
        overflow: hidden;
    }

    .fc-event::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
    }
    
    .cancel .fc-event-title,
    .cancel .fc-event-title,
    .fc-event-time {
    color:rgb(206, 1, 1) !important; 
    }

    .pending .fc-event-title,
    .pending .fc-event-time {
    color:rgb(251, 145, 6) !important; 
    }

    .passed .fc-event-title,
    .passed .fc-event-time {
    color:rgb(1, 174, 27) !important;
    }

    /* cancle  - Amber */
    .fc-event.cancel {
        background: rgba(255, 167, 38, 0.15) !important;
        color:rgb(255, 49, 38) !important;
    }
    .fc-event.cancel::before {
        background: rgb(255, 49, 38);
    }

    /* pending - Emerald */
    .fc-event.pending {
        background: rgba(102, 187, 106, 0.15) !important;
        color:rgb(232, 169, 12) !important;
    }
    .fc-event.pending::before {
        background:rgb(232, 169, 12) ;
    }

    /* passed - Rose */
    .fc-event.passed {
        background: rgba(239, 83, 80, 0.15) !important;
        color:rgb(109, 239, 80) !important;
    }
    .fc-event.passed::before {
        background:rgb(109, 239, 80) ;
    }

    /* Hover Effects */
    .fc-event:hover {
        transform: translateY(-2px) scale(1.02);
        box-shadow: 0 6px 12px rgba(0,0,0,0.25) !important;
    }

    /* Time Styling */
    .fc-event-time {
        font-weight: 300;
        opacity: 0.8;
        margin-right: 8px;
    }
   
    /* Tables */
    .table-wrapper {
        overflow-x: auto;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        background-color: #fff;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    .patients-table {
        width:200%;
    }
    table thead {
        background-color: #f4f4f4;
    }
    table th, table td {
        padding: 12px 15px;
        border: 1px solid #ddd;
        text-align: left;
        font-size: 0.9rem;
    }
    table tbody tr:hover {
        background-color: #f9f9f9;
    }
    /* Status Badges */
    .status-badge {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        color: #fff;
    }
    .status-badge.pending {
        background-color: #FFA726;
    }
    .status-badge.passed {
        background-color: #66BB6A;
    }
    .status-badge.cancel {
        background-color: #EF5350;
    }
    /* FullCalendar Customizations */
    #calendar {
        color: var(--secondary-color);
    }
    .fc-toolbar, .fc-button {
        background-color: var(--secondary-color) !important;
        color: #fff !important;
        border: none;
    }
    .fc-button:hover {
        background-color: #2980b9 !important;
    }
    .fc-event {
        background: #34495e !important;
        color: #fff !important;
        border: none !important;
        border-radius: 4px !important;
    }
    .fc-event:hover {
        transform: translateY(-2px) scale(1.02);
        box-shadow: 0 6px 12px rgba(0,0,0,0.25) !important;
    }

</style>

<script>


   // Global calendar instance variables
   let appointmentsCalendarInstance;
    let availabilitiesCalendarInstance;

    // Function to switch calendars and update size
    function switchCalendars(calendarClass) {
        // Hide all calendar containers
        const calendars = document.querySelectorAll('.calendar-container');
        calendars.forEach(c => c.style.display = 'none');

        // Show the selected calendar container
        const selectedCalendar = document.querySelector('.' + calendarClass);
        if (selectedCalendar) {
            selectedCalendar.style.display = 'block';

            // Call updateSize() on the appropriate FullCalendar instance
            if (calendarClass === 'appointments-calendar' && appointmentsCalendarInstance) {
                appointmentsCalendarInstance.updateSize();
            } else if (calendarClass === 'availabilities-calendar' && availabilitiesCalendarInstance) {
                availabilitiesCalendarInstance.updateSize();
            }
        }
    }
    function switchTable(table) {
        const tables = document.querySelectorAll('.table');
        tables.forEach(t => t.style.display = 'none');
        document.getElementById(table).style.display = 'block';
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize and store appointments calendar instance
        appointmentsCalendarInstance = initAppointmentsCalendar();
        // Initialize and store availabilities calendar instance
        availabilitiesCalendarInstance = initAvailabilitiesCalendar();

        // Show appointments calendar by default and hide availabilities
        document.querySelector('.appointments-calendar').style.display = 'block';
        document.querySelector('.availabilities-calendar').style.display = 'none';
    });
 
 function initAppointmentsCalendar(){
    var events = <?php
        $allEvents = [];
        foreach ($coachAppointments as $app) {
            // dd($app);
            $statusClass = '';
            switch ($app->status) {
                case 'passed':
                    $statusClass = 'passed';
                    break;
                case 'pending':
                    $statusClass = 'pending';
                    break;
                case 'cancel':
                    $statusClass = 'cancel';
                    break;
            }
            $appointment_date = json_decode($app->appointment_planning, true);
            // $patient = App\Models\Patient::find($app->patient_id);
            foreach ($appointment_date as $date => $time) {
                // dd($app);
                $fullName = ($app->patient->patient_type == 'kid'||$app->patient->patient_type == 'young') ? 
                    $app->patient->first_name ." ".$app->patient->last_name :
                    $app->patient->parent_first_name." ".$app->patient->parent_last_name;   

                $startTime = $time['startTime'] . "00";
                $endTime = $time['endTime'] . "00";
                $allEvents[] = [
                    'id'    => $app->id,
                    'title' => $fullName,
                    'start' => $date . 'T' . $startTime,
                    'end'   => $date . 'T' . $endTime,
                    'className' => $statusClass,
                    'extendedProps' => [
                        'status' => '{{ $app->status }}'
                    ]
                ];
            }
        }
        echo json_encode($allEvents);
    ?>;
    console.log(events);
    
    var calendarEl = document.getElementById('appointments-calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: events,
        eventContent: function(arg) {
            return {
                html: `<div class="fc-event-inner">
                        <span class="fc-event-time">${arg.timeText}</span>
                        <span class="fc-event-title">${arg.event.title}</span>
                       </div>`
            };
        },
        eventDidMount: function(arg) {
            arg.el.addEventListener('mouseenter', function() {
                arg.el.style.zIndex = '999';
            });
            arg.el.addEventListener('mouseleave', function() {
                arg.el.style.zIndex = 'auto';
            });
        },
        eventClick: function(info) {
            var detailUrl = "{{ route('appointment.show', ':id') }}";
            detailUrl = detailUrl.replace(':id', info.event.id);
            window.location.href = detailUrl;
        }
    });
    calendar.render();
 }
   // Function to initialize the availabilities calendar
function initAvailabilitiesCalendar() {
        var calendarEl = document.getElementById("availabilities-calendar");
        const oldPlanningEvents = <?php
            $coachPlanning = json_decode($coach->planning, true);
            $events = [];
            foreach ($coachPlanning as $day => $dayData) {
                foreach ($dayData as $data) {
                    $id = $data['id'];
                    $title = "Available";
                    $date = $day;
                    $start = $day.'T'.$data['startTime'].":00";
                    $end = $day.'T'.$data['endTime'].":00";
                    $events[] = [
                        'id' => $id,
                        'title' => $title,
                        'start' => $start,
                        'end' => $end,
                    ];
                }
            }
            echo json_encode($events);
        ?>;
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: "timeGridWeek",
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: oldPlanningEvents,
        });
        calendar.render();
        return calendar;
    }

</script>
@endsection
