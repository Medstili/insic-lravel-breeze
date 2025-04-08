@extends('layouts.app')

@section('content')
<style>
    .calendar-wrapper {
        /* margin-top: 80px; */
        padding: 2rem;
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
        font-size: 1.60rem;
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
        text-decoration: none;
    }

    .nav-button:hover {
        background: #2563eb;
        transform: translateY(-1px);
        color: white;
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
        background: linear-gradient(195deg, var(--primary-color), var(--secondary-color));
        color: white;
        padding: 1rem;
        font-weight: 500;
        position: sticky;
        top: 0;
        z-index: 3;
    }

    .calendar-table td {
        padding: 0.2rem;
        border: 1px solid #e2e8f0;
        vertical-align: top;
        min-width: 155px;
    }

    .day-header , .date-day-header{
        background: #f8fafc;
        font-weight: 600;
        color: #1e293b;
        position: sticky;
        top: 55px;
        left: 0;
    
    }
    .day-header{
        z-index:4 ;
    }
    .date-day-header{
      height: 50px;
      text-align: center;
      z-index: 2;
    }
    .coach-name {
        font-weight: 500;
        color: #1e293b;
        text-align: center;
        white-space: nowrap;
        position: sticky;
        left: 0;
        background: white;
        z-index: 1;
    }

    .appointment-card {
        padding: 0.2em;
        border-radius: 6px;
        margin: 1px 0;
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

    .pending {
        background: #fef3c7;
        border-left: 4px solid #f59e0b;
    }

    .passed {
        background: #dcfce7;
        border-left: 4px solid #22c55e;
    }

    .cancel {
        background: #fee2e2;
        border-left: 4px solid #ef4444;
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

    .modal-content {
        border-radius: 12px;
        border: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 1rem;
    }

    .modal-title {
        font-weight: 600;
        color: #1e293b;
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
</style>

<div class="calendar-wrapper">
    <div class="calendar-container">
        <div class="calendar-header">
            <h1 class="calendar-title">Calendrier des Rendez-vous des Coachs</h1>
            <div class="week-navigation">
                <a class="nav-button" href="{{ route('global_dashboard', ['week' => $prevWeekStart]) }}">
                    ← Précédent
                </a>
                <span class="current-week">
                    {{ \Carbon\Carbon::parse($currentWeekStart)->format('d M, Y') }} - 
                    {{ \Carbon\Carbon::parse($currentWeekStart)->addDays(5)->format('d M, Y') }}
                </span>
                <a class="nav-button" href="{{ route('global_dashboard', ['week' => $nextWeekStart]) }}">
                    Suivant →
                </a>
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
                            <td colspan="{{ count($timeSlots) + 1 }}" class="date-day-header">
                                {{ \Carbon\Carbon::parse($day)->format('l, d M, Y') }}
                            </td>
                        </tr>
                        
                        @foreach ($coaches as $coach)
                            <tr>
                                <td class="coach-name">{{ $coach->full_name }}</td>
                                @foreach ($timeSlots as $slot)
                                    @php
                                        $slotStart = strtotime($day . ' ' . $slot['start']);
                                        $slotEnd = strtotime($day . ' ' . $slot['end']);
                                        $cellAppointments = [];
                                        
                                        if(isset($organizedAppointments[$day][$coach->id])) {
                                       
                                            foreach ($organizedAppointments[$day][$coach->id] as $appointment) {
                                                $apptStart = strtotime($day . ' ' . $appointment['startTime']);
                                                if ($apptStart >= $slotStart && $apptStart < $slotEnd) {
                                                    $cellAppointments[] = $appointment;
                                                }
                                            }
                                        }
                                    @endphp
                                    
                                    <td>
                                        @foreach ($cellAppointments as $appointment)

                                    
                                            <div class="appointment-card {{ $appointment['status'] }}" data-app-id="{{ $appointment['id'] }}" >
                                                <div class="font-medium text-sm">{{ $appointment['patient'] }}</div>
                                                <div class="appointment-actions">

                                                <a href="{{ route('appointment.edit', $appointment['id']) }}" class="action-btn" title="Annuler le Rendez-vous">
                                                    <i class="fa-regular fa-rectangle-xmark"></i>
                                                </a>
                                                    <button type="button" onclick="openEditModal(this)"  class="action-btn">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                    </button>
                                                    <button type="button" class="action-btn" 
                                                  
                                                    onclick="openChangeModal(
                                                            <?php echo $appointment['speciality_id']?>,
                                                            '<?php echo $day ?>',
                                                            '<?php echo $appointment['startTime']?>',
                                                            '<?php echo $appointment['endTime'] ?>',
                                                            <?php echo $appointment['patient_id']; ?>,
                                                             this
                                                    )">
                                                        <i class="fa-solid fa-arrows-rotate"></i>
                                                    </button>
                                                    <button type="button" onclick="window.location.href='appointment/<?php echo $appointment['id'] ?>'" class="action-btn">
                                                        <i class="fa-solid fa-circle-info"></i>
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
    </div>
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

        <!-- Modal de modification du rendez-vous -->
        <div class="modal" id="editAppointmentModal" tabindex="-1" role="dialog" style="display:none; position: fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5);">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editAppointmentModalLabel">Modifier le Rendez-vous</h5>
                        <button type="button" class="btn-close" onclick="closeEditModal()"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('update_app_planning')}}" method="post" onsubmit="storeNewPlanning()" id="editAppointmentForm">
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
                            <input type="hidden" id="new_planning" name="new_planning" >
                            <input type="hidden" id="app_id" name="app_id">
                            <button type="submit" class="btn btn-primary">Enregistrer les Modifications</button>
                        </form>
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

   // chacnge patient modal
    function closeChangeModal() {
        document.getElementById('autoGeneratedPatientContainer').classList.add('d-none') ;
        document.getElementById('changePatientModal').style.display = 'none';
        document.getElementById('manualPatient').classList.add('d-none') ;
        document.querySelector('#errorMsg').classList.add('d-none')
        document.getElementById('autoGeneratedPatient').value = '';	
        document.querySelector('.autoGeneratedPatientLabel').innerHTML = '';     
        document.getElementById('allPatientsSelect').innerHTML = '<option value="">Sélectionnez un patient</option>';
        document.querySelector('#errorMsg').innerHTML = '';
        document.querySelector('#manualPatientName').innerHTML = '';

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

        console.log('Body passed:', {
            app_dd: appId,
            autoPatientId: autoGeneratedPatient.value,
            manualPatientName: manualPatientName.value
        });
        const uri ="{{ route('update.patient') }}";

        fetch(uri, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
            body: JSON.stringify({
                app_id: appId,
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
        document.querySelector('#autoGeneratedPatient').innerHTML='';
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
    function storeNewPlanning() {
        const date = document.getElementById('appointmentDate').value;
        const startTime = document.getElementById('startTime').value;
        const endTime = document.getElementById('endTime').value;

        if (!date || !startTime || !endTime) {
            alert('Please fill in all fields before saving.');
            return false;
        }

        const newPlanning = {
            [date]: {
                startTime: startTime,
                endTime: endTime
            }
        };

        document.getElementById('new_planning').value = JSON.stringify(newPlanning);
        closeEditModal();
        return true;
    }
</script>
@endsection