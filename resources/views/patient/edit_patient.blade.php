@extends('layouts.app')
@section('content')
<!-- FullCalendar CSS and JS -->
<link href='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.10/main.min.css' rel='stylesheet' />
<link href='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.10/main.min.css' rel='stylesheet' />
<link href='https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@6.1.10/main.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.10/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.10/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@6.1.10/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/interaction@6.1.10/main.min.js'></script>

<style>
/* Custom calendar styling to match the theme */
.fc-header-toolbar {
    background: linear-gradient(195deg, #06b6d4, #0891b2, #0ea5e9) !important;
    color: white !important;
    padding: 1rem !important;
    border-radius: 12px 12px 0 0 !important;
    margin-bottom: 0 !important;
}

.fc-button-primary {
    background-color: rgba(255, 255, 255, 0.2) !important;
    border-color: rgba(255, 255, 255, 0.3) !important;
    color: white !important;
}

.fc-button-primary:hover {
    background-color: rgba(255, 255, 255, 0.3) !important;
    border-color: rgba(255, 255, 255, 0.4) !important;
}

.fc-button-active {
    background-color: rgba(255, 255, 255, 0.4) !important;
    border-color: rgba(255, 255, 255, 0.5) !important;
    }
    
    .priority1 .fc-event-title,
    .priority1 .fc-event-time {
    color: rgb(206, 1, 1) !important; 
    }

    .priority2 .fc-event-title,
    .priority2 .fc-event-time {
    color: rgb(251, 145, 6) !important; 
    }

    .priority3 .fc-event-title,
    .priority3 .fc-event-time {
    color: rgb(1, 174, 27) !important;
}

.priority1 { 
    background: rgba(255, 76, 76, 0.1) !important; 
    border-left: 4px solid #ff4c4c !important; 
}

.priority2 { 
    background: rgba(255, 152, 0, 0.1) !important; 
    border-left: 4px solid #ff9800 !important; 
}

.priority3 { 
    background: rgba(76, 175, 80, 0.1) !important; 
    border-left: 4px solid #4caf50 !important; 
}

    #calendar {
    border-radius: 0 0 12px 12px !important;
    overflow: hidden !important;
}
</style>
<div class="min-h-screen bg-gradient-to-br from-cyan-50 via-sky-50 to-blue-50 p-6 mt-24">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 p-8">
            <h3 class="text-2xl font-bold text-center mb-8 bg-gradient-to-r from-cyan-500 via-sky-500 to-blue-500 text-white py-4 rounded-2xl shadow-lg">✨ Mettre à jour le profil du patient</h3>
            <form action="{{ route('patient.update', $patient->id) }}" onsubmit="storePriorities()" enctype="multipart/form-data" method="post" class="space-y-8">
            @csrf
            @method('PUT')
            @error('patient_exists')
                    <div class="bg-red-100 text-red-700 px-4 py-3 rounded-xl mb-4">{{ $message }}</div>
            @enderror
                <!-- Type de patient -->
                <div>
                    <label for="patientType" class="block text-sm font-semibold text-cyan-700 mb-2">Type de patient</label>
                    <select id="patientType" name="patient_type" class="form-select w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200 bg-white/50 backdrop-blur-sm" required>
                        <option value="" selected disabled>Sélectionner le type de patient</option>
                        <option value="kid" {{  $patient->patient_type == 'kid' ? 'selected' : '' }}>Enfant</option>
                        <option value="young" {{ $patient->patient_type == 'young' ? 'selected' : '' }}>Jeune</option>
                        <option value="adult" {{ $patient->patient_type == 'adult' ? 'selected' : '' }}>Adulte</option>
                    </select>
                </div>
                <!-- Âge, sexe, spécialité -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-cyan-700 mb-2">Âge</label>
                        <input type="number" name="age" id="kidAge" class="form-control w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200 bg-white/50 backdrop-blur-sm" value="{{ $patient->age}}" placeholder="Âge" required>
            </div>
                    <div>
                        <label class="block text-sm font-semibold text-cyan-700 mb-2">Sexe</label>
                        <select name="PatientGender" id="PatientGender" class="form-select w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200 bg-white/50 backdrop-blur-sm" required>
                            <option value="M" {{ $patient->gender == 'M' ? 'selected' : '' }}>Masculin</option>
                            <option value="F" {{ $patient->gender == 'F' ? 'selected' : ''}}>Féminin</option>
                        </select>
                </div>
                    <div>
                        <label class="block text-sm font-semibold text-cyan-700 mb-2">Spécialité</label>
                        <select class="form-select w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200 bg-white/50 backdrop-blur-sm" id="specialtySelect" name="speciality_id" required>
                        <option value="">Toutes les spécialités</option>
                    @foreach($specialities as $speciality)
                        <option value="{{ $speciality->id }}"  {{ $patient->speciality_id ==$speciality->id ? 'selected' :''}}>{{ $speciality->name }}</option>
                    @endforeach
                    </select>
                </div>
            </div>
            <!-- Section Enfant/Jeune -->
                <div id="kidSection" class="hidden">
                    <h4 class="text-lg font-semibold text-cyan-700 border-l-4 border-cyan-400 pl-3 mb-4 kid-title">Informations sur l'enfant</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-cyan-700 mb-2">Nom de famille</label>
                            <input type="text" id="kidLastName" name="kid_last_name" class="form-control w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200 bg-white/50 backdrop-blur-sm" value="{{ ($patient->patient_type=='kid'||$patient->patient_type=='young') ? $patient->last_name : ''}}" placeholder="Nom de famille">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-cyan-700 mb-2">Prénom</label>
                            <input type="text" id="kidFirstName" name="kid_first_name" class="form-control w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200 bg-white/50 backdrop-blur-sm" value="{{ ($patient->patient_type=='kid'||$patient->patient_type=='young') ? $patient->first_name : ''}}" placeholder="Prénom">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-cyan-700 mb-2">École</label>
                            <input type="text" id="kidEcole" name="kid_ecole" class="form-control w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200 bg-white/50 backdrop-blur-sm" value="{{ ($patient->patient_type=='kid'||$patient->patient_type=='young') ? $patient->ecole : ''}}" placeholder="École">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-cyan-700 mb-2">Système</label>
                            <select id="kidSystem" name="kid_system" class="form-select w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200 bg-white/50 backdrop-blur-sm">
                                <option value="" selected disabled>Sélectionner le système</option>
                                <option value="moroccan" {{ $patient->system == 'moroccan' ? 'selected' :'' }}>Système marocain</option>
                                <option value="mission" {{ $patient->system == 'mission' ? 'selected' :'' }}>Système mission</option>
                            </select>
                        </div>
                    </div>
                </div>
            <!-- Section Parent -->
                <div id="parentSection" class="hidden">
                    <h4 class="text-lg font-semibold text-cyan-700 border-l-4 border-cyan-400 pl-3 mb-4 parent-title">Informations sur le parent</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-cyan-700 mb-2">Nom de famille</label>
                            <input type="text" name="parent_last_name" class="form-control w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200 bg-white/50 backdrop-blur-sm" id="parentLastName" placeholder="Nom de famille" value = '{{ $patient->parent_last_name }}' required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-cyan-700 mb-2">Prénom</label>
                            <input type="text" name="parent_first_name" class="form-control w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200 bg-white/50 backdrop-blur-sm" id="parentFirstName" placeholder="Prénom" value = '{{$patient->parent_first_name }}' required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-cyan-700 mb-2">Numéro de téléphone</label>
                            <input type="tel" name="parent_phone" class="form-control w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200 bg-white/50 backdrop-blur-sm" id="parentPhone" placeholder="Téléphone" value = '{{ $patient->phone }}' required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-cyan-700 mb-2">Adresse e-mail</label>
                            <input type="email" id="parentEmail" name="parent_email" class="form-control w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200 bg-white/50 backdrop-blur-sm @error('parent_email') border-red-500 @enderror" value = '{{ $patient->email }}'  placeholder="Email">
                            @error('parent_email')
                                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-cyan-700 mb-2">Profession</label>
                            <input type="text" name="parent_profession" class="form-control w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200 bg-white/50 backdrop-blur-sm" id="parentProfession" value = '{{ $patient->profession }}'  placeholder="Profession" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-cyan-700 mb-2">Établissement</label>
                            <input type="text" name="parent_etablissement" id="parentEtablissement" class="form-control w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200 bg-white/50 backdrop-blur-sm" value = '{{ $patient->etablissment }} '  placeholder="Établissement" required>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-cyan-700 mb-2">Adresse</label>
                            <input type="text" name="parent_adresse" class="form-control w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200 bg-white/50 backdrop-blur-sm" id="parentAdresse" value = '{{ $patient->address }}'  placeholder="Adresse" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-cyan-700 mb-2">Mode de consultation</label>
                            <select name="mode" class="form-select w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200 bg-white/50 backdrop-blur-sm" required>
                            <option value="A Distance" {{ $patient->mode =='A Distance' ? 'selected' :''}}>À distance</option>
                            <option value="Presentiel" {{ $patient->mode =='Presentiel' ? 'selected' :''}}>En présentiel</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-cyan-700 mb-2">Type d'abonnement</label>
                            <select name="abonnement" class="form-select w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200 bg-white/50 backdrop-blur-sm" required>
                                <option value="seance" {{ $patient->subscription =='seance' ? 'selected' :''}}>Par séance</option>
                                <option value="mois"  {{ $patient->subscription =='mois' ? 'selected' :''}}>Mensuel</option>
                                <option value="pack"  {{ $patient->subscription =='pack' ? 'selected' :''}}>Pack</option>
                            </select>
                        </div>
                    </div>
                </div>
            <!-- Section des coachs -->
                <div>
                    <h4 class="text-lg font-semibold text-cyan-700 border-l-4 border-cyan-400 pl-3 mb-4">Sélectionner les coachs</h4>
                @error('coaches')
                        <div class="bg-red-100 text-red-700 px-4 py-3 rounded-xl mb-4"><i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}</div>
                @enderror
                    <div id="coachContainer" class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-white/60 rounded-xl p-4 border border-cyan-100" style="max-height: 300px; overflow-y: auto;">
                    @foreach ($orderedCoaches as $coach)
                        @php
                            $assigned = $patient->coaches->firstWhere('id', $coach->id);
                        @endphp
                            <div class="flex items-center gap-3 coach-item" data-coach-id="{{ $coach->id }}">
                                <input type="checkbox" name="coaches[]" id="coachCheckbox{{ $coach->id }}" class="form-checkbox h-5 w-5 text-cyan-600 focus:ring-cyan-500" value="{{ $coach->id }}" {{ $assigned ? 'checked' : '' }} onchange="maxCountDisplay('{{ $coach->id }}', this)">
                                <label for="coachCheckbox{{ $coach->id }}" class="text-gray-700 font-medium">{{ $coach->full_name }}</label>
                            @if($assigned)
                                    <input type="number" id="coach{{ $coach->id }}" name="coach{{ $coach->id }}" class="ml-2 w-20 border border-cyan-200 rounded-lg px-2 py-1" min="1" max="3" value="{{ $assigned->pivot->max_appointments }}">
                            @else
                                    <input type="number" id="coach{{ $coach->id }}" name="coach{{ $coach->id }}" class="hidden ml-2 w-20 border border-cyan-200 rounded-lg px-2 py-1" min="1" max="3">
                            @endif
                        </div>
                    @endforeach
                </div>
                <input type="hidden" name="coach_order" id="coach_order">
            </div>
                <!-- Max appointments per week -->
                <div>
                    <h4 class="text-lg font-semibold text-cyan-700 border-l-4 border-cyan-400 pl-3 mb-4">Rendez-vous par semaine</h4>
                    <input type="number" min="1" max="3" name="max_appointments" class="form-control w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200 bg-white/50 backdrop-blur-sm" value="{{ $patient->weekly_quota}}" required placeholder="Nombre maximum de rendez-vous par semaine">
                </div>
                <!-- Image upload section -->
                <div class="text-center mt-4">
                    <label class="block text-sm font-semibold text-cyan-700 mb-2">Choisir une image</label>
                    <input type="file" name="image" id="image-input" accept="image/*" hidden onchange="previewImage(event)">
                    <label for="image-input" class="inline-block bg-gradient-to-r from-cyan-500 to-sky-600 text-white px-6 py-2 rounded-xl font-semibold cursor-pointer hover:from-cyan-600 hover:to-sky-700 transition-all duration-200 shadow-lg">
                        <i class="fas fa-upload mr-2"></i> Télécharger l'image
                    </label>
                    <p class="text-xs text-gray-500 mt-2">Image max size: 2MB. Formats: JPG, PNG, etc.</p>
                    <div id="image-size-error" class="text-red-600 text-sm mt-2 hidden"></div>
                    <div id="image-preview" class="mt-3 {{  $patient->image_path ==null ? 'hidden': ''}} ">
                        <img src="{{ asset('storage/' . $patient->image_path) }}" alt="Aperçu de l'image" id="image-preview-img" class="rounded-full shadow-lg border-4 border-cyan-200 mx-auto" style="width: 80px; height: 80px; object-fit: cover;">
                    </div>
                </div>
                <!-- Calendar Section -->
                <div>
                    <h4 class="text-lg font-semibold text-cyan-700 border-l-4 border-cyan-400 pl-3 mb-4 mt-4">Priorités de l'emploi du temps</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-cyan-700 mb-2">Choisir une priorité</label>
                            <select id="priorityChoice" name="priority_choice" class="form-select w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200 bg-white/50 backdrop-blur-sm">
                            <option value="1" selected>Priorité 1</option>
                                <option value="2">Priorité 2</option>
                                <option value="3">Priorité 3</option>
                        </select>
                        </div>
                        <div>
                            @error('priorities')
                                <div class="bg-red-100 text-red-700 px-4 py-3 rounded-xl mb-4">
                                    <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div id="calendar" class="mt-4 bg-white rounded-2xl shadow-lg border border-cyan-100 p-4"></div>
                </div>
            <!-- Hidden Input & Submit -->
                <div class="text-center mt-8">
                <input type="hidden" name="priorities" id="prioritiesInput">
                    <button type="submit" class="bg-gradient-to-r from-cyan-500 to-sky-600 text-white px-8 py-3 rounded-2xl font-semibold hover:from-cyan-600 hover:to-sky-700 transform hover:-translate-y-1 transition-all duration-200 flex items-center justify-center gap-3 shadow-lg hover:shadow-2xl mx-auto">
                        <i class="fas fa-save mr-2"></i>Mettre à jour le profil du Patient
                </button>
            </div>
        </form>
    </div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
