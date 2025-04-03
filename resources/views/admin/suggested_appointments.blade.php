@extends('layouts.app')

@php
    use App\Models\Patient;

@endphp

@section('content')


<style>
    .calendar-wrapper {
        padding: 1rem;
        min-height: calc(100vh - 80px);
        background: #f8fafc;
    }

    .calendar-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        padding: 2rem;
    }

    .calendar-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .calendar-title {
        font-size: 1.75rem;
        font-weight: 600;
        color: #1e293b;
    }

    .week-navigation {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .nav-button {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        background: #3b82f6;
        color: white;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .nav-button:hover {
        background: #2563eb;
        transform: translateY(-1px);
    }

    .current-week {
        font-weight: 500;
        color: #64748b;
    }

    .table-container {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        position: relative;
        max-height: 70vh;
        overflow-y: auto;
    }

    .calendar-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 1200px;
    }

    .calendar-table th {
        background: #f1f5f9;
        color: #64748b;
        font-weight: 500;
        padding: 1rem;
        position: sticky;
        top: 0;
        z-index: 23;
    }

    .calendar-table td {
        padding: 0.75rem;
        border: 1px solid #e2e8f0;
        vertical-align: top;
        min-width: 150px;
    }

    .day-header {
        background: #f8fafc;
        font-weight: 600;
        color: #1e293b;
        position: sticky;
        top: 55px;
        left: 0;
        z-index: 2;
        text-align: center;
    }

    .coach-name {
        font-weight: 500;
        color: #1e293b;
        white-space: nowrap;
        position: sticky;
        left: 0;
        background: white;
        z-index: 1;
    }

    .appointment-card {
        padding: 0.2rem;
        border-radius: 6px;
        margin: 2px 0;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
    }

    .appointment-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
    }

    .appointment-card::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
    }

    .priority1 {
        background: #fee2e2;
        border-left: 4px solid #ef4444;
    }

    .priority1 .appointment-time {
        color: #dc2626;
    }

    .priority2 {
        background: #fef3c7;
        border-left: 4px solid #f59e0b;
    }

    .priority2 .appointment-time {
        color: #d97706;
    }

    .priority3 {
        background: #dcfce7;
        border-left: 4px solid #22c55e;
    }

    .priority3 .appointment-time {
        color: #16a34a;
    }

    .appointment-time {
        font-size: 0.875rem;
        color: #64748b;
        margin-top: 0.25rem;
    }

    @media (max-width: 768px) {
        .calendar-wrapper {
            margin-left: 0;
            padding: 1rem;
        }
        
        .calendar-header {
            flex-direction: column;
            gap: 1rem;
        }
    }
    
    .appointment-actions {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
    }
    .action-btn {
        background: none;
        border: none;
        color: inherit;
        opacity: 0.7;
        transition: all 0.2s ease;
        padding: 0.25rem;
    }

    .action-btn:hover {
        opacity: 1;
        transform: scale(1.1);
    }
    .booked{
        background-color: #16a34a;
        color: white;
    }
</style>

