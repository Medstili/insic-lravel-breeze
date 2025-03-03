
@extends('layouts.app')
    @section('content')

            @php
            $selectedSpecialty = request('specialty'); 
            @endphp  


        <div class="glass-form">
            <h2 class="text-2xl font-bold mb-6 text-white text-center">
                <i class="fas fa-calendar-plus form-icon"></i>New Appointment
            </h2>

            <form action="{{route('appointment.store')}}" method="post" onsubmit="appointmentUpdatePlanning()" class="space-y-5">
                @csrf
                <!-- Client Name -->
                <div>
                    <label class="form-label">
                        <i class="fas fa-user form-icon"></i>Client Full Name
                    </label>
                    <input type="text" 
                        class="form-input w-full" 
                        placeholder="John Doe"
                        name="client_name"
                        required>
                </div>

                <!-- Specialty Selection -->
                <div>
                    <label class="form-label">
                        <i class="fas fa-dumbbell form-icon"></i>Specialty
                    </label>
                    <select class="form-input w-full text-white" name="speciality" id="specialitySelect" required>
                        <option value="" class='text-black'>Select Specialty</option>
                      
                        @foreach ($specialities as $speciality)
                            <option value="{{ $speciality->name }}" class="text-black">
                                {{ $speciality->name }}
                            </option>
                        @endforeach
                    </select>

                </div>
                    <div class="col-12">
                            <div class="card bg-transparent">
                                <div class="card-body">
                                
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="card-title text-white"><i class="fas fa-clock me-2"></i>Schedule</h5>
                                        <!-- <button type="button" class="btn btn-link text-white" onclick="checkAvailability()">Check</button> -->
                                    </div>
                                
                                    <div class="row g-3" id="dayTimingsContainer">
                                        <!-- Dynamic timing cards will be added here -->
                                    </div>
                                    <div class="mt-3">
                                        <!-- Replacing the day select with a date input -->
                                        <input type="date" class="form-control bg-transparent text-white" 
                                            id="datePicker" onchange="appointmentToggleDayTime(this.value)">
                                    </div>
                                </div>
                            </div>
                        </div>
                
                        <!-- Coach Selection -->
                <div>
                    <label class="form-label">
                        <i class="fas fa-chalkboard-teacher form-icon"></i>Coach Name
                    </label>
                    <select class="form-input w-full text-white" name="coach" id="coachSelect" required>
                        <option value="">Select Coach</option>
                    </select>
                </div>
                <!-- Contact Info -->
                <div>
                    <label class="form-label">
                        <i class="fas fa-phone form-icon"></i>Client Phone
                    </label>
                    <input type="tel" 
                        class="form-input w-full" 
                        placeholder="+1 (555) 123-4567"
                        pattern="[0-9]{10}"
                        name="client_tel"
                        required>
                </div>
                <!-- input for storing the schedule -->
                <input type="hidden" id="planning" name="planning">
                <!-- booking Button -->
                <button type="submit" class="submit-btn">
                    <i class="fas fa-calendar-check mr-2"></i>Book Appointment
                </button>
            </form>
        </div>
        <script>
            // function appointmentToggleDayTime(day) {
            //     if (!day) return;
            //     const container = document.getElementById('dayTimingsContainer');
            //     const hasChildDivs = container.querySelectorAll('div').length > 0;
            //     const datetimepicker = document.getElementById('datePicker')
                
            //     // console.log(hasChildDivs)
            //     if (hasChildDivs) return;
                
            //     if (!document.getElementById(day + '/time')) {
            //         datetimepicker.value=''
            //         const card = document.createElement('div');
            //         card.className = 'col-md-12';
            //         card.innerHTML = `
            //             <div class="timing-card" id="${day}/time">
            //                 <div class="d-flex justify-content-between align-items-center">
            //                     <h6 class="mb-0 text-white" id="date">${day}</h6>
            //                     <input type='hidden' id='dateInput' value='${day}'/>
            //                     <button type="button" class="btn btn-link text-danger" 
            //                             onclick="appointmentRemoveDay('${day}')">
            //                         <i class="fas fa-times"></i>
            //                     </button>
            //                 </div>
            //                 <div class="row g-2 mt-2" id="timing-inputs">
            //                     <div class="col-6">
            //                         <input type="time" class="form-control bg-transparent text-white" id="start_time"placeholder="Start Time">
            //                     </div>
            //                     <div class="col-6">
            //                         <input type="time" class="form-control bg-transparent text-white" id="end_time"placeholder="End Time">
            //                     </div>
            //                 </div>
            //             </div>
            //         `;
            //         container.appendChild(card);
            //         // console.log(document.getElementById(day + '/time').id);

            //     }

            //     datetimepicker.disabled=true; 
                    
                    
            // }

            // function appointmentRemoveDay(day) {
            //     const section = document.getElementById(day + '/time');
            //     var datetimepicker = document.getElementById('datePicker')
            //     if (section) {
            //         section.parentElement.remove();
            //         datetimepicker.disabled=false;
            //     }
            // }

            // function appointmentUpdatePlanning() {
            //     let schedule = {};
            //     const dayTimings = document.querySelectorAll('.timing-card');
            //     const dayInputs =document.querySelectorAll('#timing-inputs');
            //     dayTimings.forEach((card, index) => {
            //         const day = card.id.split('/')[0];
            //         const inputs = dayInputs[index].querySelectorAll('input');
            //         schedule[day] = {
            //             startTime: inputs[0].value,
            //             endTime: inputs[1].value
            //         };
            //         });
            //     document.getElementById('planning').value = JSON.stringify(schedule);
            

            // }

        </script>
    @endsection