function maxCountDisplay(id, event) {
    var input = document.querySelector(`#coach${id}`);
    if (input) {
        if (event.checked) {
            input.classList.remove('hidden');
        } else {
            input.classList.add('hidden');
            input.value = '';
        }
    }
    }
    document.addEventListener("DOMContentLoaded", function() {
    const patientTypeSelect = document.getElementById('patientType');
    const kidSection = document.getElementById('kidSection');
    const parentSection = document.getElementById('parentSection');
    const parentTitle = document.querySelector('.parent-title');
    const kidTitle = document.querySelector('.kid-title');
    const kidLastName = document.querySelector('#kidLastName');
    const kidFirstName = document.querySelector('#kidFirstName');
    const kidEcole = document.querySelector('#kidEcole');
    const kidSystem = document.querySelector('#kidSystem');
        const calendarEl = document.querySelector('#calendar')
    const parentEmail = document.querySelector('#parentEmail');

    // Initialize calendar
    initPriorities();

        if (patientTypeSelect.value == 'kid'|| patientTypeSelect.value=='young') {
        kidSection.classList.remove('hidden');
        parentSection.classList.remove('hidden');
        if (parentTitle) parentTitle.textContent = 'Détails du parent';
                kidFirstName.setAttribute("required", "");
                kidLastName.setAttribute("required", "");
                kidEcole.setAttribute("required", "");
                kidSystem.setAttribute("required", "");
    } else {
        kidSection.classList.add('hidden');
        parentSection.classList.remove('hidden');
        if (parentTitle) parentTitle.textContent = 'Détails du patient';
                kidFirstName.removeAttribute('required');
                kidLastName.removeAttribute('required');
                kidEcole.removeAttribute('required');
                kidSystem.removeAttribute('required');
            }
        patientTypeSelect.addEventListener('change', function() {
            const selectedType = this.value;
            if (selectedType === 'young') {
            if (kidTitle) kidTitle.textContent = 'Détails du jeune';
        } else {
            if (kidTitle) kidTitle.textContent = 'Détails de l\'enfant';
            }
            if (selectedType === 'kid' || selectedType === 'young') {
                kidFirstName.setAttribute("required", "");
                kidLastName.setAttribute("required", "");
                kidEcole.setAttribute("required", "");
                kidSystem.setAttribute("required", "");
            kidSection.classList.remove('hidden');
            parentSection.classList.remove('hidden');
            if (parentTitle) parentTitle.textContent = 'Détails du parent';
        } else {
                kidFirstName.removeAttribute('required');
                kidLastName.removeAttribute('required');
                kidEcole.removeAttribute('required');
                kidSystem.removeAttribute('required');
            kidSection.classList.add('hidden');
            parentSection.classList.remove('hidden');
            if (parentTitle) parentTitle.textContent = 'Détails du patient';
        }
    });
    if (parentEmail && parentEmail.classList.contains('is-invalid')) {
        parentSection.classList.remove('hidden');
    }
    });
    document.addEventListener('DOMContentLoaded', function() {
    var coachContainer = document.getElementById('coachContainer');
    if (coachContainer && typeof Sortable !== 'undefined') {
    Sortable.create(coachContainer, {
            animation: 150,
        onEnd: function(evt) {
            updateCoachOrder();
        }
    });
    }
    function updateCoachOrder() {
        const coachItems = document.querySelectorAll('#coachContainer .coach-item');
        const order = [];
        coachItems.forEach(item => {
            order.push(item.getAttribute('data-coach-id'));
        });
        document.getElementById('coach_order').value = JSON.stringify(order);
    }
    updateCoachOrder();
});

