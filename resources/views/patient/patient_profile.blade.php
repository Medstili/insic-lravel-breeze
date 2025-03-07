@extends('layouts.app')
@section('content')
@php


$patient_first_name = ($patient->patient_type=='kid'|| $patient->patient_type=='young')? $patient->first_name : $patient->parent_first_name ;
$patient_last_name = ($patient->patient_type=='kid'|| $patient->patient_type=='young')? $patient->last_name : $patient->parent_last_name;
$patient_full_name = $patient_first_name . ' ' . $patient_last_name;


@endphp

<div class="patient-profile">

    @if(session('updated'))
        <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
        <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
        </symbol>
        </svg>
        <div class="alert alert-success d-flex align-items-center" role="alert">
            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>
            <div>
            {{ session('updated') }}
            </div>
        </div>

    @endif
    <!-- Header Section -->
    <div class="profile-header d-flex justify-content-between align-items-center">
        <div>
        <!-- <img src="https://i.pravatar.cc/150" width="100" alt="Patient" class="patient-avatar"> -->
        <div class="header-info">
            <h1 class="patient-name">{{ $patient_full_name }}</h1>

            <div class="patient-meta">
                <span><mark><i class="fa-solid fa-user"></i> {{ $patient->patient_type }}</mark></span>
                <span> <mark> <i class="fas fa-birthday-cake"></i> {{ $patient->age }} years </mark></span>
                <span>
                    <mark>
                    @if ($patient->gender == "M")
                        Male <i class="bi bi-gender-male"></i>
                    @elseif ($patient->gender == "F")
                        Female <i class="bi bi-gender-female"></i>
                    @endif
                    </mark>
                </span>
                <span> <mark> <i class="fas fa-file-medical"></i> ID : {{ $patient->id }} <mark></span>
                <input type="hidden" name="patient_id" id="patient_id" value="{{ $patient->id }}">
            </div>
            <hr class="mb-2 mt-2">
            @if ($patient->patient_type == 'kid' || $patient->patient_type == 'young')
                <div class="patient-meta">
                    <span><i class="fa-solid fa-school-flag"></i> ecole : {{ $patient->ecole }}</span>
                    <span><i class="fa-solid fa-sitemap"></i> System : {{ $patient->system }}</span>
                </div>
                <div class="patient-meta">
                    <span><i class="fa-solid fa-hands-holding-child"></i> parent full name : {{ $patient->parent_first_name }} {{ $patient->parent_last_name }}</span>
                    <span><i class="fa-solid fa-briefcase"></i> Prefession : {{ $patient->profession }}</span>
                    <span><i class="fa-solid fa-building"></i> etablissment : {{ $patient->etablissment }}</span>
                </div>
            @endif
            <div class="patient-meta">
                <p><i class="bi bi-envelope-at-fill"></i> email : {{ $patient->email }}</p>
                <p><i class="bi bi-geo-alt-fill"></i> adress : {{ $patient->address }}</p>
                <p><i class="fa-solid fa-people-arrows"></i> mode : {{ $patient->mode }}</p>
                <p><i class="fas fa-file-medical"></i> subscription : {{ $patient->subscription }}</p>
                <p><i class="fa-solid fa-hospital-user"></i> Speciality : {{ $patient->speciality->name }}</p>
            </div>

        </div>

        </div>
        <form action="{{ route('patient.edit', $patient->id) }}" method="GET">
            @csrf
            <button class="btn btn-secondary btn-lg" >
                <i class="fas fa-plus me-2"></i>edit Patient 
            </button>
        </form>
    </div>
    <div id="coachAvailabilityMessage" class="mb-4 mt-4"></div>
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
    <div class="calendar-container appointments-calendar">
        <div id="appointments-calendar"></div>
    </div>
    <div class="calendar-container availabilities-calendar">
        <div id="availabilities-calendar"></div>
    </div>  
