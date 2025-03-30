@extends('layouts.coach_app')
@section('content')
    <div>
        <h1 class="text-center w-100 fs-1 fw-bold mb-4 mt-4">Liste des Rendez-vous</h1>
    </div>
    <form action="{{ route('appointments_list', Auth::user()->id) }}" method="GET" class="mb-4 glass-card p-4">
        <div class="row g-2">
            <div class="col-md-3">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Rechercher par Patient" title="Rechercher par nom" class="form-control">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select" title="Rechercher par statut">
                    <option value="">Tous les Statuts</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="passed" {{ request('status') == 'passed' ? 'selected' : '' }}>Passé</option>
                    <option value="cancel" {{ request('status') == 'cancel' ? 'selected' : '' }}>Annulé</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="date" name="date" value="{{ request('date') }}" placeholder="Rechercher par date" title="Rechercher par date" class="form-control">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-2"></i>Rechercher
                </button>
            </div>
        </div>
    </form>
    <!-- Appointments Table -->
    <div id="appointments-table" class="data-table-container">
        <div>
            <div class="table-responsive">   
                <table class="appointments-table">
                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Date &amp; Heure</th>
                            <th>Statut</th>
                            <th>Rapport</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($coachAppointments!=null)
                            @foreach ($coachAppointments as $appointment)
                                <tr>
                                    <td>
                                        @if ($appointment->patient->first_name==null)
                                        {{ $appointment->patient->parent_first_name}} {{ $appointment->patient->parent_last_name }}
                                        @else
                                            {{ $appointment->patient->first_name}} {{ $appointment->patient->last_name }}
                                        @endif
                                    </td>
                                
                                    @php
                                    $appointmentDate = json_decode($appointment->appointment_planning, true);
                                    @endphp
                                    <td>
                                        @if(is_array($appointmentDate))
                                            @foreach ($appointmentDate as $date => $time)
                                                <span>{{ $date }} - </span>
                                                @foreach ($time as $slot)
                                                    <span>{{ $slot }} </span>
                                                @endforeach
                                            @endforeach
                                        @endif
                                    </td>
                                    @php
                                        $color = '';
                                        if ($appointment->status == 'pending') {
                                            $color = 'pending';
                                        } elseif ($appointment->status == 'passed') {
                                            $color = 'passed';
                                        } elseif ($appointment->status == 'cancel') {
                                            $color = 'cancel';
                                        }
                                    @endphp
                                    <td><span class="status-badge {{$color}}">{{ $appointment->status }}</span></td>

                                    @if ($appointment->report_path)
                                    <td>
                                        <a href="{{ route('appointments.viewReport', $appointment->id) }}" target="_blank" class="action-btn">
                                            <i class="fas fa-file-alt"></i>
                                        </a>
                                        <a href="{{ route('coach-appointments.downloadReport', $appointment->id)}}" class="action-btn">
                                            <i class="fas fa-cloud-download-alt"></i>
                                        </a>
                                    </td>
                                    @else
                                    <td>Pas de Rapport</td>
                                    @endif
                                        
                                    
                                    <td class="text-center">
                                    <div class="row table-row row-cols-3 text-center">
                                        @if ($appointment->status == "pending")
                                            <div class="col">
                                                <form action="{{ route('appointment_edit',$appointment->id) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm ">
                                                        <i class="fas fa-archive"></i>
                                                    </button>
                                                </form>

                                            </div>
                                            <div class="col">
                                                <form action="{{ route('coach-update-appointment-status', [$appointment->id, 'passed']) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-outline-success btn-sm">
                                                        <i class="bi bi-check-circle-fill"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                        <div class="col">
                                            <a href="{{route('appointment_details', $appointment->id)  }}" class="btn btn-sm btn-outline-secondary ">
                                                <i class="fas fa-eye"></i>
                                            </a> 

                                        </div>
                                    </div>

                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <style>
            .table-row {
                --bs-gutter-x: 0;
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
                /* overflow: hidden; */
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            }
            .appointments-table {
                width: 100%;
                border-collapse: collapse;
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
    </style>
@endsection