@extends('layouts.app')
@section('content')
@php
$patient_first_name = ($patient->patient_type=='kid'|| $patient->patient_type=='young')? $patient->first_name : $patient->parent_first_name ;
$patient_last_name = ($patient->patient_type=='kid'|| $patient->patient_type=='young')? $patient->last_name : $patient->parent_last_name;
$patient_full_name = $patient_first_name . ' ' . $patient_last_name;
@endphp

<div class="min-h-screen bg-gradient-to-br from-cyan-50 via-sky-50 to-blue-50 p-6 mt-24">
    <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar -->
        <div class="lg:col-span-1 bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 p-8 flex flex-col items-center">
        @if ($patient->image_path)
                <img src="{{ asset('storage/' . $patient->image_path) }}" alt="Image Preview" class="w-28 h-28 rounded-full object-cover border-4 border-cyan-300 shadow-lg mb-4">
        @else
                <div class="w-28 h-28 bg-gradient-to-br from-cyan-400 to-sky-500 rounded-full flex items-center justify-center text-white font-bold text-3xl shadow-lg mb-4">
                {{ strtoupper(substr($patient_full_name, 0, 1)) }}
            </div>
        @endif
            <h2 class="text-xl font-bold text-gray-900 mb-1">{{ $patient_full_name }}</h2>
            <span class="text-sm text-gray-500 mb-4">ID: {{ $patient->id }}</span>
            <form action="{{ route('patient.edit', $patient->id) }}" method="GET" class="w-full">
                    @csrf
                <button class="w-full bg-gradient-to-r from-cyan-500 to-sky-600 text-white px-4 py-2 rounded-xl font-semibold hover:from-cyan-600 hover:to-sky-700 transition-all duration-200 flex items-center justify-center gap-2 shadow-lg hover:shadow-xl">
                    <i class="fas fa-edit"></i> Modifier le patient
                    </button>
            </form>
            <div class="grid grid-cols-2 gap-4 mt-8 w-full">
                <div class="bg-cyan-50 rounded-xl p-4 flex flex-col items-center shadow">
                    <i class="fas fa-user-tag text-cyan-600 text-2xl mb-2"></i>
                    <span class="text-xs text-gray-500">Type</span>
                    <span class="font-semibold text-gray-900">{{ ucfirst($patient->patient_type) }}</span>
                </div>
                <div class="bg-cyan-50 rounded-xl p-4 flex flex-col items-center shadow">
                    <i class="fas fa-birthday-cake text-cyan-600 text-2xl mb-2"></i>
                    <span class="text-xs text-gray-500">Âge</span>
                    <span class="font-semibold text-gray-900">{{ $patient->age }} ans</span>
                </div>
                <div class="bg-cyan-50 rounded-xl p-4 flex flex-col items-center shadow">
                    <i class="fas fa-{{ $patient->gender == 'M' ? 'mars' : 'venus' }} text-cyan-600 text-2xl mb-2"></i>
                    <span class="text-xs text-gray-500">Genre</span>
                    <span class="font-semibold text-gray-900">{{ $patient->gender == 'M' ? 'Homme' : 'Femme' }}</span>
                </div>
                <div class="bg-cyan-50 rounded-xl p-4 flex flex-col items-center shadow">
                    <i class="fas fa-calendar-week text-cyan-600 text-2xl mb-2"></i>
                    <span class="text-xs text-gray-500">Quota hebdo.</span>
                    <span class="font-semibold text-gray-900">{{ $patient->weekly_quota }} Max</span>
                </div>
            </div>
        </div>
    <!-- Main Content -->
        <div class="lg:col-span-3 flex flex-col gap-8">
            <!-- Tabs -->
            <div class="flex flex-wrap gap-2 bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 px-6 py-4 mb-2">
                <button class="tab-btn px-4 py-2 rounded-xl font-semibold text-cyan-700 hover:bg-cyan-100 transition" id="personal" onclick="showTab('personal', this)">Informations personnelles</button>
                <button class="tab-btn px-4 py-2 rounded-xl font-semibold text-cyan-700 hover:bg-cyan-100 transition" onclick="showTab('medical', this)">Informations médicales</button>
                <button class="tab-btn px-4 py-2 rounded-xl font-semibold text-cyan-700 hover:bg-cyan-100 transition" onclick="showTab('coaches', this)">Informations des coachs</button>
                <button class="tab-btn px-4 py-2 rounded-xl font-semibold text-cyan-700 hover:bg-cyan-100 transition" onclick="showTab('appointmentsHistory', this)">Rendez-vous</button>
                <button class="tab-btn px-4 py-2 rounded-xl font-semibold text-cyan-700 hover:bg-cyan-100 transition" onclick="showTab('calendar', this)">Priorités</button>
        </div>
        <!-- Personal Info Tab -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 p-8 profile-tab-content" id="personalTab">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                        <h3 class="text-lg font-bold mb-4 text-cyan-700">Informations de contact</h3>
                    <div class="space-y-2">
                            <p><i class="fas fa-phone mr-2 text-cyan-500"></i> {{ $patient->phone }}</p>
                            <p><i class="fas fa-envelope mr-2 text-cyan-500"></i> {{ $patient->email }}</p>
                            <p><i class="fas fa-map-marker-alt mr-2 text-cyan-500"></i> {{ $patient->address }}</p>
                        </div>
                    </div>
                @if ($patient->patient_type == 'kid' || $patient->patient_type == 'young')
                <div>
                        <h3 class="text-lg font-bold mb-4 text-cyan-700">Détails du tuteur</h3>
                    <div class="space-y-2">
                            <p><i class="fas fa-user-tie mr-2 text-cyan-500"></i> {{ $patient->parent_first_name }} {{ $patient->parent_last_name }}</p>
                            <p><i class="fas fa-briefcase mr-2 text-cyan-500"></i> {{ $patient->profession }}</p>
                            <p><i class="fas fa-building mr-2 text-cyan-500"></i> {{ $patient->etablissment }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        <!-- Medical Info Tab -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 p-8 hidden profile-tab-content" id="medicalTab">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                        <h3 class="text-lg font-bold mb-4 text-cyan-700">Informations sur le traitement</h3>
                    <div class="space-y-2">
                            <p><i class="fas fa-calendar-alt mr-2 text-cyan-500"></i> {{ $patient->subscription }}</p>
                            <p><i class="fas fa-user-md mr-2 text-cyan-500"></i> {{ $patient->speciality->name }}</p>
                            <p><i class="fas fa-laptop-medical mr-2 text-cyan-500"></i> {{ $patient->mode }}</p>
                        </div>
                    </div>
                @if ($patient->patient_type == 'kid' || $patient->patient_type == 'young')
                <div>
                        <h3 class="text-lg font-bold mb-4 text-cyan-700">Détails de l'éducation</h3>
                    <div class="space-y-2">
                            <p><i class="fas fa-school mr-2 text-cyan-500"></i> {{ $patient->ecole }}</p>
                            <p><i class="fas fa-sitemap mr-2 text-cyan-500"></i> {{ $patient->system }} Système</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <!-- Appointments History Tab -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 p-8 hidden profile-tab-content" id="appointmentsHistoryTab">
                <h3 class="text-lg font-bold mb-4 text-cyan-700">Historique des rendez-vous</h3>
                <div class="overflow-x-auto rounded-xl">
                    <table class="min-w-full text-sm appointments-table">
                                    <thead>
                            <tr class="bg-gradient-to-r from-cyan-500 to-sky-600 text-white">
                                <th class="px-6 py-4 text-left font-semibold uppercase tracking-wider">Entraîneur</th>
                                <th class="px-6 py-4 text-left font-semibold uppercase tracking-wider">Date & Heure</th>
                                <th class="px-6 py-4 text-left font-semibold uppercase tracking-wider">Statut</th>
                                <th class="px-6 py-4 text-left font-semibold uppercase tracking-wider">Rapport</th>
                                <th class="px-6 py-4 text-left font-semibold uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($appointments!=null)
                                            @foreach ($appointments as $appointment)
                                                <tr>
                                        <td class="py-2 px-4">{{ $appointment->coach->full_name }}</td>
                                        @php $appointmentDate = json_decode($appointment->appointment_planning, true); @endphp
                                        <td class="py-2 px-4">
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
                                                $color = 'bg-yellow-100 text-yellow-800';
                                                        } elseif ($appointment->status == 'passed') {
                                                $color = 'bg-green-100 text-green-800';
                                                        } elseif ($appointment->status == 'cancel') {
                                                $color = 'bg-red-100 text-red-800';
                                                        }
                                                    @endphp
                                        <td class="py-2 px-4"><span class="px-3 py-1 rounded-xl font-semibold {{ $color }}">{{ $appointment->status }}</span></td>
                                                    @if ($appointment->report_path)
                                        <td class="py-2 px-4">
                                            <a href="{{ route('appointments.viewReport', $appointment->id) }}" target="_blank" class="text-cyan-600 hover:text-cyan-800 mr-2"><i class="fas fa-file-alt"></i></a>
                                            <a href="{{ route('appointments.downloadReport', $appointment->id) }}" class="text-cyan-600 hover:text-cyan-800"><i class="fas fa-cloud-download-alt"></i></a>
                                                    </td>
                                                    @else
                                        <td class="py-2 px-4 text-gray-400">Pas de rapport</td>
                                                    @endif
                                        <td class="py-2 px-4 text-center">
                                            <a href="{{ route('appointment.show', $appointment->id) }}" class="inline-block bg-gradient-to-r from-cyan-500 to-sky-600 text-white px-3 py-1 rounded-xl font-semibold hover:from-cyan-600 hover:to-sky-700 transition-all duration-200"><i class="fas fa-eye"></i></a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                    </div>
            </div>
            <!-- Coaches Tab -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 p-8 hidden profile-tab-content" id="coachesTab">
                <h3 class="text-lg font-bold mb-4 text-cyan-700">Coachs assignés</h3>
                <p class="bg-cyan-50 text-cyan-700 rounded-xl px-4 py-2 mb-4">Sélectionnez qui peut voir <q><b><i>{{ $patient_full_name }}</i></b></q> rapports</p>
                <form action="{{ route('who_can_See',$patient->id) }}" method="post" class="space-y-2">
                        @csrf
                        @foreach ($patient->coaches as $coach)
                        <div class="flex items-center gap-2">
                            <input type="checkbox" id="coachCheckbox{{ $coach->id }}" name="coaches[]" value="{{ $coach->id }}" {{ in_array($patient->id, $coach->can_see ?? []) ? 'checked' : '' }} class="form-checkbox h-5 w-5 text-cyan-600 focus:ring-cyan-500">
                            <label class="text-gray-700 font-medium" for="coachCheckbox{{ $coach->id }}">{{ $coach->full_name }}</label>
                        </div>
                    @endforeach
                    <div class="text-center w-full mt-4">
                        <button type="submit" class="bg-gradient-to-r from-cyan-500 to-sky-600 text-white px-6 py-2 rounded-xl font-semibold hover:from-cyan-600 hover:to-sky-700 transition-all duration-200 shadow-lg">Enregistrer</button>
                    </div>
                </form>
                </div>
            <!-- Calendar Tab -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 p-8 hidden profile-tab-content" id="calendarTab">
                <h3 class="text-lg font-bold mb-4 text-cyan-700">Priorités</h3>
                <div id="availabilities-calendar"></div>
            </div>
        </div>
    </div>
