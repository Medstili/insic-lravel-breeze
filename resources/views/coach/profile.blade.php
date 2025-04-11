@extends('layouts.coach_app')
@section('content')

<div class="coach-profile-container"> 
     <!-- success message -->

    @if(session('success'))
            <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
            <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
            </symbol>
            </svg>

            <div class="alert alert-success d-flex align-items-center" role="alert">
                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Succès:"><use xlink:href="#check-circle-fill"/></svg>
                <div>
                {{ session('success') }}
                </div>
            </div>

    @endif

     <!-- Coach Header Card -->
     <div class="coach-header-card">
        <div class="coach-profile-header">
            <div class="coach-avatar-wrapper">
                <div class="coach-avatar-initials">
                    @if ($coach->image_path)
                        <img src="{{ asset('storage/' . $coach->image_path) }}" alt="Image Previe" class="coach-avatar">
                    @else
                        {{ $coach->full_name }}

                    @endif

                </div>
            </div>
            <div class="coach-info-wrapper">
                <h1 class="coach-name">{{$coach->full_name}}</h1>
                <div class="coach-meta">
                    <span class="coach-badge speciality-badge">
                        <i class="fas fa-certificate"></i> Coach {{$coach->speciality->name}}
                    </span>
                    <span class="coach-badge availability-badge {{$coach->is_available ? 'available' : 'busy'}}">
                        <i class="fas fa-circle"></i> {{$coach->is_available ? 'Disponible maintenant' : 'Actuellement occupé'}}
                    </span>
                </div>
                <div class="coach-contact-info">
                    <a href="mailto:{{$coach->email}}" class="contact-item">
                        <i class="fas fa-envelope"></i> {{$coach->email}}
                    </a>
                    <a href="tel:{{$coach->phone}}" class="contact-item">
                        <i class="fas fa-phone"></i> {{$coach->phone}}
                    </a>
                </div>

            </div>
        </div>

        <a href="{{ route('edit_profile', $coach->id) }}" class="edit-profile-button">
            <i class="fas fa-pen-to-square"></i> Modifier le profil
        </a>
    </div>

    <!-- Calendar Section -->
    <div class="dashboard-section">
        <div class="section-switcher">
            <button class="switch-btn active" data-target="appointments-calendar">
                <i class="fas fa-calendar-day"></i> Rendez-vous
            </button>
            <button class="switch-btn" data-target="availabilities-calendar">
                <i class="fas fa-user-clock"></i> Disponibilités
            </button>
        </div>
        
            <div id="appointments-calendar" class="calendar-view active-view"></div>
   
            <div id="availabilities-calendar" class="calendar-view"></div>
    </div>
    <div class="dashboard-section mt-4 mb-4">
        <div class="table-switch-btn active " data-target="reports-table">
            <i class="fas fa-users-medical"></i> Rapport des patients
        </div>
    </div>
    <div id="reports-table" class="data-table-container ">
    <div>
        <div class="table-responsive">   
            <table class="reports-table">
                <thead>
                    <tr>
                        <th colspan="5">Patient</th>
                        <th colspan="3">Rapport</th>
                        <th colspan="4">Actions</th>
                    </tr>
                </thead>
                
                <tbody>
                    @foreach($reports as $report => $reportData)
                        @php $reportCount = count($reportData['report']);@endphp

                        @if($reportCount > 0)
                        @php $firstReport = array_shift($reportData['report']); @endphp
                        <tr>
                            <td rowspan="{{ $reportCount + 1 }}" colspan="5">{{ $reportData['patient_name'] }}</td>
                            <td colspan="3">{{ !is_null($firstReport['content']) ? basename($firstReport['content']) : 'Aucun rapport' }}</td>
                            <td colspan="4">
                                    @if (!is_null($firstReport['content']))
                                        <div class="file-actions">
                                            <a href="{{ route('coach-appointments.downloadReport', $firstReport['app_id'])  }}" class="btn-download">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            <a href="{{ route('coach-appointments.viewReport', $firstReport['app_id'])}}" target="_blank" class="btn-view">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                            @foreach($reportData['report'] as $rp)
                                <tr>
                                    <td colspan="3">{{ basename($rp['content']) }}</td>
                                        
                                    
                                    <td colspan="4">
                                        @if (!is_null($rp['content']))

                                            <div class="file-actions">
                                                <a href="{{ route('coach-appointments.downloadReport', $rp['app_id']) }}" class="btn-download">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                                <a href="{{ route('coach-appointments.viewReport', $rp['app_id']) }}" target="_blank" class="btn-view">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        @endif

                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    </div>
