@extends('layouts.coach_app')

@section('content')
<style>
    :root {
        --primary-color: #6366f1;
        --secondary: #4f46e5;
        --light-bg: #f8fafc;
        --dark-text: #1e293b;
    }

    .coach-form-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 2rem;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .form-header {
        font-size: 1.75rem;
        font-weight: 600;
        color: var(--dark-text);
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--primary-color);
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .form-card {
        background: var(--light-bg);
        border-radius: 8px;
        padding: 1.5rem;
    }

    .form-floating {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .form-control {
        width: 100%;
        padding: 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }

    .calendar-container {
        background: white;
        border-radius: 8px;
        padding: 1.5rem;
        margin-top: 2rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }
    
    #calendar {
        background: white;
        border-radius: 12px;
        padding: 1rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }
    .fc-header-toolbar {
        background: linear-gradient(195deg, var(--primary-color-color), var(--secondary-color));
        color: white;
        padding: 1rem;
        border-radius: 8px 8px 0 0;
    }
    .fc-button-primary {
        background-color: var(--primary-color-color) !important;
        border-color: var(--primary-color-color) !important;
    }
    .btn-submit {
        background: var(--primary-color);
        color: white;
        padding: 1rem 2rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        width: 100%;
        max-width: 300px;
        margin: 2rem auto 0;
    }

    .btn-submit:hover {
        background: var(--secondary);
        transform: translateY(-2px);
    }

    .invalid-feedback {
        color: #ef4444;
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }

    .is-invalid {
        border-color: #ef4444 !important;
        background-color: #fef2f2;
    }

    .alert-danger {
        background: #fef2f2;
        color: #ef4444;
        padding: 1rem;
        border-radius: 8px;
        margin: 1rem 0;
    }

    /* Responsive styles only - to be added at the end of existing CSS */

/* Mobile devices (phones, less than 768px) */
@media (max-width: 767.98px) {
    .coach-form-container {
        margin: 1rem;
        padding: 1rem;
    }
    
    .form-header {
        font-size: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .form-grid {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }
    
    .form-card {
        padding: 1rem;
    }
    
    .form-floating {
        margin-bottom: 1rem;
    }
    
    .calendar-container {
        padding: 1rem;
        margin-top: 1.5rem;
    }
    
    #calendar {
        height: auto;
        min-height: 400px;
    }
    
    /* Calendar toolbar adjustments */
    .fc-header-toolbar {
        flex-wrap: wrap;
        padding: 0.75rem;
    }
    
    .fc-toolbar-chunk {
        margin-bottom: 0.5rem;
        display: flex;
        justify-content: center;
        width: 100%;
    }
    
    /* Make toolbar buttons more touch-friendly */
    .fc-button {
        padding: 0.5rem !important;
        min-height: 44px !important;
        min-width: 44px !important;
        margin: 2px !important;
    }
    
    /* Ensure form inputs have good touch targets */
    .form-control, select, input[type="file"] {
        min-height: 44px;
        font-size: 16px; /* Prevent zoom on iOS */
    }
    
    /* Improve image upload section */
    label[for="image-input"] {
        min-height: 44px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    /* Make submit button more touch-friendly */
    .btn-submit {
        min-height: 44px;
        margin: 1.5rem auto 0;
    }
}

/* Small devices (landscape phones) */
@media (min-width: 576px) and (max-width: 767.98px) {
    .coach-form-container {
        margin: 1.5rem;
        padding: 1.5rem;
    }
    
    .form-card {
        padding: 1.25rem;
    }
}

/* Medium devices (tablets) */
@media (min-width: 768px) and (max-width: 991.98px) {
    .coach-form-container {
        max-width: 90%;
    }
    
    .form-grid {
        gap: 1.5rem;
    }
    
    /* Ensure calendar is responsive but maintains functionality */
    #calendar {
        overflow-x: auto;
    }
}



</style>

