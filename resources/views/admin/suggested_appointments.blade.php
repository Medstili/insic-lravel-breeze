@extends('layouts.app')

@php
    use App\Models\Patient;
@endphp

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-cyan-50 to-sky-50 p-6 mt-24">
    
    <!-- Hero Header -->
    <div class="relative overflow-hidden bg-gradient-to-r from-cyan-500 via-sky-500 to-blue-500 rounded-3xl shadow-2xl mb-8">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="relative p-8 md:p-12">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold text-white mb-2">
                        Rendez-vous Suggérés
                    </h1>
                    <p class="text-cyan-100 text-lg md:text-xl">
                        Gérez et planifiez les rendez-vous suggérés automatiquement
                    </p>
                </div>
                <div class="hidden md:block">
                    <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-calendar-plus text-3xl text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts Section -->
    @if ($messages != [])
        <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-6 mb-8">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-yellow-800">Avertissements</h3>
                    <ul class="mt-2 space-y-1">
                    @foreach ($messages as $message)
                            <li class="text-yellow-700">{{ $message }}</li>
                    @endforeach
                </ul>
                </div>
            </div>
            </div>
        @endif

    <div class="hidden" id="successMsg">
        <div class="bg-green-50 border border-green-200 rounded-2xl p-6 mb-8">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-green-800">Succès</h3>
                    <p class="text-green-700" id="successMessage"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 overflow-hidden">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-cyan-500 to-sky-600 px-8 py-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex items-center gap-4">
                    <h2 class="text-2xl font-bold text-white">Calendrier des Rendez-vous Suggérés</h2>
            </div>
            
                <div class="flex items-center gap-4">
                    <div class="bg-white/20 rounded-xl px-4 py-2">
                        <span class="text-white font-medium">
                    {{ \Carbon\Carbon::parse($currentWeekStart)->format('d M, Y') }} - 
                    {{ \Carbon\Carbon::parse($currentWeekStart)->addDays(5)->format('d M, Y') }}
                </span>
                    </div>
                    <form action="" method="get">
                        <button type="submit" name="generate" 
                                class="bg-white text-cyan-600 px-6 py-3 rounded-xl font-semibold hover:bg-cyan-50 transition-all duration-200 flex items-center gap-3 shadow-lg hover:shadow-xl">
                            <i class="fa-solid fa-calendar-plus"></i>
                            <span class="hidden sm:inline">Générer des Rendez-vous</span>
                    </button>
                </form>
                </div>
            </div>
        </div>

        <!-- Desktop Table View -->
        <div class="hidden lg:block overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gradient-to-r from-cyan-500 to-sky-600 text-white">
                        <th class="px-6 py-4 text-left font-semibold text-sm uppercase tracking-wider sticky left-0 z-20 bg-cyan-500">Jour/Coach</th>
                        @foreach ($timeSlots as $slot)
                            <th class="px-6 py-4 text-center font-semibold text-sm uppercase tracking-wider">{{ $slot['start'] }} - {{ $slot['end'] }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($days as $day)
                        <tr class="bg-gray-50">
                            <td colspan="{{ count($timeSlots) + 1 }}" class="px-6 py-4 text-center font-semibold text-gray-900 bg-cyan-100">
                                {{ \Carbon\Carbon::parse($day)->format('l, d M, Y') }}
                            </td>
                        </tr>
                        @foreach ($allCoaches as $coach)
                            @php
                                if (isset($_GET['generate'])) {
                                    $appointmentsForDay = $patientSchedules->where('coach_id', $coach->id)
                                        ->where('Date', $day);
                                } else {
                                    $appointmentsForDay = collect();
                                }
                            @endphp

                            <tr class="hover:bg-gray-50/80 transition-all duration-200">
                                <td class="px-6 py-4 sticky left-0 z-10 bg-white border-r border-gray-200">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-sky-400 to-blue-500 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                            {{ strtoupper(substr($coach->full_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $coach->full_name }}</div>
                                            <div class="text-sm text-gray-500">Coach</div>
                                        </div>
                                    </div>
                                </td>
                                @foreach ($timeSlots as $slot)
                                    @php
                                        $slotStart = strtotime($day . ' ' . $slot['start']);
                                        $slotEnd = strtotime($day . ' ' . $slot['end']);
                                        $cellAppointments = [];
                                        foreach ($appointmentsForDay as $appointment) {
                                            $apptStart = strtotime($day . ' ' . $appointment->startTime);
                                            if ($apptStart >= $slotStart && $apptStart < $slotEnd) {
                                                $cellAppointments[] = $appointment;
                                            }
                                        }
                                    @endphp
                                    <td class="px-4 py-3 border border-gray-200">
                                        @foreach ($cellAppointments as $appointment)
                                            @php
                                                $priorityClasses = [
                                                    'priority 1' => 'bg-red-50 border-red-200 text-red-800',
                                                    'priority 2' => 'bg-yellow-50 border-yellow-200 text-yellow-800',
                                                    'priority 3' => 'bg-green-50 border-green-200 text-green-800'
                                                ];
                                                $priorityClass = $priorityClasses[$appointment->priority] ?? 'bg-gray-50 border-gray-200 text-gray-800';
                                                $isBooked = $appointment->Status == 'booked';
                                                $display = $isBooked ? 'hidden' : 'flex';
                                                $timeColor = $isBooked ? 'text-white' : 'text-gray-600';

                                                $patient = Patient::where('id', $appointment->patient_id)->first();
                                                $patientName = in_array($patient->patient_type, ['kid', 'young']) ? 
                                                    $patient->first_name.' '.$patient->last_name : 
                                                    $patient->parent_first_name.' '.$patient->parent_last_name;
                                            @endphp
                                            <div class="appointment-card {{ $isBooked ? 'bg-gradient-to-br from-emerald-600 via-teal-600 to-cyan-600 text-white' : $priorityClass }} rounded-xl p-3 mb-2 border transition-all duration-200 hover:shadow-lg hover:-translate-y-1" 
                                                data-app-start="{{ $appointment->startTime }}" 
                                                data-app-end="{{ $appointment->endTime }}" 
                                                data-app-date="{{ $appointment->Date }}" 
                                                data-app-id="{{ $appointment->id }}">
                                                <div class="font-medium text-sm mb-1">{{ $patientName }}</div>
                                                <div class="text-xs {{ $timeColor }} mb-3">
                                                    {{ $appointment->startTime }} - {{ $appointment->endTime }}
                                                </div>
                                                <div class="flex items-center justify-center gap-1 {{ $display }}">
                                                    <button type="button" class="w-8 h-8 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition-all duration-200 hover:scale-110 flex items-center justify-center" 
                                                            title="Modifier le Rendez-vous" onclick="openEditModal(this)">
                                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                                    </button>
                                                    <button type="button" class="w-8 h-8 bg-purple-100 text-purple-600 rounded-lg hover:bg-purple-200 transition-all duration-200 hover:scale-110 flex items-center justify-center" 
                                                            title="Changer de Patient" 
                                                            onclick="openChangeModal({{ $coach->speciality_id }}, '{{ $appointment->Date }}', '{{ $appointment->startTime }}', '{{ $appointment->endTime }}', '{{ $appointment->patient_id }}', this)">
                                                        <i class="fa-solid fa-arrows-rotate text-xs"></i>
                                                    </button>
                                                    <button type="button" class="w-8 h-8 bg-indigo-100 text-indigo-600 rounded-lg hover:bg-indigo-200 transition-all duration-200 hover:scale-110 flex items-center justify-center" 
                                                            title="Changer de Coach" 
                                                            onclick="openCoachChangeModal({{ $coach->speciality_id }}, '{{ $appointment->Date }}', '{{ $appointment->startTime }}', '{{ $appointment->endTime }}', this)">
                                                        <i class="fa-solid fa-user text-xs"></i>
                                                    </button>
                                                    <button type="button" class="w-8 h-8 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-all duration-200 hover:scale-110 flex items-center justify-center" 
                                                            title="Bloquer le Créneau" 
                                                            onclick="block('{{ $appointment->id }}')">
                                                        <i class="fa-solid fa-eraser text-xs"></i>
                                                    </button>
                                                    <button type="button" class="w-8 h-8 bg-green-100 text-green-600 rounded-lg hover:bg-green-200 transition-all duration-200 hover:scale-110 flex items-center justify-center" 
                                                            title="Réserver le Rendez-vous"
                                                            onclick="bookAppointment({{ $coach->id }}, '{{ $appointment->Date }}', '{{ $appointment->startTime }}', '{{ $appointment->endTime }}', {{ $appointment->patient_id }}, {{ $appointment->speciality_id }}, {{ $appointment->id }})">
                                                        <i class="fa-solid fa-check text-xs"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
        </div>

        <!-- Mobile Calendar View -->
    <div class="lg:hidden space-y-6 mt-8">
            @foreach ($days as $day)
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 overflow-hidden">
                <div class="bg-gradient-to-r from-cyan-500 to-sky-600 px-6 py-4 text-white">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-calendar text-xl"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-lg">{{ \Carbon\Carbon::parse($day)->format('l, d M, Y') }}</div>
                            <div class="text-cyan-100 text-sm">Journée</div>
                        </div>
                    </div>
                    </div>
                    
                <div class="p-6 space-y-4">
                    @foreach ($allCoaches as $coach)
                        @php
                            if (isset($_GET['generate'])) {
                                $appointmentsForDay = $patientSchedules->where('coach_id', $coach->id)
                                    ->where('Date', $day);
                            } else {
                                $appointmentsForDay = collect();
                            }
                            
                            if ($appointmentsForDay->isEmpty()) {
                                continue;
                            }
                        @endphp
                        
                        <div class="border border-gray-200 rounded-2xl overflow-hidden">
                            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-gradient-to-br from-sky-400 to-blue-500 rounded-full flex items-center justify-center text-white font-semibold text-xs">
                                            {{ strtoupper(substr($coach->full_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $coach->full_name }}</div>
                                            <div class="text-sm text-gray-500">Coach</div>
                                        </div>
                                    </div>
                                    <button type="button" class="text-gray-500 hover:text-gray-700 transition-colors duration-200" onclick="toggleCoachTimeslots(this)">
                                    <i class="fa-solid fa-chevron-down"></i>
                                </button>
                            </div>
                            </div>
                            
                            <div class="coach-timeslots hidden p-4 space-y-3">
                                @foreach ($timeSlots as $slot)
                                    @php
                                        $slotStart = strtotime($day . ' ' . $slot['start']);
                                        $slotEnd = strtotime($day . ' ' . $slot['end']);
                                        $slotAppointments = [];
                                        
                                        foreach ($appointmentsForDay as $appointment) {
                                            $apptStart = strtotime($day . ' ' . $appointment->startTime);
                                            if ($apptStart >= $slotStart && $apptStart < $slotEnd) {
                                                $slotAppointments[] = $appointment;
                                            }
                                        }
                                    @endphp
                                    
                                    @if(count($slotAppointments) > 0)
                                        <div class="border border-gray-200 rounded-xl p-4">
                                            <div class="font-medium text-gray-900 mb-3">
                                                {{ $slot['start'] }} - {{ $slot['end'] }}
                                            </div>
                                            <div class="space-y-3">
                                                @foreach ($slotAppointments as $appointment)
                                                    @php
                                                        $priorityClasses = [
                                                            'priority 1' => 'bg-red-50 border-red-200 text-red-800',
                                                            'priority 2' => 'bg-yellow-50 border-yellow-200 text-yellow-800',
                                                            'priority 3' => 'bg-green-50 border-green-200 text-green-800'
                                                        ];
                                                        $priorityClass = $priorityClasses[$appointment->priority] ?? 'bg-gray-50 border-gray-200 text-gray-800';
                                                        $isBooked = $appointment->Status == 'booked';
                                                        $display = $isBooked ? 'hidden' : 'flex';
                                                        $timeColor = $isBooked ? 'text-white' : 'text-gray-600';

                                                        $patient = Patient::where('id', $appointment->patient_id)->first();
                                                        $patientName = in_array($patient->patient_type, ['kid', 'young']) ? 
                                                            $patient->first_name.' '.$patient->last_name : 
                                                            $patient->parent_first_name.' '.$patient->parent_last_name;
                                                    @endphp
                                                    <div class="appointment-card {{ $isBooked ? 'bg-cyan-600 text-white' : $priorityClass }} rounded-xl p-4 border transition-all duration-200 hover:shadow-lg" 
                                                        data-app-start="{{ $appointment->startTime }}" 
                                                        data-app-end="{{ $appointment->endTime }}" 
                                                        data-app-date="{{ $appointment->Date }}" 
                                                        data-app-id="{{ $appointment->id }}">
                                                        <div class="flex items-center justify-between mb-2">
                                                        <div class="font-medium text-sm">{{ $patientName }}</div>
                                                            <div class="text-xs {{ $timeColor }}">
                                                            {{ $appointment->startTime }} - {{ $appointment->endTime }}
                                                        </div>
                                                        </div>
                                                        <div class="flex items-center justify-center gap-2 {{ $display }}">
                                                            <button type="button" class="w-8 h-8 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition-all duration-200 hover:scale-110 flex items-center justify-center" 
                                                                    title="Modifier le Rendez-vous" onclick="openEditModal(this)">
                                                                <i class="fa-solid fa-pen-to-square text-xs"></i>
                                                            </button>
                                                            <button type="button" class="w-8 h-8 bg-purple-100 text-purple-600 rounded-lg hover:bg-purple-200 transition-all duration-200 hover:scale-110 flex items-center justify-center" 
                                                                    title="Changer de Patient" 
                                                                    onclick="openChangeModal({{ $coach->speciality_id }}, '{{ $appointment->Date }}', '{{ $appointment->startTime }}', '{{ $appointment->endTime }}', '{{ $appointment->patient_id }}', this)">
                                                                <i class="fa-solid fa-arrows-rotate text-xs"></i>
                                                            </button>
                                                            <button type="button" class="w-8 h-8 bg-indigo-100 text-indigo-600 rounded-lg hover:bg-indigo-200 transition-all duration-200 hover:scale-110 flex items-center justify-center" 
                                                                    title="Changer de Coach" 
                                                                    onclick="openCoachChangeModal({{ $coach->speciality_id }}, '{{ $appointment->Date }}', '{{ $appointment->startTime }}', '{{ $appointment->endTime }}', this)">
                                                                <i class="fa-solid fa-user text-xs"></i>
                                                            </button>
                                                            <button type="button" class="w-8 h-8 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-all duration-200 hover:scale-110 flex items-center justify-center" 
                                                                    title="Bloquer le Créneau" 
                                                                    onclick="block('{{ $appointment->id }}')">
                                                                <i class="fa-solid fa-eraser text-xs"></i>
                                                            </button>
                                                            <button type="button" class="w-8 h-8 bg-green-100 text-green-600 rounded-lg hover:bg-green-200 transition-all duration-200 hover:scale-110 flex items-center justify-center" 
                                                                    title="Réserver le Rendez-vous"
                                                                    onclick="bookAppointment({{ $coach->id }}, '{{ $appointment->Date }}', '{{ $appointment->startTime }}', '{{ $appointment->endTime }}', {{ $appointment->patient_id }}, {{ $appointment->speciality_id }}, {{ $appointment->id }})">
                                                                <i class="fa-solid fa-check text-xs"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
                </div>
            @endforeach
    </div>
</div>

<!-- Change Patient Modal -->
<div id="changePatientModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full mx-4">
            <div class="bg-gradient-to-r from-cyan-500 to-sky-600 px-6 py-4 rounded-t-3xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-white">Changer de Patient</h3>
                    <button type="button" class="text-white hover:text-cyan-100 transition-colors duration-200" onclick="closeChangeModal()">
                        <i class="fas fa-times text-xl"></i>
                    </button>
            </div>
            </div>
            
            <div class="p-6">
                <div id="errorMsg" class="hidden bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-4">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                        <span class="text-yellow-800" id="errorMessage"></span>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div class="flex gap-3">
                        <button class="flex-1 bg-gradient-to-r from-cyan-500 to-sky-600 text-white px-4 py-3 rounded-xl font-semibold hover:from-cyan-600 hover:to-sky-700 transition-all duration-200" onclick="selectRandomAutoPatient()">
                        Automatique
                    </button>
                        <button class="flex-1 bg-gray-100 text-gray-700 px-4 py-3 rounded-xl font-semibold hover:bg-gray-200 transition-all duration-200" onclick="manualPatient()">
                        Manuel
                    </button>
                </div>
                    
                    <div class="hidden" id="autoGeneratedPatientContainer">
                        <h6 class="font-semibold text-gray-900 mb-2">Patients Disponibles</h6>
                        <p class="text-sm text-gray-600 mb-3">Patient généré automatiquement en fonction de la date et du créneau</p>
                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                            <span class="text-gray-900 font-medium" id="autoGeneratedPatientLabel"></span>
                        </div>
                    <input type="hidden" id="autoGeneratedPatient" name="autoGeneratedPatient" readonly>
                </div>

                    <div class="hidden" id="manualPatient">
                        <h6 class="font-semibold text-gray-900 mb-2">Nom De Patient</h6>
                        <input type="text" name="manualPatientName" id="manualPatientName" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200">
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 rounded-b-3xl flex gap-3">
                <button type="button" class="flex-1 bg-gradient-to-r from-cyan-500 to-sky-600 text-white px-4 py-3 rounded-xl font-semibold hover:from-cyan-600 hover:to-sky-700 transition-all duration-200" onclick="updateSuggestedAppointment()">
                    Mettre à Jour
                </button>
                <button type="button" class="flex-1 bg-gray-200 text-gray-700 px-4 py-3 rounded-xl font-semibold hover:bg-gray-300 transition-all duration-200" onclick="closeChangeModal()">
                    Fermer
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Change Coach Modal -->
<div id="changeCoachModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full mx-4">
            <div class="bg-gradient-to-r from-cyan-500 to-sky-600 px-6 py-4 rounded-t-3xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-white">Changer de Coach</h3>
                    <button type="button" class="text-white hover:text-cyan-100 transition-colors duration-200" onclick="closeCoachChangeModal()">
                        <i class="fas fa-times text-xl"></i>
                    </button>
            </div>
            </div>
            
            <div class="p-6">
                <div id="errorMsg" class="hidden bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-4">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                        <span class="text-yellow-800" id="errorMessage"></span>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <h6 class="font-semibold text-gray-900">Tous les entraîneurs</h6>
                    <p class="text-sm text-gray-600">Tous les entraîneurs disponibles en fonction de la spécialité et du créneau horaire</p>
                    <select id="allCoachSelect" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200">
                        <option value="" selected disabled>Sélectionnez un Entraineur</option>
                    </select>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 rounded-b-3xl flex gap-3">
                <button type="button" class="flex-1 bg-gradient-to-r from-cyan-500 to-sky-600 text-white px-4 py-3 rounded-xl font-semibold hover:from-cyan-600 hover:to-sky-700 transition-all duration-200" onclick="updateAppointmentCoach()">
                    Mettre à Jour
                </button>
                <button type="button" class="flex-1 bg-gray-200 text-gray-700 px-4 py-3 rounded-xl font-semibold hover:bg-gray-300 transition-all duration-200" onclick="closeCoachChangeModal()">
                    Fermer
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Appointment Modal -->
<div id="editAppointmentModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full mx-4">
            <div class="bg-gradient-to-r from-cyan-500 to-sky-600 px-6 py-4 rounded-t-3xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-white">Modifier le Rendez-vous</h3>
                    <button type="button" class="text-white hover:text-cyan-100 transition-colors duration-200" onclick="closeEditModal()">
                        <i class="fas fa-times text-xl"></i>
                    </button>
            </div>
            </div>
            
            <div class="p-6">
                <form action="{{ route('update_sugg_app_planning') }}" method="post" id="editAppointmentForm">
                    @csrf
                    @method("Patch")

                    <div class="space-y-4">
                        <div>
                            <label for="appointmentDate" class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                            <input type="date" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200" 
                                   id="appointmentDate" name="date" required>
                    </div>
                        <div>
                            <label for="startTime" class="block text-sm font-medium text-gray-700 mb-2">Heure de Début</label>
                            <input type="time" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200" 
                                   id="startTime" name="start_time" required>
                    </div>
                        <div>
                            <label for="endTime" class="block text-sm font-medium text-gray-700 mb-2">Heure de Fin</label>
                            <input type="time" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200" 
                                   id="endTime" name="end_time" required>
                    </div>
                    <input type="hidden" id="app_id" name="app_id">
                    </div>
                </form>
            </div>

            <div class="px-6 py-4 bg-gray-50 rounded-b-3xl">
                <button type="submit" form="editAppointmentForm" 
                        class="w-full bg-gradient-to-r from-cyan-500 to-sky-600 text-white px-4 py-3 rounded-xl font-semibold hover:from-cyan-600 hover:to-sky-700 transition-all duration-200">
                    Enregistrer les Modifications
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let currentAppointmentElement = null; 
    let autoGeneratedPatients = [];
    let warningMsg = '';    

    function bookAppointment(coachId, date, startTime, endTime, originalPatientId, specialityId, suggestedAppId) {
        if (!confirm('Êtes-vous sûr de vouloir réserver ce rendez-vous ?')) {
            return;
        }

        const appointmentCard = event.target.closest('.appointment-card');
        const currentPatientId = appointmentCard.dataset.patientId || originalPatientId;
            
        const planning = { [date]: { startTime, endTime } };
        const data = {
            coach_id: coachId,
            planning,
            patient_id: currentPatientId, 
            specialityId: specialityId,
            suggestedAppId: suggestedAppId
        };

        fetch("{{ route('appointment.store') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            if (!response.ok) throw new Error('La réservation a échoué');
            return response.json();
        })
        .then(data => {
            console.log(data.appointment);
            alert('Rendez-vous réservé avec succès !');
            window.location.reload();
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de la réservation : ' + error.message);
        });
    }

    function block(id) {
        if (!confirm('Êtes-vous sûr de vouloir bloquer ce créneau ?')) {
            return; 
        }
        const data = {
            id:id   
        }
        fetch("{{ route('block_Slot') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json', 
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            if (!response.ok) throw new Error('Le blocage a échoué');
            return response.json();
        })
        .then(data => {
            alert('Créneau bloqué avec succès !');
            window.location.reload();
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors du blocage du créneau : ' + error.message);
        });
    }

    // Change patient modal
    function closeChangeModal() {
        document.getElementById('autoGeneratedPatientContainer').classList.add('hidden');
        document.getElementById('changePatientModal').classList.add('hidden');
        document.getElementById('manualPatient').classList.add('hidden');
        document.getElementById('errorMsg').classList.add('hidden');
        document.getElementById('autoGeneratedPatient').value = '';	
        document.getElementById('autoGeneratedPatientLabel').innerHTML = '';     
        document.getElementById('errorMessage').innerHTML = '';
    }    

    function openChangeModal(specialityId, date, startTime, endTime, pateint_id, event) {
        console.log('Speciality ID:', specialityId);
        console.log('Date:', date);
        console.log('Start Time:', startTime);
        console.log('End Time:', endTime);
        currentAppointmentId = event.closest('.appointment-card');
        document.getElementById('changePatientModal').classList.remove('hidden');

        const params = new URLSearchParams({
            speciality_id: specialityId,
            date: date,
            startTime: startTime,
            endTime: endTime,
            patient_id: pateint_id
        });

        fetch("{{ route('available_Patients') }}?" + params.toString(), {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error("Échec du chargement des patients disponibles");
            return response.json();
        })
        .then(data => {
            if (data.success) {
                autoGeneratedPatients = data.available_patients;
            } else {    
                warningMsg = data.msg;         
                console.log(data.msg);
            }
        })
        .catch(error => {
            console.error("Erreur lors du chargement des patients:", error);
            alert("Erreur lors du chargement des patients : " + error.message);
        });
    }

    function updateSuggestedAppointment() {
        const autoGeneratedPatient = document.getElementById('autoGeneratedPatient');
        const manualPatientName = document.getElementById('manualPatientName');

        if (!autoGeneratedPatient.value && !manualPatientName.value) {
            alert('Veuillez sélectionner un patient');
            return;
        }
        let appId = currentAppointmentId.dataset.appId;
        const uri ="{{ route('update_sugg_Patient') }}";

        fetch(uri, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
            body: JSON.stringify({
                suggestedAppId: appId,
                autoPatientId: autoGeneratedPatient.value,
                manualPatientName: manualPatientName.value  
            })
        })
        .then(response => {
            if (!response.ok) throw new Error("Échec de la mise à jour du patient");
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert('Patient mis à jour avec succès !');
                window.location.reload();
            } else {
                if (!data.exist) {
                    alert('Le patient ne\'existe pas  !');
                } 
            }
        })
        .catch(error => {
            console.error("Erreur lors de la mise à jour du patient:", error);
            alert("Erreur lors de la mise à jour du patient : " + error.message);
        });
        closeChangeModal();
    }

    function selectRandomAutoPatient() {
        document.getElementById('manualPatient').classList.add('hidden');
        document.getElementById('errorMsg').classList.add('hidden');
        document.getElementById('errorMessage').innerHTML = '';

        if (!autoGeneratedPatients || autoGeneratedPatients.length === 0) {
            document.getElementById('errorMessage').innerHTML = warningMsg;
            document.getElementById('errorMsg').classList.remove('hidden');
            return;
        }

        const randomIndex = Math.floor(Math.random() * autoGeneratedPatients.length);
        const randomPatient = autoGeneratedPatients[randomIndex];

        console.log('Random Patient:', randomPatient);
        
        const autoGeneratedPatientInput = document.getElementById('autoGeneratedPatient');
        const autoGeneratedPatientLabel = document.getElementById('autoGeneratedPatientLabel');        
        document.getElementById('autoGeneratedPatientContainer').classList.remove('hidden');
        autoGeneratedPatientInput.value = randomPatient.id;
        autoGeneratedPatientLabel.textContent = randomPatient.full_name;
    }

    function manualPatient() {
        document.getElementById('manualPatient').classList.remove('hidden');
        document.getElementById('autoGeneratedPatientContainer').classList.add('hidden');
        document.getElementById('errorMsg').classList.add('hidden');
        document.getElementById('errorMessage').innerHTML = '';
    }

    // Edit modal
    function openEditModal(event) {
        currentAppointmentId = event.closest('.appointment-card');
        document.getElementById('editAppointmentModal').classList.remove('hidden');
        document.getElementById('app_id').value = currentAppointmentId.dataset.appId;
        console.log(document.getElementById('app_id').value, currentAppointmentId.dataset.appId);
    }

    function closeEditModal() {
        document.getElementById('editAppointmentModal').classList.add('hidden');
        document.getElementById('appointmentDate').value = null;
        document.getElementById('startTime').value = null;
        document.getElementById('endTime').value = null;  
    }

    // Change coach modal
    function closeCoachChangeModal() {
        document.getElementById('changeCoachModal').classList.add('hidden');  
        document.getElementById('allCoachSelect').innerHTML = '<option value="" selected disabled>Sélectionnez un Entraineur</option>';
        document.getElementById('errorMsg').classList.add('hidden');
        document.getElementById('errorMessage').innerHTML = '';
    }

    function openCoachChangeModal(specialityId, date, startTime, endTime, event) {
        console.log('Speciality ID:', specialityId);
        console.log('Date:', date);
        console.log('Start Time:', startTime);
        console.log('End Time:', endTime);
        currentAppointmentId = event.closest('.appointment-card');
        $coachSelection = document.getElementById('allCoachSelect');
        document.getElementById('changeCoachModal').classList.remove('hidden');

        const params = new URLSearchParams({
            speciality_id: specialityId,
            date: date,
            startTime: startTime,
            endTime: endTime,
        });

        fetch("{{ route('allCoaches') }}?" + params.toString(), {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error("Échec du chargement des Entraineur disponibles");
            return response.json();
        })
        .then(data => {
            if (data.success) {
                console.log(Array.isArray(data.availableCoaches));
                console.log(typeof(data.availableCoaches));
                let coaches = data.availableCoaches;
                if (!Array.isArray(coaches)) {
                    coaches = Object.entries(coaches).map(([id, full_name]) => ({ id, full_name }));
                }
                coaches.forEach(coach => {
                    console.log("Coach ID:", coach.id, "Coach Name:", coach.full_name);
                });
                    
                $coachSelection.innerHTML = '<option value="" selected disabled>Sélectionnez un Entraineur</option>';
                
                coaches.forEach(coach => {
                console.log(coach);
                    const option = document.createElement('option');
                    option.value = coach.id;
                    option.textContent = coach.full_name;
                    $coachSelection.appendChild(option);
                });
            } else {    
                alert(data.message);
            }
        })
        .catch(error => {
            console.error("Erreur lors du chargement des entraineur:", error);
            alert("Erreur lors du chargement des entraineur : " + error.message);
        });
    }

    function updateAppointmentCoach() {
        const selectedCoach = document.getElementById('allCoachSelect').value;

        if (!selectedCoach) {
            alert('Veuillez sélectionner un entraîneur');
            return;
        }
        const uri = "{{ route('update_sugg_Coach') }}";
        fetch(uri, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                coach_id: selectedCoach,
                suggestedAppId: currentAppointmentId.dataset.appId 
            })
        })
        .then(response => {
            if (!response.ok) throw new Error('Failed to update coach');
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert('Coach updated successfully!');
                window.location.reload();
            } else {
                alert('Failed to update coach: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred: ' + error.message);
        });
    }
      
    // Toggle coach timeslots in mobile view
    function toggleCoachTimeslots(button) {
        const timeslotsContainer = button.closest('.border').querySelector('.coach-timeslots');
        const icon = button.querySelector('i');
        
        if (timeslotsContainer.classList.contains('hidden')) {
            timeslotsContainer.classList.remove('hidden');
            icon.classList.remove('fa-chevron-down');
            icon.classList.add('fa-chevron-up');
        } else {
            timeslotsContainer.classList.add('hidden');
            icon.classList.remove('fa-chevron-up');
            icon.classList.add('fa-chevron-down');
        }
    }
    
    // Initialize mobile view
    document.addEventListener('DOMContentLoaded', function() {
        const coachSections = document.querySelectorAll('.coach-timeslots');
        coachSections.forEach(section => {
            section.classList.add('hidden');
        });
    });
</script>

@endsection
