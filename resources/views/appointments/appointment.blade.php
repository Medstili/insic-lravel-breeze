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
                    <i class="fas fa-search"></i> <span class="btn-text">Appliquer les filtres</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Tableau des Rendez-vous (Desktop) -->
    <div class="appointments-table desktop-table">
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
                                <a href="{{ route('appointments.viewReport', $appointment->id) }}" target="_blank" class="action-btn btn-view" title="View">
                                    <i class="fas fa-file-alt"></i>
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

    <!-- Mobile Cards View for Appointments -->
    <div class="mobile-appointments">
        @foreach($appointments as $appointment)
        <div class="appointment-card">
            <div class="card-header">
                <div class="appointment-id">#{{ $appointment->id }}</div>
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
            </div>
            
            <div class="card-body">
                <div class="info-row">
                    <div class="info-label">Patient:</div>
                    <div class="info-value">
                        {{ ($appointment->patient->patyient_type == 'Kid'||$appointment->patient->patyient_type =='young') ?
                            $appointment->patient->first_name.' '.$appointment->patient->last_name :
                            $appointment->patient->parent_first_name.' '.$appointment->patient->parent_last_name }}
                    </div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Coach:</div>
                    <div class="info-value">{{ $appointment->coach->full_name }}</div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Spécialité:</div>
                    <div class="info-value">
                        <span class="specialty-badge">
                            {{ $appointment->Speciality->name }}
                        </span>
                    </div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Horaire:</div>
                    <div class="info-value schedule-info">
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
                    </div>
                </div>
            </div>
            
            <div class="card-footer">
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
            </div>
        </div>
        @endforeach
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
    --border-color: #e2e8f0;
    --shadow-sm: 0 4px 6px rgba(0, 0, 0, 0.05);
    --shadow-md: 0 6px 12px rgba(0, 0, 0, 0.1);
    --radius-sm: 6px;
    --radius-md: 8px;
    --radius-lg: 12px;
}

/* Base styles */
.appointment-container {
    padding: 2rem;
    background: var(--background-light);
    min-height: 100vh;
}

.appointment-header {
    background: white;
    border-radius: var(--radius-lg);
    padding: 2rem;
    box-shadow: var(--shadow-sm);
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
    margin: 0;
}

/* Search and filter styles */
.search-filter-card {
    background: white;
    border-radius: var(--radius-lg);
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: var(--shadow-sm);
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
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    width: 100%;
    transition: all 0.2s ease;
}

.search-input:focus, .select-input:focus, .date-input:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    outline: none;
}

.filter-btn {
    background: var(--primary-color);
    color: white;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: var(--radius-md);
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    justify-content: center;
    font-weight: 500;
}

.filter-btn:hover {
    background: var(--secondary-color);
    transform: translateY(-1px);
}

/* Table styles */
.appointments-table {
    background: white;
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}

.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
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
    white-space: nowrap;
}

.appointments-list td {
    padding: 1rem;
    border-bottom: 1px solid var(--border-color);
    color: var(--text-dark);
}

.appointments-list tr:hover {
    background: rgba(99, 102, 241, 0.05);
}

.appointments-list tr:last-child td {
    border-bottom: none;
}

/* Schedule styles */
.schedule-item {
    margin-bottom: 0.5rem;
}

.schedule-item:last-child {
    margin-bottom: 0;
}

.schedule-day {
    font-weight: 500;
    color: var(--text-dark);
}

.schedule-time {
    font-size: 0.875rem;
    color: var(--text-light);
}

/* Status badge styles */
.status-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 500;
    white-space: nowrap;
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

/* Specialty badge styles */
.specialty-badge {
    background: rgba(99, 102, 241, 0.1);
    color: var(--primary-color);
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.875rem;
    white-space: nowrap;
}

/* Action button styles */
.action-buttons {
    display: flex;
<<<<<<< HEAD
    gap: 0.5rem;
    flex-wrap: wrap;
=======
    gap: 0.2rem;
>>>>>>> 5c42dbee2462d4ee9de33f328d088d813db4169f
}

.action-btn {
    width: 36px;
    height: 36px;
    border: none;
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
}

.view-btn {
    background: rgba(59, 130, 246, 0.1);
    color: #3b82f6;
}

.view-btn:hover {
    background: #3b82f6;
    color: white;
}
.show-btn {
    background: rgba(59, 130, 246, 0.1);
    color: grey;
}

