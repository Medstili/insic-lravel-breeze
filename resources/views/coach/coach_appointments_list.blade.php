@extends('layouts.coach_app')

@section('header')
<div class="flex items-center justify-between">
    <div>
        <h1 class="page-title">My Appointments</h1>
        <p class="page-subtitle">Manage and track your patient appointments</p>
    </div>
    <div class="flex items-center gap-4">
        <a href="{{ route('appointment.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            New Appointment
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Search and Filters -->
    <div class="card">
        <div class="card-body">
            <form action="{{ route('appointments_list', Auth::user()->id) }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="form-group">
                        <label for="search" class="form-label">Search Patient</label>
                        <input type="text" 
                               id="search" 
                               name="q" 
                               value="{{ request('q') }}" 
                               placeholder="Enter patient name..." 
                               class="form-input">
        </div>
        
                <div class="form-group">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" name="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="passed" {{ request('status') == 'passed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancel" {{ request('status') == 'cancel' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                    
                <div class="form-group">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" 
                               id="date" 
                               name="date" 
                               value="{{ request('date') }}" 
                               class="form-input">
                </div>
                    
                <div class="form-group">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-full">
                            <i class="fas fa-search"></i>
                            Search
                    </button>
                </div>
            </div>
        </form>
        </div>
    </div>

    <!-- Appointments Table -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Appointments List</h2>
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-500">{{ $coachAppointments->total() }} appointments</span>
            </div>
        </div>
        
        <div class="card-body p-0">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Date & Time</th>
                            <th>Status</th>
                            <th>Report</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($coachAppointments as $appointment)
                        <tr class="hover:bg-gray-50">
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-primary-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">
                                            @if ($appointment->patient->first_name)
                                                {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}
                                        @else
                                                {{ $appointment->patient->parent_first_name }} {{ $appointment->patient->parent_last_name }}
                                        @endif
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            {{ $appointment->patient->email ?? 'No email' }}
                                        </p>
                                    </div>
                                </div>
                                    </td>
                                
                            <td>
                                    @php
                                    $appointmentDate = json_decode($appointment->appointment_planning, true);
                                    @endphp
                                <div class="text-sm">
                                        @if(is_array($appointmentDate))
                                            @foreach ($appointmentDate as $date => $time)
                                            <div class="font-medium text-gray-900">
                                                {{ \Carbon\Carbon::parse($date)->format('M d, Y') }}
                                            </div>
                                            <div class="text-gray-600">
                                                @foreach ($time as $slot)
                                                    <span class="inline-block bg-gray-100 px-2 py-1 rounded text-xs mr-1 mb-1">
                                                        {{ $slot }}
                                                    </span>
                                                @endforeach
                                            </div>
                                            @endforeach
                                        @endif
                                </div>
                                    </td>
                            
                            <td>
                                @php
                                    $statusColor = '';
                                    $statusText = '';
                                    switch($appointment->status) {
                                        case 'pending':
                                            $statusColor = 'warning';
                                            $statusText = 'Pending';
                                            break;
                                        case 'passed':
                                            $statusColor = 'success';
                                            $statusText = 'Completed';
                                            break;
                                        case 'cancel':
                                            $statusColor = 'error';
                                            $statusText = 'Cancelled';
                                            break;
                                        }
                                    @endphp
                                <span class="badge badge-{{ $statusColor }}">{{ $statusText }}</span>
                            </td>

                            <td>
                                    @if ($appointment->report_path)
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('appointments.viewReport', $appointment->id) }}" 
                                           target="_blank" 
                                           class="btn btn-outline btn-sm"
                                           title="View Report">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('coach-appointments.downloadReport', $appointment->id) }}" 
                                           class="btn btn-outline btn-sm"
                                           title="Download Report">
                                            <i class="fas fa-download"></i>
                                            </a>
                                        </div>
                                    @else
                                    <span class="text-gray-400 text-sm">No Report</span>
                                    @endif
                            </td>
                                        
                                    <td>
                                <div class="flex items-center gap-2">
                                            @if ($appointment->status == "pending")
                                        <a href="{{ route('appointment.edit', $appointment->id) }}" 
                                           class="btn btn-warning btn-sm"
                                           title="Edit Appointment">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button onclick="cancelAppointment({{ $appointment->id }})" 
                                                class="btn btn-error btn-sm"
                                                title="Cancel Appointment">
                                            <i class="fas fa-times"></i>
                                                    </button>
                                            @endif
                                            
                                    <a href="{{ route('appointment.show', $appointment->id) }}" 
                                       class="btn btn-primary btn-sm"
                                       title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-12">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-calendar-times text-4xl text-gray-300 mb-4"></i>
                                    <p class="text-gray-500 text-lg mb-2">No appointments found</p>
                                    <p class="text-gray-400">Try adjusting your search criteria</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        @if($coachAppointments->hasPages())
        <div class="card-footer">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-500">
                    Showing {{ $coachAppointments->firstItem() }} to {{ $coachAppointments->lastItem() }} 
                    of {{ $coachAppointments->total() }} results
                </div>
                <div class="flex items-center gap-2">
                    {{ $coachAppointments->links() }}
                </div>
                            </div>
                        </div>
                                    @endif
                                </div>
                            </div>
                            
<!-- Cancel Appointment Modal -->
<div id="cancelModal" class="modal-overlay hidden">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="text-lg font-semibold text-gray-900">Cancel Appointment</h3>
            <button onclick="closeCancelModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
                                        </div>
        <div class="modal-body">
            <p class="text-gray-600 mb-4">
                Are you sure you want to cancel this appointment? This action cannot be undone.
            </p>
            <form id="cancelForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="form-group">
                    <label for="cancellation_reason" class="form-label">Cancellation Reason (Optional)</label>
                    <textarea id="cancellation_reason" 
                              name="cancellation_reason" 
                              class="form-textarea" 
                              rows="3"
                              placeholder="Please provide a reason for cancellation..."></textarea>
                                </div>
            </form>
                            </div>
        <div class="modal-footer">
            <button onclick="closeCancelModal()" class="btn btn-secondary">
                Cancel
                                        </button>
            <button onclick="confirmCancellation()" class="btn btn-error">
                <i class="fas fa-times"></i>
                Confirm Cancellation
                                        </button>
                    </div>
        </div>
    </div>
    
<script>
function cancelAppointment(appointmentId) {
    const modal = document.getElementById('cancelModal');
    const form = document.getElementById('cancelForm');
    
    form.action = `/appointment/${appointmentId}`;
    modal.classList.remove('hidden');
}

function closeCancelModal() {
    const modal = document.getElementById('cancelModal');
    modal.classList.add('hidden');
    document.getElementById('cancellation_reason').value = '';
}

function confirmCancellation() {
    document.getElementById('cancelForm').submit();
}

// Close modal when clicking outside
document.getElementById('cancelModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeCancelModal();
    }
});

// Auto-submit form when pressing Enter in search
document.getElementById('search').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        this.closest('form').submit();
    }
});

// Real-time search (optional)
let searchTimeout;
document.getElementById('search').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        if (this.value.length >= 3 || this.value.length === 0) {
            this.closest('form').submit();
        }
    }, 500);
});
</script>
@endsection