// Calendar functionality
    var prioritiesData = {};
var storedPriorities = <?php echo $patient->priorities ?>; 
    for (var key in storedPriorities) {        
        if (storedPriorities.hasOwnProperty(key)) {
            prioritiesData[key] = storedPriorities[key];
        }
    }
   
    function addEventToPriorities(priorityChoice, date, eventId, startTime, endTime) {
    var priorityKey = "priority " + priorityChoice;    
    if (!prioritiesData[priorityKey]) {
        prioritiesData[priorityKey] = {};
    }
    if (!prioritiesData[priorityKey][date]) {
        prioritiesData[priorityKey][date] = [];
    }
    var eventData = {
    id: eventId,
    startTime: startTime,
    endTime: endTime
    };
    prioritiesData[priorityKey][date].push(eventData);
    }

    function updateEventInPriorities(event) {
    var parts = event.title.split(" ");
    var priorityChoice = parts[1];
    var priorityKey = "priority " + priorityChoice;
    var newStart = event.start;
    var newEnd = event.end ? event.end : event.start;
    var newDate = newStart.toISOString().split("T")[0];
    var newStartTime = newStart.toISOString().split("T")[1].substring(0,5);
    var newEndTime = newEnd.toISOString().split("T")[1].substring(0,5);
    
    for (var date in prioritiesData[priorityKey]) {
        var arr = prioritiesData[priorityKey][date];
        for (var i = 0; i < arr.length; i++) {
        if (arr[i].id === event.id) {
            arr.splice(i, 1);
            if (arr.length === 0) {
            delete prioritiesData[priorityKey][date];
            }
            break;
        }
        }
    }
    
    if (!prioritiesData[priorityKey][newDate]) {
        prioritiesData[priorityKey][newDate] = [];
    }
    prioritiesData[priorityKey][newDate].push({
        id: event.id,
        startTime: newStartTime,
        endTime: newEndTime
    });
    }

    function deleteEventFromPriorities(event) {
    var parts = event.title.split(" ");
    var priorityChoice = parts[1];
    var priorityKey = "priority " + priorityChoice;
    for (var date in prioritiesData[priorityKey]) {
        var arr = prioritiesData[priorityKey][date];
        for (var i = 0; i < arr.length; i++) {
        if (arr[i].id === event.id) {
            arr.splice(i, 1);
            if (arr.length === 0) {
            delete prioritiesData[priorityKey][date];
            }
            if (Object.keys(prioritiesData[priorityKey]).length === 0) {
                    delete prioritiesData[priorityKey];
                }
            return;
        }
        }
    }
    }

    function storePriorities() {
    document.getElementById("prioritiesInput").value = JSON.stringify(prioritiesData);
    return true;
}

    function initPriorities() { 
    let initialDate = '';
        const calendarEl = document.querySelector('#calendar');
        var colors = { "1": "red", "2": "orange", "3": "green" };
       
    const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: "timeGridWeek",
            editable: true,
        selectable: true,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
            firstDay: 1, 
            hiddenDays: [0], 
            slotMinTime: '12:00:00',
            slotMaxTime: '20:00:00',
        slotDuration: '01:00:00',
        slotLabelInterval: '01:00:00',
            allDaySlot: false,
            nowIndicator: true,
            expandRows: true,
        height: 500,
                select: function(info) {
                    var startDateTime = info.startStr; 
                    var endDateTime = info.endStr;      
                    var date = startDateTime.split("T")[0]; 
                    var startTime = startDateTime.split("T")[1] ? startDateTime.split("T")[1].substring(0,5) : "00:00";
                    var endTime = endDateTime ? (endDateTime.split("T")[1] ? endDateTime.split("T")[1].substring(0,5) : "23:59") : "23:59";
    
                    var priorityChoice = document.querySelector('#priorityChoice').value;
                    if (!priorityChoice || !["1","2","3"].includes(priorityChoice)) {
                        alert("Priorité sélectionnée invalide.");
                        calendar.unselect();
                        return;
                    }
                    
                    var eventId = String(Date.now());
                    var eventObj = {
                        id: eventId,
                        title: "priority " + priorityChoice,
                        start: startDateTime,
                        end: endDateTime,
                        backgroundColor: colors[priorityChoice]
                    };
                    calendar.addEvent(eventObj);
                    addEventToPriorities(priorityChoice, date, eventId, startTime, endTime);
                    calendar.unselect();
                },
                eventDidMount: function(arg) {
                    arg.el.addEventListener('mouseenter', function() {
                        arg.el.style.zIndex = '999';
                    });
                    arg.el.addEventListener('mouseleave', function() {
                        arg.el.style.zIndex = 'auto';
                    });
                },
                eventDrop: function(info) {
                    updateEventInPriorities(info.event);
                },
                eventResize: function(info) {
                    updateEventInPriorities(info.event);
                },
                eventClick: function(info) {
                    if (confirm("Voulez-vous supprimer cet événement ?"))  {
                        deleteEventFromPriorities(info.event);
                        info.event.remove();
                    };
            }
        });

        calendar.render();        
    var allEvents = <?php
                $patientPriorities = json_decode($patient->priorities, true);
                $allPriorities = [];
        if ($patientPriorities) {
                foreach ($patientPriorities as $priorityKey => $data) {
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
                                $startTime = $slot['startTime'].':00';
                                $endTime = $slot['endTime'].':00';
                        $allPriorities[] = [
                                    'id' => $slot['id'],
                                    'title' => $priorityKey,
                                    'start'=> $day.'T'.$startTime,
                                    'end' => $day.'T'.$endTime,
                                    'className' => $priorityClass,
                                    'extendedProps' => [
                                        'priority' => $priorityKey
                                    ]                       
                                ];
                            }
                }
                    }
                }
                echo json_encode($allPriorities);
            ?>;

        calendar.addEventSource(allEvents);

    if (allEvents.length > 0) {
        initialDate = allEvents[0].start;
        initialDate = initialDate.split('T')[0];
        calendar.gotoDate(initialDate);
    }
}
  
    function previewImage(event) {
        const input = event.target;
        const previewContainer = document.getElementById("image-preview");
        const previewImage = document.getElementById("image-preview-img");
    const errorDiv = document.getElementById("image-size-error");
        if (input.files && input.files[0]) {
        const file = input.files[0];
        if (file.size > 2 * 1024 * 1024) { // 2MB
            errorDiv.textContent = "L'image sélectionnée dépasse la taille maximale de 2 Mo.";
            errorDiv.classList.remove("hidden");
            previewContainer.classList.add("hidden");
            input.value = ""; // Reset the input
            return;
        } else {
            errorDiv.classList.add("hidden");
        }
            const reader = new FileReader();
            reader.onload = function (e) {
                previewImage.src = e.target.result;
            previewContainer.classList.remove("hidden"); // Show preview
            };
        reader.readAsDataURL(file);
        }
    }
</script>
@endsection
