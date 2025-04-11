@extends('layouts.app')

@section('content')
<style>
    :root {
        --primary: #6366f1;
        --secondary: #4f46e5;
        --light-bg: #f8fafc;
        --dark-text: #1e293b;
    }

    /* Responsive container */
    .coach-form-container {
        width: 100%;
        max-width: 1200px;
        margin: 1rem auto;
        padding: 1rem;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .form-header {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--dark-text);
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--primary);
    }

    /* Responsive grid layout */
    .form-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .form-card {
        background: var(--light-bg);
        border-radius: 8px;
        padding: 1.25rem;
    }

    .form-floating {
        position: relative;
        margin-bottom: 1.25rem;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        transition: all 0.3s ease;
        font-size: 16px; /* Prevent zoom on iOS */
        min-height: 44px; /* Better touch target */
    }

    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }

    /* Improve form labels */
    .form-floating label {
        padding: 0.75rem;
        font-size: 0.9rem;
    }

    .calendar-container {
        background: white;
        border-radius: 8px;
        padding: 1rem;
        margin-top: 1.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        overflow-x: auto; /* Allow horizontal scrolling for calendar on small screens */
    }
    
    #calendar {
        background: white;
        border-radius: 12px;
        padding: 0.5rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        min-height: 400px; /* Ensure minimum height for calendar */
    }

    .fc-header-toolbar {
        background: linear-gradient(195deg, var(--primary), var(--secondary));
        color: white;
        padding: 0.75rem;
        border-radius: 8px 8px 0 0;
        flex-wrap: wrap; /* Allow toolbar to wrap on small screens */
    }

    /* Make toolbar buttons more touch-friendly */
    .fc-button {
        padding: 0.5rem !important;
        min-height: 44px !important;
        min-width: 44px !important;
        margin: 2px !important;
        font-size: 0.9rem !important;
    }

    .fc-button-primary {
        background-color: var(--primary) !important;
        border-color: var(--primary) !important;
    }

    /* Improved submit button with better touch target */
    .btn-submit {
        background: var(--primary);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        width: 100%;
        max-width: 300px;
        margin: 1.5rem auto 0;
        display: block; /* Center the button */
        min-height: 44px; /* Better touch target */
        border: none;
        cursor: pointer;
        font-size: 1rem;
    }

    .btn-submit:hover {
        background: var(--secondary);
        transform: translateY(-2px);
    }

    .feedback {
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

    /* Image preview responsive styles */
    #image-preview {
        margin: 1rem auto;
        text-align: center;
    }

    #image-preview-img {
        width: 80px;
        height: 80px;
        object-fit: cover;
    }

    /* Custom file upload button */
    label[for="image-input"] {
        display: inline-block;
        padding: 0.75rem 1rem;
        min-height: 44px;
        cursor: pointer;
    }

    /* Extra small devices (phones, less than 576px) */
    @media (max-width: 575.98px) {
        .coach-form-container {
            padding: 0.75rem;
            margin: 0.75rem auto;
        }
        
        .form-header {
            font-size: 1.3rem;
            margin-bottom: 1.25rem;
            padding-bottom: 0.75rem;
        }
        
        .form-card {
            padding: 1rem;
        }
        
        .form-control {
            padding: 0.6rem;
        }
        
        .fc-toolbar-title {
            font-size: 1.1rem !important;
        }
        
        .fc-button {
            padding: 0.4rem !important;
            font-size: 0.8rem !important;
        }
        
        .btn-submit {
            padding: 0.6rem 1.2rem;
            font-size: 0.9rem;
            margin-top: 1.25rem;
        }
        
        #image-preview-img {
            width: 70px;
            height: 70px;
        }
    }

    /* Small devices (landscape phones, 576px and up) */
    @media (min-width: 576px) and (max-width: 767.98px) {
        .coach-form-container {
            padding: 1.5rem;
            margin: 1.5rem auto;
        }
        
        .form-header {
            font-size: 1.4rem;
        }
        
        .calendar-container {
            padding: 1.25rem;
        }
    }

    /* Medium devices (tablets, 768px and up) */
    @media (min-width: 768px) {
        .coach-form-container {
            padding: 2rem;
            margin: 2rem auto;
        }

        .form-header {
            font-size: 1.75rem;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
        }

        .form-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
        }

        .form-card {
            padding: 1.5rem;
        }

        .calendar-container {
            padding: 1.5rem;
        }

        #calendar {
            padding: 1rem;
        }

        .fc-header-toolbar {
            padding: 1rem;
        }
        
        #image-preview-img {
            width: 80px;
            height: 80px;
        }
    }

    /* Large devices (desktops, 992px and up) */
    @media (min-width: 992px) {
        .form-header {
            font-size: 1.85rem;
        }
        
        .form-control {
            padding: 0.85rem;
        }
        
        .btn-submit {
            padding: 0.85rem 1.75rem;
        }
    }

    /* Calendar responsive adjustments */
    @media (max-width: 767px) {
        .fc-toolbar {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
        }

        .fc-toolbar-chunk {
            display: flex;
            justify-content: center;
            margin-bottom: 0.5rem;
            width: 100%;
        }

        .fc-toolbar-title {
            font-size: 1.25rem !important;
            text-align: center;
        }

        .fc-view-harness {
            min-height: 400px;
        }

        /* Adjust calendar view for mobile */
        .fc .fc-scrollgrid-liquid {
            height: 100%;
        }

        /* Make time slots more readable on mobile */
        .fc-timegrid-slot {
            height: 3em !important;
        }

        /* Ensure calendar fits in viewport */
        .fc-view {
            overflow: visible;
        }
        
        /* Improve touch targets for time slots */
        .fc-timegrid-slot-lane {
            min-height: 44px;
        }
    }
    
  
    
    /* Print styles */
    @media print {
        .coach-form-container {
            box-shadow: none;
            margin: 0;
            padding: 0;
        }
        
        .form-card, .calendar-container {
            box-shadow: none;
            break-inside: avoid;
        }
        
        .btn-submit, label[for="image-input"] {
            display: none;
        }
        
        .fc-header-toolbar {
            background: #f8fafc !important;
            color: black !important;
        }
    }
