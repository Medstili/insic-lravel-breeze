@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between mb-8 mt-24">
    <div>
        <h1 class="text-4xl font-bold bg-cyan-500 bg-clip-text text-transparent">Dashboard</h1>
        <p class="text-gray-600 mt-2 text-lg">Welcome back! Here's what's happening today.</p>
    </div>
    <div class="flex items-center gap-6">
        <div class="text-right">
            <p class="text-sm text-gray-500 font-medium">Current Week</p>
            <p class="text-xl font-bold text-gray-900">{{ $currentWeek }}</p>
        </div>
        <div class="w-12 h-12 bg-gradient-to-br from-cyan-500 via-sky-500 to-blue-500 rounded-xl flex items-center justify-center shadow-lg">
            <i class="fas fa-calendar text-white text-lg"></i>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="space-y-8">
    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="relative overflow-hidden bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 rounded-2xl p-6 text-white shadow-xl transform hover:scale-105 transition-all duration-300">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white bg-opacity-10 rounded-full -translate-y-16 translate-x-16"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-indigo-100 text-sm font-medium">Total Appointments</p>
                        <p class="text-4xl font-bold mt-2">{{ $totalAppointments }}</p>
                        <div class="flex items-center mt-3">
                            <i class="fas fa-arrow-up text-green-300 mr-2"></i>
                            <span class="text-indigo-100 text-sm">+12% this week</span>
                        </div>
                    </div>
                    <div class="w-16 h-16 bg-white bg-opacity-20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                        <i class="fas fa-calendar-check text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="relative overflow-hidden bg-gradient-to-br from-cyan-500 via-sky-500 to-blue-500 rounded-2xl p-6 text-white shadow-xl transform hover:scale-105 transition-all duration-300">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white bg-opacity-10 rounded-full -translate-y-16 translate-x-16"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-cyan-100 text-sm font-medium">Active Coaches</p>
                        <p class="text-4xl font-bold mt-2">{{ $activeCoaches }}</p>
                        <div class="flex items-center mt-3">
                            <i class="fas fa-arrow-up text-green-300 mr-2"></i>
                            <span class="text-cyan-100 text-sm">+5% this month</span>
                        </div>
                    </div>
                    <div class="w-16 h-16 bg-white bg-opacity-20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                        <i class="fas fa-user-md text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="relative overflow-hidden bg-gradient-to-br from-orange-500 via-red-500 to-pink-500 rounded-2xl p-6 text-white shadow-xl transform hover:scale-105 transition-all duration-300">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white bg-opacity-10 rounded-full -translate-y-16 translate-x-16"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100 text-sm font-medium">Total Patients</p>
                        <p class="text-4xl font-bold mt-2">{{ $totalPatients }}</p>
                        <div class="flex items-center mt-3">
                            <i class="fas fa-arrow-up text-green-300 mr-2"></i>
                            <span class="text-orange-100 text-sm">+8% this month</span>
                        </div>
                    </div>
                    <div class="w-16 h-16 bg-white bg-opacity-20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                        <i class="fas fa-users text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="relative overflow-hidden bg-gradient-to-br from-gray-600 via-gray-700 to-gray-800 rounded-2xl p-6 text-white shadow-xl transform hover:scale-105 transition-all duration-300">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white bg-opacity-10 rounded-full -translate-y-16 translate-x-16"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-100 text-sm font-medium">Pending Reports</p>
                        <p class="text-4xl font-bold mt-2">{{ $pendingReports }}</p>
                        <div class="flex items-center mt-3">
                            <i class="fas fa-arrow-down text-red-300 mr-2"></i>
                            <span class="text-gray-100 text-sm">-3% this week</span>
                        </div>
                    </div>
                    <div class="w-16 h-16 bg-white bg-opacity-20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                        <i class="fas fa-file-alt text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Weekly Appointment Calendar -->
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 overflow-hidden">
        <div class="bg-cyan-500 px-8 py-6">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold text-white">Calendrier des Rendez-vous des Coachs</h2>
                <div class="flex items-center gap-4">
                    <a href="{{ route('global_dashboard', ['week' => $prevWeekStart]) }}" class="bg-white/20 backdrop-blur-sm text-white px-4 py-2 rounded-xl hover:bg-white/30 transition-all duration-200 flex items-center gap-2">
                        <i class="fas fa-chevron-left"></i>
                        Précédent
                    </a>
                    <span class="text-white font-medium px-4 py-2 bg-white/10 rounded-xl">
                    {{ \Carbon\Carbon::parse($currentWeekStart)->format('d M, Y') }} - 
                    {{ \Carbon\Carbon::parse($currentWeekStart)->addDays(5)->format('d M, Y') }}
                </span>
                    <a href="{{ route('global_dashboard', ['week' => $nextWeekStart]) }}" class="bg-white/20 backdrop-blur-sm text-white px-4 py-2 rounded-xl hover:bg-white/30 transition-all duration-200 flex items-center gap-2">
                        Suivant
                        <i class="fas fa-chevron-right"></i>
                </a>
                </div>
            </div>
        </div>

        <div class="p-8">
        <!-- Desktop Table View -->
            <div class="border border-gray-200 rounded-xl overflow-hidden">
                <table class="w-full">
                <thead>
                    <tr>
                            <th class="bg-cyan-500 text-white p-4 font-semibold text-left">Jour/Coach</th>
                        @foreach ($timeSlots as $slot)
                                <th class="bg-cyan-500 text-white p-4 font-semibold text-center">{{ $slot['start'] }} - {{ $slot['end'] }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($days as $day)
                        <tr>
                                <td colspan="{{ count($timeSlots) + 1 }}" class="bg-gray-50 p-4 font-semibold text-center text-gray-700 border-b border-gray-200">
                                {{ \Carbon\Carbon::parse($day)->format('l, d M, Y') }}
                            </td>
                        </tr>
                        
                        @foreach ($coaches as $coach)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="p-4 font-medium text-gray-700 text-center border-r border-gray-200 bg-white">{{ $coach->full_name }}</td>
                                @foreach ($timeSlots as $slot)
                                    @php
                                        $slotStart = strtotime($day . ' ' . $slot['start']);
                                        $slotEnd = strtotime($day . ' ' . $slot['end']);
                                        $cellAppointments = [];
                                        
                                        if(isset($organizedAppointments[$day][$coach->id])) {
                                       
                                            foreach ($organizedAppointments[$day][$coach->id] as $appointment) {
                                                $apptStart = strtotime($day . ' ' . $appointment['startTime']);
                                                if ($apptStart >= $slotStart && $apptStart < $slotEnd) {
                                                    $cellAppointments[] = $appointment;
                                                }
                                            }
                                        }
                                    @endphp
                                    
                                        <td class="p-2 border border-gray-200">
                                        @foreach ($cellAppointments as $appointment)
                                                <div class="appointment-card {{ $appointment['status'] }} bg-gradient-to-r from-cyan-50 to-sky-50 border border-cyan-200 rounded-xl p-3 mb-2 shadow-sm hover:shadow-md transition-all duration-200" data-app-id="{{ $appointment['id'] }}">
                                                    <div class="font-semibold text-cyan-900 text-sm mb-2">{{ $appointment['patient'] }}</div>
                                                    <div class="flex gap-1 justify-center">
                                                        <a href="{{ route('appointment.edit', $appointment['id']) }}" class="w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center text-xs hover:bg-red-600 transition-colors" title="Annuler le Rendez-vous">
                                                        <i class="fa-regular fa-rectangle-xmark"></i>
                                                    </a>
                                                        <button type="button" onclick="openEditModal(this)" class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center text-xs hover:bg-blue-600 transition-colors" title="Modifier">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                    </button>
                                                        <button type="button" class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-xs hover:bg-green-600 transition-colors" title="Changer Patient"
                                                        onclick="openChangeModal(
                                                                <?php echo $appointment['speciality_id']?>,
                                                                '<?php echo $day ?>',
                                                                '<?php echo $appointment['startTime']?>',
                                                                '<?php echo $appointment['endTime'] ?>',
                                                                <?php echo $appointment['patient_id']; ?>,
                                                                this
                                                        )">
                                                        <i class="fa-solid fa-arrows-rotate"></i>
                                                    </button>
                                                        <button type="button" onclick="window.location.href='appointment/<?php echo $appointment['id'] ?>'" class="w-8 h-8 bg-purple-500 text-white rounded-full flex items-center justify-center text-xs hover:bg-purple-600 transition-colors" title="Détails">
                                                        <i class="fa-solid fa-circle-info"></i>
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

        <!-- Mobile Calendar View -->
            <div class="mt-8 lg:hidden">
            @foreach ($days as $day)
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 mb-6 overflow-hidden">
                        <div class="bg-gradient-to-br from-cyan-500 via-sky-500 to-blue-500 p-4">
                            <h3 class="text-white font-semibold text-center">{{ \Carbon\Carbon::parse($day)->format('l, d M, Y') }}</h3>
                    </div>
                    
                    @foreach ($coaches as $coach)
                            <div class="border-t border-gray-200">
                                <div class="bg-gray-50 p-4 flex justify-between items-center">
                                    <span class="font-semibold text-gray-700">{{ $coach->full_name }}</span>
                                    <button type="button" class="text-gray-500 hover:text-gray-700" onclick="toggleCoachTimeslots(this)">
                                    <i class="fa-solid fa-chevron-down"></i>
                                </button>
                            </div>
                                <div class="mobile-timeslots p-4" style="display: none;">
                                @foreach ($timeSlots as $slot)
                                    @php
                                        $slotStart = strtotime($day . ' ' . $slot['start']);
                                        $slotEnd = strtotime($day . ' ' . $slot['end']);
                                        $slotAppointments = [];
                                        
                                        if(isset($organizedAppointments[$day][$coach->id])) {
                                            foreach ($organizedAppointments[$day][$coach->id] as $appointment) {
                                                $apptStart = strtotime($day . ' ' . $appointment['startTime']);
                                                if ($apptStart >= $slotStart && $apptStart < $slotEnd) {
                                                    $slotAppointments[] = $appointment;
                                                }
                                            }
                                        }
                                    @endphp
                                    
                                    @if(count($slotAppointments) > 0)
                                            <div class="mb-4 pb-4 border-b border-gray-200 last:border-b-0">
                                                <div class="font-medium text-gray-700 mb-2">
                                                {{ $slot['start'] }} - {{ $slot['end'] }}
                                            </div>
                                                <div class="space-y-2">
                                                @foreach ($slotAppointments as $appointment)
                                                        <div class="appointment-card {{ $appointment['status'] }} bg-gradient-to-r from-cyan-50 to-sky-50 border border-cyan-200 rounded-xl p-3" data-app-id="{{ $appointment['id'] }}">
                                                            <div class="font-semibold text-cyan-900 text-sm mb-2">{{ $appointment['patient'] }}</div>
                                                            <div class="flex gap-1 justify-center">
                                                                <a href="{{ route('appointment.edit', $appointment['id']) }}" class="w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center text-xs hover:bg-red-600 transition-colors" title="Annuler le Rendez-vous">
                                                                <i class="fa-regular fa-rectangle-xmark"></i>
                                                            </a>
                                                                <button type="button" onclick="openEditModal(this)" class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center text-xs hover:bg-blue-600 transition-colors" title="Modifier">
                                                                <i class="fa-solid fa-pen-to-square"></i>
                                                            </button>
                                                                <button type="button" class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-xs hover:bg-green-600 transition-colors" title="Changer Patient"
                                                                onclick="openChangeModal(
                                                                        <?php echo $appointment['speciality_id']?>,
                                                                        '<?php echo $day ?>',
                                                                        '<?php echo $appointment['startTime']?>',
                                                                        '<?php echo $appointment['endTime'] ?>',
                                                                        <?php echo $appointment['patient_id']; ?>,
                                                                        this
                                                                )">
                                                                <i class="fa-solid fa-arrows-rotate"></i>
                                                            </button>
                                                                <button type="button" onclick="window.location.href='appointment/<?php echo $appointment['id'] ?>'" class="w-8 h-8 bg-purple-500 text-white rounded-full flex items-center justify-center text-xs hover:bg-purple-600 transition-colors" title="Détails">
                                                                <i class="fa-solid fa-circle-info"></i>
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
            @endforeach
            </div>
        </div>
    </div>

    <!-- Recent Activity & Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Appointments -->
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-cyan-600 px-8 py-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-white">Recent Appointments</h3>
                    <a href="{{ route('appointment.index') }}" class="bg-white/20 backdrop-blur-sm text-white px-4 py-2 rounded-xl hover:bg-white/30 transition-all duration-200 flex items-center gap-2">
                        View All
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
            
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($recentAppointments as $appointment)
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl p-4 hover:shadow-lg transition-all duration-200 border border-gray-200/50">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center">
                                <i class="fas fa-calendar text-white"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900">
                                    @if($appointment->patient->first_name)
                                        {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}
                                    @else
                                        {{ $appointment->patient->parent_first_name }} {{ $appointment->patient->parent_last_name }}
                                    @endif
                                </p>
                                <p class="text-sm text-gray-600">
                                    with {{ $appointment->coach->full_name }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    @php
                                        $planning = json_decode($appointment->appointment_planning, true);
                                        $firstDate = $planning ? array_keys($planning)[0] : null;
                                        $firstTime = $planning ? $planning[array_keys($planning)[0]]['startTime'] : null;
                                    @endphp
                                    @if($firstDate && $firstTime)
                                        {{ \Carbon\Carbon::parse($firstDate . ' ' . $firstTime)->format('M d, Y H:i') }}
                                    @else
                                        No date set
                                    @endif
                                </p>
                            </div>
                            <div class="text-right">
                                @if($appointment->status === 'pending')
                                    <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-medium">Pending</span>
                                @elseif($appointment->status === 'passed')
                                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-medium">Completed</span>
                                @else
                                    <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-medium">Cancelled</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-12">
                        <i class="fas fa-calendar-times text-6xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500 text-lg">No recent appointments</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 overflow-hidden">
            <div class="bg-gradient-to-r from-cyan-500 to-sky-600 px-8 py-6">
                <h3 class="text-xl font-bold text-white">Quick Actions</h3>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <a href="{{ route('appointment.create') }}" class="group bg-gradient-to-r from-cyan-50 to-sky-50 border border-cyan-200 rounded-2xl p-4 hover:shadow-lg transition-all duration-200 hover:scale-105">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-cyan-500 via-sky-500 to-cyan-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                <i class="fas fa-plus text-white"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">New Appointment</p>
                                <p class="text-sm text-gray-600">Schedule a new session</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('patient.create') }}" class="group bg-gradient-to-r from-cyan-50 to-sky-50 border border-cyan-200 rounded-2xl p-4 hover:shadow-lg transition-all duration-200 hover:scale-105">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-cyan-500 to-sky-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                <i class="fas fa-user-plus text-white"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">Add Patient</p>
                                <p class="text-sm text-gray-600">Register new patient</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('user.create') }}" class="group bg-gradient-to-r from-orange-50 to-red-50 border border-orange-200 rounded-2xl p-4 hover:shadow-lg transition-all duration-200 hover:scale-105">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                <i class="fas fa-user-md text-white"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">Add Coach</p>
                                <p class="text-sm text-gray-600">Register new coach</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('suggested-appointments') }}" class="group bg-gradient-to-r from-indigo-50 to-blue-50 border border-indigo-200 rounded-2xl p-4 hover:shadow-lg transition-all duration-200 hover:scale-105">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-blue-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                <i class="fas fa-lightbulb text-white"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">Suggestions</p>
                                <p class="text-sm text-gray-600">View appointment suggestions</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Change Patient Modal -->
