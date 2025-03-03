@extends('layouts.app') {{-- Extend your main layout --}}

@section('content')

<style>
        .calendar-container {
        background-color: #fff;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 20px;
        
    }
    #calendar{
        height: 700px;
    }
</style>
<div class="container mt-5">
  <div class="card  p-4">
    <h3 class="mb-4">🌟 New Patient Profile</h3>
    <form action="{{ route('patient.store') }}" onsubmit="storePriorities()" method="POST" class="row g-4">
      @csrf
        <!-- Patient Type Selection -->
        <div class="col-12">
            <div class="form-floating mb-3">
            <select id="patientType" name="patient_type" class="form-select bg-transparent">
                <option value="" selected disabled>Select Patient Type</option>
                <option value="kid">Kid</option>
                <option value="young">Young</option>
                <option value="adult">Adult</option>
            </select>
            <label for="patientType">Patient Type</label>
            </div>
        </div>

        <!-- age -->
        <div class="row">
            <div class="col-md-6">
                    <div class="form-floating mb-3">
                    <input type="number" name="age" id="kidAge" class="form-control bg-transparent" placeholder="Age">
                    <label for="kidAge">Age</label>
                    </div>
            </div>
            <!-- gender -->
            <div class="col-md-6">
                <div class="form-floating">
                    <select name="PatientGender" id="PatientGender" class="form-select bg-transparent">
                        <option value="M">Male</option>
                        <option value="F">Female</option>
                    </select>
                    <label for="PatientGender">Patient Gender</label>
                </div>
            </div>
        </div>
        
        <!-- Kid/Young Section: Only for Kid or Young -->
        <div id="kidSection" class="col-12 d-none">
            <h4 class="KidYoungDetailsTitle">Kid Details</h4>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                    <input type="text" name="kid_last_name" id="kidLastName" class="form-control bg-transparent" placeholder="Nom">
                    <label for="kidLastName">Nom</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                    <input type="text" name="kid_first_name" id="kidFirstName" class="form-control bg-transparent" placeholder="Prénom">
                    <label for="kidFirstName">Prénom</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <!-- ecole -->
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                    <input type="text" name="kid_ecole" id="kidEcole" class="form-control bg-transparent" placeholder="Ecole">
                    <label for="kidEcole">Ecole</label>
                    </div>
                </div>
                <!-- system -->

                <div class="col-md-6">
                    <div class="form-floating">
                        <select name="kid_system" id="kidSystem" class="form-select bg-transparent">
                        <option value="" selected disabled>Select A system</option>
                        <option value="moroccan">Moroccan System</option>
                        <option value="mission">Mission System</option>
                        </select>
                        <label for="kid_system">System</label>
                    </div>
                </div>
            </div>
        </div>
        
     
        <!-- Parent / Adult Section -->
        <div id="parentSection" class="col-12 d-none">
            <h4 id="parentSectionTitle">Parent / Patient Details</h4>
            <div class="row">
            <div class="col-md-6">
                <div class="form-floating mb-3">
                <input type="text" name="parent_last_name" id="parentLastName" class="form-control bg-transparent" placeholder="Nom">
                <label for="parentLastName">Nom</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-floating mb-3">
                <input type="text" name="parent_first_name" id="parentFirstName" class="form-control bg-transparent" placeholder="Prénom">
                <label for="parentFirstName">Prénom</label>
                </div>
            </div>
            </div>
            <div class="row">
            <div class="col-md-6">
                <div class="form-floating mb-3">
                <input type="tel" name="parent_phone" id="parentPhone" class="form-control bg-transparent" placeholder="Phone">
                <label for="parentPhone">Phone</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-floating mb-3">
                <input type="text" name="parent_profession" id="parentProfession" class="form-control bg-transparent" placeholder="Profession">
                <label for="parentProfession">Profession</label>
                </div>
            </div>
            </div>
            <div class="form-floating mb-3">
            <input type="text" name="parent_etablissement" id="parentEtablissement" class="form-control bg-transparent" placeholder="Etablissement">
            <label for="parentEtablissement">Etablissement</label>
            </div>
            <div class="form-floating mb-3">
            <input type="email" name="parent_email" id="parentEmail" class="form-control bg-transparent" placeholder="Email">
            <label for="parentEmail">Email</label>
            </div>
            <div class="form-floating mb-3">
            <input type="text" name="parent_adresse" id="parentAdresse" class="form-control bg-transparent" placeholder="Adresse">
            <label for="parentAdresse">Adresse</label>
            </div>
            <div class="mb-3">
            <label for="mode" class="form-label">Mode</label>
            <select name="mode" id="mode" class="form-select bg-transparent">
                <option value="A Distance">A distance</option>
                <option value="Presentiel">Presentiel</option>
            </select>
            </div>
            <div class="mb-3">
            <label for="abonnement" class="form-label">Abonnement</label>
            <select name="abonnement" id="abonnement" class="form-select bg-transparent">
                <option value="seance">Seance</option>
                <option value="mois">Mois</option>
                <option value="pack">Pack</option>
            </select>
            </div>
        </div>

        <div class="specialities d-none">
            <label class="filter-label  text-2xl mb-2">
               Select Specialty
            </label>
            <select class="glass-select" id="specialtySelect" name="specialty_id">
                    <option value="">All Specialties</option>
                @foreach($specialities as $specialty)
                    <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                @endforeach
             </select>
        </div>

        <!-- Calendar Section -->
        <div class="calendar-container">
            <div id="calendar"></div>
        </div>           

        <!-- Submit Button -->
        <div class="col-12 text-center mt-4">
            <input type="hidden" name="priorities" id="prioritiesInput">
            <button type="submit" class="btn btn-primary btn-lg px-5">
            <i class="fas fa-save me-2"></i>Add Patient
            </button>
        </div>

  
    </form>
  </div>