</style>

<div class="coach-form-container">
    <h1 class="form-header">Mettre à jour le profil de l'entraîneur</h1>
    
    <form action="{{ route('user.update', $user->id) }}" onsubmit="return coachUpdatePlanning()" enctype="multipart/form-data"  method="POST" class="row g-4">
      @csrf
      @method('PUT')

        <div class="form-grid">
            <!-- Colonne gauche -->
            <div class="form-card">
                <div class="form-floating">
                    <input type="text" class="form-control" id="fullName" name="full_name" value='{{ $user->full_name }}' required>
                    <label for="fullName">Nom complet</label>
                    @error('full_name')
                        <div class="feedback">{{ $message }}</div>
                    @enderror
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
                        <div class="feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <!-- Colonne droite -->
            <div class="form-card">
                <div class="form-floating">
                    <select class="form-control" id="specialist" name="speciality_id" required>
                        <option value="" selected disabled>Choisir une spécialisation</option>
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
                    <label>Rôle dans le système</label>
                </div>

                <div class="form-group text-center mt-4">
                    <label class="form-label fw-bold mb-2">Choisir une image :</label>

                    <!-- Champ de fichier caché -->
                    <input type="file" name="image" id="image-input" accept="image/*" hidden onchange="previewImage(event)">

                    <!-- Bouton personnalisé -->
                    <label for="image-input" class="btn btn-primary">
                      <i class="fas fa-upload me-2"></i> Télécharger une image
                    </label>

                    <!-- Aperçu de l'image -->
                    <div id="image-preview" class="mt-3 {{  $user->image_path ==null ? 'd-none': ''}} ">
                      <img src="{{ asset('storage/' . $user->image_path) }}" alt="Aperçu de l'image" id="image-preview-img" class="rounded-circle shadow img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;">
                    </div>
                </div>

            </div>
        </div>

        <!-- Messages d'erreur -->
        @error('planning')
          <div class="alert-danger">
              <i class="fas fa-exclamation-circle mr-2"></i>
              {{ $message }}
          </div>
        @enderror

        <!-- Section du calendrier -->
        <div class="calendar-container">
            <div id="calendar"></div>
        </div>

        <!-- Entrée cachée et soumission -->
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
    availabilityData = storedPlanning;
  } else {
    availabilityData = {};
    console.log("Disponibilités initiales :", availabilityData);
  }

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
    let initialDate;
    var calendarEl = document.getElementById("calendar");
    
    // You can choose a default color for coach availability events.
    var defaultColor = "blue";
    
    // Determine initial view based on screen size
    var initialView = window.innerWidth < 768 ? "timeGridDay" : "timeGridWeek";

    var calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: initialView,
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
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'timeGridDay,timeGridWeek'
      },
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
          title: "Disponibilité",
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
      },
      // Responsive handling
      windowResize: function(view) {
        if (window.innerWidth < 768) {
          calendar.changeView('timeGridDay');
        } else {
          calendar.changeView('timeGridWeek');
        }
      }
    });
  
    calendar.render();

    const oldPlanningEvents = <?php
      $color='orange';
      $coachPlanning = json_decode($user->planning, true);
      $events = [];
      if ($coachPlanning != null) {
        foreach ($coachPlanning as $day => $dayData) {
          foreach ($dayData as $data) {
              $id = $data['id'];
              $title = "Disponibilité";
              $date = $day;
              $start = $day.'T'.$data['startTime'].":"."00";
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
      } else {
        echo json_encode([]);
      }
    ?>;
    
    console.log("Événements de planification existants :", oldPlanningEvents);
    
    if (oldPlanningEvents.length > 0) {
      initialDate = oldPlanningEvents[0].start;
      initialDate = initialDate.split('T')[0];
      calendar.gotoDate(initialDate);
    }
    
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
