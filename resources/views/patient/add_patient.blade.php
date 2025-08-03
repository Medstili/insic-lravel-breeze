@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gradient-to-br from-cyan-50 via-sky-50 to-blue-50 p-6 mt-24">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 p-8">
            <h3 class="text-2xl font-bold text-center mb-8 bg-gradient-to-r from-cyan-500 via-sky-500 to-blue-500 text-white py-4 rounded-2xl shadow-lg">✨ Nouveau Profil Patient</h3>
            <form action="{{ route('patient.store') }}" method="POST" enctype="multipart/form-data" onsubmit="storePriorities()" class="space-y-8">
                @csrf
                @error('patient_exists')
                    <div class="bg-red-100 text-red-700 px-4 py-3 rounded-xl mb-4">{{ $message }}</div>
                @enderror
                <!-- Patient Type Selection -->
                <div>
                    <label for="patientType" class="block text-sm font-semibold text-cyan-700 mb-2">Type de Patient</label>
                    <select id="patientType" name="patient_type" class="form-select w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200 bg-white/50 backdrop-blur-sm" required>
                        <option value="" selected disabled>Sélectionner le Type de Patient</option>
                        <option value="kid">Enfant</option>
                        <option value="young">Jeune</option>
                        <option value="adult">Adulte</option>
                    </select>
                </div>
                <!-- Age & Gender & Speciality -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-cyan-700 mb-2">Âge</label>
                        <input type="number" name="age" class="form-control w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200 bg-white/50 backdrop-blur-sm" placeholder="Age" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-cyan-700 mb-2">Genre</label>
                        <select name="PatientGender" class="form-select w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200 bg-white/50 backdrop-blur-sm" required>
                            <option value="M">Homme</option>
                            <option value="F">Femme</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-cyan-700 mb-2">Spécialité</label>
                        <select class="form-select w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200 bg-white/50 backdrop-blur-sm" id="specialtySelect" name="speciality_id" required>
                            <option value="">Toutes les spécialités</option>
                            @foreach($specialities as $speciality)
                                <option value="{{ $speciality->id }}">{{ $speciality->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <!-- Kid/Young Section -->
                <div id="kidSection" class="hidden">
                    <h4 class="text-lg font-semibold text-cyan-700 border-l-4 border-cyan-400 pl-3 mb-4">Informations sur l'Enfant</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-cyan-700 mb-2">Nom</label>
                            <input type="text" id="kidLastName" name="kid_last_name" class="form-control w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200 bg-white/50 backdrop-blur-sm" placeholder="Nom">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-cyan-700 mb-2">Prénom</label>
                            <input type="text" id="kidFirstName" name="kid_first_name" class="form-control w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200 bg-white/50 backdrop-blur-sm" placeholder="Prénom">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-cyan-700 mb-2">École</label>
                            <input type="text" id="kidEcole" name="kid_ecole" class="form-control w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200 bg-white/50 backdrop-blur-sm" placeholder="École">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-cyan-700 mb-2">Système</label>
                            <select id="kidSystem" name="kid_system" class="form-select w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200 bg-white/50 backdrop-blur-sm">
                                <option value="" disabled selected>Sélectionner un Système</option>
                                <option value="moroccan">Système Marocain</option>
                                <option value="mission">Système Mission</option>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- Guardian Section -->
                <div id="parentSection" class="hidden">
                    <h4 class="text-lg font-semibold text-cyan-700 border-l-4 border-cyan-400 pl-3 mb-4">Information adulte</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-cyan-700 mb-2">Nom</label>
                            <input type="text" name="parent_last_name" class="form-control w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200 bg-white/50 backdrop-blur-sm" placeholder="Nom" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-cyan-700 mb-2">Prénom</label>
                            <input type="text" name="parent_first_name" class="form-control w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200 bg-white/50 backdrop-blur-sm" placeholder="Prénom" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-cyan-700 mb-2">Numéro de Téléphone</label>
                            <input type="tel" name="parent_phone" class="form-control w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200 bg-white/50 backdrop-blur-sm" placeholder="Téléphone" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-cyan-700 mb-2">Adresse Email</label>
                            <input type="email" id="parentEmail" name="parent_email" class="form-control w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200 bg-white/50 backdrop-blur-sm @error('parent_email') border-red-500 @enderror" placeholder="Email">
                            @error('parent_email')
                                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-cyan-700 mb-2">Profession</label>
                            <input type="text" name="parent_profession" class="form-control w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200 bg-white/50 backdrop-blur-sm" placeholder="Profession" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-cyan-700 mb-2">Établissement</label>
                            <input type="text" name="parent_etablissement" id="parentEtablissement" class="form-control w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200 bg-white/50 backdrop-blur-sm" placeholder="Établissement" required>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-cyan-700 mb-2">Adresse</label>
                            <input type="text" name="parent_adresse" class="form-control w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200 bg-white/50 backdrop-blur-sm" placeholder="Adresse" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-cyan-700 mb-2">Mode de Consultation</label>
                            <select name="mode" class="form-select w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200 bg-white/50 backdrop-blur-sm" required>
                                <option value="A Distance">À Distance</option>
                                <option value="Presentiel">Présentiel</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-cyan-700 mb-2">Type d'Abonnement</label>
                            <select name="abonnement" class="form-select w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200 bg-white/50 backdrop-blur-sm" required>
                                <option value="seance">Par Séance</option>
                                <option value="mois">Mensuel</option>
                                <option value="pack">Pack</option>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- Coaches Section -->
                <div>
                    <h4 class="text-lg font-semibold text-cyan-700 border-l-4 border-cyan-400 pl-3 mb-4 parent-title">Sélectionner les Coachs</h4>
                    @error('no_coaches')
                        <div class="bg-red-100 text-red-700 px-4 py-3 rounded-xl mb-4">{{$message}}</div>
                    @enderror
                    <div id="coachContainer" class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-white/60 rounded-xl p-4 border border-cyan-100">
                        @foreach ($coaches as $coach)
                            <div class="flex items-center gap-3 coach-item" data-coach-id="{{ $coach->id }}">
                                <input type="checkbox" name="coaches[]" id="coachCheckBox{{ $coach->id }}" class="form-checkbox h-5 w-5 text-cyan-600 focus:ring-cyan-500" value="{{ $coach->id }}" onchange="maxCountDisplay('{{ $coach->id }}', this)">
                                <label for="coachCheckBox{{ $coach->id }}" class="text-gray-700 font-medium">{{ $coach->full_name }}</label>
                                <input type="number" id="coach{{ $coach->id }}" name="coach{{ $coach->id }}" class="hidden ml-2 w-20 border border-cyan-200 rounded-lg px-2 py-1" min="1" max="3">
                            </div>
                        @endforeach
                    </div>
                    <input type="hidden" name="coach_order" id="coach_order">
                </div>
                <!-- Max appointments per week -->
                <div>
                    <h4 class="text-lg font-semibold text-cyan-700 border-l-4 border-cyan-400 pl-3 mb-4">Rendez-vous par Semaine</h4>
                    @error('max_appointments')
                        <div class="bg-red-100 text-red-700 px-4 py-3 rounded-xl mb-4">{{ $message }}</div>
                    @enderror
                    <input type="number" min="1" max="3" name="max_appointments" class="form-control w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200 bg-white/50 backdrop-blur-sm" required placeholder="Nombre maximum de rendez-vous par semaine">
                </div>
                <!-- Image section -->
                <div class="text-center mt-4">
                    <label class="block text-sm font-semibold text-cyan-700 mb-2">Choisir une image</label>
                    <input type="file" name="image" id="image-input" accept="image/*" hidden onchange="previewImage(event)">
                    <label for="image-input" class="inline-block bg-gradient-to-r from-cyan-500 to-sky-600 text-white px-6 py-2 rounded-xl font-semibold cursor-pointer hover:from-cyan-600 hover:to-sky-700 transition-all duration-200 shadow-lg">
                        <i class="fas fa-upload mr-2"></i> Upload Image
                    </label>
                    <p class="text-xs text-gray-500 mt-2">Image max size: 2MB. Formats: JPG, PNG, etc.</p>
                    <div id="image-size-error" class="text-red-600 text-sm mt-2 hidden"></div>
                    <div id="image-preview" class="mt-3 hidden">
                        <img src="#" alt="Image Preview" id="image-preview-img" class="rounded-full shadow-lg border-4 border-cyan-200 mx-auto" style="width: 80px; height: 80px; object-fit: cover;">
                    </div>
                </div>
                <!-- Calendar Section -->
                <div>
                    <h4 class="text-lg font-semibold text-cyan-700 border-l-4 border-cyan-400 pl-3 mb-4 mt-4">Planifier les Priorités</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-cyan-700 mb-2">Choisir une Priorité</label>
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
                        <i class="fas fa-save mr-2"></i>Créer le Profil du Patient
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
   
    function  maxCountDisplay(id,event) {
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
    var prioritiesData = {};
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
        const parentEmail = document.querySelector('#parentEmail');

    

        patientTypeSelect.addEventListener('change', function() {
        const selectedType = this.value;
        console.log(selectedType);

        
        if (selectedType === 'young') {
            if (kidTitle) kidTitle.textContent = 'Détails du jeune';
        }
        else {
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
        } 
        else if (selectedType === 'adult') {
            kidFirstName.removeAttribute('required');
            kidLastName.removeAttribute('required');
            kidEcole.removeAttribute('required');
            kidSystem.removeAttribute('required');
            kidSection.classList.add('hidden');
            parentSection.classList.remove('hidden');
            if (parentTitle) parentTitle.textContent = 'Information adulte';
        } 
        else {
            kidSection.classList.add('hidden');
            parentSection.classList.add('hidden');
          
        }
        });
        if (parentEmail && parentEmail.classList.contains('is-invalid')) {
            parentSection.classList.remove('hidden');
        }

        console.log(parentTitle.innerHTML);
        
    }); 
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize SortableJS on the coach container
        var coachContainer = document.getElementById('coachContainer');
        if (coachContainer && typeof Sortable !== 'undefined') {
            Sortable.create(coachContainer, {
                animation: 150, // Smooth animation
                onEnd: function(evt) {
                    updateCoachOrder();
                }
            });
        }

    // Function to update the hidden input with the current order of coach IDs
    function updateCoachOrder() {
        const coachItems = document.querySelectorAll('#coachContainer .coach-item');
        const order = [];
        coachItems.forEach(item => {
            order.push(item.getAttribute('data-coach-id'));
        });
        document.getElementById('coach_order').value = JSON.stringify(order);
        console.log("Current order:", order);
    }
    
    // Update order initially
    updateCoachOrder();
    });

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
        console.log("After addition:", prioritiesData);
    }
    function updateEventInPriorities(event) {
        // Extract the priority from the event title ("Priority 1", etc.)
        var parts = event.title.split(" ");
        var priorityChoice = parts[1]; // e.g., "1"
        var priorityKey = "priority " + priorityChoice;
        
        // New date and time (use ISO string, then extract needed parts)
        var newStart = event.start;
        var newEnd = event.end ? event.end : event.start;
        var newDate = newStart.toISOString().split("T")[0];
        var newStartTime = newStart.toISOString().split("T")[1].substring(0,5);
        var newEndTime = newEnd.toISOString().split("T")[1].substring(0,5);
        
        // Remove the event from every date in its priority group.
        for (var date in prioritiesData[priorityKey]) {
            var arr = prioritiesData[priorityKey][date];
            for (var i = 0; i < arr.length; i++) {
            if (arr[i].id === event.id) {
                arr.splice(i, 1);
                // If no events remain on that date, remove the date key.
                if (arr.length === 0) {
                delete prioritiesData[priorityKey][date];
                }
                break;
            }
            }
        }
        
        // Add the event to the new date.
        if (!prioritiesData[priorityKey][newDate]) {
            prioritiesData[priorityKey][newDate] = [];
        }
        prioritiesData[priorityKey][newDate].push({
            id: event.id,
            startTime: newStartTime,
            endTime: newEndTime
        });
        console.log("After update:", prioritiesData);
    }
    function deleteEventFromPriorities(event) {
        var parts = event.title.split(" ");
        var priorityChoice = parts[1];
        var priorityKey = "priority " + priorityChoice;
        
        // If there's no data for that priority, exit early.
        if (!prioritiesData[priorityKey]) return;

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
                    console.log("After deletion:", prioritiesData);
                    return;
                }
            }
        }
    }
    document.addEventListener("DOMContentLoaded", function() {
        var calendarEl = document.getElementById("calendar");
        var colors = { "1": "red", "2": "orange", "3": "green" };

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: "timeGridWeek",
            selectable: true,
            editable: true,
            firstDay: 1, 
            hiddenDays: [0], 
            slotMinTime: '12:00:00',
            slotMaxTime: '20:00:00',
            slotDuration: '01:00:00',
            slotLabelInterval: '01:00:00', 
            allDaySlot: false,
            nowIndicator: true,
            expandRows: true,
            select: function(info) {

            var startDateTime = info.startStr; 
            var endDateTime = info.endStr;      
            
            var date = startDateTime.split("T")[0]; 
            var startTime = startDateTime.split("T")[1] ? startDateTime.split("T")[1].substring(0,5) : "00:00";
            var endTime = endDateTime ? (endDateTime.split("T")[1] ? endDateTime.split("T")[1].substring(0,5) : "23:59") : "23:59";
            
            // Prompt user to choose a priority.
            // var priorityChoice = prompt("Enter priority (1=High, 2=Medium, 3=Low):");
            var priorityChoice = document.querySelector('#priorityChoice').value;
            
            if (!priorityChoice || !["1","2","3"].includes(priorityChoice)) {
                alert("Priorité sélectionnée invalide.");
                calendar.unselect();
                return;
            }
            
            // Create a unique event ID.
            var eventId = String(Date.now());
            
            // Add the event to the calendar.
            var eventObj = {
                id: eventId,
                title: "Priority " + priorityChoice,
                start: startDateTime,
                end: endDateTime,
                backgroundColor: colors[priorityChoice]
            };
            calendar.addEvent(eventObj);
            addEventToPriorities(priorityChoice, date, eventId, startTime, endTime);
            calendar.unselect();
            },
            eventDrop: function(info) {
            updateEventInPriorities(info.event);
            },
            eventResize: function(info) {
            updateEventInPriorities(info.event);
            },
            eventClick: function(info) {
            if (confirm("Voulez-vous supprimer cet événement ?")) {
                deleteEventFromPriorities(info.event);
                info.event.remove();
            }
            }
    
        });
        calendar.render();
    });
    function storePriorities() {
        document.getElementById("prioritiesInput").value = JSON.stringify(prioritiesData);
        return true;
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