@extends('layouts.app')
@section('content')
<div class="coach-profile">
    <!-- Coach Header -->

    <div class="coach-header">
        <div class="coach-info">
            <img src="https://i.pravatar.cc/150" alt="Coach" class="coach-avatar">
            <div class="coach-details">
                <h1>{{$coach->full_name}}</h1>
                <div class="coach-tags">
                    <span class="tag speciality">{{$coach->speciality->name}} Coach</span>
                    <span class="tag availability {{$coach->is_available ? 'available' : 'busy'}}">
                        {{$coach->is_available == 1 ? 'Available' : 'Busy'}}
                    </span>
                </div>
                <div class="coach-contact">
                    <p><i class="fas fa-envelope"></i> {{$coach->email}}</p>
                    <p><i class="fas fa-phone"></i> {{$coach->phone}}</p>
                </div>
            </div>
        </div>
        <button class="action-btn" data-bs-toggle="modal" data-bs-target="#addCoachModal">
            <i class="fas fa-edit"></i> Edit Profile
        </button>
    </div>

    <!-- Calendar Section -->
    <div class="calendar-container">
        <div id="calendar"></div>
    </div>

    <!-- Table Switcher -->
    <div class="table-switcher">
        <button class="action-btn" id="appointments_id" onclick="showTable('appointments')">
            <i class="fas fa-calendar-check"></i> Appointments
        </button>
        <button class="action-btn" id="patients_id" onclick="showTable('patients')">
            <i class="fas fa-users"></i> Patients
        </button>
    </div>

    <!-- Appointments Table -->
    <div id="appointments">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Date &amp; Time</th>
                        <th>Status</th>
                        <th>Report</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($coachAppointments as $appointment)
                
                    <tr>
                        <td>{{ $appointment->patient->full_name }}</td>
                      
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
                            <a href="{{ route('appointments.downloadReport', $appointment->id) }}" class="action-btn">
                                <i class="fas fa-cloud-download-alt"></i>
                            </a>
                        </td>
                        @else
                        <td>No Report</td>
                        @endif
                        <td>
                            <form action="{{ route('appointment.show', $appointment->id) }}" method="get">
                                @csrf
                                <button class="action-btn"><i class="fas fa-eye"></i> Details</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                    <!-- More rows if needed -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Patients Table -->
    <div id="patients" style="display: none;">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Age</th>
                        <th>Gender</th>
                        <th>Contact</th>
                        <th>Status</th>
                        <th>Report</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($coachAppointments as $appointment)
                    <tr>
                        <td>{{ $appointment->patient->full_name}}</td>
                        <td>{{ $appointment->patient->age  }}</td>
                        <td>{{ $appointment->patient->gender =='M'?'Male':'Female'}}</td>
                        <td>{{ $appointment->patient->phone }}</td>
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
                            <a href="{{ route('appointments.downloadReport', $appointment->id) }}" class="action-btn">
                                <i class="fas fa-cloud-download-alt"></i>
                            </a>
                        </td>
                        @else
                        <td>No Report</td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Enhanced Edit Coach Modal -->