<div class="coach-form-container">
    <h1 class="form-header">Mettre à jour le profil de l'entraîneur</h1>
    
    <form action="{{route('update_profile', $user->id)  }}" onsubmit="return coachUpdatePlanning()" enctype="multipart/form-data"  method="POST" class="row g-4">
      @csrf
      @method('PUT')

        <div class="form-grid">
            <!-- Left Column -->
            <div class="form-card">
                <div class="form-floating">
                    <input type="text" class="form-control" id="fullName" name="full_name" value='{{ $user->full_name }}' required>
                    <label for="fullName">Nom complet</label>
                </div>
                
                <div class="form-floating">
                    <input type="number" class="form-control" id="tel" name="tel" value='{{ $user->phone }}' required>
                    <label for="tel">Numéro de téléphone</label>
                </div>
                
                <div class="form-floating">
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                           id="email" name="email"  value='{{ $user->email}}' required>
                    <label for="email">Adresse e-mail</label>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <!-- Right Column -->
            <div class="form-card">
                <div class="form-floating">
                    <select class="form-control" id="specialist" name="speciality_id" required>
                        <option value="" selected disabled>Sélectionnez une spécialisation</option>
                        @foreach ($specialities as $speciality)
                          <option value="{{ $speciality->id }}" {{ $user->speciality_id == $speciality->id ? 'selected':''}}>{{ $speciality->name }}</option>
                        @endforeach
                    </select>
                    <label for="specialist">Spécialisation</label>
                </div>

                <div class="form-floating">
                    <select class="form-control" name="is_available">
                      <option value="1" {{ $user->is_available == 1 ? 'selected':''}}>Disponible</option>
                      <option value="0" {{ $user->is_available == 0 ? 'selected':''}}>Indisponible</option>
                    </select>
                    <label>Statut de disponibilité</label>
                </div>

                <div class="form-floating">
                    <select class="form-control" name="role">
                      <option value="coach" {{ $user->role == 'coach' ?'selected' : '' }}>Entraîneur</option>
                      <option value="admin" {{ $user->role == 'admin' ?'selected' : '' }}>Administrateur</option>
                    </select>
                    <label>Rôle dans le system</label>
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
                    <div id="image-preview" class="mt-3 {{  $user->image_path ==null ? 'd-none': ''}} ">
                      <img src="{{ asset('storage/' . $user->image_path) }}" alt="Image Preview" id="image-preview-img" class="rounded-circle shadow img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;">
                    </div>
                </div>

            </div>
        </div>

                <!-- Error Messages -->
                @error('planning')
                  <div class="alert-danger">
                      <i class="fas fa-exclamation-circle mr-2"></i>
                      {{ $message }}
                  </div>
                @enderror

        <!-- Calendar Section -->
        <div class="calendar-container">
            <div id="calendar"></div>
        </div>



        <!-- Hidden Input & Submit -->
        <input type="hidden" id="planning" name="planning">
        <button type="submit" class="btn-submit">
            <i class="fas fa-save mr-2"></i>Mettre à jour le profil de l'entraîneur
        </button>
    </form>
</div>