</div>
<script>
// Tab navigation
    let availabilitiesCalendarInstance;
    function showTab(tabName, element) {
    document.querySelectorAll('.tab-btn').forEach(item => item.classList.remove('bg-gradient-to-r', 'from-cyan-500', 'to-sky-600', 'text-white'));
    document.querySelectorAll('.tab-btn').forEach(item => item.classList.add('text-cyan-700'));
    document.querySelectorAll('.profile-tab-content').forEach(el => el.classList.add('hidden'));
        if (element) {
        element.classList.add('bg-gradient-to-r', 'from-cyan-500', 'to-sky-600', 'text-white');
        element.classList.remove('text-cyan-700');
    }
    document.getElementById(tabName + 'Tab').classList.remove('hidden');
        if (tabName === 'calendar' && availabilitiesCalendarInstance) {
            setTimeout(() => {
            availabilitiesCalendarInstance.render();
            availabilitiesCalendarInstance.updateSize();
            }, 100);
        }
    }
    document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.tab-btn').forEach(item => item.classList.remove('bg-gradient-to-r', 'from-cyan-500', 'to-sky-600', 'text-white'));
    document.querySelectorAll('.tab-btn').forEach(item => item.classList.add('text-cyan-700'));
    document.querySelectorAll('.profile-tab-content').forEach(el => el.classList.add('hidden'));
    document.getElementById('personal').classList.add('bg-gradient-to-r', 'from-cyan-500', 'to-sky-600', 'text-white');
    document.getElementById('personal').classList.remove('text-cyan-700');
    document.getElementById('personalTab').classList.remove('hidden');
        availabilitiesCalendarInstance = initAvailabilitiesCalendar();
    });
