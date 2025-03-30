@extends('layouts.app')

@section('content')
<style>
    :root {
        --primary: #6366f1;
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
        border-bottom: 2px solid var(--primary);
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
        border-color: var(--primary);
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
        background: linear-gradient(195deg, var(--primary-color), var(--secondary-color));
        color: white;
        padding: 1rem;
        border-radius: 8px 8px 0 0;
    }
    .fc-button-primary {
        background-color: var(--primary-color) !important;
        border-color: var(--primary-color) !important;
    }
    .btn-submit {
        background: var(--primary);
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

    var calendar = new FullCalendar.Calendar(calendarEl, {
      // Par exemple, afficher une vue hebdomadaire.
      initialView: "timeGridWeek",
      selectable: true,
      editable: true, // Activer le glisser-déposer, redimensionner, etc.
      height:'500px',
      firstDay: 1, 
      hiddenDays: [0], 
      slotMinTime: '12:00:00',
      slotMaxTime: '20:00:00',
      allDaySlot: false,
      nowIndicator: true,
      expandRows: true,
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
