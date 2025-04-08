@extends('layouts.app')

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
        height: 500px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

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

.invalid-feedback {
        color: #ef4444;
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }

</style>

<div class="creation-container">
    <div class="glass-card">
        <h3 class="form-header">✨ Nouveau Profil Patient</h3>
        
        <form action="{{ route('patient.store') }}" method="POST" enctype="multipart/form-data" onsubmit="storePriorities()" class="row g-3">
            @csrf

            @error('patient_exists')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <!-- Patient Type Selection -->
            <div class="col-12">
                <div class="form-floating">
                    <select id="patientType" name="patient_type" class="form-select" required>
                        <option value="" selected disabled>Sélectionner le Type de Patient</option>
                        <option value="kid">Enfant</option>
                        <option value="young">Jeune</option>
                        <option value="adult">Adulte</option>
                    </select>
                    <label for="patientType">Patient Type</label>
                </div>
            </div>

            <!-- Age & Gender -->
            <div class="col-md-4">
                <div class="form-floating">
                    <input type="number" name="age" class="form-control" placeholder="Age" required>
                    <label>Âge</label>
                </div>
            </div>
            <!-- gender -->
            <div class="col-md-4">
                <div class="form-floating">
                    <select name="PatientGender" class="form-select" required>
                        <option value="M">Homme</option>
                        <option value="F">Femme</option>
                    </select>
                    <label for="PatientGender">Genre</label>
                </div>
            </div>
            <!-- speciality -->
            <div class="col-md-4">
                <div class="form-floating">
                    <select class="form-select" id="specialtySelect" name="speciality_id"  required>
                        <option value="">All Specialties</option>
                        @foreach($specialities as $speciality)
                            <option value="{{ $speciality->id }}">{{ $speciality->name }}</option>
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
                            <input type="text" id="kidLastName" name="kid_last_name" class="form-control" placeholder="Last Name">
                            <label>Nom</label>
                           
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" id="kidFirstName" name="kid_first_name" class="form-control" placeholder="First Name">
                            <label>Prénom</label>
                            <!-- @error('kid_first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror -->
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" id="kidEcole" name="kid_ecole" class="form-control" placeholder="School">
                            <label>École</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select id="kidSystem" name="kid_system" class="form-select">
                                <option value="" disabled selected>Sélectionner un Système</option>
                                <option value="moroccan">Système Marocain</option>
                                <option value="mission">Système Mission</option>
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
                            <input type="text" name="parent_last_name" class="form-control" placeholder="Last Name" required>
                            <label>Nom</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" name="parent_first_name" class="form-control" placeholder="First Name" required>
                            <label>Prénom</label>
                            <!-- @error('parent_first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror -->
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="tel" name="parent_phone" class="form-control" placeholder="Phone" required>
                            <label>Numéro de Téléphone</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="email" id="parentEmail" name="parent_email" class="form-control @error('parent_email') is-invalid @enderror" placeholder="Email">
                            <label>Adresse Email</label>
                            @error('parent_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" name="parent_profession" class="form-control" placeholder="Profession" required>
                            <label>Profession</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" name="parent_etablissement" id="parentEtablissement" class="form-control bg-transparent" placeholder="Etablissement" required>
                            <label for="parentEtablissement">Établissement</label>
                        </div>
                    </div>
            
                    <div class="col-12">
                        <div class="form-floating">
                            <input type="text" name="parent_adresse" class="form-control" placeholder="Address" required>
                            <label>Adresse</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select name="mode" class="form-select" required>
                                <option value="A Distance">À Distance</option>
                                <option value="Presentiel">Présentiel</option>
                            </select>
                            <label>Mode de Consultation</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select name="abonnement" class="form-select" required>
                                <option value="seance">Par Séance</option>
                                <option value="mois">Mensuel</option>
                                <option value="pack">Pack</option>
                            </select>
                            <label>Type d'Abonnement</label>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Coaches Section -->
            <div class="col-12">
                <h4 class="section-title">Sélectionner les Coachs</h4>
                @error('no_coaches')
                <div class="alert alert-danger">{{$message}}</div>
                @enderror
                <div class="coach-section" id="coachContainer">
                    @foreach ($coaches as $coach)
                        <div class="form-check coach-item" data-coach-id="{{ $coach->id }}">
                            <input type="checkbox" name="coaches[]" id="coachCheckBox{{ $coach->id }}" class="form-check-input" value="{{ $coach->id }}" onchange="maxCountDisplay('{{ $coach->id }}', this)">
                            <label class="form-check-label" for="coachCheckBox{{ $coach->id }}">
                                {{ $coach->full_name }}
                            </label>
                            <input type="number" id="coach{{ $coach->id }}" name="coach{{ $coach->id }}" class="d-none" min="1" max="3" style="width: 100px;">
                        </div>
                    @endforeach
                </div>
                <!-- Hidden input to store the order -->
                <input type="hidden" name="coach_order" id="coach_order">
            </div>

            <!-- max appointments on a week -->
            <div class="col-md-4">
            <h4 class="section-title" >Rendez-vous par Semaine</h4>
            @error('max_appointments')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
                <div class="form-floating">
                    <input type="number" min="1" max="3" name="max_appointments" class="form-control" required>
                    <label >Nombre maximum de rendez-vous par semaine</label>
                </div>
            </div>

            <!-- image section -->
            
            <div class="form-group text-center mt-4">
                    <label class="form-label fw-bold mb-2">Choose an Image:</label>

                    <!-- Hidden File Input -->
                    <input type="file" name="image" id="image-input" accept="image/*" hidden onchange="previewImage(event)">

                    <!-- Custom Button -->
                    <label for="image-input" class="btn btn-primary">
                      <i class="fas fa-upload me-2"></i> Upload Image
                    </label>

                    <!-- Image Preview (Circle or Square) -->
                    <div id="image-preview" class="mt-3 d-none ">
                      <img src="#" alt="Image Preview" id="image-preview-img" class="rounded-circle shadow img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;">
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
                <div id="calendar"></div>
            </div>

         

            <!-- Hidden Input & Submit -->
            <div class="col-12 text-center mt-4">
                <input type="hidden" name="priorities" id="prioritiesInput">
                <button type="submit" class="btn btn-primary px-5 py-2">
                    <i class="fas fa-save me-2"></i>Créer le Profil du Patient
                </button>
            </div>
        </form>
    </div>
</div>

<script>
   
   function  maxCountDisplay(id,event) {
        if (event.checked) {
            document.querySelector(`#coach${id}`).classList.remove('d-none'); 
            document.querySelector(`#coach-order${id}`).classList.remove('d-none');
            document.querySelector(`#coach-order-label${id}`).classList.remove('d-none');

            console.log(id);
            
            
        }else{
        document.querySelector(`#coach${id}`).classList.add('d-none');
        document.querySelector(`#coach${id}`).value = ''; 
        document.querySelector(`#coach-order${id}`).classList.add('d-none');
        document.querySelector(`#coach-order${id}`).value = '';
        document.querySelector(`#coach-order-label${id}`).classList.add('d-none');

        }

    }
    var prioritiesData = {};
    document.addEventListener("DOMContentLoaded", function() {
        const patientTypeSelect = document.getElementById('patientType');
        const kidSection = document.getElementById('kidSection');
        const parentSection = document.getElementById('parentSection');
        const parentTitle = document.getElementsByClassName('parent-title');
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
            kidTitle.textContent = 'Young Details';
        }
        else {
            kidTitle.textContent = 'Kid Details';
        }
        if (selectedType === 'kid' || selectedType === 'young') {

            kidFirstName.setAttribute("required", "");
            kidLastName.setAttribute("required", "");
            kidEcole.setAttribute("required", "");
            kidSystem.setAttribute("required", "");

            kidSection.classList.remove('d-none');
            parentSection.classList.remove('d-none');
            parentTitle.textContent = 'Parent Details';
        } 
        else if (selectedType === 'adult') {
            kidFirstName.removeAttribute('required');
            kidLastName.removeAttribute('required');
            kidEcole.removeAttribute('required');
            kidSystem.removeAttribute('required');
            kidSection.classList.add('d-none');
            parentSection.classList.remove('d-none');
            parentTitle.textContent = 'Patient Details';
        } 
        else {
            kidSection.classList.add('d-none');
            parentSection.classList.add('d-none');
          
        }
        });
        if (parentEmail.classList.contains('is-invalid')) {
            parentSection.classList.remove('d-none');
            console.log('exist'); 
        }

        
    }); 
    document.addEventListener('DOMContentLoaded', function() {
    // Initialize SortableJS on the coach container
    var coachContainer = document.getElementById('coachContainer');
    Sortable.create(coachContainer, {
        animation: 150, // Smooth animation
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