// Calendar initialization (unchanged)
    function initAvailabilitiesCalendar() {
        let initialDate;
        var calendarEl = document.getElementById("availabilities-calendar");
        var allPatientPriorities = 
        <?php
            $patientPriorities = json_decode($patient->priorities, true);
            $allPriorities = [];
            foreach ($patientPriorities as $priorityKey => $data) {
                $priorityClass = '';
                switch ($priorityKey) {
                case 'priority 1': $priorityClass = 'priority1'; break;
                case 'priority 2': $priorityClass = 'priority2'; break;
                case 'priority 3': $priorityClass = 'priority3'; break;
            }
                foreach ($data as $day => $slots) {
                    foreach ($slots as $slot) {
                        $startTime = $slot['startTime'] . ':00';
                        $endTime = $slot['endTime'] . ':00';
                        $allPriorities[] = [
                            'id' => $slot['id'],
                            'start' => $day . 'T' . $startTime,
                            'end' => $day . 'T' . $endTime,
                            'className' => $priorityClass,
                        'extendedProps' => [ 'priority' => $priorityKey ]
                        ];
                    }
                }
            }
            echo json_encode($allPriorities);
        ?>;
        initialDate = allPatientPriorities.length > 0 ? allPatientPriorities[0].start : null;
    initialDate = initialDate ? initialDate.split('T')[0] : undefined;
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
    if (initialDate) calendar.gotoDate(initialDate);
        return calendar;
}
</script>
@endsection