</div>
<style>

       /* Action Button */
       .action-btn {
        background-color: #3498db;
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
    /* Overall Container */
    .patient-profile {
        /* Main-content background is set by the layout (#ecf0f1) */
        margin: 20px;
    }
    /* Profile Header */
    .profile-header {
        display: flex;
        align-items: center;
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
    .patient-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        margin-right: 20px;
        object-fit: cover;
        border: 3px solid #3498db;
    }
    .header-info h1.patient-name {
        font-size: 1.8rem;
        color: #2c3e50;
        margin-bottom: 8px;
    }
    .patient-meta{
        margin-top: 10px;

    }
    .patient-meta span ,p{
        /* display: inline-block; */
        margin-top: 10px;
        margin-right: 25px;
        color: #7f8c8d;
        font-size: 0.9rem;
    }
    .calendar-container {
        background-color: #fff;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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
    #appointments-calendar, #availabilities-calendar {
        /* color: var(--secondary-color); */
        height: 600px;
    }

    .priority1 .fc-event-title,
    .priority1 .fc-event-time {
    color:rgb(206, 1, 1) !important; 
    }

    .priority2 .fc-event-title,
    .priority2 .fc-event-time {
    color:rgb(251, 145, 6) !important; 
    }

    .priority3 .fc-event-title,
    .priority3 .fc-event-time {
    color:rgb(1, 174, 27) !important;
    }
        /* priority 1 - Amber */
        .fc-event.priority1 {
            background: rgba(255, 167, 38, 0.15) !important;
            color:rgb(255, 38, 38) !important;
        }
        .fc-event.priority1::before {
            background:rgb(255, 38, 38);
        }

        /* priority 2 - Emerald */
        .fc-event.priority2 {
            background: rgba(102, 187, 106, 0.15) !important;
            color:rgb(231, 140, 2) !important;
        }
        .fc-event.priority2::before {
            background: rgb(231, 140, 2);
        }

        /* priority 3 - Rose */
        .fc-event.priority3 {
            background: rgba(239, 83, 80, 0.15) !important;
            color:rgb(16, 236, 0) !important;
        }
        .fc-event.priority3::before {
            background: rgb(16, 236, 0) ;
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


/* Smaller Coach Cards */
    .glass-card {
        background-color: #2c3e50;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        padding: 10px; /* Reduced padding for a smaller card */
        transition: transform 0.3s, box-shadow 0.3s;
        color: #ecf0f1;
        font-size: 0.9rem; /* Slightly smaller text */
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
    function bookAppointment(coachId, appointmentDate, startTime, endTime, patientId, specialityId) {

        const planning = {};
        planning[appointmentDate] = {
            startTime: startTime,
            endTime: endTime
        };
        const data = {
            coach_id: coachId,
            planning: planning,
            patient_id: patientId,
            specialityId: specialityId
        };

        const bookingAppointmentsUrl = " {{ route('appointment.store') }}";
        // Send the POST request to your Laravel store route.
        fetch( bookingAppointmentsUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.json();
        })
        .then(result => {
            console.log('Appointment stored successfully:', result);
            alert('Appointment booked successfully!');
        })
        .catch(error => {
            console.error('Error storing appointment:', error);
            alert('There was an error booking the appointment.');
        });
        
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
    function initAppointmentsCalendar() {
    const patientId = document.getElementById('patient_id').value;    
    const calendarEl = document.getElementById('appointments-calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: [], // start with no events
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
            const coachId = info.event.extendedProps.coach_id;
            const patientId = info.event.extendedProps.patient_id;
            const date = info.event.extendedProps.date;
            const specialityId = info.event.extendedProps.speciality_id;
            const priority = info.event.extendedProps.priority;
            
            // Use moment.js or native methods to get time in HH:mm format
            const startTimeApp = moment(info.event.start).format('HH:mm');
            const endTimeApp = moment(info.event.end).format('HH:mm');
            
            console.log(coachId, patientId, date, specialityId, priority, startTimeApp, endTimeApp);
            bookAppointment(coachId, date, startTimeApp, endTimeApp, patientId, specialityId );
        }
    });
    calendar.render();
    // Now fetch events and add them to the calendar
    const data = { patient_id: patientId };
    fetch("{{ route('coach-availability') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error("Network response was not ok");
        }
        return response.json();
    })
    .then(result => {
        const container = document.querySelector('#coachAvailabilityMessage')
        if (result.success) {
            console.log(result);
            let priorityUsedClass = '';
            switch (result['priorityUsed']) {
                case 'priority 1':
                    priorityUsedClass = 'priority1';
                    break;
                case 'priority 2':
                    priorityUsedClass = 'priority2';
                    break;
                case 'priority 3':
                    priorityUsedClass = 'priority3';
                    break;
            }
            result.available_coaches.forEach(av => {
                const coach = av['coach']['full_name'];
                const coach_id = av['coach']['id'];
                const speciality_id = av['speciality']['id'];
                const patient_id = av['patient']['id'];
                const date = av['date'];
                // Ensure time strings are formatted properly
                const startTime = av['free_interval']['startTime'] + ":00";
                const endTime = av['free_interval']['endTime'] + ":00";
                
                // Build the event object
                const eventObj = {
                    title: coach,
                    start: date + 'T' + startTime,
                    end: date + 'T' + endTime,
                    className: priorityUsedClass,
                    extendedProps: {
                        priority: result['priorityUsed'],
                        patient_id: patient_id,
                        coach_id: coach_id,
                        date: date,
                        speciality_id: speciality_id
                    }
                };
                // Add the event to the calendar
                calendar.addEvent(eventObj);
            });
        } 
        else {
            var message =result.message;
            if (result.outdatedPrioritiesMessage!=undefined) {
                
                message = result.outdatedPrioritiesMessage;
                console.log(message);
                console.log('outdatedPrioritiesMessage is not null');  
            } 
            container.innerHTML = `
                    <div class="glass-card p-4 text-center text-white/70">
                        <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
                        ${message}
                    </div>
                `;            
        }
    })
    .catch(error => console.error("Error fetching events:", error));
}
    // Function to initialize the availabilities calendar
    function initAvailabilitiesCalendar() {
        var calendarEl = document.getElementById("availabilities-calendar");
        var allPatientPriorities = 
            <?php
$patientPriorities = json_decode($patient->priorities, true);
$allPriorities = [];
// dd($patientPriorities);

foreach ($patientPriorities as $priorityKey => $data) {
    // dd($priorityKey,$data);
    $priorityClass = '';
    switch ($priorityKey) {
        case 'priority 1':
            $priorityClass = 'priority1';
            break;
        case 'priority 2':
            $priorityClass = 'priority2';
            break;
        case 'priority 3':
            $priorityClass = 'priority3';
            break;
    }

    foreach ($data as $day => $slots) {

        foreach ($slots as $slot) {
            // dd($slot["startTime"].':00');
            $startTime = $slot['startTime'] . ':00';
            $endTime = $slot['endTime'] . ':00';
            $allPriorities[] = [
                'id' => $slot['id'],
                'title' => $priorityKey,
                'start' => $day . 'T' . $startTime,
                'end' => $day . 'T' . $endTime,
                'className' => $priorityClass,
                'extendedProps' => [
                    'priority' => $priorityKey
                ]
            ];
        }

        // if ($slot['recurrence'] && $slot['recurrence'] === "weekly") {
        //     $extraEvents = generateRecurringEvents($day, $slot, $slot['recurrence'], 6);
        //     // dd($extraEvents);
        //     $allPriorities = array_merge($allPriorities, $extraEvents);
        //   }

    }
}

// dd($allPriorities)
echo json_encode($allPriorities);
                ?>;

                console.log(allPatientPriorities);
                
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: "timeGridWeek",
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: allPatientPriorities,
        });
        calendar.render();
        return calendar;
    };

//  <?php
//     function generateRecurringEvents($date, $slot, $recurrence, $occurrences = 5) {
//         // $date is expected to be in "YYYY-MM-DD" format.
//         $events = [];
//         $currentDate = DateTime::createFromFormat('Y-m-d', $date);

//         for ($i = 1; $i <= $occurrences; $i++) {
//             // Add 7 days to the current date for each occurrence.
//             $currentDate->modify('+7 days');
//             $newDate = $currentDate->format('Y-m-d');

//             $events[] = [
//                 'id' => $slot['id'] . '-' . $i, // unique id for occurrence
//                 'title' => 'Priority 1',         // Adjust accordingly or include priority info
//                 'start' => $newDate . 'T' . $slot['startTime'] . ':00',
//                 'end' => $newDate . 'T' . $slot['endTime'] . ':00',
//                 'backgroundColor' => 'red',      // Use appropriate color based on priority
//                 'extendedProps' => [
//                     'recurrence' => $recurrence
//                 ]
//             ];
//         }
//         return $events;
//     }

//  ?>

</script>

@endsection


