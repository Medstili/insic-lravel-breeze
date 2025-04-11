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
                <div class="header-left">
                    <i class="fas fa-user-injured"></i>
                    <h2>Détails du Patient</h2>
                </div>
                <a href="{{ route('patient.show',$appointment->patient->id) }}" class="view-profile">
                    <span class="profile-text">Voir le Profil</span> <i class="fas fa-external-link-alt"></i>
                </a>
            </div>
            <div class="card-body">
                <div class="info-grid">
                    <div class="info-column">
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

                    <div class="info-column">
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
                        <div class="info-column">
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
                <div class="header-left">
                    <i class="fas fa-user-md"></i>
                    <h2>Détails du Coach</h2>
                </div>
                <a href="{{ route('user.show',$appointment->coach->id) }}" class="view-profile">
                    <span class="profile-text">Voir le Profil</span> <i class="fas fa-external-link-alt"></i>
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
                <div class="header-left">
                    <i class="fas fa-calendar-alt"></i>
                    <h2>Planning</h2>
                </div>
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
                    <div class="header-left">
                        <i class="fas fa-question"></i>
                        <h2>Raison</h2>
                    </div>
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
                <div class="header-left">
                    <i class="fas fa-file-medical"></i>
                    <h2>Session Report</h2>
                </div>
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
                            <a href="{{ route('appointments.downloadReport', $appointment->id) }}" class="btn-download" title="Download">
                                <i class="fas fa-download"></i>
                            </a>
                            <a href="{{ route('appointments.viewReport', $appointment->id) }}" target="_blank" class="btn-view" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <form action="{{ route('appointments.deleteReport', $appointment->id) }}" method="POST" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn-delete" onclick="confirmDelete(this)" title="Delete">
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
    :root {
        --primary-color: #6366f1;
        --success-color: #16a34a;
        --warning-color: #f59e0b;
        --danger-color: #dc2626;
        --light-bg: #f8f9fa;
        --border-color: #cbd5e1;
        --text-dark: #1e293b;
        --text-muted: #64748b;
        --shadow-sm: 0 2px 4px rgba(0,0,0,0.05);
        --shadow-md: 0 4px 6px rgba(0,0,0,0.1);
        --radius-sm: 6px;
        --radius-md: 8px;
        --radius-lg: 12px;
    }

    /* Base styles */
    .appointment-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    /* Header styles */
    .appointment-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2rem;
        padding: 1rem;
        background: var(--light-bg);
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-sm);
    }

    .back-button {
        padding: 0.5rem;
        color: var(--text-muted);
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .back-button:hover {
        color: var(--primary-color);
        transform: translateX(-3px);
    }

    .appointment-header h1 {
        font-size: 1.5rem;
        color: var(--text-dark);
        margin: 0;
        font-weight: 600;
    }

    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.9rem;
        margin-left: auto;
        font-weight: 500;
    }

    /* Card styles */
    .detail-card {
        background: white;
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-sm);
        margin-bottom: 1.5rem;
        overflow: hidden;
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background: var(--light-bg);
        border-bottom: 1px solid var(--border-color);
    }

    .header-left {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .card-header i {
        color: var(--primary-color);
        font-size: 1.25rem;
    }

    .card-header h2 {
        font-size: 1.25rem;
        color: var(--text-dark);
        margin: 0;
        font-weight: 600;
    }

    .view-profile {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--primary-color);
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .view-profile:hover {
        text-decoration: underline;
    }

    .card-body {
        padding: 1.5rem;
    }

    /* Info grid styles */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .info-column {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .info-row {
        display: flex;
        flex-wrap: wrap;
        margin-bottom: 0.75rem;
    }

    .info-row:last-child {
        margin-bottom: 0;
    }

    .info-row label {
        font-weight: 600;
        color: var(--text-dark);
        width: 150px;
        flex-shrink: 0;
    }

    .info-row span {
        color: var(--text-dark);
        flex: 1;
    }

    /* Schedule styles */
    .schedule-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 0.75rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid var(--border-color);
    }

    .schedule-item:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .day {
        font-weight: 600;
        color: var(--text-dark);
        min-width: 120px;
    }

    .time {
        color: var(--text-dark);
    }

    /* Report section styles */
    .report-section {
        margin-top: 2rem;
    }

    .existing-report {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background: var(--light-bg);
        border-radius: var(--radius-md);
        flex-wrap: wrap;
        gap: 1rem;
    }

    .file-info {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex: 1;
        min-width: 200px;
    }

    .file-info i {
        font-size: 1.5rem;
        color: #ef4444;
    }

    .filename {
        font-weight: 500;
        color: var(--text-dark);
        margin: 0;
        word-break: break-all;
    }

    .text-muted {
        color: var(--text-muted);
        font-size: 0.875rem;
    }

    .file-actions {
        display: flex;
        gap: 0.75rem;
    }

    .btn-download, .btn-view, .btn-delete {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: var(--radius-md);
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-download {
        color: var(--primary-color);
        background: #dbeafe;
    }

    .btn-download:hover {
        background: var(--primary-color);
        color: white;
    }

    .btn-view {
        color: var(--success-color);
        background: #dcfce7;
    }

    .btn-view:hover {
        background: var(--success-color);
        color: white;
    }

    .btn-delete {
        color: var(--danger-color);
        background: #fee2e2;
    }

    .btn-delete:hover {
        background: var(--danger-color);
        color: white;
    }

    /* Upload form styles */
    .upload-container {
        position: relative;
        border: 2px dashed var(--border-color);
        border-radius: var(--radius-md);
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
        border-color: var(--primary-color);
    }

    #file-preview {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
        padding: 1rem;
        background: var(--light-bg);
        border-radius: var(--radius-md);
        flex-wrap: wrap;
    }

    #file-preview.hidden {
        display: none;
    }

    .upload-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
    }

    .upload-content i {
        font-size: 2rem;
        color: var(--text-muted);
        margin-bottom: 1rem;
    }

    .upload-content p {
        margin-bottom: 0.5rem;
        color: var(--text-dark);
    }

    .browse-link {
        color: var(--primary-color);
        font-weight: 500;
        cursor: pointer;
    }

    .upload-content small {
        color: var(--text-muted);
    }

    #submit-btn {
        margin-top: 1rem;
        background: var(--primary-color);
        color: white;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: var(--radius-md);
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    #submit-btn:hover {
        background: #1d4ed8;
        transform: translateY(-1px);
    }

    #submit-btn.hidden {
        display: none;
    }

    .btn-remove {
        background: #fee2e2;
        color: var(--danger-color);
        border: none;
        border-radius: var(--radius-sm);
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-remove:hover {
        background: var(--danger-color);
        color: white;
    }

    .error-msg {
        color: var(--danger-color);
        margin-top: 0.5rem;
        font-size: 0.875rem;
    }

    /* Action buttons styles */
    .action-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        flex-wrap: wrap;
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: var(--radius-md);
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 150px;
    }

    .cancel-btn {
        background: #fee2e2;
        color: var(--danger-color);
    }

    .cancel-btn:hover {
        background: var(--danger-color);
        color: white;
    }

    .complete-btn {
        background: #dcfce7;
        color: var(--success-color);
    }

    .complete-btn:hover {
        background: var(--success-color);
        color: white;
    }

    /* Responsive styles */
    @media (max-width: 1200px) {
        .appointment-container {
            max-width: 100%;
            margin: 1.5rem auto;
        }
    }

    @media (max-width: 991px) {
        .info-grid {
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        }
        
        .card-body {
            padding: 1.25rem;
        }
    }

    @media (max-width: 768px) {
        .appointment-container {
            margin: 1rem auto;
            padding: 0 0.75rem;
        }
        
        .appointment-header {
            padding: 0.75rem;
            margin-bottom: 1.5rem;
        }
        
        .appointment-header h1 {
            font-size: 1.25rem;
        }
        
        .card-header h2 {
            font-size: 1.1rem;
        }
        
        .profile-text {
            display: none;
        }
        
        .info-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .info-row label {
            width: 120px;
        }
        
        .schedule-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
        
        .day {
            min-width: auto;
        }
        
        .existing-report {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .file-actions {
            width: 100%;
            justify-content: flex-end;
        }
        
        .action-buttons {
            flex-direction: column;
            width: 100%;
        }
        
        .btn {
            width: 100%;
        }
    }

    @media (max-width: 480px) {
        .appointment-header {
            flex-wrap: wrap;
        }
        
        .status-badge {
            margin-left: 0;
            margin-top: 0.5rem;
            width: 100%;
            text-align: center;
        }
        
        .card-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
        }
        
        .view-profile {
            align-self: flex-end;
        }
        
        .info-row {
            flex-direction: column;
        }
        
        .info-row label {
            width: 100%;
            margin-bottom: 0.25rem;
        }
        
        .upload-container {
            padding: 1rem;
        }
        
        .upload-box {
            height: 150px;
        }
    }