<script>




  var availabilityData = {};
  
  var storedPlanning = <?php echo $user->planning ? $user->planning : '{}' ?>;
  console.log(storedPlanning);
  if (storedPlanning!=null) {
    availabilityData =storedPlanning;
  } else {
    availabilityData={};
  console.log("Disponibilités initiales :", availabilityData);

  }
  // console.log("Initial availabilityData:", availabilityData);

   
  function addAvailabilityEvent(date, eventId, startTime, endTime) {
    if (!availabilityData[date]) {
      availabilityData[date] = [];
    }
    availabilityData[date].push({
      id: eventId,
      startTime: startTime,
      endTime: endTime
    });
    console.log("Après ajout :", availabilityData);
  }

  function updateAvailabilityEvent(event) {
    var newStart = event.start;
    var newEnd = event.end ? event.end : event.start;
    var newDate = newStart.toISOString().split("T")[0];
    var newStartTime = newStart.toISOString().split("T")[1].substring(0,5);
    var newEndTime = newEnd.toISOString().split("T")[1].substring(0,5);
    
    // Remove event from all dates.
    for (var date in availabilityData) {
      availabilityData[date] = availabilityData[date].filter(function(slot) {
        return slot.id !== event.id;
      });
      // If no events remain for that date, delete the date key.
      if (availabilityData[date].length === 0) {
        delete availabilityData[date];
      }
    }
    
    // Add event to newDate.
    if (!availabilityData[newDate]) {
      availabilityData[newDate] = [];
    }
    availabilityData[newDate].push({
      id: event.id,
      startTime: newStartTime,
      endTime: newEndTime
    });
    console.log("Après mise à jour :", availabilityData);
  }

  function deleteAvailabilityEvent(event) {
    for (var date in availabilityData) {
      availabilityData[date] = availabilityData[date].filter(function(slot) {
        return slot.id !== event.id;
      });
      if (availabilityData[date].length === 0) {
        delete availabilityData[date];
      }
    }
    console.log("Après suppression :", availabilityData);
  }
  document.addEventListener("DOMContentLoaded", function() {
    // initAppointmentsCalendar();
    let initialDate;
  var calendarEl = document.getElementById("calendar");
    
    // You can choose a default color for coach availability events.
    var defaultColor = "blue";

    var calendar = new FullCalendar.Calendar(calendarEl, {
      // For example, show a week view.
      initialView: "timeGridWeek",
      selectable: true,
      editable: true,
      height:'500px',
      
      firstDay: 1, 
      hiddenDays: [0], 
      slotMinTime: '12:00:00',
      slotMaxTime: '20:00:00',
      slotDuration: '01:00:00', // Set the slot duration to 1 hour
      slotLabelInterval: '01:00:00', // Set the interval for slot labels to 1 hour
      allDaySlot: false,
      nowIndicator: true,
      expandRows: true, 
      select: function(info) {
        var startDateTime = info.startStr; 
        var endDateTime = info.endStr;
        
        var date = startDateTime.split("T")[0];
        var startTime = startDateTime.split("T")[1] ? startDateTime.split("T")[1].substring(0,5) : "00:00";
        var endTime = endDateTime ? (endDateTime.split("T")[1] ? endDateTime.split("T")[1].substring(0,5) : "23:59") : "23:59";
        
        // Create a unique ID for this availability event.
        var eventId = String(Date.now());
        
        // Add event to the calendar.
        var eventObj = {
          id: eventId,
          title: "Availability",
          start: startDateTime,
          end: endDateTime,
          backgroundColor: defaultColor
        };
        calendar.addEvent(eventObj);
        addAvailabilityEvent(date, eventId, startTime, endTime);
        calendar.unselect();
      },
      eventDrop: function(info) {
        updateAvailabilityEvent(info.event);
      },
      eventResize: function(info) {
        updateAvailabilityEvent(info.event);
      },
      eventClick: function(info) {
        if (confirm("Voulez-vous supprimer ce créneau de disponibilité ?")) {
          deleteAvailabilityEvent(info.event);
          info.event.remove();
        }
      }
    });
  
    calendar.render();

    
    const oldPlanningEvents =  <?php
      $color='orange';
      $coachPlanning =  json_decode($user->planning ,true);
      $events =[];
      if ($coachPlanning!=null) {
        foreach ($coachPlanning as $day => $dayData) {
          foreach ($dayData as $data) {
              // dd($data);
              $id = $data['id'];
              $title = "Availability";
              $date = $day;
              $start=$day.'T'.$data['startTime'].":"."00";
              $end = $day.'T'.$data['endTime'].":"."00";
              $backgroundColor = $color;
              $events[] = [
                'id' => $id,
                'title'=> $title,
                'start'=> $start,
                'end'=> $end,
                'backgroundColor'=> $backgroundColor
              ]; 
          }
        }
        echo json_encode($events);   

      }else{
        echo json_encode([]);
      }
    
    ?>;
    
    console.log("Événements de planification existants :", oldPlanningEvents);
    
    initialDate = oldPlanningEvents.length>0? oldPlanningEvents[0].start :null;
    initialDate = initialDate.split('T')[0];
    calendar.gotoDate(initialDate);
    calendar.addEventSource(oldPlanningEvents);
  });
  function coachUpdatePlanning() {
      document.getElementById("planning").value = JSON.stringify(availabilityData);
      console.log("Planification mise à jour :", document.getElementById("planning").value);
      return true; // Allow the form to submit
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
