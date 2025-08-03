
@extends('layouts.coach_app')
@section('content')

<div class="min-h-screen bg-gradient-to-br from-cyan-50 via-sky-50 to-blue-50 p-6 mt-24">
    <div class="max-w-7xl mx-auto">
    <!-- Coach Header Card -->
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 p-8 mb-8">
            <div class="flex flex-col lg:flex-row items-center lg:items-start gap-8">
                <!-- Coach Avatar -->
                <div class="relative">
                    <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-cyan-200 shadow-lg">
                        <img src="{{ asset('storage/' . $coach->image_path) }}" alt="Coach Avatar" class="w-full h-full object-cover">
                </div>
                </div>
                
                <!-- Coach Info -->
                <div class="flex-1 text-center lg:text-left">
                    <h1 class="text-3xl font-bold text-cyan-800 mb-4">{{$coach->full_name}}</h1>
                    
                    <div class="flex flex-wrap gap-3 mb-6 justify-center lg:justify-start">
                        <span class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-cyan-100 to-sky-100 text-cyan-700 rounded-full text-sm font-semibold border border-cyan-200">
                        <i class="fas fa-certificate"></i> Entraîneur {{$coach->speciality->name}}
                    </span>
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold {{$coach->is_available ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-red-100 text-red-700 border border-red-200'}}">
                            <i class="fas fa-circle text-xs"></i> {{$coach->is_available ? 'Disponible Maintenant' : 'Actuellement Occupé'}}
                    </span>
                </div>
                    
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="mailto:{{$coach->email}}" class="inline-flex items-center gap-2 text-cyan-700 hover:text-cyan-600 transition-colors">
                        <i class="fas fa-envelope"></i> {{$coach->email}}
                    </a>
                        <a href="tel:{{$coach->phone}}" class="inline-flex items-center gap-2 text-cyan-700 hover:text-cyan-600 transition-colors">
                        <i class="fas fa-phone"></i> {{$coach->phone}}
                    </a>
                </div>
            </div>
                
                <!-- Edit Profile Button -->
            <div class="lg:self-start">
                <a href="{{ route('edit_profile',$coach->id) }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-cyan-500 to-sky-600 text-white px-6 py-3 rounded-2xl font-semibold hover:from-cyan-600 hover:to-sky-700 transform hover:-translate-y-1 transition-all duration-200 shadow-lg hover:shadow-2xl">
                    <i class="fas fa-pen-to-square"></i> Modifier le Profil
                </a>
            </div>
        </div>
    </div>

    <!-- Calendar Section -->
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 p-8 mb-8">
            <div class="flex flex-wrap gap-3 mb-6">
                <button class="switch-btn active bg-gradient-to-r from-cyan-500 to-sky-600 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-200 shadow-lg" data-target="appointments-calendar">
                <i class="fas fa-calendar-day"></i> Rendez-vous
            </button>
                <button class="switch-btn bg-gray-100 text-gray-700 hover:bg-gray-200 px-6 py-3 rounded-xl font-semibold transition-all duration-200" data-target="availabilities-calendar">
                <i class="fas fa-user-clock"></i> Disponibilités
            </button>
        </div>
        
            <div id="appointments-calendar" class="calendar-view active-view bg-white rounded-2xl shadow-lg border border-cyan-100 p-6"></div>
            <div id="availabilities-calendar" class="calendar-view bg-white rounded-2xl shadow-lg border border-cyan-100 p-6"></div>
    </div>

    <!-- Data Tables Section -->
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 p-8">
            <div class="flex flex-wrap gap-3 mb-6">
                <button class="table-switch-btn active bg-gradient-to-r from-cyan-500 to-sky-600 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-200 shadow-lg" data-target="appointments-table">
                <i class="fas fa-calendar-check"></i> Rendez-vous
            </button>
                <button class="table-switch-btn bg-gray-100 text-gray-700 hover:bg-gray-200 px-6 py-3 rounded-xl font-semibold transition-all duration-200" data-target="patients-table">
                <i class="fas fa-users-medical"></i> Patients
             </button>

        </div>
            <!-- Appointments Table -->
        <div id="appointments-table" class="data-table-container active-view">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                                    <tr class="bg-gradient-to-r from-cyan-500 via-sky-500 to-blue-500 text-white">
                                        <th class="px-6 py-4 text-left font-semibold">Patient</th>
                                        <th class="px-6 py-4 text-left font-semibold">Date &amp; Heure</th>
                                        <th class="px-6 py-4 text-left font-semibold">Statut</th>
                                        <th class="px-6 py-4 text-left font-semibold">Rapport</th>
                                        <th class="px-6 py-4 text-left font-semibold">Actions</th>
                            </tr>
                        </thead>
                                <tbody class="bg-white">
                            @if ($coachAppointments!=null)
                                @foreach ($coachAppointments as $appointment)
                                            <tr class="hover:bg-cyan-50 transition-colors border-b border-gray-100">
                                                <td class="px-6 py-4">
                                                    <div class="flex items-center gap-3">
                                            @if ($appointment->patient->image_path)
                                                            <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-cyan-200">
                                                                <img src="{{ asset('storage/' . $appointment->patient->image_path) }}" alt="Patient Avatar" class="w-full h-full object-cover">
                                                </div>
                                            @else
                                                            <div class="w-10 h-10 rounded-full bg-gradient-to-r from-cyan-400 to-sky-500 text-white flex items-center justify-center font-semibold text-sm">
                                                                {{ strtoupper(substr($appointment->patient->patient_type=='adult'? $appointment->patient->parent_first_name : $appointment->patient->first_name, 0, 1)) }}
                                                </div>
                                            @endif
                                                        <div>
                                            @if ($appointment->patient->first_name==null)
                                            {{ $appointment->patient->parent_first_name}} {{ $appointment->patient->parent_last_name }}
                                            @else
                                                {{ $appointment->patient->first_name}} {{ $appointment->patient->last_name }}
                                            @endif
                                                        </div>
                                                    </div>
                                        </td>
                                    
                                        @php
                                        $appointmentDate = json_decode($appointment->appointment_planning, true);
                                        @endphp
                                                <td class="px-6 py-4">
                                            @if(is_array($appointmentDate))
                                                @foreach ($appointmentDate as $date => $time)
                                                            <div class="font-medium">{{ $date }}</div>
                                                    @foreach ($time as $slot)
                                                                <span class="text-sm text-gray-600">{{ $slot }}</span>
                                                    @endforeach
                                                @endforeach
                                            @endif
                                        </td>
                                        @php
                                                    $statusColor = '';
                                                    $statusBg = '';
                                            if ($appointment->status == 'pending') {
                                                        $statusColor = 'text-green-700';
                                                        $statusBg = 'bg-green-100';
                                            } elseif ($appointment->status == 'passed') {
                                                        $statusColor = 'text-orange-700';
                                                        $statusBg = 'bg-orange-100';
                                            } elseif ($appointment->status == 'cancel') {
                                                        $statusColor = 'text-red-700';
                                                        $statusBg = 'bg-red-100';
                                            }
                                        @endphp
                                                <td class="px-6 py-4">
                                                    <span class="inline-flex px-3 py-1 rounded-full text-sm font-semibold {{ $statusBg }} {{ $statusColor }}">
                                                        {{ $appointment->status }}
                                                    </span>
                                                </td>
                                        @if ($appointment->report_path)
                                                <td class="px-6 py-4">
                                                    <div class="flex gap-2">
                                                        <a href="{{ route('coach-appointments.viewReport', $appointment->id) }}" target="_blank" class="p-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition-colors">
                                                            <i class="fas fa-file-alt"></i>
                                                        </a>
                                                        <a href="{{ route('coach-appointments.downloadReport', $appointment->id) }}" class="p-2 bg-green-100 text-green-600 rounded-lg hover:bg-green-200 transition-colors">
                                                            <i class="fas fa-cloud-download-alt"></i>
                                                        </a>
                                                    </div>
                                        </td>
                                        @else
                                                <td class="px-6 py-4 text-gray-500">Pas de rapport</td>
                                        @endif
                                            
                                                <td class="px-6 py-4">
                                                    <a href="{{ route('appointment_details', $appointment->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-cyan-100 text-cyan-700 rounded-lg hover:bg-cyan-200 transition-colors">
                                                        <i class="fas fa-eye"></i> Voir
                                                    </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        
                        </tbody>
                    </table>
                </div>
        </div>
        <!-- Patients Table -->
        <div id="patients-table" class="data-table-container">
                <div class="overflow-x-auto">
                    <table class="w-full">
                            <thead>
                            <tr class="bg-gradient-to-r from-cyan-500 via-sky-500 to-blue-500 text-white">
                                <th class="px-6 py-4 text-left font-semibold">ID</th>
                                <th class="px-6 py-4 text-left font-semibold">Nom complet de l'enfant/jeune</th>
                                <th class="px-6 py-4 text-left font-semibold">Nom complet du parent/adulte</th>
                                <th class="px-6 py-4 text-left font-semibold">Âge</th>
                                <th class="px-6 py-4 text-left font-semibold">Contact</th>
                                <th class="px-6 py-4 text-left font-semibold">Adresse</th>
                                <th class="px-6 py-4 text-left font-semibold">Sexe</th>
                                <th class="px-6 py-4 text-left font-semibold">École/Système</th>
                                <th class="px-6 py-4 text-left font-semibold">Profession/Établissement</th>
                                <th class="px-6 py-4 text-left font-semibold">Mode</th>
                                <th class="px-6 py-4 text-left font-semibold">Abonnement</th>
                                <th class="px-6 py-4 text-left font-semibold">Actions</th>
                                </tr>
                            </thead>
                        <tbody class="bg-white">
                                @foreach($coachAppointments as $appointment)
                                <tr class="hover:bg-cyan-50 transition-colors border-b border-gray-100">
                                    <td class="px-6 py-4 font-bold">#{{ $appointment->patient->id }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                                @if ($appointment->patient->image_path)
                                                <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-cyan-200">
                                                    <img src="{{ asset('storage/' . $appointment->patient->image_path) }}" alt="Patient Avatar" class="w-full h-full object-cover">
                                                    </div>
                                                @else
                                                < div class="w-10 h-10 rounded-full bg-gradient-to-r from-cyan-400 to-sky-500 text-white flex items-center justify-center font-semibold text-sm">
                                                    {{ strtoupper(substr($appointment->patient->patient_type=='adult'? $appointment->patient->parent_first_name : $appointment->patient->first_name, 0, 1)) }}
                                                    </div>
                                                @endif
                                            
                                            @if ($appointment->patient->first_name==null)
                                                <span class="text-gray-400">&ndash;</span>
                                            @else
                                                <div>
                                                    <div class="font-bold">{{ $appointment->patient->first_name}} {{ $appointment->patient->last_name }}</div>
                                                    <small class="text-gray-500">{{ $appointment->patient->mode }}</small>
                                            </div>
                                            @endif
                                            </div>
                                        </td>
                                    <td class="px-6 py-4 font-bold">{{ $appointment->patient->parent_first_name }} {{ $appointment->patient->parent_last_name }}</td>
                                    <td class="px-6 py-4">{{ $appointment->patient->age }}</td>
                                    <td class="px-6 py-4">
                                            <div>{{ $appointment->patient->phone }}</div>
                                        <small class="text-gray-500">{{ $appointment->patient->email }}</small>
                                        </td>
                                    <td class="px-6 py-4 text-gray-500 text-sm">{{ $appointment->patient->address }}</td>
                                    <td class="px-6 py-4">
                                            @if($appointment->patient->gender == 'M')
                                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm">
                                                <i class="fas fa-mars"></i>Homme
                                            </span>
                                            @else
                                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-pink-100 text-pink-700 rounded-full text-sm">
                                                <i class="fas fa-venus"></i>Femme
                                            </span>
                                            @endif
                                        </td>
                                    <td class="px-6 py-4">
                                            @if ($appointment->patient->ecole)
                                                {{ $appointment->patient->ecole }}
                                            <div class="text-gray-500 text-sm">{{ $appointment->patient->system }}</div>
                                            @else
                                            <span class="text-gray-400">&ndash;</span>
                                            @endif
                                        </td>
                                    <td class="px-6 py-4">
                                        {{ $appointment->patient->profession }}
                                        <div class="text-gray-500 text-sm">{{ $appointment->patient->etablissment }}</div>
                                        </td>
                                    <td class="px-6 py-4">{{ $appointment->patient->mode }}</td>
                                    <td class="px-6 py-4">{{  $appointment->patient->subscription }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex gap-2">
                                            <a href="{{ route('patient_profile', $appointment->patient->id) }}" class="inline-flex items-center gap-1 px-3 py-1 bg-cyan-100 text-cyan-700 rounded-lg hover:bg-cyan-200 transition-colors text-sm">
                                                    <i class="fas fa-eye"></i> Voir
                                            </a>
                                            <!-- <form action="{{ route('patient.destroy', $appointment->patient->id) }}" 
                                                method="POST" 
                                                class="inline"
                                                onsubmit="return confirm('Supprimer ce patient de manière permanente ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center gap-1 px-3 py-1 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors text-sm">
                                                        <i class="fas fa-trash"></i> Supprimer
                                                </button>
                                            </form> -->
                                        </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                    </table>
                </div>
        </div>

        </div>

 
</div>

<script>
    let appointmentsCalendarInstance;
    let availabilitiesCalendarInstance;

     document.addEventListener('DOMContentLoaded', function() {
    // Initialize calendars
        appointmentsCalendarInstance = initAppointmentsCalendar();
        availabilitiesCalendarInstance = initAvailabilitiesCalendar();
    
    // Initialize visibility - only show first tab of each section
    document.querySelectorAll('.calendar-view').forEach((view, index) => {
        if (index === 0) {
            view.classList.add('active-view');
            view.style.display = 'block';
        } else {
            view.classList.remove('active-view');
            view.style.display = 'none';
        }
    });
    
    document.querySelectorAll('.data-table-container').forEach((view, index) => {
        if (index === 0) {
            view.classList.add('active-view');
            view.style.display = 'block';
        } else {
            view.classList.remove('active-view');
            view.style.display = 'none';
        }
    });
    });

    document.querySelectorAll('.switch-btn').forEach(button => {
            button.addEventListener('click', function() {
                const target = this.dataset.target;
                
                // Remove active state from all buttons
        document.querySelectorAll('.switch-btn').forEach(btn => {
            btn.classList.remove('active', 'bg-gradient-to-r', 'from-cyan-500', 'to-sky-600', 'text-white', 'shadow-lg');
            btn.classList.add('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200');
        });
                
                // Activate selected
        this.classList.add('active', 'bg-gradient-to-r', 'from-cyan-500', 'to-sky-600', 'text-white', 'shadow-lg');
        this.classList.remove('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200');
        
        // Hide all calendar views
        document.querySelectorAll('.calendar-view').forEach(view => {
                    view.classList.remove('active-view');
            view.style.display = 'none';
        });
        
        // Show selected calendar view
        const selectedView = document.getElementById(target);
        if (selectedView) {
            selectedView.classList.add('active-view');
            selectedView.style.display = 'block';
        }
        
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
        document.querySelectorAll('.table-switch-btn').forEach(btn => {
            btn.classList.remove('active', 'bg-gradient-to-r', 'from-cyan-500', 'to-sky-600', 'text-white', 'shadow-lg');
            btn.classList.add('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200');
        });
                
                // Activate selected
        this.classList.add('active', 'bg-gradient-to-r', 'from-cyan-500', 'to-sky-600', 'text-white', 'shadow-lg');
        this.classList.remove('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200');
        
        // Hide all table containers
        document.querySelectorAll('.data-table-container').forEach(view => {
                    view.classList.remove('active-view');
            view.style.display = 'none';
        });
        
        // Show selected table container
        const selectedView = document.getElementById(target);
        if (selectedView) {
            selectedView.classList.add('active-view');
            selectedView.style.display = 'block';
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
    document.addEventListener('DOMContentLoaded', function() {
        // Add data-label attributes dynamically for mobile view
        document.querySelectorAll('table').forEach(table => {
            const headerTexts = Array.from(table.querySelectorAll('thead th')).map(th => th.textContent.trim());
            
            table.querySelectorAll('tbody tr').forEach(row => {
                Array.from(row.querySelectorAll('td')).forEach((cell, index) => {
                    if (index < headerTexts.length && !cell.hasAttribute('data-label')) {
                        cell.setAttribute('data-label', headerTexts[index]);
                    }
                });
            });
        });
    });
</script>

@endsection