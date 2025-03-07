@extends('layouts.app')
@section('content')
<div class="container py-5">

    @if(session('success'))
        <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
        <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
        </symbol>
        </svg>

        <div class="alert alert-success d-flex align-items-center" role="alert">
            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>
            <div>
            {{ session('success') }}
            </div>
        </div>


    @endif

    <div class="dashboard-header glass-card p-4 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="display-6 mb-0">Coachs List</h1>
            <form action="{{ route('user.create')}}" method="get">
                @csrf
                <button class="btn btn-secondary btn-lg" >
                    <i class="fas fa-plus me-2"></i>Add New Coach
                </button>
            </form>
          
        </div>
    </div>

        <!-- Search Form -->
    <form action="{{ route('user.index') }}" method="GET" class="mb-4 glass-card p-4">
        <div class="row g-2">
            <div class="col-md-6">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by Coach" class="form-control bg-transparent">
            </div>
            <div class="col-md-2">
                <select name="availability" class="form-select bg-transparent">
                    <option value="">All Coaches</option>
                    <option value="1" {{ request('availability') == 'Available' ? 'selected' : '' }}>Available</option>
                    <option value="0" {{ request('availability') == 'Not Available' ? 'selected' : '' }}>Not Available</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="specialities" class="form-select bg-transparent">
                    <option value="">All specialities</option>
                    @foreach($specialities as $speciality)
                    <option value="{{ $speciality->id }}" {{ request('specialities') == $speciality->id ? 'selected' : '' }}>{{ $speciality->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-2"></i>Search
                </button>
            </div>
        </div>
    </form>
    <!-- Coaches Table -->
    <div class="glass-card p-4 mb-4">
        <table class="table table-hover align-middle">
            <thead class="thead-light">
                <tr>
                    <th><i class="fas fa-id-badge text-center"></i> ID</th>
                    <th><i class="fas fa-user text-center"></i> Coach</th>
                    <th><i class="fas fa-star text-center"></i> Specialty</th>
                    <th><i class="fas fa-clock text-center"></i> Availability</th>
                    <!-- <th><i class="fas fa-shield-alt text-center"></i> Permission Allowness</th> -->
                    <th><i class="fas fa-cogs text-center"></i> Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr class="coach-card">
                    <td>{{ $user->id }}</td>
                    <td>
                        <div class="">
                     
                            <div>
                                <div class="fw-bold">{{ $user->full_name }}</div>
                                <div class="text-muted small">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-primary">{{ $user->speciality->name }}</span>
                    </td>
                    <td>
                        <span class="status-badge {{ $user->is_available ? 'available' : 'unavailable' }}">
                            {{ $user->is_available ? 'Available' : 'Busy' }}
                        </span>
                    </td>
                    <!-- <td class="text-center">
                        <span class="badge {{ $user->is_admin ? 'bg-warning' : 'bg-success' }}">
                            {{ $user->is_admin ? 'Allow' : 'Not Allow' }}
                        </span>
                    </td> -->
                    <td>
                        <a href="{{ route('user.show', $user->id) }}" class="btn btn-sm btn-outline-secondary me-2">
                           <i class="fas fa-eye"></i>
                        </a>
                        <form action="{{ route('user.destroy', $user->id) }}" method="POST" onsubmit="return cancellationConfirmation()" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger me-2">
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

<!-- Custom Styles to Match Sidebar/Navbar Style -->
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
    /* Glass Card styling */
    .glass-card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        border: none;
    }
    /* Dashboard Header */
    .dashboard-header {
        padding: 20px;
        margin-bottom: 20px;
        text-align: center;
        background-color: #fff;
    }
    /* Table Styling */
    .table.table-hover {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    /* Coach Card row hover effect */
    .coach-card {
        transition: transform 0.3s ease;
        cursor: pointer;
    }
    .coach-card:hover {
        transform: translateY(-2px);
    }
    /* Status Badges */
    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.8rem;
    }
    .available {
        background-color: rgba(72, 187, 120, 0.2);
        color: #48bb78;
    }
    .unavailable {
        background-color: rgba(245, 101, 101, 0.2);
        color: #f56565;
    }
    /* Action Button */
    .action-btn {
        transition: all 0.3s ease;
        border-radius: 8px;
    }
    /* Timing Card */
    .timing-card {
        background-color: #f9f9f9;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        border: 1px solid #ddd;
    }
    /* Modal adjustments
    .modal-content.glass-card {
        background-color: #fff;
        color: #333;
    }
    .modal-header {
        border-bottom: none;
    } */
</style>
<script>
    function cancellationConfirmation() {
            return confirm('are you sure you want to delete this coach ?')
    }
</script>
@endsection
