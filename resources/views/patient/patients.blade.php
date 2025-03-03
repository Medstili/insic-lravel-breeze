@extends('layouts.app')

@section('content')

<style>
    /* Hover Shadow Effect for table rows */
    .hover-shadow:hover {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        transition: box-shadow 0.3s ease;
    }
    /* Define common color variables */
    :root {
        --primary-color: #3498db;
        --secondary-color: #2c3e50;
        --light-bg: #ecf0f1;
    }
    /* Main container adjusted for fixed sidebar */
    .container.py-5 {
        background-color: var(--light-bg);
        min-height: calc(100vh - 60px);
        padding: 40px;
    }
    /* Glass Card styling for headers, forms, and panels */
    .glass-card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        border: none;
    }
    /* Dashboard Header */
    .dashboard-header {
        padding: 20px;
        margin-bottom: 20px;
        text-align: center;
        background-color: #fff;
    }
    .dashboard-header h1 {
        color: var(--secondary-color);
        font-weight: bold;
    }
    /* Form & Input Styling */
    .form-control, .form-select {
        border-radius: 4px;
        border: 1px solid #ccc;
    }
    .form-control.bg-transparent,
    .form-select.bg-transparent {
        background-color: transparent;
        color: var(--secondary-color);
    }
    /* Table Styling */
    /* .table_container{
        overflow: scroll;
    } */
    .table {
        width:200%;
    }
    .table.table-hover {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .table thead {
        background-color: var(--secondary-color);
        color: #ecf0f1;
    }
    .table thead th {
        border-bottom: 2px solid #34495e;
    }
    .table tbody tr:hover {
        background-color: #f1f1f1;
    }
    /* Badge Styling for Gender */
    .badge.bg-primary {
        background-color: #3498db !important;
    }
    .badge.bg-danger {
        background-color: #e74c3c !important;
    }
    /* Modal adjustments */
    .modal-content.glass-card {
        background-color: #fff;
        color: var(--secondary-color);
    }
    .modal-header {
        border-bottom: none;
    }
</style>

<div class="container py-5">
    <!-- Header -->
    <div class="dashboard-header glass-card p-4 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="display-6 mb-0">patients List</h1>
            <form action=" {{ route('patient.create') }}" method="get">
                @csrf
                <button type="submit" class="btn btn-secondary btn-lg">
                    <i class="fas fa-plus me-2"></i>Add New patient
                </button>
            </form>
        </div>
    </div>

    <!-- Search Form -->
    <form action="{{ route('patient.index') }}" method="GET" class="mb-4 glass-card p-4">
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

    <!-- patients Table -->
    <div class="glass-card p-4 table_container">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr class="bg-primary text-white">
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
                        <th><i class="fas fa-cogs me-2"></i>Actions</th>
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


                        <td>
                            <a href="{{ route('patient.show', $patient->id) }}" class="btn btn-sm btn-outline-secondary me-2">
                                <i class="fas fa-eye"></i>
                            </a>
                            <form action="{{ route('patient.destroy', $patient->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
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
  function patientUpdatePlanning() {
    // Collect data for Priority 1
    const priority1Day = document.getElementById('priority_1_day').value.trim();
    const priority1StartTime = document.getElementById('priority_1_start_time').value.trim();
    const priority1EndTime = document.getElementById('priority_1_end_time').value.trim();
    // Collect data for Priority 2
    const priority2Day = document.getElementById('priority_2_day').value.trim();
    const priority2StartTime = document.getElementById('priority_2_start_time').value.trim();
    const priority2EndTime = document.getElementById('priority_2_end_time').value.trim();
    // Collect data for Priority 3
    const priority3Day = document.getElementById('priority_3_day').value.trim();
    const priority3StartTime = document.getElementById('priority_3_start_time').value.trim();
    const priority3EndTime = document.getElementById('priority_3_end_time').value.trim();

    // Initialize an empty planning object
    let planning = {};

    // Helper function to add valid priority to planning
    function addPriority(priority, day, startTime, endTime) {
        if (day && startTime && endTime) {
            planning[priority] = {
                [day]: {
                    startTime: startTime,
                    endTime: endTime
                }
            };
        }
    }

    // Validate: Priority 1 is mandatory
    if (!priority1Day || !priority1StartTime || !priority1EndTime) {
        alert("Please fill out Priority 1 as it's required.");
        return false; // Prevent form submission
    }

    // Add only filled priorities
    addPriority("priority 1", priority1Day, priority1StartTime, priority1EndTime);
    addPriority("priority 2", priority2Day, priority2StartTime, priority2EndTime);
    addPriority("priority 3", priority3Day, priority3StartTime, priority3EndTime);

    // Convert planning object to JSON
    const planningJSON = JSON.stringify(planning);

    // Set the JSON data in the hidden input field
    document.getElementById('planning').value = planningJSON;

    // Log the JSON for debugging
    console.log("Generated Planning JSON:", planningJSON);

    // Allow form submission
    return true;
  }
</script>
@endsection
