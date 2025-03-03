
@extends('layouts.coach_app')

@section('content')

    <div>
        <h1 class="text-center w-100 fs-1 fw-bold mb-4 mt-4"> Patients List</h1>
    </div>
    <!-- search form -->
    <form action="{{ route('patients_list', auth()->user()->id) }}" method="GET" class="mb-4 p-4">
        <div class="row g-2">
            <div class="col-md-6">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by patient" class="form-control bg-transparent">
            </div>
            <div class="col-md-2">
                <select name="gender" class="form-select bg-transparent">
                    <option value="">All Gender</option>
                    <option value="M" {{ request('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="F" {{ request('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="patient_type" class="form-select bg-transparent">
                    <option value="">All Type</option>
                    <option value="kid" {{ request('patient_type') == 'kid' ? 'selected' : '' }}>kid</option>
                    <option value="young" {{ request('patient_type') == 'young' ? 'selected' : '' }}>young</option>
                    <option value="adult" {{ request('patient_type') == 'adult' ? 'selected' : '' }}>adult</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-2"></i>Search
                </button>
            </div>
        </div>
    </form>
    <!-- patients table -->
    <div id="patients" class="table">
                <div class="table-wrapper">
                    <table class="patients-table">
                    <thead>
                        <tr>
                            <th><i class="fas fa-id-badge me-2"></i> ID</th>
                            <th><i class="fas fa-user me-2"></i> Kid/Young Full Name</th>
                            <th><i class="fas fa-phone me-2"></i> Phone</th>
                            <th>Age</th>
                            <th><i class="fas fa-venus-mars me-2"></i> Gender</th>
                            <th><i class="fa-solid fa-school-flag"></i> Ecole</th>
                            <th><i class="fa-solid fa-sitemap"></i> System</th>
                            <th><i class="fas fa-user me-2"></i> Parent/adult Full Name</th>
                            <th><i class="fa-solid fa-briefcase"></i> Profession</th>
                            <th><i class="fa-solid fa-building"></i> Etablissment</th>
                            <th><i class="bi bi-envelope-at-fill"></i> Email</th>
                            <th><i class="bi bi-geo-alt-fill"></i> Adress</th>
                            <th><i class="fa-solid fa-people-arrows"></i> Mode</th>
                            <th>Subscription</th>
                            <!-- <th><i class="fas fa-cogs me-2"></i>Actions</th> -->
                        </tr>
                    </thead>
                        <tbody>
                            @foreach($patients as $patient)
                            <tr class="hover-shadow">
                                <!-- id -->
                                <td>{{ $patient->id }}</td>
                                <!-- full name kid/young -->
                                <td>
                                    @if ($patient->first_name==null)
                                    <mark><i>vide</i></mark>
                                    @else
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-40 me-3">
                                            <img src="https://i.pravatar.cc/150" class="rounded-circle" width="40" alt="patient Avatar">
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $patient->first_name}} {{ $patient->last_name }}</div>
                                        </div>
                                    </div>
                                    @endif


                                </td>
                                <!-- phone -->
                                <td>{{ $patient->phone }}</td>
                                <!-- age -->
                                <td>{{ $patient->age }}</td>
                                <!-- gender -->
                                <td>
                                    @if ($patient->gender == 'M')
                                        <span class="badge bg-primary">Male <i class="bi bi-gender-male"></i></span>
                                    @else
                                        <span class="badge bg-danger">Female <i class="bi bi-gender-female"></i></span>
                                    @endif
                                </td>
                                <!-- ecole -->
                                <td>
                                    @if ( $patient->ecole  == null)
                                    <mark><i>vide</i></mark>
                                    @else
                                    {{ $patient->ecole }}
                                    @endif
                                    </td>
                                <!-- system -->
                                <td>
                                @if ( $patient->system  == null)
                                        <mark><i>vide</i></mark>
                                    @else
                                    {{ $patient->system }}
                                    @endif
                                    </td>
                                <!-- parent patient full name -->
                                <td>{{ $patient->parent_first_name }}  {{ $patient->parent_last_name  }}</td>
                                <!-- profession -->
                                <td>{{ $patient->profession }}</td>
                                <!-- etablissment -->
                                <td>{{ $patient->etablissment }}</td>
                                <!-- email -->
                                <td>{{ $patient->email }}</td>
                                <!-- adress -->
                                <td>{{ $patient->address }}</td>
                                <!-- mode -->
                                <td>{{ $patient->mode }}</td>
                                <!-- subscription -->
                                <td>{{  $patient->subscription }}</td>


                                <!-- <td class="text-center">
                                    <a href="{{ route('patient_profile', $patient->id) }}" class="btn btn-sm btn-outline-secondary me-2">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                </td> -->
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
    </div>

    <style>
        .patients-table {
            width:200%;
        }
    </style>
@endsection