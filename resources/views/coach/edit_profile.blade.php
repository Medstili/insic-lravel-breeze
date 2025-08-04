@extends('layouts.coach_app')

@section('content')

<div class="min-h-screen bg-gradient-to-br from-cyan-50 via-sky-50 to-blue-50 p-6 mt-24">
    <div class="max-w-7xl mx-auto">
        <!-- Hero Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-cyan-600 via-sky-600 to-blue-600 bg-clip-text text-transparent mb-4">
                Modifier le Profil
            </h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Mettez à jour votre informations personnelles et la planification de disponibilité 
            </p>
        </div>

        <!-- Main Form Container -->
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 p-8 mb-8">
            <form action="{{ route('update_profile', $user->id) }}" onsubmit="return coachUpdatePlanning()" enctype="multipart/form-data" method="POST">
      @csrf
      @method('PUT')

                <!-- Form Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <div class="form-group">
                            <label for="fullName" class="form-label">Nom complet</label>
                            <input type="text" class="form-input" id="fullName" name="full_name" value="{{ $user->full_name }}" required>
                    @error('full_name')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                        <div class="form-group">
                            <label for="tel" class="form-label">Numéro de téléphone</label>
                            <input type="tel" class="form-input" id="tel" name="tel" value="{{ $user->phone }}" required>
                </div>
                
                        <div class="form-group">
                            <label for="email" class="form-label">Adresse e-mail</label>
                            <input type="email" class="form-input @error('email') border-red-500 @enderror" 
                                   id="email" name="email" value="{{ $user->email }}" required>
                    @error('email')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <div class="form-group">
                            <label for="password" class="form-label">Mot de passe</label>
                            <div style="position: relative;">
                                <input type="password" class="form-input" id="password" name="password" placeholder="Laissez vide pour ne pas changer">
                            </div>
                            <small class="text-gray-500">Laissez vide pour ne pas changer le mot de passe</small>
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation" class="form-label">Confirmation du mot de passe</label>
                            <div style="position: relative;">
                                <input type="password" class="form-input" id="password_confirmation" name="password_confirmation" placeholder="Confirmez le mot de passe">
                            </div>
                            <small class="text-gray-500">Laissez vide pour ne pas changer le mot de passe</small>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Statut de disponibilité</label>
                            <select class="form-select" name="is_available">
                                <option value="1" {{ $user->is_available == 1 ? 'selected' : '' }}>Disponible</option>
                                <option value="0" {{ $user->is_available == 0 ? 'selected' : '' }}>Indisponible</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Photo de profil</label>
                            <div class="text-center">
                                <input type="file" name="image" id="image-input" accept="image/*" class="hidden" onchange="previewImage(event)">
                                
                                <label for="image-input" class="inline-flex items-center gap-2 bg-gradient-to-r from-cyan-500 to-sky-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-cyan-600 hover:to-sky-700 transform hover:-translate-y-1 transition-all duration-200 shadow-lg hover:shadow-xl cursor-pointer">
                                    <i class="fas fa-upload"></i> Télécharger une image
                                </label>

                                <div class="text-xs text-gray-500 mt-2">Taille maximale: 2MB</div>

                                <!-- Image Preview -->
                                <div id="image-preview" class="mt-4 {{ $user->image_path == null ? 'hidden' : '' }}">
                                    <img src="{{ asset('storage/' . $user->image_path) }}" alt="Aperçu de l'image" 
                                         id="image-preview-img" class="w-20 h-20 rounded-full object-cover border-4 border-cyan-200 shadow-lg">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Error Messages -->
        @error('planning')
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-exclamation-circle"></i>
              {{ $message }}
                        </div>
          </div>
        @enderror

                <!-- Calendar Section -->
                <div class="bg-white rounded-2xl shadow-lg border border-cyan-100 p-6 mb-8">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Planification de disponibilité</h3>
                    <div id="calendar" class="min-h-[500px]"></div>
        </div>

                <!-- Hidden Input and Submit -->
        <input type="hidden" id="planning" name="planning">
                <div class="text-center">
                    <button type="submit" class="inline-flex items-center gap-2 bg-gradient-to-r from-cyan-500 to-sky-600 text-white px-8 py-4 rounded-2xl font-semibold hover:from-cyan-600 hover:to-sky-700 transform hover:-translate-y-1 transition-all duration-200 shadow-lg hover:shadow-2xl">
                        <i class="fas fa-save"></i> Mettre à jour le profil
        </button>
                </div>
    </form>
        </div>
    </div>
</div>

<script>
  var availabilityData = {};
  
  var storedPlanning = <?php echo $user->planning ? $user->planning : '{}' ?>;
  console.log(storedPlanning);
  if (storedPlanning!=null) {
    availabilityData = storedPlanning;
  } 
  else {
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
    const file = input.files[0];

    if (file) {
      // Check file size (2MB = 2 * 1024 * 1024 bytes)
      if (file.size > 2 * 1024 * 1024) {
        alert("L'image est trop volumineuse. La taille maximale autorisée est de 2MB.");
        input.value = '';
        return;
      }

      const reader = new FileReader();
      reader.onload = function (e) {
        previewImage.src = e.target.result;
        previewContainer.classList.remove("hidden"); // Show preview
      };
      reader.readAsDataURL(file);
    }
  }

  document.getElementById('password').addEventListener('input', function() {
    const passwordConfirmation = document.getElementById('password_confirmation');
    if (this.value) {
      passwordConfirmation.setAttribute('required', 'required');
    } else {
      passwordConfirmation.removeAttribute('required');
    }
  });
</script>
@endsection