<div class="calendar-wrapper">
    <div class="calendar-container">

        @if ($messages !=[])
            <div class="alert alert-warning">
                <ul>
                    @foreach ($messages as $message)
                        <li>{{ $message }}</li>
                        <br>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="alert alert-success d-none" id="successMsg"></div>
        <div class="calendar-header">
            <div class="flex items-center gap-4">
                <h1 class="calendar-title">Rendez-vous Suggérés</h1>
            </div>
   
            
            <div class="week-navigation">

                <span class="current-week me-3">
                    {{ \Carbon\Carbon::parse($currentWeekStart)->format('d M, Y') }} - 
                    {{ \Carbon\Carbon::parse($currentWeekStart)->addDays(5)->format('d M, Y') }}
                </span>
                <form action="" method="get"  class="nav-button" >
                    <button type="submit" name="generate" >
                        <i class="fa-solid fa-calendar-plus"></i> Générer des Rendez-vous
                    </button>
                </form>
         
            </div>
        </div>

        <div class="table-container">
            <table class="calendar-table">
                <thead>
                    <tr>
                        <th class="day-header">Jour/Coach</th>
                        @foreach ($timeSlots as $slot)
                            <th>{{ $slot['start'] }} - {{ $slot['end'] }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($days as $day)
                        <tr>
                            <td colspan="{{ count($timeSlots) + 1 }}" class="day-header">
                                {{ \Carbon\Carbon::parse($day)->format('l, d M, Y') }}
                            </td>
                        </tr>
                        @foreach ($allCoaches as $coach)
                            @php
                                if (isset($_GET['generate'])) {
                                    $appointmentsForDay = $patientSchedules->where('coach_id', $coach->id)
                                        ->where('Date', $day);
                                } else {
                                    $appointmentsForDay = collect();
                                }
                             
                               
                            @endphp

                            <tr>
                                <td class="coach-name">{{ $coach->full_name }}</td>
                                @foreach ($timeSlots as $slot)
                                    @php
                                        $slotStart = strtotime($day . ' ' . $slot['start']);
                                        $slotEnd = strtotime($day . ' ' . $slot['end']);
                                        $cellAppointments = [];
                                        foreach ($appointmentsForDay as $appointment) {
                                            $apptStart = strtotime($day . ' ' . $appointment->startTime);
                                            if ($apptStart >= $slotStart && $apptStart < $slotEnd) {
                                                $cellAppointments[] = $appointment;
                                            }
                                        }
                                    @endphp
                                    <td>
                                        @foreach ($cellAppointments as $appointment)
                                            @php
                                               
                                                $classStatus = '';
                                                switch ($appointment->priority) {
                                                    case 'priority 1': $classStatus = 'priority1'; break;
                                                    case 'priority 2': $classStatus = 'priority2'; break;
                                                    case 'priority 3': $classStatus = 'priority3'; break;
                                                }
                                                $classbooked =$appointment->Status ==  'booked' ? 'booked':'';
                                                $display = $appointment->Status ==  'booked' ? 'd-none' : '';
                                                $timeColor =$appointment->Status ==  'booked'? 'style = color:white' : '';

                                                $patient = Patient::where('id',$appointment->patient_id)->first();
                                                $patientName = in_array($patient->patient_type, ['kid', 'young']) ? 
                                                $patient->first_name.' '.$patient->last_name :  $patient->parent_first_name.' '.$patient->parent_last_name;

                                            @endphp
                                            <div class="appointment-card {{ $classStatus }} {{ $classbooked }}" data-app-start="{{ $appointment->startTime }}"  data-app-end="{{ $appointment->endTime }}" data-app-date="{{ $appointment->Date }}" data-app-id="{{ $appointment->id}}">
                                                <div class="font-medium text-sm">{{ $patientName }}</div>
                                                <div class="appointment-time" {{ $timeColor }}>
                                                    {{ $appointment->startTime }} - {{ $appointment->endTime }}
                                                </div>
                                                <div class="appointment-actions  {{ $display }}">
                                                    <button type="button" class="action-btn" title="Modifier le Rendez-vous" onclick="openEditModal(this)">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                    </button>
                                                    <button type="button" class="action-btn" title="Changer de Patient" 
                                                        onclick="openChangeModal(
                                                            <?php echo $coach->speciality_id?>,
                                                            '<?php echo $appointment->Date?>',
                                                            '<?php echo $appointment->startTime?>',
                                                            '<?php echo $appointment->endTime ?>',
                                                            '<?php echo $appointment->patient_id ?>',
                                                            
                                                             this)"
                                                    >
                                                        <i class="fa-solid fa-arrows-rotate"></i>
                                                    </button>
                                                    <button type="button" class="action-btn" title="Changer de Coach" 
                                                        onclick="openCoachChangeModal(
                                                            <?php echo $coach->speciality_id?>,
                                                            '<?php echo $appointment->Date?>',
                                                            '<?php echo $appointment->startTime?>',
                                                            '<?php echo $appointment->endTime ?>',
                                                             this)"
                                                    >
                                                        <i class="fa-solid fa-user"></i>
                                                    </button>
                                                    
                                                    <button type="button" class="action-btn"  title="Bloquer le Créneau" onclick="block('<?php echo  $appointment->id ?>')">
                                                        <i class="fa-solid fa-eraser"></i>
                                                    </button>
                                                    <button type="button" class="action-btn"  title="Réserver le Rendez-vous"
                                                        onclick="bookAppointment(
                                                            <?php echo $coach->id?>,
                                                            '<?php echo $appointment->Date?>',
                                                            '<?php echo $appointment->startTime?>',
                                                            '<?php echo $appointment->endTime ?>',
                                                            <?php echo $appointment->patient_id?>, 
                                                            <?php echo $appointment->speciality_id?>,
                                                            <?php echo $appointment->id?>
                                                        )">
                                                        <i class="fa-solid fa-check"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
            <!--  Change Patient Modal -->
            <div id="changePatientModal" class="modal" tabindex="-1" role="dialog" style="display:none; position: fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5);">
                <div class="modal-dialog" role="document" style="margin: 10% auto; max-width: 500px;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Changer de Patient</h5>
                            <button type="button" class="close" onclick="closeChangeModal()">×</button>
                        </div>
                        <div id="errorMsg"class="alert alert-warning d-none"></div>
                        <div class="modal-body">
                            <div class="buttons">
                                <button class="btn btn-primary" 
                                    onclick="selectRandomAutoPatient()">
                                    Automatique
                                </button>
                                <button class="btn btn-primary" 
                                    onclick="manualPatient()">
                                    Manuel
                                </button>
                            </div>
                            <div class="mt-4 d-none" id="autoGeneratedPatientContainer">
                                <h6>Patients Disponibles</h6>
                                <small class="text-muted">Patient généré automatiquement en fonction de la date et du créneau</small>
                                <label class="autoGeneratedPatientLabel form-control text-black"></label>
                                <input type="hidden" id="autoGeneratedPatient" name="autoGeneratedPatient" readonly>
                            </div>

                            <div class="mt-4 d-none" id="manualPatient">
                                <h6 >Nom De Patient</h6>
                    
                                <input type="text" name="manualPatientName" id="manualPatientName">
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" onclick="updateSuggestedAppointment()">Mettre à Jour</button>
                            <button type="button" class="btn btn-secondary" onclick="closeChangeModal()">Fermer</button>
                        </div>
                    </div>
                </div>
            </div>
               <!--  Change Coach Modal -->
            <div id="changeCoachModal" class="modal" tabindex="-1" role="dialog" style="display:none; position: fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5);">
                <div class="modal-dialog" role="document" style="margin: 10% auto; max-width: 500px;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Changer de Coach</h5>
                            <button type="button" class="close" onclick="closeCoachChangeModal()">×</button>
                        </div>
                        <div id="errorMsg"class="alert alert-warning d-none"></div>
                        <div class="modal-body">

                            <div class="mt-4 " id="coachSelectionContainer">
                                <h6 >Tous les entraîneurs</h6>
                                <small class="text-muted">tous les entraîneurs disponibles en fonction de la spécialité et du créneau horaire</small>
                                <select name="" id="allCoachSelect" class="form-control text-black">
                                    <option value="" selected disabled>Sélectionnez un Entraineur</option>
                                </select>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" onclick="updateAppointmentCoach()">Mettre à Jour</button>
                            <button type="button" class="btn btn-secondary" onclick="closeCoachChangeModal()">Fermer</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- edit appointment Modal-->
        
            <div class="modal" id="editAppointmentModal" tabindex="-1" role="dialog" style="display:none; position: fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5);">
            <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editAppointmentModalLabel">Modifier le Rendez-vous</h5>
                            <button type="button" class="btn-close" onclick="closeEditModal()"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Add your edit form here -->
                            <form action="{{ route("update_sugg_app_planning") }}" method="post" id="editAppointmentForm">
                                @csrf
                                @method("Patch")

                                <div class="mb-3">
                                    <label for="appointmentDate" class="form-label">Date</label>
                                    <input type="date" class="form-control" id="appointmentDate" name="date" required>
                                </div>
                                <div class="mb-3">
                                    <label for="startTime" class="form-label">Heure de Début</label>
                                    <input type="time" class="form-control" id="startTime" name="start_time" required>
                                </div>
                                <div class="mb-3">
                                    <label for="endTime" class="form-label">Heure de Fin</label>
                                    <input type="time" class="form-control" id="endTime" name="end_time" required>
                                </div>
                                <input type="hidden" id="app_id" name="app_id">
                                <button type="submit" class="btn btn-primary">Enregistrer les Modifications</button>
                            </form>
                        </div>
                    </div>
            </div>
            </div>
    </div>
