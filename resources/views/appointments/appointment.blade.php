@extends('layouts.app')

@section('content')
<div class="appointment-container">
    
    <!-- Header -->
    <div class="appointment-header">
        <div class="header-content">
            <h1>Gestion des Rendez-vous</h1>
        </div>
        
        <!-- Section Calendrier -->
        <div class="calendar-section">
            <div id="calendar"></div>
        </div>
    </div>

    <!-- Recherche et Filtrage -->
    <div class="search-filter-card">
        <form action="{{ route('appointment.index') }}" method="GET">
            <div class="filter-grid">
                <div class="filter-group">
                    <input type="text" name="q" value="{{ request('q') }}" 
                           placeholder="Rechercher par Patient..." class="search-input">
                </div>
                
                <div class="filter-group">
                    <select name="speciality" class="select-input">
                        <option value="">Toutes les spécialités</option>
                        @foreach($specilaities as $speciality)
                        <option value="{{ $speciality->id }}" {{ request('speciality') == $speciality->id ? 'selected' : '' }}>
                            {{ $speciality->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <select name="status" class="select-input">
                        <option value="">Tous les statuts</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }} class="status-pending">En attente</option>
                        <option value="passed" {{ request('status') == 'passed' ? 'selected' : '' }}  class="status-passed">Passé</option>
                        <option value="cancel" {{ request('status') == 'cancel' ? 'selected' : '' }} class="status-cancel">Annulé</option>
                    </select>
                </div>

                <div class="filter-group">
                    <input type="date" name="date" value="{{ request('date') }}" 
                           class="date-input">
                </div>

                <button type="submit" class="filter-btn">
                    <i class="fas fa-search"></i> Appliquer les filtres
                </button>
            </div>
        </form>
    </div>

    <!-- Tableau des Rendez-vous -->
    <div class="appointments-table">
            <div class="table-responsive">
                <table class="appointments-list">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Patient</th>
                            <th>Horaire</th>
                            <th>Coach</th>
                            <th>Spécialité</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appointments as $appointment)
                        <tr>
                            <td>#{{ $appointment->id }}</td>
                            <td class="patient-info">
                                {{ ($appointment->patient->patyient_type == 'Kid'||$appointment->patient->patyient_type =='young') ?
                                    $appointment->patient->first_name.' '.$appointment->patient->last_name :
                                    $appointment->patient->parent_first_name.' '.$appointment->patient->parent_last_name }}
                            </td>
                            <td class="schedule-info">
                                @php
                                    $schedule = json_decode($appointment->appointment_planning, true);
                                @endphp
                                @if (is_array($schedule))
                                    @foreach ($schedule as $day => $time)
                                    <div class="schedule-item">
                                        <div class="schedule-day">{{ $day }}</div>
                                        @foreach ($time as $slot)
                                        <div class="schedule-time">{{ $slot }}</div>
                                        @endforeach
                                    </div>
                                    @endforeach
                                @endif
                            </td>
                            <td>{{ $appointment->coach->full_name }}</td>
                            <td>
                                <span class="specialty-badge">
                                    {{ $appointment->Speciality->name }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $colorClasses = [
                                        'pending' => 'status-pending',
                                        'passed' => 'status-passed',
                                        'cancel' => 'status-cancel'
                                    ][$appointment->status] ?? 'status-default';
                                @endphp
                                <span class="status-badge {{ $colorClasses }}">
                                    {{ $appointment->status }}
                                </span>
                            </td>
                            <td class="actions">
                                <div class="action-buttons">
                                    @if ($appointment->report_path)
                                    <a href="{{ route('appointments.downloadReport', $appointment->id) }}" 
                                    class="action-btn download-btn" title="Télécharger le rapport">
                                        <i class="fas fa-file-download"></i>
                                    </a>
                                    @endif
                                    
                                    <a href="{{ route('appointment.show', $appointment->id) }}" 
                                    class="action-btn view-btn" title="Voir les détails">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    @if ($appointment->status == "pending")
                                    <a href="{{ route('appointment.edit', $appointment->id) }}"
                                    class="action-btn edit-btn" title="Modifier le rendez-vous">
                                        <i class="fa-regular fa-rectangle-xmark"></i>
                                    </a>
                                    
                                    <form action="{{ route('appointments.update-appointment-status', [$appointment->id, 'passed']) }}" method="POST" class="inline-form">
                                        @csrf 
                                        @method('PATCH')
                                        <button type="submit" class="action-btn complete-btn" title="Marquer comme terminé">
                                            <i class="fas fa-check-circle"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
    </div>

</div>

<style>
:root {
    --primary-color: #6366f1;
    --secondary-color: #4f46e5;
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --danger-color: #ef4444;
    --background-light: #f8fafc;
    --text-dark: #1e293b;
    --text-light: #64748b;
}

.appointment-container {
   
    padding: 2rem;
    background: var(--background-light);
    min-height: 100vh;
}

.appointment-header {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    margin-bottom: 2rem;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.header-content h1 {
    font-size: 2rem;
    color: var(--text-dark);
    font-weight: 600;
}



.search-filter-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.filter-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    align-items: end;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.search-input, .select-input, .date-input {
    padding: 0.75rem;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    width: 100%;
    transition: all 0.2s ease;
}

.search-input:focus, .select-input:focus, .date-input:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.filter-btn {
    background: var(--primary-color);
    color: white;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    justify-content: center;
}

.filter-btn:hover {
    background: var(--secondary-color);
    transform: translateY(-1px);
}

.appointments-table {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.appointments-list {
    width: 100%;
    border-collapse: collapse;
}

.appointments-list thead {

    background: linear-gradient(195deg, var(--primary-color), var(--secondary-color));
    color: white;
}

.appointments-list th {
    padding: 1rem;
    text-align: left;
    font-weight: 500;
}

.appointments-list td {
    padding: 1rem;
    border-bottom: 1px solid #e2e8f0;
    color: var(--text-dark);
}
.appointments-list tr:hover {
        background: rgba(99, 102, 241, 0.05);
    }

.schedule-item {
    margin-bottom: 0.5rem;
}

.schedule-day {
    font-weight: 500;
    color: var(--text-dark);
}

.schedule-time {
    font-size: 0.875rem;
    color: var(--text-light);
}

.status-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 500;
}

.status-passed {
    background: rgba(245, 158, 11, 0.1);
    color: #d97706;
}

.status-pending {
    background: rgba(16, 185, 129, 0.1);
    color: #059669;
}

.status-cancel {
    background: rgba(239, 68, 68, 0.1);
    color: #dc2626;
}

.specialty-badge {
    background: rgba(99, 102, 241, 0.1);
    color: var(--primary-color);
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.875rem;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.action-btn {
    width: 36px;
    height: 36px;
    border: none;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
}

.view-btn {
    background: rgba(59, 130, 246, 0.1);
    color: #3b82f6;
}

.view-btn:hover {
    background: #3b82f6;
    color: white;
}

.edit-btn {
    background: rgba(245, 158, 11, 0.1);
    color: #f59e0b;
}

.edit-btn:hover {
    background: #f59e0b;
    color: white;
}

.complete-btn {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
}

.complete-btn:hover {
    background: #10b981;
    color: white;
}

.download-btn {
    background: rgba(99, 102, 241, 0.1);
    color: var(--primary-color);
}

.download-btn:hover {
    background: var(--primary-color);
    color: white;
}

/* Calendar Styling */
.calendar-section {
    margin-top: 2rem;
}
.fc-event {
        border: none !important;
        border-radius: 8px !important;
        padding: 8px 12px !important;
        margin: 4px !important;
        font-weight: 500 !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        box-shadow: 0 3px 6px rgba(0,0,0,0.16) !important;
        overflow: scroll;
        scrollbar-width: none;
    }

    .fc-event::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
    }
    /* cancel */
        .fc-event.cancel {
            
            background:rgb(255, 38, 38) !important;
            color:rgb(255, 38, 38) !important;
        }
        .fc-event.cancel::before {
            background: rgb(247, 79, 79) ;
            /* background:rgb(255, 38, 38); */
        }

        /* pending  */
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
    .fc-header-toolbar {
        background: linear-gradient(195deg, var(--primary-color), var(--secondary-color));
        color: white;
        padding: 1rem;
        border-radius: 8px 8px 0 0;
    }
    .fc .fc-button-primary {
        background-color: var(--primary-color) !important;
        border-color: var(--primary-color) !important;
        text-transform: capitalize;
    }
 
    


@media (max-width: 768px) {
    .appointment-container {
        margin-left: 0;
        padding: 1rem;
    }
    
    .header-content {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }
    
    .filter-grid {
        grid-template-columns: 1fr;
    }
    
    .appointments-list {
        display: block;
        overflow-x: auto;
    }
}
</style>


<script>
        document.addEventListener('DOMContentLoaded', function() {
            var events = <?php
                $allEvents = [];
                foreach ($appointments as $app) {
                    $patientFullName ='';
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
                        $patientFullName = $app->patient->first_name != null ? 
                        ($app->patient->first_name." ".$app->patient->last_name) : ( $app->patient->parent_first_name." ".$app->patient->parent_last_name) ;

                        $startTime = $time['startTime'] .":". "00";
                        $endTime = $time['endTime'] . ":"."00";
                        $allEvents[] = [
                            'id'    => $app->id,
                            'title' => $patientFullName,
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
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
            
                editable: true,
                selectable:true,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                firstDay: 1, 
                hiddenDays: [0], 
                slotMinTime: '12:00:00',
                slotMaxTime: '20:00:00',
                allDaySlot: false,
                nowIndicator: true,
                expandRows: true,
                events: events,
                eventContent: function(arg) {
                    console.log(arg.event.speciality);
                    
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
        });
</script>
@endsection