<div id="changePatientModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full">
            <div class="bg-gradient-to-br from-cyan-500 via-sky-500 to-blue-500 p-6 rounded-t-3xl">
                <div class="flex items-center justify-between">
                    <h5 class="text-white font-semibold text-lg">Changer de Patient</h5>
                    <button type="button" class="text-white hover:text-gray-200 text-2xl" onclick="closeChangeModal()">×</button>
                </div>
            </div>
            <div id="errorMsg" class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mx-4 mt-4 hidden"></div>
            <div class="p-6">
                <div class="flex gap-3 mb-6">
                    <button class="flex-1 bg-gradient-to-br from-cyan-500 via-sky-500 to-blue-500 text-white px-4 py-2 rounded-xl hover:from-cyan-600 hover:via-sky-600 hover:to-blue-600 transition-all duration-200" 
                        onclick="selectRandomAutoPatient()">
                        Automatique
                    </button>
                    <button class="flex-1 bg-gradient-to-br from-cyan-500 via-sky-500 to-blue-500 text-white px-4 py-2 rounded-xl hover:from-cyan-600 hover:via-sky-600 hover:to-blue-600 transition-all duration-200" 
                        onclick="manualPatient()">
                        Manuel
                    </button>
                </div>
                <div class="hidden" id="autoGeneratedPatientContainer">
                    <h6 class="font-semibold text-gray-700 mb-2">Patients Disponibles</h6>
                    <p class="text-sm text-gray-600 mb-3">Patient généré automatiquement en fonction de la date et du créneau</p>
                    <label class="autoGeneratedPatientLabel block bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-gray-900"></label>
                    <input type="hidden" id="autoGeneratedPatient" name="autoGeneratedPatient" readonly>
                </div>

                <div class="hidden" id="manualPatient">
                    <h6 class="font-semibold text-gray-700 mb-2">Nom De Patient</h6>
                    <input type="text" name="manualPatientName" id="manualPatientName" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                </div>
            </div>

            <div class="bg-gray-50 px-6 py-4 rounded-b-3xl flex gap-3">
                <button type="button" class="flex-1 bg-gradient-to-br from-cyan-500 via-sky-500 to-blue-500 text-white px-4 py-2 rounded-xl hover:from-cyan-600 hover:via-sky-600 hover:to-blue-600 transition-all duration-200" onclick="updateSuggestedAppointment()">Mettre à Jour</button>
                <button type="button" class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded-xl hover:bg-gray-400 transition-all duration-200" onclick="closeChangeModal()">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Appointment Modal -->
