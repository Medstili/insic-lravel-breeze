@extends('layouts.app')
@section('content')

<div class="coach-profile-container">
    <!-- Coach Header Card -->
    <div class="coach-header-card">
        <div class="coach-profile-header">
            <!-- <div class="coach-avatar-wrapper"> -->
                <div class="coach-avatar-initials">
                    <img src="{{ asset('storage/' . $coach->image_path) }}" alt="Image Previe" class="coach-avatar">
                </div>
            <!-- </div> -->
            <div class="coach-info-wrapper">
                <h1 class="coach-name">{{$coach->full_name}}</h1>
                <div class="coach-meta">
                    <span class="coach-badge speciality-badge">
                        <i class="fas fa-certificate"></i> Entraîneur {{$coach->speciality->name}}
                    </span>
                    <span class="coach-badge availability-badge {{$coach->is_available ? 'available' : 'busy'}}">
                        <i class="fas fa-circle"></i> {{$coach->is_available ? 'Disponible Maintenant' : 'Actuellement Occupé'}}
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
        <a href="{{ route('user.edit',$coach->id) }}" class="edit-profile-button">
            <i class="fas fa-pen-to-square"></i> Modifier le Profil
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

    <!-- Data Tables Section -->
    
    <div class="dashboard-section">
        <div class="section-table-switcher mt-4 mb-2 ">
            <button class="table-switch-btn me-2 active" data-target="appointments-table">
                <i class="fas fa-calendar-check"></i> Rendez-vous
            </button>
            <button class="table-switch-btn" data-target="patients-table">
                <i class="fas fa-users-medical"></i> Patients
            </button>
            <button class="table-switch-btn" data-target="reports-table">
                <i class="fas fa-users-medical"></i> Rapports des patients
            </button>
        </div>

        <!-- Tableau des Rendez-vous -->
        <div id="appointments-table" class="data-table-container active-view">
            <div>
                <div class="table-responsive">   
                    <table class="appointments-table">
                        <thead>
                            <tr>
                                <th>Patient</th>
                                <th>Date &amp; Heure</th>
                                <th>Statut</th>
                                <th>Rapport</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($coachAppointments!=null)
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
                                            <a href="{{ route('appointments.viewReport', $appointment->id) }}" target="_blank" class="action-btn me-2">
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

        <!-- Patients Table -->
        <div id="patients-table" class="data-table-container">
            <div>
                <div class="table-responsive">
                    <table class="patient-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nom complet de l'enfant/jeune</th>
                                    <th>Nom complet du parent/adulte</th>
                                    <th>Âge</th>
                                    <th>Contact</th>
                                    <th>Adresse</th>
                                    <th>Sexe</th>
                                    <th>École/Système</th>
                                    <th>Profession/Établissement</th>
                                    <th>Mode</th>
                                    <th>Abonnement</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($coachAppointments as $appointment)
                                    <tr class="hover-shadow">
                                        <td class="fw-bold">#{{ $appointment->patient->id }}</td>
                                        <!-- kidd full name -->
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-3">
                                                    <div class="avatar-title bg-light rounded-circle">
                                                        <i class="fas fa-user text-primary"></i>
                                                    </div>
                                                </div>
                                            
                                            @if ($appointment->patient->first_name==null)
                                            &ndash;
                                            @else
                                            <div >
                                                <div>
                                                    <div class="fw-bold">{{ $appointment->patient->first_name}} {{ $appointment->patient->last_name }}</div>
                                                </div>
                                                <small class="text-muted">{{ $appointment->patient->mode }}</small>
                                            </div>
                                            @endif
                                            </div>


                                        </td>
                                        <!--  parent/adult full name -->
                                        <td>
                                        <div class="fw-bold">{{ $appointment->patient->parent_first_name }} {{ $appointment->patient->parent_last_name }}</div>
                                    
                                    </td>
                                    <!-- age -->
                                    <td>{{ $appointment->patient->age }}</td>
                                    <!-- contact -->
                                        <td>
                                            <div>{{ $appointment->patient->phone }}</div>
                                            <small class="text-muted">{{ $appointment->patient->email }}</small>
                                        </td>
                                        <!-- adresse -->
                                        <td class="text-muted small">{{ $appointment->patient->address }}</td>
                                        <!-- gender -->
                                        <td>
                                            @if($appointment->patient->gender == 'M')
                                            <span class="badge bg-primary">
                                                <i class="fas fa-mars me-1"></i>Homme
                                            </span>
                                            @else
                                            <span class="badge bg-danger">
                                                <i class="fas fa-venus me-1"></i>Femme
                                            </span>
                                            @endif
                                        </td>
                                        <!-- school/system -->
                                        <td>
                                            @if ($appointment->patient->ecole)
                                                {{ $appointment->patient->ecole }}
                                                <div class="text-muted small">{{ $appointment->patient->system }}</div>
                                            @else
                                                &ndash;
                                            @endif

                                            
                                            
                                        </td>
                                        <!-- profession/etablissment -->
                                        <td>
                                        {{ $appointment->patient->profession }}
                                        <div class="text-muted small">{{ $appointment->patient->etablissment }}</div>
                                        </td>
                                        <!-- Mode -->
                                        <td>{{ $appointment->patient->mode }}</td>
                                        <!-- subscription -->
                                        <td>{{  $appointment->patient->subscription }}</td>
                                        <!-- actions -->
                                        <td class="action-buttons">
                                            <a href="{{ route('patient.show', $appointment->patient->id) }}" 
                                            class="btn btn-outline-secondary btn-sm me-1"
                                            data-bs-toggle="tooltip" title="Voir les détails">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <form action="{{ route('patient.destroy', $appointment->patient->id) }}" 
                                                method="POST" 
                                                class="d-inline"
                                                onsubmit="return confirm('Supprimer ce patient de manière permanente ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-outline-danger btn-sm"
                                                        data-bs-toggle="tooltip" 
                                                        title="Supprimer le patient">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Patients Report -->

        <div id="reports-table" class="data-table-container">
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
                                @php $reportCount = count($reportData['report']); @endphp
                                @if($reportCount > 0)
                                    @php 
                                        $firstReport = array_shift($reportData['report']);
                                    @endphp
                                    <tr>
                                        <td rowspan="{{ $reportCount + 1 }}" colspan="5">{{ $reportData['patient_name'] }}</td>
                                        <td colspan="3">{{ basename($firstReport['content']) }}</td>
                                        <td colspan="4">
                                            <div class="file-actions">
                                                <a href="{{ route('appointments.downloadReport', $firstReport['app_id']) }}" class="btn-download">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                                <a href="{{ route('appointments.viewReport', $firstReport['app_id']) }}" target="_blank" class="btn-view">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @foreach($reportData['report'] as $rp)
                                                
                                        @if (!is_null($rp['content']))
                                            <tr>
                                                <td colspan="3">{{ basename($rp['content']) }}</td>
                                                <td colspan="4">
                                                    <div class="file-actions">
                                                        <a href="{{ route('appointments.downloadReport', $rp['app_id']) }}" class="btn-download">
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                        <a href="{{ route('appointments.viewReport', $rp['app_id']) }}" target="_blank" class="btn-view">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    
                                    @endforeach
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
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
        display: none;
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
    
    .fc-event.pending {
            background:rgb(10, 144, 1) !important;

            color:rgb(16, 236, 0) !important;

        }
        .fc-event.pending::before {
            background: rgba(91, 252, 99, 0.76);
        }

        /* passed  */
        .fc-event.passed {
            background: rgb(245, 110, 7) !important;
    
            color:rgb(231, 140, 2) !important;

        }
        .fc-event.passed::before {
            /* background: rgb(16, 236, 0) ; */
            background: rgb(238, 169, 67);

        }
    .cancel .fc-event-title,
    .cancel .fc-event-time {
    color:rgb(255, 255, 255) !important; 
    }

    .pending .fc-event-title,
    .pending .fc-event-time {
     color:rgb(255, 255, 255) !important;
    
    }

    .passed .fc-event-title,
    .passed .fc-event-time {

        color:rgb(243, 243, 243) !important; 

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
    .coach-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--primary-color);
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
    document.querySelectorAll('.table-switch-btn').forEach(button => {
            button.addEventListener('click', function() {
                const target = this.dataset.target;
                
                // Remove active state from all buttons
                document.querySelectorAll('.table-switch-btn').forEach(btn => 
                    btn.classList.remove('active'));
                
                // Activate selected
                this.classList.add('active');
                document.querySelectorAll('.data-table-container').forEach(view=>{
                    view.classList.remove('active-view');
                })
                document.getElementById(target).classList.add('active-view');
          
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
    console.log(events);
    
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
            var detailUrl = "{{ route('appointment.show', ':id') }}";
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