</div>


<script>
    let currentAppointmentElement = null; 
    let autoGeneratedPatients = [];
    // let all_patients = [];
    let warningMsg = '';    

    function bookAppointment(coachId, date, startTime, endTime, originalPatientId, specialityId, suggestedAppId) {
        if (!confirm('Êtes-vous sûr de vouloir réserver ce rendez-vous ?')) {
            return;
        }

        const appointmentCard = event.target.closest('.appointment-card');
        const currentPatientId = appointmentCard.dataset.patientId || originalPatientId;
            
        const planning = { [date]: { startTime, endTime } };
        const data = {
            coach_id: coachId,
            planning,
            patient_id: currentPatientId, 
            specialityId: specialityId,
            suggestedAppId: suggestedAppId
        };

        fetch("{{ route('appointment.store') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            if (!response.ok) throw new Error('La réservation a échoué');
            return response.json();
        })
        .then(data => {
            console.log(data.appointment);
            alert('Rendez-vous réservé avec succès !');
            window.location.reload();
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de la réservation : ' + error.message);
        });
    }

    function block(id) {
        if (!confirm('Êtes-vous sûr de vouloir bloquer ce créneau ?')) {
            return; 
        }
        const data = {
            id:id   
        }
        fetch("{{ route('block_Slot') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json', 
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            if (!response.ok) throw new Error('Le blocage a échoué');
            return response.json();
        })
        .then(data => {
            alert('Créneau bloqué avec succès !');
            window.location.reload();
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors du blocage du créneau : ' + error.message);
        });
    }
    // chacnge patient modal
    function closeChangeModal() {
        document.getElementById('autoGeneratedPatientContainer').classList.add('d-none') ;
        document.getElementById('changePatientModal').style.display = 'none';
        document.getElementById('manualPatient').classList.add('d-none') ;
        document.querySelector('#errorMsg').classList.add('d-none')
        document.getElementById('autoGeneratedPatient').value = '';	
        document.querySelector('.autoGeneratedPatientLabel').innerHTML = '';     
 
        document.querySelector('#errorMsg').innerHTML = '';
    }    
    function openChangeModal(specialityId, date, startTime, endTime,pateint_id, event) {
        console.log('Speciality ID:', specialityId);
        console.log('Date:', date);
        console.log('Start Time:', startTime);
        console.log('End Time:', endTime);
        currentAppointmentId = event.closest('.appointment-card');
        document.getElementById('changePatientModal').style.display = 'block';

        const params = new URLSearchParams({
            speciality_id: specialityId,
            date: date,
            startTime: startTime,
            endTime: endTime,
            patient_id: pateint_id
        });

        fetch("{{ route('available_Patients') }}?" + params.toString(), {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error("Échec du chargement des patients disponibles");
            return response.json();
        })
        .then(data => {

            if (data.success) {
                autoGeneratedPatients = data.available_patients;
                // all_patients = data.all_patients;
                // console.log('all patinet',all_patients);
            }
            else{    
                // all_patients = data.all_patients;
                warningMsg = data.msg;         
                console.log(data.msg);
            }
        
        })
        .catch(error => {
            console.error("Erreur lors du chargement des patients:", error);
            alert("Erreur lors du chargement des patients : " + error.message);
        });

    }
    function updateSuggestedAppointment() {
        const autoGeneratedPatient = document.getElementById('autoGeneratedPatient');
        const manualPatientName = document.getElementById('manualPatientName');

        if (!autoGeneratedPatient.value && !manualPatientName.value) {
            alert('Veuillez sélectionner un patient');
            return;
        }
        let appId = currentAppointmentId.dataset.appId;
        const uri ="{{ route('update_sugg_Patient') }}";

        fetch(uri, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',

        },
            body: JSON.stringify({
                suggestedAppId: appId,
                autoPatientId: autoGeneratedPatient.value ,
                manualPatientName: manualPatientName.value  
               
            })
        })
        .then(response => {
            if (!response.ok) throw new Error("Échec de la mise à jour du patient");
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert('Patient mis à jour avec succès !');
                window.location.reload();
            } else {
                if (!data.exist) {
                    alert('Le patient ne\'existe pas  !');
                } 
            }
        })
        .catch(error => {
            console.error("Erreur lors de la mise à jour du patient:", error);
            alert("Erreur lors de la mise à jour du patient : " + error.message);
        });
        closeChangeModal();
    }
    function selectRandomAutoPatient() {
        document.querySelector('#manualPatient').classList.add('d-none');
        document.querySelector('#errorMsg').classList.add('d-none');
        document.querySelector('#errorMsg').innerHTML = '';


        if (!autoGeneratedPatients || autoGeneratedPatients.length === 0) {
            document.querySelector('#errorMsg').innerHTML = warningMsg;
            document.querySelector('#errorMsg').classList.remove('d-none')
            return;
        }

        const randomIndex = Math.floor(Math.random() * autoGeneratedPatients.length);
        const randomPatient = autoGeneratedPatients[randomIndex];

        console.log('Random Patient:', randomPatient);
        
        const autoGeneratedPatientInput = document.getElementById('autoGeneratedPatient');
        const autoGeneratedPatientLabel = document.querySelector('.autoGeneratedPatientLabel');        
        document.querySelector('#autoGeneratedPatientContainer').classList.remove('d-none');
        autoGeneratedPatientInput.value = randomPatient.id;
        autoGeneratedPatientLabel.textContent = randomPatient.full_name;

    }
    function manualPatient() {
        document.querySelector('#manualPatient').classList.remove('d-none');
        document.querySelector('#autoGeneratedPatientContainer').classList.add('d-none');
        document.querySelector('#errorMsg').classList.add('d-none');
        document.querySelector('#errorMsg').innerHTML = '';
    }