</div>



<script>
    document.addEventListener("DOMContentLoaded", function() {
        const patientTypeSelect = document.getElementById('patientType');
        const kidSection = document.getElementById('kidSection');
        const parentSection = document.getElementById('parentSection');
        const parentSectionTitle = document.getElementById('parentSectionTitle');
        const kidYoungDetailsTitle = document.querySelector('.KidYoungDetailsTitle');
        const calendarContainer = document.querySelector('.calendar-container');
        const specialities = document.querySelector('.specialities');

        patientTypeSelect.addEventListener('change', function() {
        const selectedType = this.value;
        specialities.classList.remove('d-none');
        
        if (selectedType === 'young') {
            kidYoungDetailsTitle.textContent = 'Young Details';
        } else {
            kidYoungDetailsTitle.textContent = 'Kid Details';
        }
        if (selectedType === 'kid' || selectedType === 'young') {
            kidSection.classList.remove('d-none');
            parentSection.classList.remove('d-none');
            // For Kid/Young, label the parent section as "Parent Details"
            parentSectionTitle.textContent = 'Parent Details';
        } else if (selectedType === 'adult') {
            kidSection.classList.add('d-none');
            parentSection.classList.remove('d-none');
            // For adult patients, show only this section
            parentSectionTitle.textContent = 'Patient Details';
        } else {
            kidSection.classList.add('d-none');
            parentSection.classList.add('d-none');
            specialities.classList.add('d-none');
        }
        });
        
    });


    var prioritiesData = {
    "priority 1": {},
    "priority 2": {},
    "priority 3": {}
    };

    // Add a new event to the prioritiesData object.
    function addEventToPriorities(priorityChoice, date, eventId, startTime, endTime) {
    var priorityKey = "priority " + priorityChoice;
    if (!prioritiesData[priorityKey]) {
        prioritiesData[priorityKey] = {};
    }
    if (!prioritiesData[priorityKey][date]) {
        prioritiesData[priorityKey][date] = [];
    }
    prioritiesData[priorityKey][date].push({
        id: eventId,
        startTime: startTime,
        endTime: endTime
    });
    console.log("After addition:", prioritiesData);
    }

    // Update an event when dragged/resized.
    // If the event’s date changes, remove it from the old date and add it to the new date.
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

    // Delete an event from prioritiesData.
    // Remove the event from the appropriate date array and delete the date key if the array becomes empty.
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
        // Set the initial view to week (timeGridWeek for a time-based week view)
        initialView: "timeGridWeek",
        selectable: true,
        editable: true,
        select: function(info) {
        // info.startStr and info.endStr include the date and time.
        // If time isn't provided, use defaults.
        var startDateTime = info.startStr; // e.g., "2025-02-03T14:00:00"
        var endDateTime = info.endStr;       // e.g., "2025-02-03T14:45:00"
        
        var date = startDateTime.split("T")[0]; // "YYYY-MM-DD"
        var startTime = startDateTime.split("T")[1] ? startDateTime.split("T")[1].substring(0,5) : "00:00";
        var endTime = endDateTime ? (endDateTime.split("T")[1] ? endDateTime.split("T")[1].substring(0,5) : "23:59") : "23:59";
        
        // Prompt user to choose a priority.
        var priorityChoice = prompt("Enter priority (1=High, 2=Medium, 3=Low):");
        if (!priorityChoice || !["1","2","3"].includes(priorityChoice)) {
            alert("Invalid priority selected.");
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
        // Update the prioritiesData object.
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
        if (confirm("Do you want to delete this event?")) {
            deleteEventFromPriorities(info.event);
            info.event.remove();
        }
        }
   
    });
    calendar.render();
});
    function storePriorities() {
    // Update the hidden input with the stringified JSON.
    document.getElementById("prioritiesInput").value = JSON.stringify(prioritiesData);
    // Return true to allow the form submission to proceed.
    return true;
}

</script>
@endsection
