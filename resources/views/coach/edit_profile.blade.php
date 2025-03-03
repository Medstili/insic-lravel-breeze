@extends('layouts.coach_app') 
@section('content')
<style>
        .calendar-container {
        background-color: #fff;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
       }
</style>
<div class="container mt-5">
  <div class="card glass-card p-4">
    <h3 class="mb-4">🌟 Update Profile</h3>
    <form action="{{ route('update_profile', $user->id) }}" onsubmit="coachUpdatePlanning()" method="POST" class="row g-4">
      @csrf
      @method('PUT')

      <!-- Left Column -->
      <div class="col-md-6">
        <div class="form-floating">
          <input type="text" class="form-control" id="fullName" name="full_name" value='{{ $user->full_name }}' required>
          <label for="fullName"><i class="fas fa-user me-2"></i>Full Name</label>
        </div>
        <div class="form-floating mt-2">
          <input type="number" class="form-control" id="tel" name="tel" value='{{ $user->phone }}' required>
          <label for="tel"><i class="fas fa-phone me-2"></i>Phone Number</label>
        </div>
        <div class="form-floating mt-3">
          <input type="email" class="form-control" id="email" name="email" value='{{ $user->email}}' required>
          <label for="email"><i class="fas fa-envelope me-2"></i>Email</label>
        </div>
      </div>

      <!-- Right Column -->
      <div class="col-md-6">
        <div class="card border-0 mb-3">
          <div class="card-body p-0">
            <h5 class="card-title"><i class="fas fa-dumbbell me-2"></i>Specialization</h5>
            <select class="form-select" id="specialist" name="speciality_id" required>
              <option value="" selected disabled>Select Specialization</option>
              @foreach ($specialities as $speciality)
                <option value="{{ $speciality->id }}" {{ $user->speciality_id == $speciality->id ? 'selected':''}}>{{ $speciality->name }}</option>
              @endforeach
            </select> 
          </div>
        </div>
        <div class="row g-2">
          <div class="col-6">
            <div class="card border-0">
              <div class="card-body p-0">
                <h5 class="card-title"><i class="fas fa-calendar-check me-2"></i>Availability</h5>
                <select class="form-select" name="is_available">
                  <option value="1" {{ $user->is_available == 1 ? 'selected':''}}>Available</option>
                  <option value="0" {{ $user->is_available == 0 ? 'selected':''}}>Unavailable</option>
                </select>
              </div>
            </div>
          </div>
        </div>
      </div>

  
         <!-- Calendar Section -->
        <div class="calendar-container">
            <div id="calendar"></div>
        </div>

      <!-- Submit Button -->
      <div class="col-12 text-center mt-4">
        <input type="hidden" id="planning" name="planning">
        <button type="submit" class="btn btn-light btn-lg px-5">
          <i class="fas fa-save me-2"></i>Update Profile
        </button>
      </div>
    </form>
  </div>
</div>
<script>

  var availabilityData = {};
  var storedPlanning =<?php echo $user->planning ?> ; 
  availabilityData=storedPlanning;
  console.log("Initial availabilityData:", availabilityData);
   

  // Helper function to add an event to availabilityData.
  function addAvailabilityEvent(date, eventId, startTime, endTime) {
    if (!availabilityData[date]) {
      availabilityData[date] = [];
    }
    availabilityData[date].push({
      id: eventId,
      startTime: startTime,
      endTime: endTime
    });
    console.log("After addition:", availabilityData);
  }
  // Helper function to update an event in availabilityData.
  // If the event's date has changed, remove it from the old date and add it under the new date.
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
    console.log("After update:", availabilityData);
  }
  // Helper function to delete an event from availabilityData.
  function deleteAvailabilityEvent(event) {
    for (var date in availabilityData) {
      availabilityData[date] = availabilityData[date].filter(function(slot) {
        return slot.id !== event.id;
      });
      if (availabilityData[date].length === 0) {
        delete availabilityData[date];
      }
    }
    console.log("After deletion:", availabilityData);
  }
  document.addEventListener("DOMContentLoaded", function() {
    availabilitiesCalendar();
  });

  function availabilitiesCalendar() {
    var calendarEl = document.getElementById("calendar");
    // You can choose a default color for coach availability events.
    var defaultColor = "blue";
    var calendar = new FullCalendar.Calendar(calendarEl, {
      // For example, show a week view.
      initialView: "timeGridWeek",
      selectable: true,
      editable: true, // Enable drag, resize, etc.
      
      // When a coach selects a time slot, automatically capture start/end times.
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
        
        // Update our global availabilityData.
        addAvailabilityEvent(date, eventId, startTime, endTime);
        calendar.unselect();
      },
      
      // When an event is dragged or resized, update its time.
      eventDrop: function(info) {
        updateAvailabilityEvent(info.event);
      },
      eventResize: function(info) {
        updateAvailabilityEvent(info.event);
      },
      
      // When an event is clicked, ask for deletion.
      eventClick: function(info) {
        if (confirm("Do you want to delete this availability slot?")) {
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
    ?>;
    
    console.log(oldPlanningEvents);
    
    calendar.addEventSource(oldPlanningEvents);
  }

  function coachUpdatePlanning() {
    document.getElementById("planning").value = JSON.stringify(availabilityData);
  }
</script>
@endsection