</style>

<script>
    // File upload preview
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('report-input');
        const filePreview = document.getElementById('file-preview');
        const fileName = document.getElementById('file-name');
        const submitBtn = document.getElementById('submit-btn');
        const uploadLabel = document.getElementById('upload-label');
        
        if (fileInput) {
            fileInput.addEventListener('change', function() {
                if (this.files.length > 0) {
                    const file = this.files[0];
                    fileName.textContent = file.name;
                    filePreview.classList.remove('hidden');
                    submitBtn.classList.remove('hidden');
                    uploadLabel.style.display = 'none';
                }
            });
            
            // Drag and drop functionality
            const uploadBox = document.querySelector('.upload-box');
            
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                uploadBox.addEventListener(eventName, preventDefaults, false);
            });
            
            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }
            
            ['dragenter', 'dragover'].forEach(eventName => {
                uploadBox.addEventListener(eventName, highlight, false);
            });
            
            ['dragleave', 'drop'].forEach(eventName => {
                uploadBox.addEventListener(eventName, unhighlight, false);
            });
            
            function highlight() {
                uploadBox.classList.add('dragover');
            }
            
            function unhighlight() {
                uploadBox.classList.remove('dragover');
            }
            
            uploadBox.addEventListener('drop', handleDrop, false);
            
            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                fileInput.files = files;
                
                if (files.length > 0) {
                    const file = files[0];
                    fileName.textContent = file.name;
                    filePreview.classList.remove('hidden');
                    submitBtn.classList.remove('hidden');
                    uploadLabel.style.display = 'none';
                }
            }
        }
    });
    
    // Clear file selection
    function clearFile() {
        const fileInput = document.getElementById('report-input');
        const filePreview = document.getElementById('file-preview');
        const submitBtn = document.getElementById('submit-btn');
        const uploadLabel = document.getElementById('upload-label');
        
        fileInput.value = '';
        filePreview.classList.add('hidden');
        submitBtn.classList.add('hidden');
        uploadLabel.style.display = 'block';
    }
    
    // Confirm delete
    function confirmDelete(button) {
        if (confirm('Êtes-vous sûr de vouloir supprimer ce rapport ?')) {
            button.closest('form').submit();
        }
    }
</script>
@endsection