//     edit modal
    function openEditModal(event) {
        currentAppointmentId = event.closest('.appointment-card');

        document.getElementById('editAppointmentModal').style.display = 'block';
        document.getElementById('app_id').value= currentAppointmentId.dataset.appId;
        console.log( document.getElementById('app_id').value,currentAppointmentId.dataset.appId);
    }
    function closeEditModal() {
        document.getElementById('editAppointmentModal').style.display = 'none';
        document.getElementById('appointmentDate').value = null;
        document.getElementById('startTime').value = null;
        document.getElementById('endTime').value = null;  
    }


    //    change coach modal
    function closeCoachChangeModal() {
        document.getElementById('changeCoachModal').style.display = 'none';  
        document.getElementById('allCoachSelect').innerHTML ='<option value="" selected disabled>Sélectionnez un Entraineur</option>';
        document.querySelector('#errorMsg').classList.add('d-none')
        document.querySelector('#errorMsg').innerHTML = '';
    }
    function openCoachChangeModal(specialityId, date, startTime, endTime, event) {
        console.log('Speciality ID:', specialityId);
        console.log('Date:', date);
        console.log('Start Time:', startTime);
        console.log('End Time:', endTime);
        currentAppointmentId = event.closest('.appointment-card');
        $coachSelection = document.querySelector('#allCoachSelect');
        document.getElementById('changeCoachModal').style.display = 'block';

        const params = new URLSearchParams({
            speciality_id: specialityId,
            date: date,
            startTime: startTime,
            endTime: endTime,
            // patient_id: pateint_id
        });

        fetch("{{ route('allCoaches') }}?" + params.toString(), {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error("Échec du chargement des Entraineur disponibles");
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Ensure availableCoaches is an array
                console.log(Array.isArray(data.availableCoaches) );
                console.log(typeof(data.availableCoaches));
                // const coaches = Array.isArray(data.availableCoaches) 
                //     ? data.availableCoaches 
                //     : (data.availableCoaches ? [data.availableCoaches] : []);
                let coaches = data.availableCoaches;
                if (!Array.isArray(coaches)) {
                    coaches = Object.entries(coaches).map(([id, full_name]) => ({ id, full_name }));
                }
                coaches.forEach(coach => {
                    console.log("Coach ID:", coach.id, "Coach Name:", coach.full_name);
                });

                
                    
                $coachSelection.innerHTML = '<option value="" selected disabled>Sélectionnez un Entraineur</option>';
                
                // console.log(coaches);
                coaches.forEach(coach => {
                console.log(coach);
                    
                    // Assuming each coach object has an "id" and "full_name" property.
                    const option = document.createElement('option');
                    option.value = coach.id; // Use coach.id directly
                    option.textContent = coach.full_name;
                    $coachSelection.appendChild(option);
                });
            } else {    
                // Optionally, handle the case when success is false.
                alert(data.message);

            }
        })

        .catch(error => {
            console.error("Erreur lors du chargement des entraineur:", error);
            alert("Erreur lors du chargement des entraineur : " + error.message);
        });

    }
    function updateAppointmentCoach() {

        const selectedCoach = document.getElementById('allCoachSelect').value;

        if (!selectedCoach) {
            alert('Veuillez sélectionner un entraîneur');
            return;
        }
        const uri = "{{ route('update_sugg_Coach')  }}";
        fetch(uri, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                coach_id: selectedCoach,
                suggestedAppId: currentAppointmentId.dataset.appId 
            })
        })
        .then(response => {
            if (!response.ok) throw new Error('Failed to update coach');
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert('Coach updated successfully!');
                window.location.reload();
            } else {
                alert('Failed to update coach: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred: ' + error.message);
        });
       
      
    }
    
</script>

@endsection