</div>
<style>
    :root {
        --primary-color: #6366f1;
        --secondary-color: #4f46e5;
        --success-color: #22c55e;
        --warning-color: #f59e0b;
        --danger-color: #ef4444;
        --light-bg: #f8fafc;
        --dark-text: #1e293b;
    }
    
    .table-responsive {
        border-radius: 12px;
        max-height: 70vh;
    }

    thead th {
        background: linear-gradient(195deg, var(--primary-color), var(--secondary-color));
        color: white;
        padding: 1rem;
        font-weight: 500;
        position: sticky;
        top: 0;
        z-index: 2;
    }

    tbody td {
        padding: 0.75rem;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: middle;
    }
    .patient-table {
        width: 200%;
        overflow: scroll;
        border-collapse: collapse;
        background: var(--glass-bg);
    }
    .appointments-table , .reports-table {
        width: 100%;
        border-collapse: collapse;
    }
    .hover-shadow:hover {
        background: rgba(99, 102, 241, 0.05);
        transform: translateY(-2px);
        transition: all 0.3s ease;
    }

    .badge {
        padding: 0.5em 0.75em;
        border-radius: 8px;
        font-weight: 500;
    }

    .action-buttons .btn {
        width: 40px;
        height: 40px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .action-buttons .btn-outline-secondary {
        border-color: #e2e8f0;
    }

    .action-buttons .btn-outline-secondary:hover {
        background: var(--light-bg);
    }

    .action-buttons .btn-outline-danger:hover {
        background: rgba(239, 83, 80, 0.1);
    }

  

    .coach-profile-container {
        max-width: 1400px;
        margin: 2rem auto;
        padding: 0 1.5rem;
    }

    /* Coach Header Card */
    .coach-header-card {
        background: white;
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 2rem;
    }

    .coach-profile-header {
        display: flex;
        gap: 1.5rem;
        align-items: center;
    }

    .coach-avatar-wrapper {
        position: relative;
        flex-shrink: 0;
    }

    .coach-avatar-initials {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        font-weight: 600;
        border: 3px solid var(--primary-color);

    }
    .coach-avatar {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        background: white;

    }

    .coach-info-wrapper {
        flex-grow: 1;
    }

    .coach-name {
        font-size: 1.875rem;
        color: var(--dark-text);
        margin: 0 0 0.5rem;
    }

    .coach-meta {
        display: flex;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .coach-badge {
        padding: 0.5rem 1rem;
        border-radius: 2rem;
        font-size: 0.875rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .speciality-badge {
        background: #eef2ff;
        color: var(--primary-color);
        border: 1px solid #c7d2fe;
    }

    .availability-badge {
        background: #f0fdf4;
        color: var(--success-color);
        border: 1px solid #bbf7d0;
    }

    .availability-badge.busy {
        background: #fef2f2;
        color: var(--danger-color);
        border-color: #fecaca;
    }

    .coach-contact-info {
        display: flex;
        gap: 1.5rem;
    }

    .contact-item {
        color: var(--dark-text);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: color 0.2s;
    }

    .contact-item:hover {
        color: var(--primary-color);
    }

    .edit-profile-button {
        background: var(--primary-color);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 0.75rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .edit-profile-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.3);
    }

    /* Section Switcher */
    .section-switcher {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .switch-btn , .table-switch-btn{
        background: none;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 0.75rem;
        color: #64748b;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .switch-btn.active {
        background: var(--primary-color);
        color: white;
        box-shadow: 0 2px 4px rgba(99, 102, 241, 0.2);
    }
    .table-switch-btn.active{
        background: var(--primary-color);
        color: white;
        box-shadow: 0 2px 4px rgba(99, 102, 241, 0.2);
    }

    /* Calendar Views */
    .calendar-view {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        display: none;
    }


    .calendar-view.active-view {
        display: block;
    }

    /* Data Tables */
    .data-table-container {
        background: white;
        border-radius: 1rem;

        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);

    }

    .data-table-container.active-view {
        display: block;
    }

    /* Modern FullCalendar Overrides */
    .fc {
        --fc-border-color: #e2e8f0;
        --fc-today-bg-color: #f8fafc;
        --fc-neutral-bg-color: #f8fafc;
        --fc-page-bg-color: white;
    }

    .fc .fc-button-primary {
        background: var(--primary-color);
        border-color: var(--primary-color);
        text-transform: capitalize;
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

    .fc-header-toolbar {
        background: linear-gradient(195deg, var(--primary-color), var(--secondary-color));
        color: white;
        padding: 1rem;
        border-radius: 8px 8px 0 0;
    }

    .fc-button-primary {
        background-color: var(--primary-color) !important;
        border-color: var(--primary-color) !important;
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
   



    @media (max-width: 768px) {
        .coach-header-card {
            flex-direction: column;
            align-items: stretch;
        }
        
        .coach-profile-header {
            flex-direction: column;
            text-align: center;
        }
        
        .coach-contact-info {
            flex-direction: column;
            gap: 0.5rem;
        }
    }

    .btn-download, .btn-view, .btn-delete {
        padding: 0.5rem;
        border-radius: 6px;
        transition: all 0.3s ease;
    }

    .btn-download {
        color: #2563eb;
        background: #dbeafe;
    }

    .btn-view {
        color: #16a34a;
        background: #dcfce7;
    }
    .file-actions {
        display: flex;
        gap: 1rem;
    }


    /* Responsive styles only - to be added at the end of existing CSS */

/* Mobile devices (phones, less than 768px) */
@media (max-width: 767.98px) {
    .coach-profile-container {
        margin: 1rem auto;
        padding: 0 1rem;
    }
    
    /* Coach Header Card */
    .coach-header-card {
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        flex-direction: column;
        gap: 1rem;
    }
    
    .coach-profile-header {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .coach-avatar-wrapper {
        margin: 0 auto;
    }
    
    .coach-name {
        font-size: 1.5rem;
    }
    
    .coach-meta {
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
    }
    
    .coach-contact-info {
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
    }
    
    .edit-profile-button {
        width: 100%;
        justify-content: center;
    }
    
    /* Section Switcher */
    .section-switcher {
        flex-wrap: wrap;
    }
    
    .switch-btn, .table-switch-btn {
        flex: 1 0 auto;
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
        justify-content: center;
    }
    
    /* Calendar Views */
    .calendar-view {
        padding: 1rem;
    }
    
    /* Calendar toolbar adjustments */
    .fc-header-toolbar {
        flex-wrap: wrap;
        padding: 0.75rem;
    }
    
    .fc-toolbar-chunk {
        margin-bottom: 0.5rem;
        display: flex;
        justify-content: center;
        width: 100%;
    }
    
    /* Make toolbar buttons more touch-friendly */
    .fc-button {
        padding: 0.5rem !important;
        min-height: 44px !important;
        min-width: 44px !important;
        margin: 2px !important;
    }
    
    /* Table adjustments */
    .table-responsive {
        max-height: 60vh;
    }
    
    thead th, tbody td {
        padding: 0.5rem;
        font-size: 0.9rem;
    }
    
    /* File action buttons */
    .file-actions {
        display: flex;
        gap: 0.5rem;
    }
    
    .btn-download, .btn-view, .btn-delete {
        min-height: 44px;
        min-width: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
}

/* Small devices (landscape phones) */
@media (min-width: 576px) and (max-width: 767.98px) {
    .coach-profile-container {
        margin: 1.5rem auto;
        padding: 0 1.25rem;
    }
    
    .coach-header-card {
        padding: 1.75rem;
    }
    
    .coach-name {
        font-size: 1.625rem;
    }
    
    .coach-meta {
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: center;
    }
}

/* Medium devices (tablets) */
@media (min-width: 768px) and (max-width: 991.98px) {
    .coach-profile-container {
        max-width: 90%;
    }
    
    .coach-header-card {
        flex-wrap: wrap;
    }
    
    .coach-profile-header {
        flex: 1 0 100%;
        margin-bottom: 1rem;
    }
    
    .edit-profile-button {
        margin-left: auto;
    }
    
    /* Ensure calendar is responsive but maintains functionality */
    .calendar-view {
        overflow-x: auto;
    }
}



</style>

<script>
    let appointmentsCalendarInstance;
    let availabilitiesCalendarInstance;
    // Enhanced Interaction Script
     document.addEventListener('DOMContentLoaded', function() {
        appointmentsCalendarInstance = initAppointmentsCalendar();
        availabilitiesCalendarInstance = initAvailabilitiesCalendar();
    });

    document.querySelectorAll('.switch-btn').forEach(button => {
            button.addEventListener('click', function() {
                const target = this.dataset.target;
                
                // Remove active state from all buttons
                document.querySelectorAll('.switch-btn').forEach(btn => 
                    btn.classList.remove('active'));
                
                // Activate selected
                this.classList.add('active');
                document.querySelectorAll('.calendar-view').forEach(view=>{
                    view.classList.remove('active-view');
                })
                document.getElementById(target).classList.add('active-view');
                if (target === 'appointments-calendar' && appointmentsCalendarInstance) {
                appointmentsCalendarInstance.updateSize();
                } else if (target === 'availabilities-calendar' && availabilitiesCalendarInstance) {
                    availabilitiesCalendarInstance.updateSize();
                }
            });
        });

    function initAppointmentsCalendar(){
        let initialDate;
    var events = <?php
        $allEvents = [];
        if ($coachAppointments!=null) {
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
        }
       
        echo json_encode($allEvents);
    ?>;
    console.log("Événements :", events);
    
    var calendarEl = document.getElementById('appointments-calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        contentHeight: 500,
        firstDay: 1, 
        hiddenDays: [0], 
        slotMinTime: '12:00:00',
        slotMaxTime: '20:00:00',
        allDaySlot: false,
        nowIndicator: true,
        expandRows: true,
        events: events,
        eventContent: function(arg) {
            return {
                html: `<div class="fc-event-inner">
                        <div class="fc-event-time">${arg.timeText}</div>
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
    initialDate = events.length > 0 ? events[0].start : null;
   
    
    calendar.render();
    if (initialDate!=null) {
    console.log(initialDate);

        initialDate = initialDate.split('T')[0];
        calendar.gotoDate(initialDate);
    }
 }

    function initAvailabilitiesCalendar() {
        let initialDate;
        var calendarEl = document.getElementById("availabilities-calendar");
        const oldPlanningEvents = <?php
            $coachPlanning = json_decode($coach->planning, true);
            $events = [];
            if ($coachPlanning!=null) {
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
            }
            
            echo json_encode($events);
        ?>;
        initialDate = oldPlanningEvents.length > 0 ? oldPlanningEvents[0].start : null;
        initialDate = initialDate.split('T')[0]
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: "timeGridWeek",
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            contentHeight: 500,
            firstDay: 1, 
            hiddenDays: [0], 
            slotMinTime: '12:00:00',
            slotMaxTime: '20:00:00',
            allDaySlot: false,
            nowIndicator: true,
            expandRows: true,
            events: oldPlanningEvents,
        });
        calendar.render();
        calendar.gotoDate(initialDate);
        return calendar;
    }

</script>

@endsection