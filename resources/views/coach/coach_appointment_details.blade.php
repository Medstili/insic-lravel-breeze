@extends('layouts.coach_app')

@section('content')
@php
    $color = '';
    if($appointment->status == 'pending'){
        $color = 'bg-yellow-100 text-yellow-800';
    } elseif($appointment->status == 'passed'){
        $color = 'bg-green-100 text-green-700';
    } else{
        $color = 'bg-red-100 text-red-700';
    }
    $patient_first_name = ($appointment->patient->patient_type=='kid'|| $appointment->patient->patient_type=='young') ? $appointment->patient->first_name : $appointment->patient->parent_first_name;
    $patient_last_name = ($appointment->patient->patient_type=='kid'|| $appointment->patient->patient_type=='young') ? $appointment->patient->last_name : $appointment->patient->parent_last_name;
    $patient_full_name = $patient_first_name . ' ' . $patient_last_name;
@endphp
<div class="min-h-screen bg-gradient-to-br from-cyan-50 via-sky-50 to-blue-50 p-6 mt-24">
    <a onclick="window.history.back()" class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-cyan-100 text-cyan-700 hover:bg-cyan-200 transition-all">
        <i class="fas fa-arrow-left"></i>
    </a>
    <div class="max-w-4xl mx-auto">
    <!-- Header -->
        <div class="flex items-center gap-4 mb-8 bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 p-6">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800 flex-1">Détails du Rendez-vous</h1>
            <span class="inline-flex items-center px-4 py-2 rounded-full font-semibold {{ $color }} text-base shadow">
                {{ ucfirst($appointment->status) }}
            </span>
    </div>
    <!-- Main Content -->
    <div class="space-y-8">
        <!-- Patient Section -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20">
                <div class="flex items-center justify-between p-6 border-b border-cyan-100">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-user-injured text-cyan-500 text-xl"></i>
                        <h2 class="text-xl font-semibold text-gray-800">Détails du Patient</h2>
                    </div>
                    <a href="{{ route('patient_profile',$appointment->patient->id) }}" class="inline-flex items-center gap-1 text-cyan-600 hover:text-cyan-800 font-medium transition-all">
                        <span class="hidden sm:inline">Voir le Profil</span> <i class="fas fa-external-link-alt"></i>
                    </a>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-3">
                        <div><span class="font-semibold text-gray-700">ID :</span> {{ $appointment->patient->id }}</div>
                        <div><span class="font-semibold text-gray-700">Nom :</span> {{ $patient_full_name }}</div>
                        <div><span class="font-semibold text-gray-700">Téléphone :</span> {{ $appointment->patient->phone }}</div>
                        <div><span class="font-semibold text-gray-700">Email :</span> {{ $appointment->patient->email }}</div>
                        <div><span class="font-semibold text-gray-700">Adresse :</span> {{ $appointment->patient->address }}</div>
                    </div>
                    <div class="space-y-3">
                        <div><span class="font-semibold text-gray-700">Sexe :</span> {{ $appointment->patient->patient_type }}</div>
                        <div><span class="font-semibold text-gray-700">Genre :</span>
                            @if ($appointment->patient->gender == "M")
                                Homme <i class="fas fa-mars text-blue-500"></i>
                            @elseif ($appointment->patient->gender == "F")
                                Femme <i class="fas fa-venus text-pink-500"></i>
                            @endif
                        </div>
                        <div><span class="font-semibold text-gray-700">Âge :</span> {{ $appointment->patient->age }}</div>
                        <div><span class="font-semibold text-gray-700">Mode :</span> {{ $appointment->patient->mode }}</div>
                        <div><span class="font-semibold text-gray-700">Abonnement :</span> {{ $appointment->patient->subscription }}</div>
                    </div>
                    @if ($appointment->patient->patient_type == 'kid' || $appointment->patient->patient_type == 'young')
                        <div class="space-y-3 md:col-span-2">
                            <div><span class="font-semibold text-gray-700">École :</span> {{ $appointment->patient->ecole }}</div>
                            <div><span class="font-semibold text-gray-700">Système :</span> {{ $appointment->patient->system }}</div>
                            <div><span class="font-semibold text-gray-700">Nom complet du parent :</span> {{ $appointment->patient->parent_first_name }} {{ $appointment->patient->parent_last_name }}</div>
                            <div><span class="font-semibold text-gray-700">Profession :</span> {{ $appointment->patient->profession }}</div>
                            <div><span class="font-semibold text-gray-700">Établissement :</span> {{ $appointment->patient->etablissment }}</div>
                        </div>
                    @endif
                </div>
            </div>
        <!-- Schedule Section -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20">
                <div class="flex items-center gap-3 p-6 border-b border-cyan-100">
                    <i class="fas fa-calendar-alt text-cyan-500 text-xl"></i>
                    <h2 class="text-xl font-semibold text-gray-800">Planning</h2>
                </div>
                <div class="p-6 space-y-2">
                    @php $schedule = json_decode($appointment->appointment_planning, true); @endphp
                @foreach ($schedule as $day => $time)
                        <div class="flex items-center gap-4 border-b border-cyan-50 py-2 last:border-b-0">
                            <span class="font-semibold text-gray-700 min-w-[120px]">{{ $day }}</span>
                            <span class="text-gray-700">
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
                <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20">
                    <div class="flex items-center gap-3 p-6 border-b border-cyan-100">
                        <i class="fas fa-question text-cyan-500 text-xl"></i>
                        <h2 class="text-xl font-semibold text-gray-800">Raison</h2>
                    </div>
                    <div class="p-6 space-y-2">
                        <div><span class="font-semibold text-gray-700">Annulé par :</span> {{ $appointment->cancelledBy }}</div>
                        <div><span class="font-semibold text-gray-700">Description :</span> {{ $appointment->reason }}</div>
                </div>
            </div>
        @endif
        <!-- Report Section -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20">
                <div class="flex items-center justify-between p-6 border-b border-cyan-100">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-file-medical text-cyan-500 text-xl"></i>
                        <h2 class="text-xl font-semibold text-gray-800">Session Report</h2>
                </div>
                @if($errors->has('report'))
                        <div class="text-red-500 text-sm">{{ $errors->first('report') }}</div>
                @endif
            </div>
                <div class="p-6">
                @if($appointment->report_path)
                    <!-- Existing Report -->
                     <div class="flex flex-col md:flex-row items-center justify-between gap-4 bg-cyan-50 rounded-xl p-4">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-file-pdf text-red-500 text-2xl"></i>
                            <div>
                                <p class="font-semibold  text-gray-800">{{ basename($appointment->report_path) }}</p>
                                <small class="text-gray-500">Uploaded {{ $appointment->updated_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('coach-appointments.downloadReport', $appointment->id) }}" class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-blue-100 text-blue-700 hover:bg-blue-200 transition-all" title="Download">
                                <i class="fas fa-download"></i>
                            </a>
                            <a href="{{ route('coach-appointments.viewReport', $appointment->id) }}" target="_blank" class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-green-100 text-green-700 hover:bg-green-200 transition-all" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <form action="{{ route('coach-appointments.deleteReport', $appointment->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-red-100 text-red-700 hover:bg-red-200 transition-all" onclick="confirmDelete(this)" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Upload Form -->
                        <form action="{{ route('coach-appointments.uploadReport', $appointment->id) }}" method="POST" enctype="multipart/form-data" id="report-form">
                        @csrf
                            <div class="flex flex-col items-center gap-4 border-2 border-dashed border-cyan-200 rounded-xl p-6">
                            <!-- File Preview -->
                                <div id="file-preview" class="hidden flex items-center gap-2 bg-cyan-50 rounded-lg p-2 w-full max-w-md">
                                    <i class="fas fa-file-upload text-cyan-500"></i>
                                    <span id="file-name" class="flex-1"></span>
                                    <button type="button" class="inline-flex items-center justify-center w-8 h-8 rounded bg-red-100 text-red-700 hover:bg-red-200 transition-all" onclick="clearFile()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <!-- Upload Box -->
                                <label class="flex flex-col items-center justify-center gap-2 w-full max-w-md h-32 border-2 border-dashed border-cyan-200 rounded-lg cursor-pointer hover:border-cyan-400 transition-all" id="upload-label">
                                <input type="file" name="report" id="report-input" accept=".pdf,.doc,.docx" hidden>
                                    <div class="flex flex-col items-center gap-1">
                                        <i class="fas fa-cloud-upload-alt text-2xl text-cyan-400"></i>
                                        <p class="text-gray-700">Glissez-déposez un fichier ou <span class="text-cyan-600 font-semibold underline cursor-pointer">Parcourir</span></p>
                                        <small class="text-gray-500">Formats supportés: PDF, DOC, DOCX (Max 2MB)</small>
                                </div>
                            </label>
                            <!-- Upload Button -->
                                <button type="submit" id="submit-btn" class="hidden mt-2 inline-flex items-center gap-2 bg-gradient-to-r from-cyan-500 to-sky-600 text-white px-6 py-2 rounded-xl font-semibold hover:from-cyan-600 hover:to-sky-700 transition-all">
                                <i class="fas fa-upload"></i> Upload Report
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
        <!-- Action Buttons -->
        @if ($appointment->status == 'pending')
                <div class="flex flex-col md:flex-row gap-4 justify-center mt-8">
                    <a href="{{ route('appointment_edit',$appointment->id) }}" class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-red-100 text-red-700 font-semibold hover:bg-red-200 transition-all">
                    Annuler le Rendez-vous
                </a>
                <form method="POST" action="{{ route('coach-update-appointment-status' ,[$appointment->id,'passed']) }}">
                    @csrf
                    @method('PATCH')
                        <button type="submit" class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-green-100 text-green-700 font-semibold hover:bg-green-200 transition-all">
                        Marquer comme Terminé
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>
</div>
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
            const uploadBox = document.getElementById('upload-label');
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
                uploadBox.classList.add('border-cyan-400');
            }
            function unhighlight() {
                uploadBox.classList.remove('border-cyan-400');
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
