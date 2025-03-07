@extends('layouts.coach_app')
@section('content')
    <div>
        <h1 class="text-center w-100 fs-1 fw-bold mb-4 mt-4"> Appointments List</h1>
    </div>
    <form action="{{ route('appointments_list', Auth::user()->id) }}" method="GET" class="mb-4 p-4">
            <div class="row g-2">
                <div class="col-md-3">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by Coach or Patient" class="form-control">
                </div>
                <div class="col-md-3">
                    <select name="speciality" class="form-select">
                        <option value="">All Specialities</option>
                        @foreach($specilaities as $speciality)
                        <option value="{{ $speciality->id }}" {{ request('speciality') == $speciality->id ? 'selected' : '' }}>{{ $speciality->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>pending</option>
                        <option value="passed" {{ request('status') == 'passed' ? 'selected' : '' }}>passed</option>
                        <option value="cancel" {{ request('status') == 'cancel' ? 'selected' : '' }}>cancel</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date" value="{{ request('date') }}" placeholder="Search by Coach or Patient" class="form-control">
                </div>
      
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-2"></i>Search
                    </button>
                </div>
            </div>
    </form>
        <!-- Appointment Table -->
        <div class="glass-card p-2 mb-4">
            <table class="table table-hover align-middle">
                <thead class="thead-light">
                    <tr>
                        <th><i class="fas fa-id-badge text-center"></i> ID</th>
                        <th><i class="fas fa-user text-center"></i> Patient Name</th>
                        <th><i class="fas fa-date text-center"></i> Date </th>
                        <th><i class="fas fa-user text-center"></i> Coach</th>
                        <th><i class="fas fa-star text-center"></i> Specialty</th>
                        <th><i class="fas fa-clock text-center"></i> Status</th>
                        <th><i class="fas fa-cogs text-center"></i> Actions</th>
                    </tr>
                </thead>
                <tbody>
              
                    @foreach($coachAppointments as $appointment)
                                               
                    <tr class="coach-card">
                        <td>{{ $appointment->id }}</td>
                        <td>
                            {{ 
                                $fullName = ($appointment->patient->patyient_type == 'Kid'||$appointment->patient->patyient_type =='young')?
                                $appointment->patient->first_name.' '.$appointment->patient->last_name :
                                $appointment->patient->parent_first_name.' '.$appointment->patient->parent_last_name
                             }}
                        </td>
                        <td>
                        
                            @php
                                $schedule = json_decode($appointment->appointment_planning, true);
                            @endphp
                        
                            @if (is_array($schedule))
                                @foreach ($schedule as $day => $time)
                                    <ul class="list-unstyled mb-0">
                                        <li class="fw-bold">{{ $day }}</li>
                                        @foreach ($time as $slot)
                                            <li>{{ $slot }}</li>
                                        @endforeach
                                    </ul>
                                @endforeach
                            @else
                                <div class="schedule-item">No schedule available</div>
                            @endif
                        </td>
                        <td>
                           
                            <span class="status-badge">
                                {{ $appointment->coach->full_name }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge text-black">
                                {{ $appointment->Speciality->name }}
                            </span>
                        </td>
                        <td>
                            @php
                                $color = ''; // Default color
                                if ($appointment->status == 'pending') {
                                    $color = 'badge bg-warning';
                                } elseif ($appointment->status == 'passed') {
                                    $color = 'badge bg-success';
                                } elseif ($appointment->status == 'cancel') {
                                    $color = 'badge bg-danger';
                                }
                            @endphp
                            <span class="{{ $color }}">
                                {{ $appointment->status }}
                            </span>
                        </td>
                        <td>
                            <div class="row row-cols-2 g-2 text-center">
                                @if ($appointment->report_path)
                                    <div class="col">
                                        <form action="{{ route('coach-appointments.downloadReport', $appointment->id) }}" method="GET">
                                            @csrf
                                            <button class="btn btn-outline-primary btn-sm w-100">
                                                <i class="fas fa-cloud-download-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                                <div class="col">
                                    <form action="{{ route('appointment_details', $appointment->id) }}" method="GET">
                                        
                                        @csrf
                                        <button type="submit" class="btn btn-outline-secondary btn-sm w-100">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </form>
                                </div>
                                @if ($appointment->status == "pending")
                                    <div class="col">
                                        <form action="{{ route('appointment_edit',$appointment->id) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                                <i class="fas fa-archive"></i>
                                            </button>
                                        </form>
                                    </div>
                                    <div class="col">
                                        <form action="{{ route('appointments.update-appointment-status', [$appointment->id, 'passed']) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-outline-success btn-sm w-100">
                                                <i class="bi bi-check-circle-fill"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <style>
                .glass-card {
                    background-color: #fff;
                    border-radius: 8px;
                    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                    border: none;
                }
        </style>
@endsection