.show-btn:hover {
    background:grey;
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

.inline-form {
    display: inline;
}

/* Calendar Styling */
.calendar-section {
    margin-top: 2rem;
}

.fc-event {
    border: none !important;
    border-radius: var(--radius-md) !important;
    padding: 8px 12px !important;
    margin: 4px !important;
    font-weight: 500 !important;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    box-shadow: var(--shadow-sm) !important;
    overflow: auto;
    scrollbar-width: none;
}

.fc-event::-webkit-scrollbar {
    display: none;
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
    background: rgb(255, 38, 38) !important;
    color: rgb(255, 38, 38) !important;
}

.fc-event.cancel::before {
    background: rgb(247, 79, 79);
}

/* pending  */
.fc-event.pending {
    background: rgb(10, 144, 1) !important;
    color: rgb(16, 236, 0) !important;
}

.fc-event.pending::before {
    background: rgba(91, 252, 99, 0.76);
}

/* passed  */
.fc-event.passed {
    background: rgb(245, 110, 7) !important;
    color: rgb(231, 140, 2) !important;
}

.fc-event.passed::before {
    background: rgb(238, 169, 67);
}

.cancel .fc-event-title,
.cancel .fc-event-time {
    color: rgb(255, 255, 255) !important;
}

.pending .fc-event-title,
.pending .fc-event-time {
    color: rgb(255, 255, 255) !important;
}

.passed .fc-event-title,
.passed .fc-event-time {
    color: rgb(243, 243, 243) !important;
}

/* Hover Effects */
.fc-event:hover {
    transform: translateY(-2px) scale(1.02);
    box-shadow: var(--shadow-md) !important;
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

/* Mobile card view styles */
.mobile-appointments {
    display: none;
}

.appointment-card {
    background: white;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
    margin-bottom: 1rem;
    overflow: hidden;
}

.card-header {
    padding: 1rem;
    background: linear-gradient(195deg, var(--primary-color), var(--secondary-color));
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.appointment-id {
    font-weight: 600;
    font-size: 1.1rem;
}

.card-body {
    padding: 1rem;
}

.info-row {
    display: flex;
    margin-bottom: 0.75rem;
    flex-wrap: wrap;
}

.info-row:last-child {
    margin-bottom: 0;
}

.info-label {
    font-weight: 600;
    color: var(--text-dark);
    width: 100px;
    flex-shrink: 0;
}

.info-value {
    color: var(--text-dark);
    flex: 1;
}

.card-footer {
    padding: 1rem;
    background: rgba(99, 102, 241, 0.05);
    border-top: 1px solid var(--border-color);
    display: flex;
    justify-content: flex-end;
}

.btn-view {
        color: var(--text-light);
        background:rgb(208, 221, 240);
    }

    .btn-view:hover {
        background: var(--text-light);
        color: white;
    }
/* Responsive styles */
@media (max-width: 1200px) {
    .appointment-container {
        padding: 1.5rem;
    }
    
    .appointment-header, 
    .search-filter-card {
        padding: 1.5rem;
    }
    
    .header-content h1 {
        font-size: 1.75rem;
    }
}

@media (max-width: 991px) {
    .appointment-container {
        padding: 1rem;
    }
    
    .appointment-header, 
    .search-filter-card {
        padding: 1.25rem;
    }
    
    .header-content h1 {
        font-size: 1.5rem;
    }
    
    .filter-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .appointment-container {
        padding: 0.75rem;
    }
    
    .appointment-header, 
    .search-filter-card {
        padding: 1rem;
        border-radius: var(--radius-md);
    }
    
    .header-content {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .header-content h1 {
        font-size: 1.35rem;
    }
    
    .filter-grid {
        grid-template-columns: 1fr;
    }
    
    .btn-text {
        display: none;
    }
    
    .filter-btn {
        width: 100%;
        justify-content: center;
    }
    
    /* Hide desktop table, show mobile cards */
    .desktop-table {
        display: none;
    }
    
    .mobile-appointments {
        display: block;
    }
    
    /* Calendar responsive adjustments */
    .fc-header-toolbar {
        flex-direction: column;
        gap: 0.5rem;
        align-items: stretch !important;
    }
    
    .fc-toolbar-chunk {
        display: flex;
        justify-content: center;
    }
    
    .fc-toolbar-title {
        font-size: 1.1rem !important;
        text-align: center;
    }
    
    .fc-button {
        padding: 0.4em 0.65em !important;
        font-size: 0.9em !important;
    }
}

@media (max-width: 480px) {
    .appointment-container {
        padding: 0.5rem;
    }
    
    .appointment-header, 
    .search-filter-card {
        padding: 0.75rem;
        border-radius: var(--radius-sm);
    }
    
    .header-content h1 {
        font-size: 1.25rem;
    }
    
    .action-buttons {
        justify-content: center;
    }
    
    .info-row {
        flex-direction: column;
    }
    
    .info-label {
        width: 100%;
        margin-bottom: 0.25rem;
    }
    
    .card-header {
        flex-direction: column;
        gap: 0.5rem;
        align-items: flex-start;
    }
    
    .card-header .status-badge {
        align-self: flex-start;
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
                        'title' => $patientFullName,
                        'start' => $date . 'T' . $startTime,
                        'end' => $date . 'T' . $endTime,
                        'className' => $statusClass,
                        'url' => route('appointment.show', $app->id)
                    ];
                }
            }
            echo json_encode($allEvents);
        ?>;

        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: events,
            eventTimeFormat: {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            },
            height: 'auto',
            contentHeight: 'auto',
            aspectRatio: 1.8,
            // Make calendar responsive
            windowResize: function(view) {
                if (window.innerWidth < 768) {
                    calendar.changeView('timeGridDay');
                } else {
                    calendar.changeView('dayGridMonth');
                }
            }
        });
        
        calendar.render();
        
        // Initialize with correct view based on screen size
        if (window.innerWidth < 768) {
            calendar.changeView('timeGridDay');
        }
    });
</script>


@endsection