<div class="modal fade" id="addCoachModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content glass-card">
            <div class="modal-header">
                <h3 class="modal-title">🌟 Update Coach Profile</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('user.update', $coach->id) }}" onsubmit="coachUpdatePlanning()" method="POST" class="row">
                    @csrf
                    @method('put')
                    <!-- Left Column -->
                    <div class="form-column">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="fullName" name="full_name" value="{{$coach->full_name}}" required>
                            <label for="fullName"><i class="fas fa-user"></i> Full Name</label>
                        </div>
                        <div class="form-floating">
                            <input type="number" class="form-control" id="tel" name="tel" value="{{$coach->phone}}" required>
                            <label for="tel"><i class="fas fa-phone"></i> Phone Number</label>
                        </div>
                        <div class="form-floating">
                            <input type="email" class="form-control" id="email" name="email" value="{{$coach->email}}" required>
                            <label for="email"><i class="fas fa-envelope"></i> Email</label>
                        </div>
                        <!-- <div class="form-floating">
                            <input type="password" class="form-control" id="password" name="password" value="{{$coach->password}}" required>
                            <label for="password"><i class="fas fa-lock"></i> Password</label>
                        </div> -->
                    </div>
                    <!-- Right Column -->
                    <div class="form-column">
                        <div class="card">
                            <div class="card-body">
                                <h5><i class="fas fa-dumbbell"></i> Specialization</h5>
                                <select class="form-select" id="specialist" name="speciality_id" required>
                                    <option value="">Select Specialization</option>
                                    @foreach ($specialities as $speciality)
                                        <option value="{{$speciality->id}}" {{$coach->speciality->id == $speciality->id ? 'selected' : ''}}>{{$speciality->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h5><i class="fas fa-calendar-check"></i> Availability</h5>
                                <select class="form-select" name="is_available">
                                    <option value="1" {{$coach->is_available == 1 ? 'selected' : ''}}>Available</option>
                                    <option value="0" {{$coach->is_available == 0 ? 'selected' : ''}}>Unavailable</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="planning" name="planning">
                        <button type="submit" class="action-btn modal-submit">
                            <i class="fas fa-save"></i> Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Custom Styles -->
<style>
    :root {
        --primary-color: #3498db;
        --secondary-color: #2c3e50;
        --light-bg: #ecf0f1;
    }
    body {
        background-color: var(--light-bg);
        color: #333;
    }
    /* Glass Card: used for panels, modals, and main containers */
    .glass-card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        padding: 20px;
        margin-bottom: 20px;
    }
    /* Coach Profile */
    .coach-profile {
        margin: 20px;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    /* Header */
    .coach-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #ddd;
        padding-bottom: 20px;
        margin-bottom: 20px;
    }
    .coach-info {
        display: flex;
        align-items: center;
        gap: 20px;
    }
    .coach-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--primary-color);
    }
    .coach-details h1 {
        font-size: 2rem;
        margin: 0 0 10px;
        color: var(--secondary-color);
    }
    .coach-tags {
        margin-bottom: 10px;
    }
    .coach-tags .tag {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.9rem;
        margin-right: 10px;
    }
    .coach-tags .speciality {
        background-color: #f0f0f0;
        color: var(--secondary-color);
    }
    .coach-tags .availability.available {
        background-color: rgba(72, 187, 120, 0.2);
        color: #48bb78;
    }
    .coach-tags .availability.busy {
        background-color: rgba(245, 101, 101, 0.2);
        color: #f56565;
    }
    .coach-contact p {
        margin: 5px 0;
        font-size: 0.9rem;
        color: #777;
    }
    /* Action Button */
    .action-btn {
        background-color: var(--primary-color);
        color: #fff;
        border: none;
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 0.9rem;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    .action-btn:hover {
        background-color: #2980b9;
    }
    /* Calendar Container */
    .calendar-container {
        background-color: #fff;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
       /* Event Styling with Status Colors */
    .fc-event {
        border: none !important;
        border-radius: 8px !important;
        padding: 8px 12px !important;
        margin: 4px !important;
        font-weight: 500 !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        box-shadow: 0 3px 6px rgba(0,0,0,0.16) !important;
        position: relative;
        overflow: hidden;
    }

    .fc-event::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
    }

    /* Pending - Amber */
    .fc-event.pending {
        background: rgba(255, 167, 38, 0.15) !important;
        color: #FFA726 !important;
    }
    .fc-event.pending::before {
        background: #FFA726;
    }

    /* Passed - Emerald */
    .fc-event.passed {
        background: rgba(102, 187, 106, 0.15) !important;
        color: #66BB6A !important;
    }
    .fc-event.passed::before {
        background: #66BB6A;
    }

    /* Canceled - Rose */
    .fc-event.cancel {
        background: rgba(239, 83, 80, 0.15) !important;
        color: #EF5350 !important;
    }
    .fc-event.cancel::before {
        background: #EF5350;
    }

    /* Hover Effects */
    .fc-event:hover {
        transform: translateY(-2px) scale(1.02);
        box-shadow: 0 6px 12px rgba(0,0,0,0.25) !important;
    }

    /* Time Styling */
    .fc-event-time {
        font-weight: 300;
        opacity: 0.8;
        margin-right: 8px;
    }
    /* Table Switcher */
    .table-switcher {
        display: flex;
        gap: 10px;
        margin: 20px 0;
    }
    /* Tables */
    .table-wrapper {
        overflow-x: auto;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        background-color: #fff;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    table thead {
        background-color: #f4f4f4;
    }
    table th, table td {
        padding: 12px 15px;
        border: 1px solid #ddd;
        text-align: left;
        font-size: 0.9rem;
    }
    table tbody tr:hover {
        background-color: #f9f9f9;
    }
    /* Status Badges */
    .status-badge {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        color: #fff;
    }
    .status-badge.pending {
        background-color: #FFA726;
    }
    .status-badge.passed {
        background-color: #66BB6A;
    }
    .status-badge.cancel {
        background-color: #EF5350;
    }
    /* FullCalendar Customizations */
    #calendar {
        color: var(--secondary-color);
    }
    .fc-toolbar, .fc-button {
        background-color: var(--secondary-color) !important;
        color: #fff !important;
        border: none;
    }
    .fc-button:hover {
        background-color: #2980b9 !important;
    }
    .fc-event {
        background: #34495e !important;
        color: #fff !important;
        border: none !important;
        border-radius: 4px !important;
    }
    .fc-event:hover {
        transform: translateY(-2px) scale(1.02);
        box-shadow: 0 6px 12px rgba(0,0,0,0.25) !important;
    }
    /* Modal Adjustments */
    .modal-content.glass-card {
        background-color: #fff;
        color: #333;
    }
    .modal-header {
        border-bottom: none;
    }
    .form-column {
        flex: 1;
        padding: 10px;
    }
    .form-column .form-floating {
        margin-bottom: 15px;
    }
    .modal-footer {
        text-align: center;
        margin-top: 20px;
    }
