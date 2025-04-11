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
        font-size: 1rem; /* Ensure readable text size on mobile */
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

    /* Calendar container with better mobile support */
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
        .form-header {
            font-size: 1.3rem;
        }
        
        .form-card {
            padding: 1rem;
        }
        
        .form-control {
            font-size: 16px; /* Prevent zoom on iOS */
        }
        
        /* Optimize calendar for very small screens */
        .fc-toolbar-title {
            font-size: 1.1rem !important;
        }
        
        .fc-button {
            padding: 0.4rem !important;
            font-size: 0.8rem !important;
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
    }

    /* Medium devices (tablets, 768px and up) */
    @media (min-width: 768px) {
        .coach-form-container {
            padding: 2rem;
            margin: 2rem auto;
        }

        .form-header {
            font-size: 1.75rem;
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
    }

    /* Large devices (desktops, 992px and up) */
    @media (min-width: 992px) {
        .form-header {
            font-size: 1.85rem;
        }
        
        .form-control {
            padding: 0.85rem;
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
    
    /* Fix for iOS input styling */

</style>

<div class="coach-form-container">
    <h1 class="form-header">Créer un nouveau profil d'entraîneur</h1>
    
    <form action="{{ route('user.store') }}" onsubmit="coachUpdatePlanning()" enctype="multipart/form-data" method="POST">
        @csrf
        
        <div class="form-grid">
            <!-- Left Column -->
            <div class="form-card">
                <div class="form-floating">
                    <input type="text" class="form-control" id="fullName" name="full_name" required>
                    <label for="fullName">Nom complet</label>
                </div>
                
                <div class="form-floating">
                    <input type="number" class="form-control" id="tel" name="tel" required>
                    <label for="tel">Numéro de téléphone</label>
                </div>
                
                <div class="form-floating">
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                           id="email" name="email" required>
                    <label for="email">Adresse e-mail</label>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-floating">
                    <input type="password" class="form-control" 
                           id="password" name="password" required>
                    <label for="password">Mot de passe</label>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />

            </div>

            <!-- Right Column -->
            <div class="form-card">
                <div class="form-floating">
                    <select class="form-control" id="specialist" name="speciality_id" required>
                        <option value="" selected disabled>Choisir une spécialisation</option>
                        @foreach ($specialities as $speciality)
                            <option value="{{ $speciality->id }}">{{ $speciality->name }}</option>
                        @endforeach
                    </select>
                    <label for="specialist">Spécialisation</label>
                </div>

                <div class="form-floating">
                    <select class="form-control" name="is_available">
                        <option value="1">Disponible</option>
                        <option value="0">Indisponible</option>
                    </select>
                    <label>Statut de disponibilité</label>
                </div>

                <div class="form-floating">
                    <select class="form-control" name="role">
                        <option value="coach">Entraîneur</option>
                        <option value="admin">Administrateur</option>
                    </select>
                    <label>Rôle dans le système</label>
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
                    <div id="image-preview" class="mt-3 d-none ">
                      <img src="#" alt="Image Preview" id="image-preview-img" class="rounded-circle shadow img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;">
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
            <i class="fas fa-save mr-2"></i>Créer le profil d'entraîneur
        </button>
    </form>
</div>

<script>
  var availabilityData = {};

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
    
    // Supprimer l'événement de toutes les dates.
    for (var date in availabilityData) {
      availabilityData[date] = availabilityData[date].filter(function(slot) {
        return slot.id !== event.id;
      });
      // Si aucun événement ne reste pour cette date, supprimer la clé de date.
      if (availabilityData[date].length === 0) {
        delete availabilityData[date];
      }
    }
    
    // Ajouter l'événement à newDate.
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
    var calendarEl = document.getElementById("calendar");
    
    // Vous pouvez choisir une couleur par défaut pour les événements de disponibilité des entraîneurs.
    var defaultColor = "blue";
    
    // Determine initial view based on screen size
    var initialView = window.innerWidth < 768 ? "timeGridDay" : "timeGridWeek";

    var calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: initialView,
      selectable: true,
      editable: true, // Activer le glisser-déposer, redimensionner, etc.
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
      // Lorsqu'un entraîneur sélectionne un créneau horaire, capturer automatiquement les heures de début/fin.
      select: function(info) {
        var startDateTime = info.startStr; 
        var endDateTime = info.endStr;
        
        var date = startDateTime.split("T")[0];
        var startTime = startDateTime.split("T")[1] ? startDateTime.split("T")[1].substring(0,5) : "00:00";
        var endTime = endDateTime ? (endDateTime.split("T")[1] ? endDateTime.split("T")[1].substring(0,5) : "23:59") : "23:59";
        
        // Créer un ID unique pour cet événement de disponibilité.
        var eventId = String(Date.now());
        
        // Ajouter l'événement au calendrier.
        var eventObj = {
          id: eventId,
          title: "Disponibilité",
          start: startDateTime,
          end: endDateTime,
          backgroundColor: defaultColor
        };
        calendar.addEvent(eventObj);
        
        // Mettre à jour notre disponibilité globale.
        addAvailabilityEvent(date, eventId, startTime, endTime);
        calendar.unselect();
      },
      // Lorsqu'un événement est déplacé ou redimensionné, mettre à jour son heure.
      eventDrop: function(info) {
        updateAvailabilityEvent(info.event);
      },
      eventResize: function(info) {
        updateAvailabilityEvent(info.event);
      },
      // Lorsqu'un événement est cliqué, demander une suppression.
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
 
  function coachUpdatePlanning() {
    var planningInput = document.getElementById("planning");
    planningInput.value = JSON.stringify(availabilityData);
  }
</script>
@endsection
