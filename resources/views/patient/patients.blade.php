@extends('layouts.app')

@section('content')
<style>
    :root {
        --primary-color: #6366f1;
        --secondary-color: #4f46e5;
        --accent-color: #818cf8;
        --light-bg: #f8fafc;
        --dark-text: #1e293b;
        --glass-bg: rgba(255, 255, 255, 0.9);
    }

    .container-py-5 {
        background-color: var(--light-bg);
        padding: 2rem;
        margin-top: 80px;
    }

    .glass-card {
        background: var(--glass-bg);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .dashboard-header {
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .table-responsive {
        border-radius: 12px;
        max-height: 70vh;
    }

    .patient-table {
        width: 200%;
        overflow: scroll;
        border-collapse: collapse;
        background: var(--glass-bg);
    }

    thead th {
        background: linear-gradient(195deg, var(--primary-color), var(--secondary-color));
        color: white;
        padding: 1rem;
        font-weight: 500;
        position: sticky;
        top: 0;
        z-index: 2;
    }

    tbody td {
        padding: 0.75rem;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: middle;
    }

    .badge {
        padding: 0.5em 0.75em;
        border-radius: 8px;
        font-weight: 500;
    }

    .btn-secondary {
        background: var(--secondary-color);
        border: none;
        padding: 0.75rem 1.5rem;
        transition: all 0.3s ease;
    }

    .btn-secondary:hover {
        background: var(--primary-color);
        transform: translateY(-1px);
    }

    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }

    .alert-success {
        background: rgba(76, 175, 80, 0.15);
        border: none;
        color: #4caf50;
        border-radius: 8px;
    }

    .hover-shadow:hover {
        background: rgba(99, 102, 241, 0.05);
        transform: translateY(-2px);
        transition: all 0.3s ease;
    }

    .action-buttons .btn {
        width: 40px;
        height: 40px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .action-buttons .btn-outline-secondary {
        border-color: #e2e8f0;
    }

    .action-buttons .btn-outline-secondary:hover {
        background: var(--light-bg);
    }

    .action-buttons .btn-outline-danger:hover {
        background: rgba(239, 83, 80, 0.1);
    }
    .patient-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
    }

    .patient-img-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--primary-color);
    }

    /* Responsive styles only - to be added at the end of existing CSS */

/* Mobile devices (phones, less than 768px) */
@media (max-width: 767.98px) {
    .container-py-5 {
        padding: 1rem;
        margin-top: 60px;
    }
    
    .dashboard-header {
        padding: 1rem;
        margin-bottom: 1rem;
    }
    
    .dashboard-header .d-flex {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start !important;
    }
    
    .dashboard-header .btn-secondary {
        width: 100%;
    }
    
    /* Search form adjustments */
    .glass-card.p-4 {
        padding: 1rem !important;
    }
    
    .row.g-3 > div {
        margin-bottom: 0.5rem;
    }
    
    /* Table adjustments */
    .table-responsive {
        max-height: 60vh;
    }
    
    .patient-table {
        width: 300%; /* Extra wide for horizontal scrolling */
    }
    
    thead th, tbody td {
        padding: 0.5rem;
        font-size: 0.9rem;
    }
    
    /* Improve touch targets */
    .btn, .form-control, .form-select {
        min-height: 44px;
    }
    
    .action-buttons .btn {
        width: 44px;
        height: 44px;
    }
    
    /* Patient avatar adjustments */
    .patient-avatar, .patient-img-avatar {
        width: 40px;
        height: 40px;
        font-size: 1.5rem;
    }
}

/* Small devices (landscape phones) */
@media (min-width: 576px) and (max-width: 767.98px) {
    .container-py-5 {
        padding: 1.25rem;
    }
    
    .dashboard-header {
        padding: 1.25rem;
    }
    
    .glass-card.p-4 {
        padding: 1.25rem !important;
    }
}

/* Medium devices (tablets) */
@media (min-width: 768px) and (max-width: 991.98px) {
    .container-py-5 {
        padding: 1.5rem;
    }
    
    .patient-table {
        width: 250%; /* Less wide for tablets */
    }
    
    /* Search form adjustments for tablets */
    .row.g-3 {
        display: flex;
        flex-wrap: wrap;
    }
    
    .row.g-3 > div {
        flex: 0 0 50%;
        max-width: 50%;
    }
    
    .row.g-3 > div:last-child {
        flex: 0 0 100%;
        max-width: 100%;
        margin-top: 0.5rem;
    }
}

/* Large devices (desktops) */
@media (min-width: 992px) and (max-width: 1199.98px) {
    .patient-table {
        width: 200%;
    }
}

/* Extra large devices (large desktops) */
@media (min-width: 1200px) {
    .patient-table {
        width: 200%;
    }
}


</style>

