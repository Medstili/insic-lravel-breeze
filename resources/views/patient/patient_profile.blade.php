@extends('layouts.app')
@section('content')
@php


$patient_first_name = ($patient->patient_type=='kid'|| $patient->patient_type=='young')? $patient->first_name : $patient->parent_first_name ;
$patient_last_name = ($patient->patient_type=='kid'|| $patient->patient_type=='young')? $patient->last_name : $patient->parent_last_name;
$patient_full_name = $patient_first_name . ' ' . $patient_last_name;

@endphp
<style>
  :root {
        --primary-color: #6366f1;
        --secondary-color: #4f46e5;
        --light-bg: #f8fafc;
        --dark-text: #1e293b;
    }


    .patient-profile {
        display: grid;
        grid-template-columns: 300px 1fr;
        gap: 2rem;
        padding: 2rem;
        background: var(--light-bg);
        min-height: 100vh;
    }

    .patient-sidebar {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        height: fit-content;
    }

    .patient-main {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    .profile-header {
        display: flex;
        flex-direction: column;
        align-items: start;
        gap: 1rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .patient-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
    }

    .patient-img-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--primary-color);
    }

    .patient-stats {
        display: grid;
        gap: 1rem;
    }

    .stat-card {
        background: var(--light-bg);
        padding: 1rem;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        background: rgba(99, 102, 241, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-color);
    }

    .detail-section {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .nav-tabs {
        display: flex;
        gap: 1rem;
        border-bottom: 1px solid #e2e8f0;
        padding-bottom: 1rem;
        margin-bottom: 1.5rem;
    }

    .nav-item {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .nav-item.active {
        background: var(--primary-color);
        color: white;
    }

    .calendar-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        height: 650px; 
        min-height: 350px;
        display: flex;
        flex-direction: column;
    }

    .calendar-nav {
        display: flex;
        gap: 1rem;
        padding: 1rem;
        border-bottom: 1px solid #e2e8f0;
    }


    #availabilities-calendar {
        flex: 1;
        padding: 1rem;
    }

  
    .fc {
        --fc-border-color: #e2e8f0;
        --fc-today-bg-color: #f8fafc;
        --fc-neutral-bg-color: #f8fafc;
        --fc-page-bg-color: white;
    }

    .fc-header-toolbar {
        background: linear-gradient(195deg, var(--primary-color), var(--secondary-color));
        color: white;
        padding: 1rem;
        margin: 0 !important;
        border-radius: 8px 8px 0 0;
    }
    .fc-view-harness {
        height: 100% !important;
    }
    .fc-button-primary {
        background-color: var(--primary-color) !important;
        border-color: var(--primary-color) !important;
    }

    .fc-event {
        border: none !important;
        border-radius: 8px !important;
        padding: 6px 8px !important;
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
    .fc .fc-button-primary {
        background: var(--primary-color);
        border-color: var(--primary-color);
        text-transform: capitalize;
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
    .priority1 { background: rgba(255, 76, 76, 0.1) !important; border-left: 4px solid #ff4c4c !important; }
    .priority2 { background: rgba(255, 152, 0, 0.1) !important; border-left: 4px solid #ff9800 !important; }
    .priority3 { background: rgba(76, 175, 80, 0.1) !important; border-left: 4px solid #4caf50 !important; }
    .table-responsive {
        border-radius: 12px;
        max-height: 70vh;
    }

    .appointments-table th {
        background: linear-gradient(195deg, var(--primary-color), var(--secondary-color));
        color: white;
        padding: 1rem;
        font-weight: 500;
        position: sticky;
        top: 0;
        z-index: 2;
    }

    .appointments-table td {
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
    
</style>
<div class="patient-profile">
    <!-- Left Sidebar -->
    <div class="patient-sidebar">
        <div class="profile-header">

        @if ($patient->image_path)
            <div class="patient-avatar-initials">
                <img src="{{ asset('storage/' . $patient->image_path) }}" alt="Image Previe" class="patient-img-avatar">
                
            </div>
        @else
            <div class="patient-avatar">
                {{ strtoupper(substr($patient_full_name, 0, 1)) }}
            </div>
        
        @endif

            <h2 class="text-xl font-semibold">{{ $patient_full_name }}</h2>
            <span class="text-sm text-gray-500">ID: {{ $patient->id }}</span>
            <input type="hidden" name="patient_id" id="patient_id" value="{{ $patient->id }}">

            <form action="{{ route('patient.edit', $patient->id) }}" method="GET">
                    @csrf
                    <button class="btn btn-primary btn-smdeepsee">
                        <i class="fas fa-plus me-2"></i>Modifier le patient
                    </button>
            </form>

        </div>

        <div class="patient-stats">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-user-tag"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Type</p>
                    <p class="font-medium">{{ ucfirst($patient->patient_type) }}</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-birthday-cake"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Âge</p>
                    <p class="font-medium">{{ $patient->age }} ans</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    @if ($patient->gender == "M")
                        <i class="fas fa-mars"></i>
                    @else
                        <i class="fas fa-venus"></i>
                    @endif
                </div>
                <div>
                    <p class="text-sm text-gray-500">Genre</p>
                    <p class="font-medium">{{ $patient->gender == "M" ? 'Homme' : 'Femme' }}</p>
                </div>

            </div>

            <div class="stat-card">
                <div class="stat-icon">
                     <i class="fas fa-calendar-week"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Quota hebdomadaire</p>
                    <p class="font-medium">{{ $patient->weekly_quota }} Max</p>
                </div>
            </div>
            
        </div>
    </div>

    <!-- Main Content -->
    <div class="patient-main">
        <!-- Navigation Tabs -->
        <div class="nav-tabs">
            <div class="nav-item active" id="personal" onclick="showTab('personal', this)">Informations personnelles</div>
            <div class="nav-item" onclick="showTab('medical', this)">Informations médicales</div>
            <div class="nav-item" onclick="showTab('coaches', this)">Informations des coachs</div>
            <div class="nav-item" onclick="showTab('appointmentsHistory', this)">Rendez vous</div>
            <div class="nav-item" onclick="showTab('calendar', this)">Priorités</div>
        </div>


        <!-- Personal Info Tab -->
        <div class="detail-section" id="personalTab">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h3 class="text-lg font-semibold mb-4">Informations de contact</h3>
                    <div class="space-y-2">
                        <p><i class="fas fa-phone mr-2"></i> {{ $patient->phone }}</p>
                        <p><i class="fas fa-envelope mr-2"></i> {{ $patient->email }}</p>
                        <p><i class="fas fa-map-marker-alt mr-2"></i> {{ $patient->address }}</p>
                    </div>
                </div>

                @if ($patient->patient_type == 'kid' || $patient->patient_type == 'young')
                <div>
                    <h3 class="text-lg font-semibold mb-4">Détails du tuteur</h3>
                    <div class="space-y-2">
                        <p><i class="fas fa-user-tie mr-2"></i> {{ $patient->parent_first_name }} {{ $patient->parent_last_name }}</p>
                        <p><i class="fas fa-briefcase mr-2"></i> {{ $patient->profession }}</p>
                        <p><i class="fas fa-building mr-2"></i> {{ $patient->etablissment }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Medical Info Tab -->
        <div class="detail-section hidden" id="medicalTab">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h3 class="text-lg font-semibold mb-4">Informations sur le traitement</h3>
                    <div class="space-y-2">
                        <p><i class="fas fa-calendar-alt mr-2"></i> {{
                         $patient->subscription 
                          }}</p>
                        <p><i class="fas fa-user-md mr-2"></i> {{ $patient->speciality->name }}</p>
                        <p><i class="fas fa-laptop-medical mr-2"></i> {{ $patient->mode }}</p>
                    </div>
                </div>

                @if ($patient->patient_type == 'kid' || $patient->patient_type == 'young')
                <div>
                    <h3 class="text-lg font-semibold mb-4">Détails de l'éducation</h3>
                    <div class="space-y-2">
                        <p><i class="fas fa-school mr-2"></i> {{ $patient->ecole }}</p>
                        <p><i class="fas fa-sitemap mr-2"></i> {{ $patient->system }} Système</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
        <!-- appointments history Info Tab -->
        <div class="detail-section hidden" id="appointmentsHistoryTab">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
           
                    <h3 class="text-lg font-semibold mb-4">appointments </h3>
                    <div id="appointments-table" class="data-table-container active-view">

            
                            <div class="table-responsive">   
                                <table class="appointments-table">
                                    <thead>
                                        <tr>
                                            <th>Entraîneur</th>
                                            <th>Date &amp; Heure</th>
                                            <th>Statut</th>
                                            <th>Rapport</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                        @endphp
                                        @if ($appointments!=null)
                                            @foreach ($appointments as $appointment)
                                                <tr>
                                                    <td>
                                                      {{ $appointment->coach->full_name }}
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
                                                    <td>Pas de rapport</td>
                                                    @endif
                                                        
                                                    <td class="text-center">
                                                        <a href="{{ route('appointment.show', $appointment->id) }}" class="btn btn-sm btn-outline-secondary me-2">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    
                                        <!-- More rows if needed -->
                                    </tbody>
                                </table>
                            </div>
                        
                    </div>
            </div>
        </div>

              <!-- assigned coach Info Tab -->
        <div class="detail-section hidden" id="coachesTab">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h3 class="text-lg font-semibold mb-4">Coachs assignés</h3>
                    <p class="alert alert-info"> Sélectionnez qui peut voir <q> <b><i>{{ $patient_full_name }}</i></b> </q> rapports </p>
                    <div class="space-y-2">
                    <form action="{{ route('who_can_See',$patient->id) }}" method="post" >
                        @csrf

                        @foreach ($patient->coaches as $coach)
                            <input type="checkbox"
                                id="coachCheckbox{{ $coach->id }}"
                                name="coaches[]"
                                value="{{ $coach->id }}"
                                {{ in_array($patient->id, $coach->can_see ?? []) ? 'checked' : '' }}>
                            <label class="form-check-label" for="coachCheckbox{{ $coach->id }}">
                                {{ $coach->full_name }}
                            </label>
                            <br>
                        @endforeach

                        <div class="text-center w-100">
                            <button type="submit" class="btn btn-primary"> Enregistrer </button>
                        </div>
                    </form>
                    
                    </div>
                </div>
            </div>
        </div>



        <div class="calendar-container  " id="calendarTab">
            <div id="availabilities-calendar"></div>
        </div> 
  
    </div>
</div>

<script>
    // Global calendar instance variables
    let availabilitiesCalendarInstance;
    // Tab navigation
    function showTab(tabName, element) {
        // Remove active class and hide all sections
        document.querySelectorAll('.nav-item').forEach(item => item.classList.remove('active'));
        document.querySelectorAll('.detail-section, .calendar-container').forEach(el => el.style.display = 'none');
        
        // If an element is passed (i.e. from a click event), mark it as active
        if (element) {
            element.classList.add('active');
        }
        
        // Show the target tab
        document.getElementById(tabName + 'Tab').style.display = 'block';

        // If showing the calendar tab, re-render and update its size after a brief delay
        if (tabName === 'calendar' && availabilitiesCalendarInstance) {
            setTimeout(() => {
            availabilitiesCalendarInstance.render();
            availabilitiesCalendarInstance.updateSize();
            }, 100);
        }
    }
    // Initialize default tab
    document.addEventListener('DOMContentLoaded', () => {

        // Remove active class and hide all sections
        document.querySelectorAll('.nav-item').forEach(item => item.classList.remove('active'));
        document.querySelectorAll('.detail-section, .calendar-container').forEach(el => el.style.display = 'none');
        document.querySelector('#personal').classList.add('active')
        // Show the target tab
        document.getElementById('personalTab').style.display = 'block';
    
        availabilitiesCalendarInstance = initAvailabilitiesCalendar();
    });
 // Function to initialize the availabilities calendar
    function initAvailabilitiesCalendar() {
        let initialDate;
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
                            'start' => $day . 'T' . $startTime,
                            'end' => $day . 'T' . $endTime,
                            'className' => $priorityClass,
                            'extendedProps' => [
                                'priority' => $priorityKey
                            ]
                        ];
                    }
                }
            }

            // dd($allPriorities)
            echo json_encode($allPriorities);
        ?>;
        initialDate = allPatientPriorities.length > 0 ? allPatientPriorities[0].start : null;
        initialDate = initialDate.split('T')[0]

                        console.log(allPatientPriorities);
                        
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: "timeGridWeek",
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            height: 'auto',
            contentHeight: 'auto',
            aspectRatio: 1.8,
            firstDay: 1, 
            hiddenDays: [0], 
            eventMinHeight: 50,
            slotMinTime: '12:00:00',
            slotMaxTime: '20:00:00',
            allDaySlot: false,
            nowIndicator: true,
            expandRows: true,
            events: allPatientPriorities,
        });
        calendar.render();
        calendar.gotoDate(initialDate);

        return calendar;
    };

</script>

@endsection