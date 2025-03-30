@extends('layouts.app') {{-- Extend your main layout --}}

@section('content')
<style>
    :root {
        --primary-color: #6366f1;
        --secondary-color: #4f46e5;
        --accent-color: #818cf8;
        --light-bg: #f8fafc;
        --dark-text: #1e293b;
        --glass-bg: rgba(255, 255, 255, 0.9);
    }

    .creation-container {
        background: var(--light-bg);
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .form-header {
        color: var(--secondary-color);
        font-weight: 600;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--primary-color);
    }

    .glass-card {
        background: var(--glass-bg);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .form-floating label {
        color: #64748b;
        transition: all 0.3s ease;
    }

    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
        padding: 0.75rem 1rem;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }

    .section-title {
        color: var(--secondary-color);
        font-weight: 500;
        margin: 1.5rem 0;
        padding-left: 0.5rem;
        border-left: 3px solid var(--primary-color);
    }

    #calendar {
        background: white;
        border-radius: 12px;
        padding: 1rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        height: 700px;
    }
    
    .priority1 .fc-event-title,
    .priority1 .fc-event-time {
    color:rgb(206, 1, 1) !important; 
    }

    .priority2 .fc-event-title,
    .priority2 .fc-event-time {
    color:rgb(251, 145, 6) !important; 
    }

    .priority3 .fc-event-title,
    .priority3 .fc-event-time {
    color:rgb(1, 174, 27) !important;
    }
    .priority1 { background: rgba(255, 76, 76, 0.1) !important; border-left: 4px solid #ff4c4c !important; }
    .priority2 { background: rgba(255, 152, 0, 0.1) !important; border-left: 4px solid #ff9800 !important; }
    .priority3 { background: rgba(76, 175, 80, 0.1) !important; border-left: 4px solid #4caf50 !important; }

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

    .priority-marker {
        display: inline-block;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin-right: 0.5rem;
    }

    .btn-primary {
        background: var(--primary-color);
        border: none;
        padding: 0.75rem 2rem;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background: var(--secondary-color);
        transform: translateY(-2px);
    }

    .alert-danger {
        background: rgba(239, 68, 68, 0.1);
        border: none;
        color: #ef4444;
        border-radius: 8px;
    }

    .is-invalid {
        border-color: #ef4444 !important;
    }

    .invalid-feedback {
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    .coach-section {
    background: var(--glass-bg);
    padding: 1rem;
    border-radius: 12px;
    border: 1px solid rgba(255, 255, 255, 0.3);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}
.coach-section .form-check {
    margin-bottom: 0.5rem;
}
.coach-section .form-check-input {
    cursor: pointer;
}
.coach-section .form-check-label {
    color: var(--dark-text);
    cursor: pointer;
}
</style>

<div class="creation-container">
    <div class="glass-card">
        <h3 class="form-header">✨ Mettre à Jour le Profil du Patient</h3>
        
        <form action=" {{ route('patient.update', $patient->id) }}" onsubmit=" storePriorities()" enctype="multipart/form-data"  method="post"  class="row g-3">
            @csrf
            @method('PUT')

            <!-- Patient Type Selection -->
            <div class="col-12">
                <div class="form-floating">
                    <select id="patientType" name="patient_type" class="form-select bg-transparent" required>
                        <option value="" selected disabled>Sélectionner le Type de Patient</option>
                        <option value="kid" {{  $patient->patient_type == 'kid' ? 'selected' : '' }}>Enfant</option>
                        <option value="young" {{ $patient->patient_type == 'young' ? 'selected' : '' }}>Jeune</option>
                        <option value="adult" {{ $patient->patient_type == 'adult' ? 'selected' : '' }}>Adulte</option>
                    </select>
                    <label for="patientType">Type de Patient</label>
                </div>
            </div>

            <!-- Age & Gender -->
            <div class="col-md-4">
                <div class="form-floating">
                    <input type="number" name="age" id="kidAge" class="form-control" value="{{ $patient->age}}" placeholder="Age" required>
                    <label for="kidAge">Âge</label>
                </div>
            </div>
            <!-- gender -->
            <div class="col-md-4">
                <div class="form-floating">
                    <select name="PatientGender" id="PatientGender" class="form-select " required>
                            <option value="M" {{ $patient->gender == 'M' ? 'selected' : '' }}>Homme</option>
                            <option value="F" {{ $patient->gender == 'F' ? 'selected' : ''}}>Femme</option>
                        </select>
                        <label for="PatientGender">Genre du Patient</label>
                </div>
            </div>
            <!-- speciality -->
            <div class="col-md-4">
                <div class="form-floating">
                    <select class="form-select" id="specialtySelect" name="speciality_id" required>
                        <option value="">Toutes les Spécialités</option>
                    @foreach($specialities as $speciality)
                        <option value="{{ $speciality->id }}"  {{ $patient->speciality_id ==$speciality->id ? 'selected' :''}}>{{ $speciality->name }}</option>
                    @endforeach
                    </select>
                    <label>Sélectionner la Spécialité</label>
                </div>

            </div>
            <!-- Kid/Young Section -->
            <div id="kidSection" class="d-none">
                <h4 class="section-title kid-title ">Informations sur l'Enfant</h4>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" id="kidLastName" name="kid_last_name" class="form-control"  value="{{ ($patient->patient_type=='kid'||$patient->patient_type=='young') ? $patient->last_name : ''}}" placeholder="Last Name">
                            <label>Nom</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" id="kidFirstName" name="kid_first_name" class="form-control"  value="{{ ($patient->patient_type=='kid'||$patient->patient_type=='young') ? $patient->first_name : ''}}" placeholder="First Name">
                            <label>Prénom</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" id="kidEcole" name="kid_ecole" class="form-control" value="{{ ($patient->patient_type=='kid'||$patient->patient_type=='young') ? $patient->ecole : ''}}"placeholder="School">
                            <label>École</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select id="kidSystem" name="kid_system" class="form-select">
                                <option value="" selected disabled>Sélectionner un Système</option>
                                <option value="moroccan" {{ $patient->system == 'moroccan' ? 'selected' :'' }}>Système Marocain</option>
                                <option value="mission" {{ $patient->system == 'mission' ? 'selected' :'' }}>Système Mission</option>
                            </select>
                            <label>Education System</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Guardian Section -->
            <div id="parentSection" class="d-none">
                <h4 class="section-title parent-title">Informations sur le Tuteur</h4>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" name="parent_last_name" class="form-control" id="parentLastName" placeholder="Last Name" 
                            value = '{{ $patient->parent_last_name }}' required>
                            <label>Nom</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" name="parent_first_name" class="form-control" id="parentFirstName" placeholder="First Name" 
                            value = '{{ $patient->parent_first_name }}' required>
                            <label>Prénom</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="tel" name="parent_phone" class="form-control" id="parentPhone"  placeholder="Phone" 
                            value = '{{ $patient->phone }}' required>
                            <label>Numéro de Téléphone</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="email" id="parentEmail" name="parent_email" class="form-control @error('parent_email') is-invalid @enderror" 
                            value = '{{ $patient->email }}'  placeholder="Email">
                            <label>Adresse Email</label>
                            @error('parent_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" name="parent_profession" class="form-control" id="parentProfession"
                            value = '{{ $patient->profession }}'  placeholder="Profession" required>
                            <label>Profession</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" name="parent_etablissement" id="parentEtablissement" class="form-control bg-transparent" 
                            value = '{{ $patient->etablissment }} '  placeholder="Etablissement" required>
                            <label for="parentEtablissement">Établissement</label>
                        </div>
                    </div>
            
                    <div class="col-12">
                        <div class="form-floating">
                            <input type="text" name="parent_adresse" class="form-control" id="parentAdresse"
                            value = '{{ $patient->address }}'  placeholder="Address" required>
                            <label>Adresse</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select name="mode" class="form-select" required>
                            <option value="A Distance" {{ $patient->mode =='A Distance' ? 'selected' :''}}>À Distance</option>
                            <option value="Presentiel" {{ $patient->mode =='Presentiel' ? 'selected' :''}}>Présentiel</option>
                            </select>
                            <label>Mode de Consultation</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select name="abonnement" class="form-select" required>
                                <option value="seance" {{ $patient->subscription =='seance' ? 'selected' :''}}>Par Séance</option>
                                <option value="mois"  {{ $patient->subscription =='mois' ? 'selected' :''}}>Mensuel</option>
                                <option value="pack"  {{ $patient->subscription =='pack' ? 'selected' :''}}>Pack</option>
                            </select>
                            <label>Type d'Abonnement</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- @error('coaches')
                <div class="alert alert-danger">
                    {{ $message }}
                </div>
            @enderror -->
            <!-- Coaches Section -->
            <div class="col-12">
                <h4 class="section-title">Sélectionner les Coachs</h4>
                @error('coaches')
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ $message }}
                    </div>	
                @enderror
                <!-- Scrollable container for coaches -->
                <div class="coach-section" id="coachContainer" style="max-height: 300px; overflow-y: auto;">
                    @foreach ($orderedCoaches as $coach)
                        @php
                            // Check if this coach is already assigned to the patient.
                            $assigned = $patient->coaches->firstWhere('id', $coach->id);
                        @endphp
                        <div class="form-check coach-item" data-coach-id="{{ $coach->id }}">
                            <input type="checkbox" name="coaches[]" id="coachCheckbox{{ $coach->id }}" class="form-check-input" value="{{ $coach->id }}" 
                                {{ $assigned ? 'checked' : '' }} onchange="maxCountDisplay('{{ $coach->id }}', this)">
                            <label class="form-check-label" for="coachCheckbox{{ $coach->id }}">
                                {{ $coach->full_name }}
                            </label>
                            @if($assigned)
                                <!-- Show the capacity input with the value from pivot -->
                                <input type="number" id="coach{{ $coach->id }}" name="coach{{ $coach->id }}" class="coach-capacity" min="1" max="3" style="width: 100px;" value="{{ $assigned->pivot->max_appointments }}">
                                <small>Position: {{ $assigned->pivot->position }}</small>
                            @else
                                <input type="number" id="coach{{ $coach->id }}" name="coach{{ $coach->id }}" class="d-none coach-capacity" min="1" max="3" style="width: 100px;">
                            @endif
                        </div>
                    @endforeach
                </div>
                <!-- Hidden input to store the order -->
                <input type="hidden" name="coach_order" id="coach_order">
            </div>

            <!-- max appointments on a week -->
            <div class="col-md-4">
            <h4 class="section-title" >Rendez-vous par Semaine</h4>
                <div class="form-floating">
                    <input type="number" min="1" max="3" name="max_appointments" class="form-control" value="{{ $patient->weekly_quota}}" required >
                    <label>Nombre maximum de rendez-vous par semaine</label>
                </div>
            </div>

            
            <div class="form-group text-center mt-4">
                    <label class="form-label fw-bold mb-2">Choose an Image:</label>

                    <!-- Hidden File Input -->
                    <input type="file" name="image" id="image-input" accept="image/*" hidden onchange="previewImage(event)">

                    <!-- Custom Button -->
                    <label for="image-input" class="btn btn-primary">
                      <i class="fas fa-upload me-2"></i> Upload Image
                    </label>

                    <!-- Image Preview (Circle or Square) -->
                    <div id="image-preview" class="mt-3 {{  $patient->image_path ==null ? 'd-none': ''}} ">
                      <img src="{{ asset('storage/' . $patient->image_path) }}" alt="Image Preview" id="image-preview-img" class="rounded-circle shadow img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;">
                    </div>
                </div>

            <!-- Calendar Section -->
            <div class="col-12">
                <h4 class="section-title mt-4">Planifier les Priorités</h4>
                <div class="col-md-6">
                    <div class="form-floating">
                        <select id="priorityChoice" name="priority_choice" class="form-select">
                            <option value="1" selected>Priorité 1</option>
                            <option value="2" >Priorité 2</option>
                            <option value="3" >Priorité 3</option>
                        </select>
                        <label>Choisir une Priorité</label>
                    </div>
                </div>
                @error('priorities')
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ $message }}
                    </div>
                @enderror

                <div class="calendar-container">
                    <div id="calendar"></div>
                </div> 
            </div>

            <!-- Hidden Input & Submit -->
            <div class="col-12 text-center mt-4">
                <input type="hidden" name="priorities" id="prioritiesInput">
                <button type="submit" class="btn btn-primary px-5 py-2">
                    <i class="fas fa-save me-2"></i>Mettre à Jour le Profil du Patient
                </button>
            </div>
        </form>
    </div>
</div>


<script>
     function  maxCountDisplay(id,event) {
        if (event.checked) {
            document.querySelector(`#coach${id}`).classList.remove('d-none'); 
            console.log(id);
            
            
        }else{
        document.querySelector(`#coach${id}`).classList.add('d-none');
        document.querySelector(`#coach${id}`).value = ''; 
        }

    }
    document.addEventListener("DOMContentLoaded", function() {
    const patientTypeSelect = document.getElementById('patientType');
    const kidSection = document.getElementById('kidSection');
    const parentSection = document.getElementById('parentSection');
    const parentSectionTitle = document.getElementsByClassName('parent-title');
    const kidYoungDetailsTitle =document.querySelector('.kid-title');
    const kidLastName = document.querySelector('#kidLastName');
    const kidFirstName = document.querySelector('#kidFirstName');
    const kidEcole = document.querySelector('#kidEcole');
    const kidSystem = document.querySelector('#kidSystem');
    const calendarEl = document.querySelector('#calendar');

    document.querySelectorAll('.form-check-input').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            let coachId = this.value;
            let input = document.getElementById('coach' + coachId);
            if (this.checked) {
                // Show the input if checked.
                if(input) {
                    input.classList.remove('d-none');
                }
            } else {
                // Hide the input if unchecked.
                if(input) {
                    input.classList.add('d-none');
                }
            }
        });
    });

     if (patientTypeSelect.value == 'kid'|| patientTypeSelect.value=='young') {
            kidSection.classList.remove('d-none');
            parentSection.classList.remove('d-none');
            parentSectionTitle.textContent = 'Parent Details';

            kidFirstName.setAttribute("required", "");
            kidLastName.setAttribute("required", "");
            kidEcole.setAttribute("required", "");
            kidSystem.setAttribute("required", "");
        }
        else{
            kidSection.classList.add('d-none');
            parentSection.classList.remove('d-none');
            parentSectionTitle.textContent = 'Patient Details';

            kidFirstName.removeAttribute('required');
            kidLastName.removeAttribute('required');
            kidEcole.removeAttribute('required');
            kidSystem.removeAttribute('required');
        }

    patientTypeSelect.addEventListener('change', function() {
        const selectedType = this.value;
        if (selectedType === 'young') {
            kidYoungDetailsTitle.textContent = 'Young Details';
        } 
        else {
            kidYoungDetailsTitle.textContent = 'Kid Details';
        }
        if (selectedType === 'kid' || selectedType === 'young') {
            kidFirstName.setAttribute("required", "");
            kidLastName.setAttribute("required", "");
            kidEcole.setAttribute("required", "");
            kidSystem.setAttribute("required", "");

            kidSection.classList.remove('d-none');
            parentSection.classList.remove('d-none');
            parentSectionTitle.textContent = 'Parent Details';
        } 
        else  {
            kidFirstName.removeAttribute('required');
            kidLastName.removeAttribute('required');
            kidEcole.removeAttribute('required');
            kidSystem.removeAttribute('required');

            kidSection.classList.add('d-none');
            parentSection.classList.remove('d-none');
            parentSectionTitle.textContent = 'Patient Details';
        } 
        });
    
        initPriorities();
    });
    document.addEventListener('DOMContentLoaded', function() {
    // Initialize SortableJS on the coach container
    var coachContainer = document.getElementById('coachContainer');
    Sortable.create(coachContainer, {
        animation: 150, // smooth animation during sorting
        onEnd: function(evt) {
            updateCoachOrder();
        }
    });

    // Function to update the hidden input with the current order of coach IDs
    function updateCoachOrder() {
        const coachItems = document.querySelectorAll('#coachContainer .coach-item');
        const order = [];
        coachItems.forEach(item => {
            order.push(item.getAttribute('data-coach-id'));
        });
        document.getElementById('coach_order').value = JSON.stringify(order);
        console.log("Current coach order:", order);
    }
    
    // Update the order initially
    updateCoachOrder();
});

    var prioritiesData = {};
    var storedPriorities =<?php echo $patient->priorities ?> ; 
    for (var key in storedPriorities) {        
        if (storedPriorities.hasOwnProperty(key)) {
            prioritiesData[key] = storedPriorities[key];
        }
    }
    console.log("Initial prioritiesData:", prioritiesData);
   
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
    function storePriorities() {
    // Update the hidden input with the stringified JSON.
    document.getElementById("prioritiesInput").value = JSON.stringify(prioritiesData);
    // Return true to allow the form submission to proceed.
    return true;
}
    function initPriorities() { 
        let initialDate ='';
        const calendarEl = document.querySelector('#calendar');
        var colors = { "1": "red", "2": "orange", "3": "green" };
       
        const calendar = new FullCalendar.Calendar(calendarEl,{
            
            initialView: "timeGridWeek",
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
                select: function(info) {
    
                    var startDateTime = info.startStr; 
                    var endDateTime = info.endStr;      
                    
                    var date = startDateTime.split("T")[0]; 
                    var startTime = startDateTime.split("T")[1] ? startDateTime.split("T")[1].substring(0,5) : "00:00";
                    var endTime = endDateTime ? (endDateTime.split("T")[1] ? endDateTime.split("T")[1].substring(0,5) : "23:59") : "23:59";
    
                    
                  
                    // var priorityChoice = prompt("Enter priority (1=High, 2=Medium, 3=Low):");
                    var priorityChoice = document.querySelector('#priorityChoice').value;
                    if (!priorityChoice || !["1","2","3"].includes(priorityChoice)) {
                        alert("Priorité sélectionnée invalide.");
                        calendar.unselect();
                        return;
                    }
                    
                    // Create a unique event ID.
                    var eventId = String(Date.now());
                
                    var eventObj = {
                        id: eventId,
                        title: "Priority " + priorityChoice,
                        start: startDateTime,
                        end: endDateTime,
                        backgroundColor: colors[priorityChoice]
                    };
                    calendar.addEvent(eventObj);
           
                    // var isRecurring = confirm("Do you want this event to repeat every week on this day?");
                    addEventToPriorities(priorityChoice, date, eventId, startTime, endTime);
                    calendar.unselect();
                },
                // eventContent: function(arg) {
                //     return {
                //         html: `<div class="fc-event-inner">
                //                 <p class="fc-event-time">${arg.timeText}</p>
                //             </div>`
                //     };
                // },
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
                    if (confirm("Voulez-vous supprimer cet événement ?")) {
                        deleteEventFromPriorities(info.event);
                        info.event.remove();
                    }
                }
            });
        
        calendar.render();        
        var allEvents = 
            <?php
                $patientPriorities = json_decode($patient->priorities, true);
                $allPriorities = [];
                foreach ($patientPriorities as $priorityKey => $data) {
                        // dd($priorityKey,$data);
                        $priorityClass ='';
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
                            // dd($slots[0]['startTime']);
                            foreach ($slots as $slot) {
                                // dd($slot);
                                $startTime = $slot['startTime'].':00';
                                $endTime = $slot['endTime'].':00';
                                $allPriorities[]=[
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
                // dd($allPriorities)
                echo json_encode($allPriorities);
                ?>;

        calendar.addEventSource(allEvents);

        initialDate = allEvents.length > 0 ? allEvents[0].start : null;
        initialDate = initialDate.split('T')[0]
        calendar.gotoDate(initialDate);
        console.log(initialDate);
        

    }

    function fetchCoachesBySpeciality(specialityId) {
        fetch("{{ route('getCoachesBySpeciality') }}",{
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            body: JSON.stringify({ specialityId })
        })
            .then(response => response.json())
            .then(data => {
                const coaches = data.coaches || [];

                const coachSection = document.querySelector('.coach-section');
                coachSection.innerHTML = ''; 
                
    
                    if (coaches.length === 0) {
                        document.getElementById('caochesSectionWarning').classList.remove('d-none');
                        return; 
                    }else{
                        document.getElementById('caochesSectionWarning').classList.add('d-none');
                    }
                coaches.forEach(coach => {
                    const coachDiv = document.createElement('div');
                    coachDiv.classList.add('form-check');

                    const checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.classList.add('form-check-input');
                    checkbox.id = `coachCheckbox${coach.id}`;
                    checkbox.name = 'coaches[]';
                    checkbox.value = coach.id;
                    checkbox.setAttribute('onchange', `maxCountDisplay('${coach.id}', this)`);

                    const label = document.createElement('label');
                    label.classList.add('form-check-label');
                    label.setAttribute('for', `coachCheckbox${coach.id}`);
                    label.textContent = coach.full_name;

                    const inputNumber = document.createElement('input');
                    inputNumber.type = 'number';
                    inputNumber.id = `coach${coach.id}`;
                    inputNumber.name = `coach${coach.id}`;
                    inputNumber.classList.add('d-none');
                    inputNumber.min = '1';
                    inputNumber.style.width = '100px';

                    coachDiv.appendChild(checkbox);
                    coachDiv.appendChild(label);
                    coachDiv.appendChild(inputNumber);

                    coachSection.appendChild(coachDiv);
                });
                
            })
            .catch(error => console.error('Error fetching coaches:', error));
    }

    document.getElementById('specialtySelect').addEventListener('change', function() {
        const selectedSpeciality = this.value;

        console.log(selectedSpeciality);
        
        if (selectedSpeciality) {
            fetchCoachesBySpeciality(selectedSpeciality);
            document.getElementById('caochesSectionAlert').classList.add('d-none');
        }else{
            document.getElementById('caochesSectionAlert').classList.remove('d-none');
            const coachSection = document.querySelector('.coach-section');
            coachSection.innerHTML = ''; 
        }
    });
    function previewImage(event) {
        const input = event.target;
        const previewContainer = document.getElementById("image-preview");
        const previewImage = document.getElementById("image-preview-img");

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                previewImage.src = e.target.result;
                previewContainer.classList.remove("d-none"); // Show preview
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
 
</script>
@endsection
