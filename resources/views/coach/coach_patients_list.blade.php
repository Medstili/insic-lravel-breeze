@extends('layouts.coach_app')

@section('content')

    <div>
        <h1 class="text-center w-100 fs-1 fw-bold mb-4 mt-4">Liste des Patients</h1>
    </div>
    <!-- search form -->
    <form action="{{ route('patients_list', auth()->user()->id) }}" method="GET" class="mb-4 glass-card p-4">
        <div class="row g-2">
            <div class="col-md-6">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Rechercher par patient" class="form-control bg-transparent">
            </div>
            <div class="col-md-2">
                <select name="gender" class="form-select bg-transparent">
                    <option value="">Tous les Genres</option>
                    <option value="M" {{ request('gender') == 'Male' ? 'selected' : '' }}>Homme</option>
                    <option value="F" {{ request('gender') == 'Female' ? 'selected' : '' }}>Femme</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="patient_type" class="form-select bg-transparent">
                    <option value="">Tous les Types</option>
                    <option value="kid" {{ request('patient_type') == 'kid' ? 'selected' : '' }}>Enfant</option>
                    <option value="young" {{ request('patient_type') == 'young' ? 'selected' : '' }}>Jeune</option>
                    <option value="adult" {{ request('patient_type') == 'adult' ? 'selected' : '' }}>Adulte</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-2"></i>Rechercher
                </button>
            </div>
        </div>
    </form>
    <!-- Patients Table -->
    <div id="patients-table" class="data-table-container">
         <div>
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
                                     <!-- kidd full name -->
                                     <td>
                                         <div class="d-flex align-items-center">
                                             <div class="avatar-sm me-3">
                                                 <div class="avatar-title bg-light rounded-circle">
                                                     <i class="fas fa-user text-primary"></i>
                                                 </div>
                                             </div>
                                         
                                         @if ($patient->first_name==null)
                                         &ndash;
                                         @else
                                         <div >
                                             <div>
                                                 <div class="fw-bold">{{ $patient->first_name}} {{ $patient->last_name }}</div>
                                             </div>
                                             <small class="text-muted">{{ $patient->mode }}</small>
                                         </div>
                                         @endif
                                         </div>
                                     </td>
                                     <!--  parent/adult full name -->
                                     <td>
                                     <div class="fw-bold">{{ $patient->parent_first_name }} {{ $patient->parent_last_name }}</div>
                                 
                                 </td>
                                 <!-- age -->
                                 <td>{{ $patient->age }}</td>
                                 <!-- contact -->
                                     <td>
                                         <div>{{ $patient->phone }}</div>
                                         <small class="text-muted">{{ $patient->email }}</small>
                                     </td>
                                     <!-- adresse -->
                                     <td class="text-muted small">{{ $patient->address }}</td>
                                     <!-- gender -->
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
                                     <!-- school/system -->
                                     <td>
                                         @if ($patient->ecole)
                                             {{ $patient->ecole }}
                                             <div class="text-muted small">{{ $patient->system }}</div>
                                         @else
                                             &ndash;
                                         @endif
                                         
                                         
                                     </td>
                                     <!-- profession/etablissment -->
                                     <td>
                                     {{ $patient->profession }}
                                     <div class="text-muted small">{{ $patient->etablissment }}</div>
                                     </td>
                                     <!-- Mode -->
                                     <td>{{ $patient->mode }}</td>
                                     <!-- subscription -->
                                     <td>{{  $patient->subscription }}</td>
                                     <!-- actions -->
                                     <td class="action-buttons">
                                         <a href="{{ route('patient_profile', $patient->id) }}" 
                                         class="btn btn-outline-secondary btn-sm me-1"
                                         data-bs-toggle="tooltip" title="Voir les Détails">
                                             <i class="fas fa-eye"></i>
                                         </a>
                                     </td>
                                 </tr>
                             @endforeach
                         </tbody>
                 </table>
             </div>
         </div>
     </div>
    <style>
        .patient-table {
        width: 200%;
        overflow: scroll;
        border-collapse: collapse;
        background: var(--glass-bg);
        }
        .glass-card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        border: none;
        }

        .data-table-container {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
          
        }
        .table-responsive {
        border-radius: 12px;
        max-height: 70vh;
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
        .hover-shadow:hover {
        background: rgba(99, 102, 241, 0.05);
        transform: translateY(-2px);
        transition: all 0.3s ease;
    }
    .badge {
        padding: 0.5em 0.75em;
        border-radius: 8px;
        font-weight: 500;
    }
/* Responsive styles only - to be added at the end of existing CSS */

/* Mobile devices (phones, less than 768px) */
@media (max-width: 767.98px) {
    /* Header adjustments */
    h1.text-center {
        font-size: 1.5rem !important;
        margin: 1rem 0 !important;
    }
    
    /* Search form adjustments */
    .glass-card.p-4 {
        padding: 1rem !important;
    }
    
    .row.g-2 > div {
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
    
    /* Avatar adjustments */
    .avatar-sm {
        width: 30px;
        height: 30px;
    }
    
    .badge {
        font-size: 0.7rem;
        padding: 0.4em 0.6em;
    }
}

/* Small devices (landscape phones) */
@media (min-width: 576px) and (max-width: 767.98px) {
    .glass-card.p-4 {
        padding: 1.25rem !important;
    }
    
    h1.text-center {
        font-size: 1.75rem !important;
    }
}

/* Medium devices (tablets) */
@media (min-width: 768px) and (max-width: 991.98px) {
    .patient-table {
        width: 250%; /* Less wide for tablets */
    }
    
    /* Search form adjustments for tablets */
    .row.g-2 {
        display: flex;
        flex-wrap: wrap;
    }
    
    .row.g-2 > div:first-child {
        flex: 0 0 100%;
        max-width: 100%;
        margin-bottom: 0.5rem;
    }
    
    .row.g-2 > div:not(:first-child) {
        flex: 1;
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
@endsection