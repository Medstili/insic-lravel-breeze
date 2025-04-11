@extends('layouts.coach_app')
@section('content')
    <div class="appointments-container">
        <div class="page-header">
            <h1>Liste des Rendez-vous</h1>
        </div>
        
        <!-- Search Form -->
        <form action="{{ route('appointments_list', Auth::user()->id) }}" method="GET" class="search-form">
            <div class="search-grid">
                <div class="form-group">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Rechercher par Patient" title="Rechercher par nom" class="form-control">
                </div>
                <div class="form-group">
                    <select name="status" class="form-select" title="Rechercher par statut">
                        <option value="">Tous les Statuts</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="passed" {{ request('status') == 'passed' ? 'selected' : '' }}>Passé</option>
                        <option value="cancel" {{ request('status') == 'cancel' ? 'selected' : '' }}>Annulé</option>
                    </select>
                </div>
                <div class="form-group">
                    <input type="date" name="date" value="{{ request('date') }}" placeholder="Rechercher par date" title="Rechercher par date" class="form-control">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> <span class="btn-text">Rechercher</span>
                    </button>
                </div>
            </div>
        </form>
        
        <!-- Appointments Table (Desktop) -->
        <div class="appointments-table-container desktop-view">
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
                                        <div class="report-actions">
                                            <a href="{{ route('appointments.viewReport', $appointment->id) }}" target="_blank" class="action-btn view-btn" title="Voir le rapport">
                                                <i class="fas fa-file-alt"></i>
                                            </a>
                                            <a href="{{ route('coach-appointments.downloadReport', $appointment->id)}}" class="action-btn download-btn" title="Télécharger le rapport">
                                                <i class="fas fa-cloud-download-alt"></i>
                                            </a>
                                        </div>
                                    </td>
                                    @else
                                    <td><span class="no-report">Pas de Rapport</span></td>
                                    @endif
                                        
                                    <td>
                                        <div class="action-buttons">
                                            @if ($appointment->status == "pending")
                                                <form action="{{ route('appointment_edit',$appointment->id) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="action-btn cancel-btn" title="Annuler">
                                                        <i class="fas fa-archive"></i>
                                                    </button>
                                                </form>

                                                <form action="{{ route('coach-update-appointment-status', [$appointment->id, 'passed']) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="action-btn complete-btn" title="Marquer comme terminé">
                                                        <i class="bi bi-check-circle-fill"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            <a href="{{route('appointment_details', $appointment->id)  }}" class="action-btn view-details-btn" title="Voir les détails">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Mobile Cards View -->
        <div class="mobile-appointments">
            @if ($coachAppointments!=null)
                @foreach ($coachAppointments as $appointment)
                    <div class="appointment-card">
                        <div class="card-header">
                            <div class="patient-name">
                                @if ($appointment->patient->first_name==null)
                                {{ $appointment->patient->parent_first_name}} {{ $appointment->patient->parent_last_name }}
                                @else
                                    {{ $appointment->patient->first_name}} {{ $appointment->patient->last_name }}
                                @endif
                            </div>
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
                            <span class="status-badge {{$color}}">{{ $appointment->status }}</span>
                        </div>
                        
                        <div class="card-body">
                            <div class="info-row">
                                <div class="info-label">Date & Heure:</div>
                                <div class="info-value">
                                    @php
                                    $appointmentDate = json_decode($appointment->appointment_planning, true);
                                    @endphp
                                    @if(is_array($appointmentDate))
                                        @foreach ($appointmentDate as $date => $time)
                                            <span>{{ $date }} - </span>
                                            @foreach ($time as $slot)
                                                <span>{{ $slot }} </span>
                                            @endforeach
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            
                            <div class="info-row">
                                <div class="info-label">Rapport:</div>
                                <div class="info-value">
                                    @if ($appointment->report_path)
                                        <div class="report-actions">
                                            <a href="{{ route('appointments.viewReport', $appointment->id) }}" target="_blank" class="action-btn view-btn" title="Voir le rapport">
                                                <i class="fas fa-file-alt"></i>
                                            </a>
                                            <a href="{{ route('coach-appointments.downloadReport', $appointment->id)}}" class="action-btn download-btn" title="Télécharger le rapport">
                                                <i class="fas fa-cloud-download-alt"></i>
                                            </a>
                                        </div>
                                    @else
                                        <span class="no-report">Pas de Rapport</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-footer">
                            <div class="action-buttons">
                                @if ($appointment->status == "pending")
                                    <form action="{{ route('appointment_edit',$appointment->id) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="action-btn cancel-btn" title="Annuler">
                                            <i class="fas fa-archive"></i>
                                        </button>
                                    </form>

                                    <form action="{{ route('coach-update-appointment-status', [$appointment->id, 'passed']) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="action-btn complete-btn" title="Marquer comme terminé">
                                            <i class="bi bi-check-circle-fill"></i>
                                        </button>
                                    </form>
                                @endif
                                
                                <a href="{{route('appointment_details', $appointment->id)  }}" class="action-btn view-details-btn" title="Voir les détails">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
    
    
    <style>
        :root {
 
            --primary-color: #6366f1; 
            --secondary-color: #4f46e5;
            --success-color: #4cc9f0;
            --warning-color: #f72585;
            --danger-color: #e63946;
            --light-bg: #f8f9fa;
            --border-color: #e2e8f0;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --pending-color: #ffc107;
            --passed-color: #198754;
            --cancel-color: #dc3545;
            --shadow-sm: 0 2px 4px rgba(0,0,0,0.1);
            --shadow-md: 0 4px 6px rgba(0,0,0,0.1);
            --radius-sm: 6px;
            --radius-md: 8px;
            --radius-lg: 12px;
        }
        
        /* Base styles */
        .appointments-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1.5rem;
        }
        
        .page-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        
        .page-header h1 {
            font-size: 1.875rem;
            font-weight: 700;
            color: var(--text-dark);
        }
        
        /* Search form styles */
        .search-form {
            background-color: white;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .search-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
        }
        
        .form-control, .form-select {
            padding: 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-sm);
            font-size: 0.875rem;
            width: 100%;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        }
        
        .btn-primary {
            background: linear-gradient(195deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            padding: 0.75rem 1rem;
            border-radius: var(--radius-sm);
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
        }
        
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }
        
        /* Table styles */
        .appointments-table-container {
            background: white;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }
        
        .table-responsive {
            overflow-x: auto;
            max-height: 70vh;
        }
        
        .appointments-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .appointments-table thead th {
            background: linear-gradient(195deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 1rem;
            font-weight: 500;
            text-align: left;
            position: sticky;
            top: 0;
            z-index: 2;
        }
        
        .appointments-table tbody td {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }
        
        .appointments-table tbody tr:hover {
            background-color: rgba(67, 97, 238, 0.05);
        }
        
        /* Status badge styles */
        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
            text-transform: capitalize;
        }
        
        .pending {
            background-color: rgba(255, 193, 7, 0.2);
            color: var(--pending-color);
        }
        
        .passed {
            background-color: rgba(25, 135, 84, 0.2);
            color: var(--passed-color);
        }
        
        .cancel {
            background-color: rgba(220, 53, 69, 0.2);
            color: var(--cancel-color);
        }
        
        /* Action buttons styles */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
        }
        
        .action-btn {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: var(--radius-sm);
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            color: white;
            text-decoration: none;
        }
        
        .cancel-btn {
            background-color: var(--cancel-color);
        }
        
        .complete-btn {
            background-color: var(--passed-color);
        }
        
        .view-details-btn {
            background-color: var(--text-muted);
        }
        
        .view-btn {
            background-color: var(--primary-color);
        }
        
        .download-btn {
            background-color: var(--success-color);
        }
        
        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-sm);
        }
        
        .report-actions {
            display: flex;
            gap: 0.5rem;
        }
        
        .no-report {
            color: var(--text-muted);
            font-style: italic;
        }
        
        /* Mobile card styles */
        .mobile-appointments {
            display: none;
        }
        
        .appointment-card {
            background: white;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
            margin-bottom: 1rem;
            overflow: hidden;
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background: linear-gradient(195deg, var(--primary-color), var(--secondary-color));
            color: white;
        }
        
        .patient-name {
            font-weight: 600;
            font-size: 1rem;
        }
        
        .card-body {
            padding: 1rem;
        }
        
        .info-row {
            margin-bottom: 0.75rem;
        }
        
        .info-row:last-child {
            margin-bottom: 0;
        }
        
        .info-label {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.25rem;
        }
        
        .info-value {
            color: var(--text-dark);
        }
        
        .card-footer {
            padding: 1rem;
            background-color: var(--light-bg);
            border-top: 1px solid var(--border-color);
        }
        
        /* Responsive styles */
        @media (max-width: 1200px) {
            .appointments-container {
                padding: 1rem;
            }
        }
        
        @media (max-width: 991px) {
            .search-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 1.5rem;
            }
            
            .search-form {
                padding: 1rem;
            }
            
            .search-grid {
                grid-template-columns: 1fr;
            }
            
            .btn-text {
                display: none;
            }
            
            .desktop-view {
                display: none;
            }
            
            .mobile-appointments {
                display: block;
            }
            
            .action-buttons {
                justify-content: flex-end;
            }
        }
        
        @media (max-width: 480px) {
            .appointments-container {
                padding: 0.75rem;
            }
            
            .page-header h1 {
                font-size: 1.25rem;
            }
            
            .card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
            
            .status-badge {
                align-self: flex-start;
            }
        }
    </style>
@endsection