<div class="container-py-5">
    <!-- Message de succès -->
    @if(session('success'))
    <div class="alert alert-success glass-card mb-4 d-flex align-items-center">
        <i class="fas fa-check-circle me-2"></i>
        <div>{{ session('success') }}</div>
    </div>
    @endif

    <!-- En-tête -->
    <div class="dashboard-header glass-card mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0 text-dark">Gestion des Patients</h1>
            <a href="{{ route('patient.create') }}" class="btn btn-secondary">
                <i class="fas fa-plus me-2"></i>Nouveau Patient
            </a>
        </div>
    </div>

    <!-- Formulaire de recherche -->
    <form action="{{ route('patient.index') }}" method="GET" class="glass-card p-4 mb-4">
        <div class="row g-3">
            <div class="col-md-3">
                <input type="text" name="q" class="form-control" placeholder="Rechercher des patients..." value="{{ request('q') }}">
            </div>
            <div class="col-md-3">
                <select name="gender" class="form-select">
                    <option value="">Tous les Genres</option>
                    <option value="M" {{ request('gender') == 'M' ? 'selected' : '' }}>Homme</option>
                    <option value="F" {{ request('gender') == 'F' ? 'selected' : '' }}>Femme</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="specialities" class="form-select ">
                    <option value="">Toutes les spécialités</option>
                    @foreach($specialities as $speciality)
                    <option value="{{ $speciality->id }}" {{ request('specialities') == $speciality->id ? 'selected' : '' }}>
                        {{ $speciality->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="patient_type" class="form-select">
                    <option value="">Tous les Types</option>
                    <option value="kid" {{ request('patient_type') == 'kid' ? 'selected' : '' }}>Enfant</option>
                    <option value="young" {{ request('patient_type') == 'young' ? 'selected' : '' }}>Jeune</option>
                    <option value="adult" {{ request('patient_type') == 'adult' ? 'selected' : '' }}>Adulte</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-2"></i>Filtrer
                </button>
            </div>
        </div>
    </form>

    <!-- Tableau des patients -->
    <div class="glass-card">
        <div class="table-responsive">
            <table class="patient-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom Complet Enfant/Jeune</th>
                        <th>Nom Complet Parent/Adulte</th>
                        <th>Âge</th>
                        <th>Contact</th>
                        <th>Adresse</th>
                        <th>Genre</th>
                        <th>École/Système</th>
                        <th>Profession/Établissement</th>
                        <th>Mode</th>
                        <th>Abonnement</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($patients as $patient)
                    <tr class="hover-shadow">
                        <td class="fw-bold">#{{ $patient->id }}</td>
                        <!-- Nom complet enfant/jeune -->
                        <td>
                            <div class="d-flex align-items-center">
                                <!-- <div class="avatar-sm me-3">
                                    <div class="avatar-title bg-light rounded-circle">
                                        <i class="fas fa-user text-primary"></i>
                                    </div>
                                </div> -->
                                @if ($patient->image_path)
                                    <div class="patient-avatar-initials me-2">
                                        <img src="{{ asset('storage/' . $patient->image_path) }}" alt="Image Previe" class="patient-img-avatar">
                                        
                                    </div>
                                @else
                                    <div class="patient-avatar me-2">
                                        {{ strtoupper(substr($patient->patient_type=='adult'? $patient->parent_first_name : $patient->first_name, 0, 1)) }}
                                    </div>
                                
                                @endif
                               
                            @if ($patient->patient_type!='adult')
                        
                            <div >
                                <div>
                                    <div class="fw-bold">{{ $patient->first_name}} {{ $patient->last_name }}</div>
                                </div>
                                <small class="text-muted">{{ $patient->mode }}</small>
                            </div>

                            @endif
                            </div>


                        </td>
                        <!-- Nom complet parent/adulte -->
                        <td>
                          <div class="fw-bold">{{ $patient->parent_first_name }} {{ $patient->parent_last_name }}</div>
                          <!-- <div class="text-muted small">{{ $patient->address }}</div> -->
                           @if ($patient->patient_type=='adult')
                           
                           <small class="text-muted">{{ $patient->mode }}</small>
                           @endif
                      </td>
                      <!-- Âge -->
                      <td>{{ $patient->age }}</td>
                      <!-- Contact -->
                        <td>
                            <div>{{ $patient->phone }}</div>
                            <small class="text-muted">{{ $patient->email }}</small>
                        </td>
                        <!-- Adresse -->
                        <td class="text-muted small">{{ $patient->address }}</td>
                        <!-- Genre -->
                        <td>
                            @if($patient->gender == 'M')
                            <span class="badge bg-primary">
                                <i class="fas fa-mars me-1"></i>Homme
                            </span>
                            @else
                            <span class="badge bg-danger">
                                <i class="fas fa-venus me-1"></i>Femme
                            </span>
                            @endif
                        </td>
                        <!-- École/Système -->
                        <td>
                            @if ($patient->ecole)
                                {{ $patient->ecole }}
                                <div class="text-muted small">{{ $patient->system }}</div>
                            @else
                                &ndash;
                            @endif

                            
                            
                        </td>
                        <!-- Profession/Établissement -->
                        <td>
                          {{ $patient->profession }}
                          <div class="text-muted small">{{ $patient->etablissment }}</div>
                        </td>
                        <!-- Mode -->
                        <td>{{ $patient->mode }}</td>
                        <!-- Abonnement -->
                        <td>{{  $patient->subscription }}</td>
                        <!-- Actions -->
                        <td class="action-buttons">
                            <a href="{{ route('patient.show', $patient->id) }}" 
                               class="btn btn-outline-secondary btn-sm me-1"
                               data-bs-toggle="tooltip" title="Voir les Détails">
                                <i class="fas fa-eye"></i>
                            </a>
                            <form action="{{ route('patient.destroy', $patient->id) }}" 
                                  method="POST" 
                                  class="d-inline"
                                  onsubmit="return confirm('Supprimer ce patient définitivement ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="btn btn-outline-danger btn-sm"
                                        data-bs-toggle="tooltip" 
                                        title="Supprimer le Patient">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Activer les tooltips Bootstrap
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    const tooltipList = tooltipTriggerList.map(tooltipTriggerEl => {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
@endsection