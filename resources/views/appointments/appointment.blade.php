@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-cyan-50 to-sky-50 p-6 mt-24">
    
    <!-- Hero Header -->
    <div class="relative overflow-hidden bg-gradient-to-r from-cyan-500 via-sky-500 to-blue-500 rounded-3xl shadow-2xl mb-8">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="relative p-8 md:p-12">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold text-white mb-2">
                        Gestion des Rendez-vous
                    </h1>
                    <p class="text-cyan-100 text-lg md:text-xl">
                        Gérez et suivez tous vos rendez-vous en temps réel
                    </p>
                </div>
                <div class="hidden md:block">
                    <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-3xl text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Rendez-vous</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $appointments->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-cyan-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-calendar text-cyan-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">En Attente</p>
                    <p class="text-3xl font-bold text-green-600">{{ $appointments->where('status', 'pending')->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-clock text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Terminés</p>
                    <p class="text-3xl font-bold text-orange-600">{{ $appointments->where('status', 'passed')->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-check-circle text-orange-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Annulés</p>
                    <p class="text-3xl font-bold text-red-600">{{ $appointments->where('status', 'cancel')->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-times-circle text-red-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendar Section -->
    <div class="hidden lg:blocke bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 overflow-hidden mb-8">
        <div class="bg-gradient-to-r from-cyan-500 to-sky-600 px-8 py-6">
            <h3 class="text-xl font-bold text-white flex items-center gap-3">
                <i class="fas fa-calendar-week"></i>
                Calendrier des Rendez-vous
            </h3>
        </div>
        <div class="p-6">
            <div id="calendar" class="bg-white rounded-2xl shadow-lg border border-gray-200 p-4"></div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 overflow-hidden mb-8">
        <div class="bg-gradient-to-r from-cyan-500 to-sky-600 px-8 py-6">
            <h3 class="text-xl font-bold text-white flex items-center gap-3">
                <i class="fas fa-search"></i>
                Recherche et Filtres
            </h3>
        </div>
        <div class="p-8">
        <form action="{{ route('appointment.index') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 items-end">
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">Rechercher Patient</label>
                    <input type="text" name="q" value="{{ request('q') }}" 
                               placeholder="Nom du patient..." 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200 bg-white/50 backdrop-blur-sm">
                </div>
                
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">Spécialité</label>
                        <select name="speciality" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200 bg-white/50 backdrop-blur-sm">
                        <option value="">Toutes les spécialités</option>
                        @foreach($specilaities as $speciality)
                        <option value="{{ $speciality->id }}" {{ request('speciality') == $speciality->id ? 'selected' : '' }}>
                            {{ $speciality->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">Statut</label>
                        <select name="status" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200 bg-white/50 backdrop-blur-sm">
                        <option value="">Tous les statuts</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                            <option value="passed" {{ request('status') == 'passed' ? 'selected' : '' }}>Terminé</option>
                            <option value="cancel" {{ request('status') == 'cancel' ? 'selected' : '' }}>Annulé</option>
                    </select>
                </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">Date</label>
                    <input type="date" name="date" value="{{ request('date') }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200 bg-white/50 backdrop-blur-sm">
                </div>

                    <button type="submit" class="w-full bg-gradient-to-r from-cyan-500 to-sky-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-cyan-600 hover:to-sky-700 transform hover:-translate-y-1 transition-all duration-200 flex items-center justify-center gap-3 shadow-lg hover:shadow-xl">
                        <i class="fas fa-search"></i>
                        <span class="hidden sm:inline">Appliquer</span>
                </button>
            </div>
        </form>
    </div>
    </div>

    <!-- Appointments Table Section -->
    <div class="hidden lg:block bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 overflow-hidden">
        <div class="bg-gradient-to-r from-cyan-500 to-sky-600 px-8 py-6">
            <h3 class="text-xl font-bold text-white flex items-center gap-3">
                <i class="fas fa-list"></i>
                Liste des Rendez-vous
            </h3>
        </div>
        
        <!-- Desktop Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gradient-to-r from-cyan-500 to-sky-600 text-white">
                        <th class="px-6 py-4 text-left font-semibold text-sm uppercase tracking-wider">ID</th>
                        <th class="px-6 py-4 text-left font-semibold text-sm uppercase tracking-wider">Patient</th>
                        <th class="px-6 py-4 text-left font-semibold text-sm uppercase tracking-wider">Horaire</th>
                        <th class="px-6 py-4 text-left font-semibold text-sm uppercase tracking-wider">Coach</th>
                        <th class="px-6 py-4 text-left font-semibold text-sm uppercase tracking-wider">Spécialité</th>
                        <th class="px-6 py-4 text-left font-semibold text-sm uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-4 text-left font-semibold text-sm uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($appointments as $appointment)
                    <tr class="hover:bg-gray-50/80 transition-all duration-200 group">
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-cyan-100 text-cyan-800">
                                #{{ $appointment->id }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-br from-cyan-400 to-sky-500 rounded-full flex items-center justify-center text-white font-semibold text-sm mr-3">
                                    {{ strtoupper(substr(($appointment->patient->patyient_type == 'Kid'||$appointment->patient->patyient_type =='young') ?
                                        $appointment->patient->first_name : $appointment->patient->parent_first_name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">
                            {{ ($appointment->patient->patyient_type == 'Kid'||$appointment->patient->patyient_type =='young') ?
                                $appointment->patient->first_name.' '.$appointment->patient->last_name :
                                $appointment->patient->parent_first_name.' '.$appointment->patient->parent_last_name }}
                                    </div>
                                    <div class="text-sm text-gray-500">{{ $appointment->patient->patyient_type }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $schedule = json_decode($appointment->appointment_planning, true);
                            @endphp
                            @if (is_array($schedule))
                                @foreach ($schedule as $day => $time)
                                <div class="mb-2 last:mb-0">
                                    <div class="font-medium text-gray-900 text-sm">{{ $day }}</div>
                                    @foreach ($time as $slot)
                                    <div class="text-xs text-gray-600 bg-gray-100 px-2 py-1 rounded-md inline-block mr-1 mb-1">{{ $slot }}</div>
                                    @endforeach
                                </div>
                                @endforeach
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gradient-to-br from-sky-400 to-blue-500 rounded-full flex items-center justify-center text-white font-semibold text-xs mr-2">
                                    {{ strtoupper(substr($appointment->coach->full_name, 0, 1)) }}
                                </div>
                                <span class="text-gray-900 font-medium">{{ $appointment->coach->full_name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-3 py-1 text-sm font-medium bg-cyan-100 text-cyan-800 rounded-full">
                                {{ $appointment->Speciality->name }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusClasses = [
                                    'pending' => 'bg-green-100 text-green-800 border-green-200',
                                    'passed' => 'bg-orange-100 text-orange-800 border-orange-200',
                                    'cancel' => 'bg-red-100 text-red-800 border-red-200'
                                ];
                                $statusClass = $statusClasses[$appointment->status] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                            @endphp
                            <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full border {{ $statusClass }}">
                                <span class="w-2 h-2 rounded-full mr-2 {{ $appointment->status == 'pending' ? 'bg-green-500' : ($appointment->status == 'passed' ? 'bg-orange-500' : 'bg-red-500') }}"></span>
                                {{ $appointment->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                @if ($appointment->report_path)
                                <a href="{{ route('appointments.downloadReport', $appointment->id) }}" 
                                   class="inline-flex items-center justify-center w-9 h-9 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition-all duration-200 hover:scale-110" 
                                   title="Télécharger le rapport">
                                    <i class="fas fa-file-download text-sm"></i>
                                </a>
                                <a href="{{ route('appointments.viewReport', $appointment->id) }}" target="_blank" 
                                   class="inline-flex items-center justify-center w-9 h-9 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition-all duration-200 hover:scale-110" 
                                   title="Voir le rapport">
                                    <i class="fas fa-file-alt text-sm"></i>
                                </a>
                                @endif
                                
                                <a href="{{ route('appointment.show', $appointment->id) }}" 
                                   class="inline-flex items-center justify-center w-9 h-9 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition-all duration-200 hover:scale-110" 
                                   title="Voir les détails">
                                    <i class="fas fa-eye text-sm"></i>
                                </a>

                                @if ($appointment->status == "pending")
                                <a href="{{ route('appointment.edit', $appointment->id) }}"
                                   class="inline-flex items-center justify-center w-9 h-9 bg-yellow-100 text-yellow-600 rounded-lg hover:bg-yellow-200 transition-all duration-200 hover:scale-110" 
                                   title="Modifier le rendez-vous">
                                    <i class="fa-regular fa-rectangle-xmark text-sm"></i>
                                </a>
                                
                                <form action="{{ route('appointments.update-appointment-status', [$appointment->id, 'passed']) }}" method="POST" class="inline">
                                    @csrf 
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="inline-flex items-center justify-center w-9 h-9 bg-green-100 text-green-600 rounded-lg hover:bg-green-200 transition-all duration-200 hover:scale-110" 
                                            title="Marquer comme terminé">
                                        <i class="fas fa-check-circle text-sm"></i>
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

    <!-- Mobile Cards View -->
    <div class="lg:hidden space-y-6 mt-8">
        @foreach($appointments as $appointment)
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 overflow-hidden hover:shadow-3xl transition-all duration-300">
            <div class="bg-gradient-to-r from-cyan-500 to-sky-600 px-6 py-4 text-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-calendar text-xl"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-lg">#{{ $appointment->id }}</div>
                            <div class="text-cyan-100 text-sm">Rendez-vous</div>
                        </div>
                    </div>
                    @php
                        $statusClasses = [
                            'pending' => 'bg-green-100 text-green-800',
                            'passed' => 'bg-orange-100 text-orange-800',
                            'cancel' => 'bg-red-100 text-red-800'
                        ];
                        $statusClass = $statusClasses[$appointment->status] ?? 'bg-gray-100 text-gray-800';
                @endphp
                    <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full {{ $statusClass }}">
                    {{ $appointment->status }}
                </span>
                </div>
            </div>
            
            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-cyan-400 to-sky-500 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                            {{ strtoupper(substr(($appointment->patient->patyient_type == 'Kid'||$appointment->patient->patyient_type =='young') ?
                                $appointment->patient->first_name : $appointment->patient->parent_first_name, 0, 1)) }}
                        </div>
                        <div>
                            <div class="font-medium text-gray-900">
                        {{ ($appointment->patient->patyient_type == 'Kid'||$appointment->patient->patyient_type =='young') ?
                            $appointment->patient->first_name.' '.$appointment->patient->last_name :
                            $appointment->patient->parent_first_name.' '.$appointment->patient->parent_last_name }}
                            </div>
                            <div class="text-sm text-gray-500">{{ $appointment->patient->patyient_type }}</div>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-gradient-to-br from-sjy-400 to-blue-500 rounded-full flex items-center justify-center text-white font-semibold text-xs">
                            {{ strtoupper(substr($appointment->coach->full_name, 0, 1)) }}
                        </div>
                        <div>
                            <div class="font-medium text-gray-900">{{ $appointment->coach->full_name }}</div>
                            <div class="text-sm text-gray-500">Coach</div>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="font-medium text-gray-900">Spécialité:</div>
                    <div>
                        <span class="inline-flex px-3 py-1 text-sm font-medium bg-cyan-100 text-cyan-800 rounded-full">
                            {{ $appointment->Speciality->name }}
                        </span>
                    </div>
                </div>
                
                <div class="space-y-2">
                    <div class="font-medium text-gray-900">Horaire:</div>
                    <div class="text-gray-900">
                        @php
                            $schedule = json_decode($appointment->appointment_planning, true);
                        @endphp
                        @if (is_array($schedule))
                            @foreach ($schedule as $day => $time)
                            <div class="mb-2 last:mb-0">
                                <div class="font-medium text-gray-900 text-sm">{{ $day }}</div>
                                @foreach ($time as $slot)
                                <div class="text-xs text-gray-600 bg-gray-100 px-2 py-1 rounded-md inline-block mr-1 mb-1">{{ $slot }}</div>
                                @endforeach
                            </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-200/50">
                <div class="flex items-center justify-end gap-2">
                    @if ($appointment->report_path)
                    <a href="{{ route('appointments.downloadReport', $appointment->id) }}" 
                       class="inline-flex items-center justify-center w-9 h-9 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition-all duration-200 hover:scale-110" 
                       title="Télécharger le rapport">
                        <i class="fas fa-file-download text-sm"></i>
                    </a>
                    @endif
                    
                    <a href="{{ route('appointment.show', $appointment->id) }}" 
                       class="inline-flex items-center justify-center w-9 h-9 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition-all duration-200 hover:scale-110" 
                       title="Voir les détails">
                        <i class="fas fa-eye text-sm"></i>
                    </a>

                    @if ($appointment->status == "pending")
                    <a href="{{ route('appointment.edit', $appointment->id) }}"
                       class="inline-flex items-center justify-center w-9 h-9 bg-yellow-100 text-yellow-600 rounded-lg hover:bg-yellow-200 transition-all duration-200 hover:scale-110" 
                       title="Modifier le rendez-vous">
                        <i class="fa-regular fa-rectangle-xmark text-sm"></i>
                    </a>
                    
                    <form action="{{ route('appointments.update-appointment-status', [$appointment->id, 'passed']) }}" method="POST" class="inline">
                        @csrf 
                        @method('PATCH')
                        <button type="submit" 
                                class="inline-flex items-center justify-center w-9 h-9 bg-green-100 text-green-600 rounded-lg hover:bg-green-200 transition-all duration-200 hover:scale-110" 
                                title="Marquer comme terminé">
                            <i class="fas fa-check-circle text-sm"></i>
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var events = <?php
            $allEvents = [];
            foreach ($appointments as $app) {
                $patientFullName ='';
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
            windowResize: function(view) {
                if (window.innerWidth < 768) {
                    calendar.changeView('timeGridDay');
                } else {
                    calendar.changeView('dayGridMonth');
                }
            }
        });
        
        calendar.render();
        
        if (window.innerWidth < 768) {
            calendar.changeView('timeGridDay');
        }
    });
</script>

@endsection