<div id="editAppointmentModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full">
            <div class="bg-gradient-to-r from-blue-600 to-cyan-600 p-6 rounded-t-3xl">
                <div class="flex items-center justify-between">
                    <h5 class="text-white font-semibold text-lg">Modifier le Rendez-vous</h5>
                    <button type="button" class="text-white hover:text-gray-200 text-2xl" onclick="closeEditModal()">×</button>
                </div>
            </div>
            <div class="p-6">
                <form action="{{ route('update_app_planning')}}" method="post" onsubmit="storeNewPlanning()" id="editAppointmentForm">
                    @csrf
                    @method("Patch")

                    <div class="mb-4">
                        <label for="appointmentDate" class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                        <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="appointmentDate" name="date" required>
                    </div>
                    <div class="mb-4">
                        <label for="startTime" class="block text-sm font-medium text-gray-700 mb-2">Heure de Début</label>
                        <input type="time" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="startTime" name="start_time" required>
                    </div>
                    <div class="mb-6">
                        <label for="endTime" class="block text-sm font-medium text-gray-700 mb-2">Heure de Fin</label>
                        <input type="time" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="endTime" name="end_time" required>
                    </div>
                    <input type="hidden" id="new_planning" name="new_planning">
                    <input type="hidden" id="app_id" name="app_id">
                    <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-cyan-500 text-white px-4 py-2 rounded-xl hover:from-blue-600 hover:to-cyan-600 transition-all duration-200">Enregistrer les Modifications</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
   let currentAppointmentElement = null; 
    let autoGeneratedPatients = [];
    let warningMsg = '';    
   // chacnge patient modal
    function closeChangeModal() {
        document.getElementById('autoGeneratedPatientContainer').classList.add('hidden');
        document.getElementById('changePatientModal').classList.add('hidden');
        document.getElementById('manualPatient').classList.add('hidden');
        document.querySelector('#errorMsg').classList.add('hidden');
        document.getElementById('autoGeneratedPatient').value = '';	
        document.querySelector('.autoGeneratedPatientLabel').innerHTML = '';     
        document.getElementById('allPatientsSelect').innerHTML = '<option value="">Sélectionnez un patient</option>';
        document.querySelector('#errorMsg').innerHTML = '';
        document.querySelector('#manualPatientName').innerHTML = '';
    }    
    function openChangeModal(specialityId, date, startTime, endTime,pateint_id, event) {
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
            }
            else{    
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

        console.log('Body passed:', {
            app_dd: appId,
            autoPatientId: autoGeneratedPatient.value,
            manualPatientName: manualPatientName.value
        });
        const uri ="{{ route('update.patient') }}";

        fetch(uri, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
            body: JSON.stringify({
                app_id: appId,
                autoPatientId: autoGeneratedPatient.value ,
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
        document.querySelector('#manualPatient').classList.add('hidden');
        document.querySelector('#errorMsg').classList.add('hidden');
        document.querySelector('#errorMsg').innerHTML = '';

        if (!autoGeneratedPatients || autoGeneratedPatients.length === 0) {
            document.querySelector('#errorMsg').innerHTML = warningMsg;
            document.querySelector('#errorMsg').classList.remove('hidden');
            return;
        }

        const randomIndex = Math.floor(Math.random() * autoGeneratedPatients.length);
        const randomPatient = autoGeneratedPatients[randomIndex];

        console.log('Random Patient:', randomPatient);
        
        const autoGeneratedPatientInput = document.getElementById('autoGeneratedPatient');
        const autoGeneratedPatientLabel = document.querySelector('.autoGeneratedPatientLabel');        
        document.querySelector('#autoGeneratedPatientContainer').classList.remove('hidden');
        autoGeneratedPatientInput.value = randomPatient.id;
        autoGeneratedPatientLabel.textContent = randomPatient.full_name;

    }
    
    function manualPatient() {
        document.querySelector('#manualPatient').classList.remove('hidden');
        document.querySelector('#autoGeneratedPatientContainer').classList.add('hidden');
        document.querySelector('#autoGeneratedPatient').innerHTML='';
        document.querySelector('#errorMsg').classList.add('hidden');
        document.querySelector('#errorMsg').innerHTML = '';
    }
//     edit modal
    function openEditModal(event) {
        currentAppointmentId = event.closest('.appointment-card');

        document.getElementById('editAppointmentModal').classList.remove('hidden');
        document.getElementById('app_id').value= currentAppointmentId.dataset.appId;
        console.log( document.getElementById('app_id').value,currentAppointmentId.dataset.appId);
    }  
   
    function closeEditModal() {
        document.getElementById('editAppointmentModal').classList.add('hidden');
        document.getElementById('appointmentDate').value = null;
        document.getElementById('startTime').value = null;
        document.getElementById('endTime').value = null;  
    }
    function storeNewPlanning() {
        const date = document.getElementById('appointmentDate').value;
        const startTime = document.getElementById('startTime').value;
        const endTime = document.getElementById('endTime').value;

        if (!date || !startTime || !endTime) {
            alert('Please fill in all fields before saving.');
            return false;
        }

        const newPlanning = {
            [date]: {
                startTime: startTime,
                endTime: endTime
            }
        };

        document.getElementById('new_planning').value = JSON.stringify(newPlanning);
        closeEditModal();
        return true;
    }

    // Toggle coach timeslots in mobile view
    function toggleCoachTimeslots(button) {
        const timeslotsContainer = button.closest('.mobile-coach-section').querySelector('.mobile-timeslots');
        const icon = button.querySelector('i');
        
        if (timeslotsContainer.style.display === 'none') {
            timeslotsContainer.style.display = 'block';
            icon.classList.remove('fa-chevron-down');
            icon.classList.add('fa-chevron-up');
        } else {
            timeslotsContainer.style.display = 'none';
            icon.classList.remove('fa-chevron-up');
            icon.classList.add('fa-chevron-down');
        }
    }
    // Initialize mobile view
    document.addEventListener('DOMContentLoaded', function() {
        const coachSections = document.querySelectorAll('.mobile-coach-section');
        coachSections.forEach(section => {
            const timeslots = section.querySelector('.mobile-timeslots');
            if (timeslots) {
                timeslots.style.display = 'none';
            }
        });
    });
</script>
@endsection
