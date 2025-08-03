@extends('layouts.coach_app')

@section('content')

    <div>
        <h1 class="text-center w-100 fs-1 fw-bold mb-4 mt-24">Liste des Patients</h1>
    </div>
    <!-- search form -->
         <!-- Search and Filter Section -->
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 overflow-hidden mb-8">
        <div class="bg-gradient-to-r from-cyan-500 to-sky-600 px-8 py-6">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-white flex items-center gap-3">
                    <i class="fas fa-search"></i>
                    Recherche et Filtres
                </h3>
            </div>
        </div>
        <div class="p-8">
            <form action="{{ route('patients_list', auth()->user()->id) }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 items-end">
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">Rechercher</label>
                        <input type="text" name="q" value="{{ request('q') }}" 
                               placeholder="Nom, email ou téléphone..." 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200 bg-white/50 backdrop-blur-sm">
                    </div>
                    
                    <di v class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">Type de Patient</label>
                        <select name="patient_type" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200 bg-white/50 backdrop-blur-sm">
                            <option value="">Tous les types</option>
                            <option value="adult" {{ request('patient_type') == 'adult' ? 'selected' : '' }}>Adulte</option>
                    <option value="kid" {{ request('patient_type') == 'kid' ? 'selected' : '' }}>Enfant</option>
                    <option value="young" {{ request('patient_type') == 'young' ? 'selected' : '' }}>Jeune</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">Genre</label>
                        <select name="gender" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200 bg-white/50 backdrop-blur-sm">
                            <option value="">Tous les genres</option>
                            <option value="M" {{ request('gender') == 'M' ? 'selected' : '' }}>Masculin</option>
                            <option value="F" {{ request('gender') == 'F' ? 'selected' : '' }}>Féminin</option>
                </select>
            </div>

                <button type="submit" class="w-full bg-gradient-to-r from-cyan-500 to-sky-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-cyan-600 hover:to-sky-700 transform hover:-translate-y-1 transition-all duration-200 flex items-center justify-center gap-3 shadow-lg hover:shadow-xl">
                        <i class="fas fa-search"></i>
                        <span class="hidden sm:inline">Rechercher</span>
                </button>
                </div>
            </form>
        </div>
    </div>
    <!-- <form action="{{ route('patients_list', auth()->user()->id) }}" method="GET" class="mb-4 glass-card p-4">
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
    </form> -->

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



    </style>
@endsection