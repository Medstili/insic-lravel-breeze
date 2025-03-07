@extends('layouts.app')

@section('content')
@php
    
   
    $color = '';
    if($appointment->status == 'pending'){
        $color = 'status-pending';
    }elseif($appointment->status == 'passed'){
        $color = 'status-passed';
    }else{
        $color = 'status-cancel';
    }

    $patient_first_name = ($appointment->patient->patient_type=='kid'|| $appointment->patient->patient_type=='young')? $appointment->patient->first_name : $appointment->patient->parent_first_name ;
    $patient_last_name = ($appointment->patient->patient_type=='kid'|| $appointment->patient_type=='young')? $appointment->patient->last_name : $appointment->patient->parent_last;
    $patient_full_name = $patient_first_name . ' ' . $patient_last_name;
@endphp
<div class="appointment-details">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="header-section">
            <h1 class="header-title">
                <i class="fas fa-calendar-star mr-4"></i>Appointment Details
            </h1>
            <form action="{{ Route('appointment.index')}}" method="get">
                <button class="back-btn"><i class="fas fa-arrow-left"></i></button>
            </form>
        </div>

        <!-- Main Card -->
        <div class="glass-card main-card">
            <!-- Info Grid -->
            <div class="info-grid">
                <!-- Client Card -->
                <div class="info-card client-card">
                   
                    <div class="d-flex justify-content-between">
                        <i class="fas fa-user-astronaut icon"></i>
                        <form action="{{ route('patient.show',$appointment->patient->id) }}">
                            <button class=" text-white bg-primary p-2 rounded">
                                detials
                            </button>
                        </form>
                    </div>
                    <h3>Client</h3>

                    <mark>
                    <span class="" style="margin-left: 5px;"><i class="fas fa-user ml-2"></i> : 
                        {{ $patient_full_name }}
                    </span>
                    <span class="" style="margin-left: 5px;"><i class="fas fa-phone ml-2"></i> : {{ $appointment->patient->phone }}</span>
                    <span style="margin-left: 5px;">
                    <!-- <span style="margin-left: 5px;"> -->
                    @if ($appointment->patient->gender == "M")
                        Male <i class="bi bi-gender-male"></i>
                    @elseif ($appointment->patient->gender == "F")
                        Female <i class="bi bi-gender-female"></i>
                    @endif
                    <!-- </span> -->
                    </span>
                    <span style="margin-left: 5px;"> <mark> <i class="fas fa-file-medical"></i> ID : {{ $appointment->patient->id }} <mark></span>
                    </mark>

                    <hr class="mb-2 mt-2">
                    @if ($appointment->patient->patient_type == 'kid'||$appointment->patient->patient_type == 'young')
                        <div class="">
                            <p><i class="fa-solid fa-school-flag"></i> ecole : {{ $appointment->patient->ecole }}</p>
                            <p><i class="fa-solid fa-sitemap"></i> System : {{ $appointment->patient->system }}</p>
                        </div>
                        <div class="">
                            <p><i class="fa-solid fa-hands-holding-child"></i> parent full name : {{ $appointment->patient->parent_first_name }} {{ $appointment->patient->parent_last_name }}</p>
                            <p><i class="fa-solid fa-briefcase"></i> Prefession : {{ $appointment->patient->profession }}</p>
                            <p><i class="fa-solid fa-building"></i> etablissment : {{ $appointment->patient->etablissment }}</p>
                        </div>
                    @endif

                    <div class="">
                        <p><i class="bi bi-envelope-at-fill"></i> email : {{ $appointment->patient->email }}</p>
                        <p><i class="bi bi-geo-alt-fill"></i> adress : {{ $appointment->patient->address }}</p>
                        <p><i class="fa-solid fa-people-arrows"></i> mode : {{ $appointment->patient->mode }}</p>
                        <p><i class="fas fa-file-medical"></i> subscription : {{ $appointment->patient->subscription }}</p>
                        <p><i class="fa-solid fa-hospital-user"></i> Speciality : {{ $appointment->patient->speciality->name }}</p>
                    </div>
                </div>

                <!-- Coach Card -->
                <div class="info-card coach-card">
                    <div class="d-flex justify-content-between">
                        <i class="fas fa-rocket icon"></i>
                        <form action="{{ route('user.show',$appointment->coach->id) }}">
                            <button class=" text-white bg-primary p-2 rounded">
                                detials
                            </button>
                        </form>
                    </div>
                    <h3>Coach</h3>
    
                    <p><i class="fas fa-user ml-2"></i> : {{$appointment->coach->full_name }}</p>
                    <span class="badge-glass">{{ $appointment->speciality->name }}</span>
                </div>

                <!-- Time Card -->
                <div class="info-card time-card">
                    <i class="fas fa-clock icon"></i>
                    <h3>Date & Time</h3>
                    @php
                        $schedule = json_decode($appointment->appointment_planning, true);
                    @endphp
                    @foreach ($schedule as $day => $time)
                        <div class="schedule-group">
                            <span class="schedule-day">{{ $day }} :</span>
                            <span class="schedule-time">
                                @foreach ($time as $slot)
                                    {{ $slot }}
                                @endforeach
                            </span>
                        </div>
                    @endforeach
                </div>

                <!-- Status Card -->
                <div class="info-card status-card">
                    <div class="status-header">
                        <i class="fas fa-hourglass-half icon"></i>
                        <h3>Status</h3>
                  
                        <p class="status-badge {{ $color }}">{{ ucfirst($appointment->status) }}</p>
                    </div>
                    @if ($appointment->status=='cancel')
                        <div class="cancel-info glass-card-inner">
                            <div class="cancel-header">
                                <h3>Cancelled By : <span>{{ $appointment->cancelledBy }}</span></h3>
                            </div>
                            <div class="cancel-description">
                                <h3>Description :</h3>
                                <p>{{ $appointment->reason }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Report Section -->
            @if ($appointment->status != 'cancel')
            <div class="report-section">
                <div class="report-header">
                    <i class="fas fa-file-waveform icon"></i>
                    <h3>Session Report</h3>
                </div>

                @if($appointment->report_path)
                    <div class="report-card glass-card-inner">
                        <div class="report-info">
                            <i class="fas fa-file-pdf"></i>
                            <span>{{ basename($appointment->report_path) }}</span>
                        </div>
                        <div class="report-actions">
                            <form action="{{ route('appointments.deleteReport', $appointment->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="action-btn delete-btn"><i class="fas fa-trash-alt"></i></button>
                            </form>
                            <form action="{{ route('appointments.downloadReport', $appointment->id) }}" method="GET">
                                @csrf
                                <button class="action-btn download-btn"><i class="fas fa-cloud-download-alt"></i></button>
                            </form>
                            <a href="{{ route('appointments.viewReport', $appointment->id) }}" target="_blank" class="action-btn view-btn">
                                <i class="fas fa-file-alt"></i>
                            </a>
                        </div>
                    </div>
                @else
                    <div class="report-upload glass-card-inner">
                        <form action="{{ route('appointments.uploadReport', $appointment->id) }}"
                              method="POST" 
                              enctype="multipart/form-data"
                              class="upload-form">
                            @csrf
                            <div class="alert alert-info" id="report-file-info">
                                <i class="fas fa-info-circle"></i>
                                <span>Only PDF, DOC, DOCX files are allowed</span>
                            </div>
                            <div class="" id="report-file" style="display: none;">
                                <span id="report-file-name"></span>
                            </div>

                            <label class="upload-label">
                                <div>
                                    <i class="fas fa-cloud-upload-alt icon"></i>
                                    <span>Upload Session Report</span>
                                </div>
                                <input type="file" 
                                       name="report" 
                                        id="report"
                                       hidden
                                       accept=".pdf,.doc,.docx">
                            </label>
                            <button type="submit" style="display: none;" class="upload-btn">
                                <i class="fas fa-upload"></i> Submit Report
                            </button>
                        </form>
                    </div>
                @endif
            </div>
            @endif

            <div class="action-buttons">
                @if ($appointment->status == 'pending')
                    <div class="btn-group">
                        <form action="{{ route('appointment.edit',$appointment->id) }}" method="get">
                            @csrf
                            <button type="submit" class="btn btn-secondary">Cancel Appointment</button>
                        </form>

                        <form action="{{ route('appointments.update-appointment-status' ,[$appointment->id,'passed']) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success">Completed Appointment</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Inline CSS -->
<style>
    /* Main container styling to account for the fixed sidebar */
    .appointment-details {
        background-color: #ecf0f1;
        min-height: calc(100vh - 60px);
        padding: 40px;
    }

    /* Header Section */
    .header-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }
    .header-title {
        font-size: 2.5rem;
        font-weight: bold;
        color: #2c3e50;
    }
    .back-btn {
        background: none;
        border: none;
        font-size: 1.5rem;
        color: #8e44ad;
        cursor: pointer;
    }
    .back-btn:hover {
        color: #732d91;
    }

    /* Glass Card styling */
    .glass-card {
        background: rgba(255, 255, 255, 0.85);
        border-radius: 12px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 2rem;
        backdrop-filter: blur(10px);
        margin-bottom: 2rem;
    }

    .glass-card-inner {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 8px;
        padding: 1rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    /* Info Grid: Two columns */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    .info-card {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 8px;
        padding: 1.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .info-card .icon {
        font-size: 2rem;
        margin-bottom: 1rem;
        color: #6c63ff;
    }
    .info-card h3 {
        font-size: 1.25rem;
        margin-bottom: 0.5rem;
        color: #2c3e50;
    }
    .info-card p {
        font-size: 1rem;
        color: #555;
        margin-bottom: 0.5rem;
    }

    /* Badge Glass */
    .badge-glass {
        display: inline-block;
        background: rgba(44, 62, 80, 0.8);
        color: #ecf0f1;
        padding: 0.3rem 0.6rem;
        border-radius: 4px;
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }

    /* Schedule Styling */
    .schedule-group {
        margin-bottom: 0.5rem;
    }
    .schedule-day {
        font-weight: bold;
        color: #8e44ad;
    }
    .schedule-time {
        margin-left: 0.5rem;
        color: #555;
    }

    /* Status Card */
    .status-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .status-header h3 {
        margin: 0;
        font-size: 1.25rem;
        color: #2c3e50;
    }
    .status-badge {
        padding: 0.3rem 0.6rem;
        border-radius: 4px;
        font-size: 0.875rem;
        color: #fff;
    }
    .status-pending {
        background-color: #f1c40f;
    }
    .status-passed {
        background-color: #2ecc71;
    }
    .status-cancel {
        background-color: #e74c3c;
    }

    /* Report Section */
    .report-section {
        margin-bottom: 2rem;
    }
    .report-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
        color: #2c3e50;
    }
    .report-card {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem;
    }
    .report-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #e74c3c;
        font-size: 1.1rem;
    }
    .report-actions {
        display: flex;
        gap: 0.5rem;
    }
    .action-btn {
        background: none;
        border: none;
        font-size: 1.2rem;
        cursor: pointer;
        color: #2c3e50;
    }
    .action-btn:hover {
        color: #8e44ad;
    }
    .report-upload {
        text-align: center;
    }
    .upload-form {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
    }
    .upload-label {
        cursor: pointer;
        color: #2c3e50;
        font-size: 1.2rem;
    }
    .upload-btn {
        background-color: #2ecc71;
        color: #fff;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        cursor: pointer;
    }
    .upload-btn:hover {
        background-color: #27ae60;
    }

    /* Action Buttons */
    .action-buttons {
        text-align: center;
        margin-top: 2rem;
    }
    .btn-group {
        display: flex;
        justify-content: center;
        gap: 1rem;
    }
    .btn {
        padding: 0.5rem 1rem;
        border-radius: 4px;
        font-size: 1rem;
        cursor: pointer;
        border: none;
    }
    .btn-secondary {
        background-color: #e74c3c;
        color: #fff;
    }
    .btn-secondary:hover {
        background-color: #c0392b;
    }
    .btn-success {
        background-color: #2ecc71;
        color: #fff;
    }
    .btn-success:hover {
        background-color: #27ae60;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .appointment-details {
            margin-left: 0;
            padding: 20px;
        }
    }
</style>
<script>
    // Listen for changes on the file input
  document.getElementById('report').addEventListener('change', function() {
    const fileInput = this;
    const reportFileDiv = document.getElementById('report-file');
    const reportFileNameSpan = document.getElementById('report-file-name');

    // Check if any file is selected
    if (fileInput.files && fileInput.files.length > 0) {
      // Get the name of the first file
      const fileName = fileInput.files[0].name;
      // Set the file name to the span
      reportFileNameSpan.textContent = fileName;
      // Display the container div
      reportFileDiv.style.display = 'block';
        // Hide the info alert
        document.getElementById('report-file-info').style.display = 'none';
        document.querySelector('.upload-btn').style.display = 'block';

    }
    
  });

</script>
@endsection
