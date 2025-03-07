
@extends('layouts.coach_app')
@section('content')
<style>
    :root {
        --primary-color: #3498db;
        --secondary-color: #2c3e50;
        --light-bg: #ecf0f1;
    }

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
       /* Event Styling with Status Colors */
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

    /* Pending - Amber */
    .fc-event.pending {
        background: rgba(255, 167, 38, 0.15) !important;
        color: #FFA726 !important;
    }
    .fc-event.pending::before {
        background: #FFA726;
    }

    /* Passed - Emerald */
    .fc-event.passed {
        background: rgba(102, 187, 106, 0.15) !important;
        color: #66BB6A !important;
    }
    .fc-event.passed::before {
        background: #66BB6A;
    }

    /* Canceled - Rose */
    .fc-event.cancel {
        background: rgba(239, 83, 80, 0.15) !important;
        color: #EF5350 !important;
    }
    .fc-event.cancel::before {
        background: #EF5350;
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
    /* Table Switcher */
    .calendar-switcher {
        display: flex;
        gap: 10px;
        margin: 20px 0;
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
    #appointments-calendar, #availabilities-calendar {
        color: var(--secondary-color);
        height: 600px;
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

    /* Main Content */
    .main-content {
      padding: 20px;
      min-height: calc(100vh - 80px);
      background-color: #ecf0f1;
    }
</style>     
<div class="coach-profile">


<!-- success message -->

        @if(session('success'))
            <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
            <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
            </symbol>
            </svg>

            <div class="alert alert-success d-flex align-items-center" role="alert">
                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>
                <div>
                {{ session('success') }}
                </div>
            </div>

        @endif
    <!-- Coach Header -->
    <div class="coach-header">
        <div class="coach-info">
            <!-- <img src="https://i.pravatar.cc/150" alt="Coach" class="coach-avatar"> -->
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
        <form action="{{ route('edit_profile', $coach->id) }}" method="get">
            @csrf
            <button class="action-btn">
                <i class="fas fa-edit"></i> Edit Profile
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

    <!-- Appointments Calendar Section -->
    <div class="calendar-container appointments-calendar">
        <div id="appointments-calendar"></div>
    </div>
    <!-- Availabilities Calendar Section -->
    <div class="calendar-container availabilities-calendar">
        <div id="availabilities-calendar"></div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
    // Initialize calendars on DOMContentLoaded
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize and store appointments calendar instance
        appointmentsCalendarInstance = initAppointmentsCalendar();
        // Initialize and store availabilities calendar instance
        availabilitiesCalendarInstance = initAvailabilitiesCalendar();

        // Show appointments calendar by default and hide availabilities
        document.querySelector('.appointments-calendar').style.display = 'block';
        document.querySelector('.availabilities-calendar').style.display = 'none';
    });
    // Function to initialize the appointments calendar
    function initAppointmentsCalendar() {
        var events = <?php
            $allEvents = [];
            foreach ($coachAppointments as $app) {
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
                foreach ($appointment_date as $date => $time) {
                    $full_name=( $app->patient->patient_type == 'kid' || $app->patient->patient_type == 'young' ) ? $app->patient->first_name .' '.$app->patient->last_name : $app->patient->parent_first_name.' '.$app->patient->parent_last_name;
                    // dd($app->patient->patient_type,$full_name);

                    $startTime = $time['startTime'] . "00";
                    $endTime = $time['endTime'] . "00";
                    $allEvents[] = [
                        'id'    => $app->id,
                        'title' => $full_name,
                        'start' => $date . 'T' . $startTime,
                        'end'   => $date . 'T' . $endTime,
                        'className' => $statusClass,
                        'extendedProps' => [
                            'status' => $app->status
                        ]
                    ];
                }
            }
            echo json_encode($allEvents);
        ?>;
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
                var detailUrl = "{{ route('appointment_details', ':id') }}";
                detailUrl = detailUrl.replace(':id', info.event.id);
                window.location.href = detailUrl;
            }
        });
        calendar.render();
        return calendar;
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