</style>

<script>
    function showTable(id) {
        var appointments = document.getElementById('appointments');
        var patients = document.getElementById('patients');
        var appointmentsBtn = document.getElementById('appointments_id');
        var patientsBtn = document.getElementById('patients_id');
        if (id === 'appointments') {
            appointments.style.display = 'block';
            patients.style.display = 'none';
       
        } else {
            appointments.style.display = 'none';
            patients.style.display = 'block';

        }
    }
document.addEventListener('DOMContentLoaded', function() {
    var events = <?php
        $allEvents = [];
        foreach ($coachAppointments as $app) {
            // dd($app);
            $statusClass = '';
            switch ($app->status) {
                case 'passed':
                    $statusClass = 'passed';
                    break;
                case 'pending':
                    $statusClass = 'pending';
                    break;
                case 'cancel':
                    $statusClass = 'cancel';
                    break;
            }
            $appointment_date = json_decode($app->appointment_planning, true);
            // $patient = App\Models\Patient::find($app->patient_id);
            foreach ($appointment_date as $date => $time) {
                // dd($app);
                // echo 'kkk';
                $startTime = $time['startTime'] . "00";
                $endTime = $time['endTime'] . "00";
                $allEvents[] = [
                    'id'    => $app->id,
                    // 'title'=> $time['startTime'] . " : 00",
                    'title' => $app->patient->full_name,
                    'start' => $date . 'T' . $startTime,
                    'end'   => $date . 'T' . $endTime,
                    'className' => $statusClass,
                    'extendedProps' => [
                        'status' => '{{ $app->status }}'
                    ]
                ];
            }
        }
        echo json_encode($allEvents);
    ?>;
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: events,
        eventContent: function(arg) {
            return {
                html: `<div class="fc-event-inner">
                        <span class="fc-event-time">${arg.timeText}</span>
                        <span class="fc-event-title">${arg.event.title}</span>
                       </div>`
            };
        },
        eventDidMount: function(arg) {
            arg.el.addEventListener('mouseenter', function() {
                arg.el.style.zIndex = '999';
            });
            arg.el.addEventListener('mouseleave', function() {
                arg.el.style.zIndex = 'auto';
            });
        },
        eventClick: function(info) {
            var detailUrl = "{{ route('appointment.show', ':id') }}";
            detailUrl = detailUrl.replace(':id', info.event.id);
            window.location.href = detailUrl;
        }
    });
    calendar.render();
});
</script>
@endsection
