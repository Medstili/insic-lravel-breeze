@extends('layouts.app')

@section('content')
@php
    $color = '';
    if($appointment->status == 'pending'){
        $color = 'bg-warning text-white';
    } elseif($appointment->status == 'passed'){
        $color = 'bg-success text-white';
    } else{
        $color = 'bg-danger text-white';
    }

    $patient_first_name = ($appointment->patient->patient_type=='kid'|| $appointment->patient->patient_type=='young') ? $appointment->patient->first_name : $appointment->patient->parent_first_name;
    $patient_last_name = ($appointment->patient->patient_type=='kid'|| $appointment->patient->patient_type=='young') ? $appointment->patient->last_name : $appointment->patient->parent_last_name;
    $patient_full_name = $patient_first_name . ' ' . $patient_last_name;
@endphp
<div class="appointment-container">
    <!-- Header -->
    <div class="appointment-header">
        <a href="{{ route('appointment.index') }}" class="back-button">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1>Détails du Rendez-vous</h1>
        <span class="status-badge {{ $color }}">{{ ucfirst($appointment->status) }}</span>
    </div>

    <!-- Main Content -->
    <div class="appointment-content">
        <!-- Patient Section -->
        <div class="detail-card patient-info">
            <div class="card-header">
                <i class="fas fa-user-injured"></i>
                <h2>Détails du Patient</h2>
                <a href="{{ route('patient.show',$appointment->patient->id) }}" class="view-profile">
                    Voir le Profil <i class="fas fa-external-link-alt"></i>
                </a>
            </div>
            <div class="card-body container p-4">
                <div class="row">
                    <div class="col">
                        <div class="info-row">
                            <label>ID :</label>
                            <span>{{ $appointment->patient->id }}</span>
                        </div>
                        <div class="info-row">
                            <label>Nom :</label>
                            <span>{{ $patient_full_name }}</span>
                        </div>
                        <div class="info-row">
                            <label>Téléphone :</label>
                            <span>{{ $appointment->patient->phone }}</span>
                        </div>
                        <div class="info-row">
                            <label>Email :</label>
                            <span>{{ $appointment->patient->email }}</span>
                        </div>
                        <div class="info-row">
                            <label>Adresse :</label>
                            <span>{{ $appointment->patient->address }}</span>
                        </div>
                    </div>

                    <div class="col">
                        <div class="info-row">
                            <label>Sexe :</label>
                            <span>{{ $appointment->patient->patient_type }}</span>
                        </div>
                        <div class="info-row">
                            <label>Genre :</label>
                            @if ($appointment->patient->gender == "M")
                               <span>Homme <i class="bi bi-gender-male"></i></span>
                            @elseif ($appointment->patient->gender == "F")
                               <span>Femme <i class="bi bi-gender-female"></i></span> 
                            @endif
                        </div>
                        <div class="info-row">
                            <label>Âge :</label>
                            <span>{{ $appointment->patient->age }}</span>
                        </div>
                        <div class="info-row">
                            <label>Mode :</label>
                            <span>{{ $appointment->patient->mode }}</span>
                        </div>
                        <div class="info-row">
                            <label>Abonnement :</label>
                            <span>{{ $appointment->patient->subscription }}</span>
                        </div>
                    </div>

                    @if ($appointment->patient->patient_type == 'kid' || $appointment->patient->patient_type == 'young')
                        <div class="col">
                            <div class="info-row">
                                <label>École :</label>
                                <span>{{ $appointment->patient->ecole }}</span>
                            </div>
                            <div class="info-row">
                                <label>Système :</label>
                                <span>{{ $appointment->patient->system }}</span>
                            </div>
                            <div class="info-row">
                                <label>Nom complet du parent :</label>
                                <span>{{ $appointment->patient->parent_first_name }} {{ $appointment->patient->parent_last_name }}</span>
                            </div>
                            <div class="info-row">
                                <label>Profession :</label>
                                <span>{{ $appointment->patient->profession }}</span>
                            </div>
                            <div class="info-row">
                                <label>Établissement :</label>
                                <span>{{ $appointment->patient->etablissment }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Coach Section -->
        <div class="detail-card coach-info">
            <div class="card-header">
                <i class="fas fa-user-md"></i>
                <h2>Détails du Coach</h2>
                <a href="{{ route('user.show',$appointment->coach->id) }}" class="view-profile">
                    Voir le Profil <i class="fas fa-external-link-alt"></i>
                </a>
            </div>
            <div class="card-body">
                <div class="info-row">
                    <label>Nom :</label>
                    <span>{{ $appointment->coach->full_name }}</span>
                </div>
                <div class="info-row">
                    <label>Spécialité :</label>
                    <span>{{ $appointment->speciality->name }}</span>
                </div>
            </div>
        </div>

        <!-- Schedule Section -->
        <div class="detail-card schedule-info">
            <div class="card-header">
                <i class="fas fa-calendar-alt"></i>
                <h2>Planning</h2>
            </div>
            <div class="card-body">
                @php
                    $schedule = json_decode($appointment->appointment_planning, true);
                @endphp
                @foreach ($schedule as $day => $time)
                    <div class="schedule-item">
                        <span class="day">{{ $day }}</span>
                        <span class="time">
                            @foreach ($time as $slot)
                                {{ $slot }}
                            @endforeach
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Reason Section -->
        @if ($appointment->status=='cancel')
            <div class="detail-card schedule-info reason-info">
                <div class="card-header">
                    <i class="fas fa-question"></i>
                    <h2>Raison</h2>
                </div>
                <div class="card-body">
                    <div class="cancel-info">
                        <div class="info-row">
                            <label>Annulé par :</label>
                            <span>{{ $appointment->cancelledBy }}</span>
                        </div>
                        <div class="cancel-description">
                            <div class="info-row">
                                <label>Description :</label>
                                <span>{{ $appointment->reason }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Report Section -->
        <div class="detail-card report-section">
            <div class="card-header">
                <i class="fas fa-file-medical"></i>
                <h2>Session Report</h2>
                <!-- Error Messages -->
                @if($errors->has('report'))
                    <div class="error-msg">{{ $errors->first('report') }}</div>
                @endif
            </div>
            <div class="card-body">
                @if($appointment->report_path)
                    <!-- Existing Report -->
                    <div class="existing-report">
                        <div class="file-info">
                            <i class="fas fa-file-pdf text-red-500"></i>
                            <div>
                                <p class="filename">{{ basename($appointment->report_path) }}</p>
                                <small class="text-muted">Uploaded {{ $appointment->updated_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        <div class="file-actions">
                            <a href="{{ route('appointments.downloadReport', $appointment->id) }}" class="btn-download">
                                <i class="fas fa-download"></i>
                            </a>
                            <a href="{{ route('appointments.viewReport', $appointment->id) }}" target="_blank" class="btn-view">
                                <i class="fas fa-eye"></i>
                            </a>
                            <form action="{{ route('appointments.deleteReport', $appointment->id) }}" method="POST" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn-delete" onclick="confirmDelete(this)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Upload Form -->
                    <form action="{{ route('appointments.uploadReport', $appointment->id) }}" method="POST" enctype="multipart/form-data" id="report-form" class="upload-form">
                        @csrf
                        <div class="upload-container">
                            <!-- File Preview -->
                            <div id="file-preview" class="hidden">
                                <i class="fas fa-file-upload"></i>
                                <span id="file-name"></span>
                                <button type="button" class="btn-remove" onclick="clearFile()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>

                            <!-- Upload Box -->
                            <label class="upload-box" id="upload-label">
                                <input type="file" name="report" id="report-input" accept=".pdf,.doc,.docx" hidden>
                                <div class="upload-content">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <p>Drag & drop files or <span class="browse-link">Browse</span></p>
                                    <small>Supported formats: PDF, DOC, DOCX (Max 2MB)</small>
                                </div>
                            </label>

                            <!-- Upload Button -->
                            <button type="submit" id="submit-btn" class="hidden">
                                <i class="fas fa-upload"></i> Upload Report
                            </button>
                        </div>

          
                    </form>
                @endif
            </div>
        </div>

        <!-- Action Buttons -->
        @if ($appointment->status == 'pending')
            <div class="action-buttons">
                <a href="{{ route('appointment.edit',$appointment->id) }}" class="btn cancel-btn">
                    Annuler le Rendez-vous
                </a>
                <form method="POST" action="{{ route('appointments.update-appointment-status' ,[$appointment->id,'passed']) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn complete-btn">
                        Marquer comme Terminé
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>

<style>
    /* Report Section Styling */
    .report-section {
        margin-top: 2rem;
    }

    .existing-report {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .file-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .file-actions {
        display: flex;
        gap: 1rem;
    }

    .btn-download, .btn-view, .btn-delete {
        padding: 0.5rem;
        border-radius: 6px;
        transition: all 0.3s ease;
    }

    .btn-download {
        color: #2563eb;
        background: #dbeafe;
    }

    .btn-view {
        color: #16a34a;
        background: #dcfce7;
    }

    .btn-delete {
        color: #dc2626;
        background: #fee2e2;
    }

    .upload-container {
        position: relative;
        border: 2px dashed #cbd5e1;
        border-radius: 8px;
        padding: 2rem;
        text-align: center;
        transition: border-color 0.3s ease;
    }

    .upload-box {
        display: block;
        width: 100%;
        height: 200px;
        cursor: pointer;
        position: relative;
    }
    .upload-box:hover .upload-container {
        border-color: #2563eb;
    }

    #file-preview {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
        padding: 1rem;
        background: #f8fafc;
        border-radius: 6px;
    }

    #file-preview.hidden {
        display: none;
    }

    .upload-content i {
        font-size: 2rem;
        color: #94a3b8;
        margin-bottom: 1rem;
    }

    .browse-link {
        color: #2563eb;
        font-weight: 500;
        cursor: pointer;
    }

    #submit-btn {
        margin-top: 1rem;
        background: #2563eb;
        color: white;
        padding: 0.75rem 1.5rem;
    }

    #submit-btn.hidden {
        display: none;
    }

    .error-msg {
        color: #dc2626;
        margin-top: 0.5rem;
        font-size: 0.875rem;
    }

    /* Drag & Drop Hover State */
    .upload-box.dragover {
        border-color: #2563eb;
        background: #f8fafc;
    }

    /* Modern Clean CSS */
    .appointment-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .appointment-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2rem;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .back-button {
        padding: 0.5rem;
        color: #6c757d;
        transition: all 0.3s ease;
    }

    .back-button:hover {
        color: #0d6efd;
        transform: translateX(-3px);
    }

    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.9rem;
        margin-left: auto;
    }

    .detail-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        margin-bottom: 1.5rem;
    }

    .card-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        border-bottom: 1px solid #eee;
    }

    .card-header i {
        font-size: 1.25rem;
        color: #6c757d;
    }

    .view-profile {
        margin-left: auto;
        color: #0d6efd;
        text-decoration: none;
    }

    .card-body {
        padding: 1rem;
    }

    .info-row {
        display: grid;
        grid-template-columns: 120px 1fr;
        gap: 1rem;
        padding: 0.5rem 0;
    }

    .info-row label {
        color: rgb(197, 53, 241);
        font-weight: bold;
    }
    .info-row span {
        color: #6c757d;
        font-weight: 500;
        font-size: small;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }

    .btn {
        padding: 0.5rem 1.25rem;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .cancel-btn {
        background: #dc3545;
        color: white;
    }

    .complete-btn {
        background: #198754;
        color: white;
    }

    /* Status Colors */
    .status-pending { background: #ffc107; color: black; }
    .status-passed { background: #198754; color: white; }
    .status-cancel { background: #dc3545; color: white; }

    @media (max-width: 768px) {
        .info-row {
            grid-template-columns: 1fr;
        }
        
        .action-buttons {
            flex-direction: column;
        }
    }
</style>

<script>
    // File Upload Handling
    const input = document.getElementById('report-input');
    const preview = document.getElementById('file-preview');
    const fileName = document.getElementById('file-name');
    const submitBtn = document.getElementById('submit-btn');
    const uploadLabel = document.getElementById('upload-label');

    // File Input Change
    input.addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            updateFileDisplay(this.files[0]);
        }
    });

    // Prevent default drag behaviors for both the upload area and document body
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        uploadLabel.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    // Highlight drop area when item is dragged over it
    ['dragenter', 'dragover'].forEach(eventName => {
        uploadLabel.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        uploadLabel.addEventListener(eventName, unhighlight, false);
    });

    function highlight(e) {
        uploadLabel.classList.add('dragover');
    }

    function unhighlight(e) {
        uploadLabel.classList.remove('dragover');
    }

    // Handle dropped files
    uploadLabel.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            // Directly assign the dropped files to the file input
            input.files = files;
            updateFileDisplay(files[0]);
        }
    }

    function updateFileDisplay(file) {
        preview.classList.remove('hidden');
        fileName.textContent = file.name;
        submitBtn.classList.remove('hidden');
        uploadLabel.classList.add('hidden');
    }

    // Clear File
    function clearFile() {
        input.value = '';
        preview.classList.add('hidden');
        submitBtn.classList.add('hidden');
        uploadLabel.classList.remove('hidden');
    }

    // Delete Confirmation
    function confirmDelete(button) {
        if (confirm('Êtes-vous sûr de vouloir supprimer ce rapport ?')) {
            button.closest('form').submit();
        }
    }
</script>

@endsection
