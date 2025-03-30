@extends('layouts.app')
@section('content')

<style>
    :root {
        --primary: #6366f1;
        --secondary: #4f46e5;
        --light-bg: #f8fafc;
        --dark-text: #1e293b;
        --success: #10b981;
        --danger: #ef4444;
    }

    .coaches-container {
        padding: 2rem;
        background: var(--light-bg);
    }

    .header-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        margin-bottom: 2rem;
    }

    .header-card h1 {
        font-size: 1.75rem;
        font-weight: 600;
        color: var(--dark-text);
    }

    .search-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        margin-bottom: 2rem;
    }

    .coaches-table {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .coaches-table table {
        width: 100%;
        border-collapse: collapse;
    }

    .coaches-table thead {
        background: linear-gradient(195deg, var(--primary), var(--secondary));
        color: white;
    }

    .coaches-table th {
        padding: 1rem;
        text-align: left;
        font-weight: 500;
    }

    .coaches-table td {
        padding: 1rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .coaches-table tr:hover {
        background: rgba(99, 102, 241, 0.05);
    }

    .status-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .status-badge.available {
        background: rgba(16, 185, 129, 0.1);
        color: var(--success);
    }

    .status-badge.unavailable {
        background: rgba(239, 68, 68, 0.1);
        color: var(--danger);
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .btn {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-primary {
        background: var(--primary);
        color: white;
        border: none;
    }

    .btn-primary:hover {
        background: var(--secondary);
    }

    .btn-secondary {
        background: rgba(99, 102, 241, 0.1);
        color: var(--primary);
        border: none;
    }

    .btn-secondary:hover {
        background: rgba(99, 102, 241, 0.2);
    }

    .btn-danger {
        background: rgba(239, 68, 68, 0.1);
        color: var(--danger);
        border: none;
    }

    .btn-danger:hover {
        background: rgba(239, 68, 68, 0.2);
    }

    .form-control {
        /* padding: 0.75rem 1rem; */
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        width: 100%;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }

    .alert-success {
        background: rgba(16, 185, 129, 0.1);
        color: var(--success);
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 2rem;
    }

    .coach-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
    }

    .coach-img-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--primary-color);
    }
</style>

<div class="coaches-container">
    <!-- Success Message -->
    @if(session('success'))
    <div class="alert-success">
        <i class="fas fa-check-circle mr-2"></i>
        {{ session('success') }}
    </div>
    @endif

    <!-- Header Section -->
    <div class="header-card">
        <div class="flex justify-between items-center">
            <h1>Gestion des entraîneurs</h1>
            <a href="{{ route('user.create') }}" class="btn btn-primary">
                <i class="fas fa-plus mr-2"></i>Ajouter un nouvel entraîneur
            </a>
        </div>
    </div>

    <!-- Search Form -->
    <div class="search-card">
        <form action="{{ route('user.index') }}" method="GET" class="container">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 row">
                <input type="text" name="q" value="{{ request('q') }}" 
                       class="form-control col" placeholder="Rechercher des entraîneurs...">
                
                <select name="availability" class="form-control col">
                    <option value="">Toutes les disponibilités</option>
                    <option value="1" {{ request('availability') == '1' ? 'selected' : '' }}>Disponible</option>
                    <option value="0" {{ request('availability') == '0' ? 'selected' : '' }}>Occupé</option>
                </select>

                <select name="specialities" class="form-control col">
                    <option value="">Toutes les spécialités</option>
                    @foreach($specialities as $speciality)
                    <option value="{{ $speciality->id }}" {{ request('specialities') == $speciality->id ? 'selected' : '' }}>
                        {{ $speciality->name }}
                    </option>
                    @endforeach
                </select>

                <button type="submit" class="btn btn-secondary col">
                    <i class="fas fa-search mr-2"></i>Rechercher
                </button>
            </div>
        </form>
    </div>

    <!-- Coaches Table -->
    <div class="coaches-table">
        <table>
            <thead>
                <tr>
                    <th colspan="2">ID</th>
                    <th colspan="3">Entraîneur</th>
                    <th colspan="2">Spécialité</th>
                    <th colspan="2">Disponibilité</th>
                    <th colspan="2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td colspan="2">{{ $user->id }}</td>
                    <td colspan="3" >
                        <div class="items-center flex">
                                @if ($user->image_path)
                                    <div class="coach-avatar-initials me-2">
                                        <img src="{{ asset('storage/' . $user->image_path) }}" alt="Image Previe" class="coach-img-avatar">
                                        
                                    </div>
                                @else
                                    <div class="coach-avatar me-2">
                                        {{ strtoupper(substr($user->full_name, 0, 1)) }}
                                    </div>
                                
                                @endif
                            <div>
                                <div class="font-medium">{{ $user->full_name }}</div>
                                <div class="text-sm text-gray-600">{{ $user->email }}</div>
                            </div>   
                        </div>
                    </td>
                    <td colspan="2">
                        <span class="badge bg-primary-light text-black fs-6">
                            {{ $user->speciality->name }}
                        </span>
                    </td>
                    <td colspan="2">
                        <span class="status-badge {{ $user->is_available ? 'available' : 'unavailable' }}">
                            {{ $user->is_available ? 'Disponible' : 'Occupé' }}
                        </span>
                    </td>
                    
                    <td colspan="2">
                        <div class="action-buttons">
                            <a href="{{ route('user.show', $user->id) }}" 
                               class="btn btn-secondary">
                               <i class="fas fa-eye"></i>
                            </a>
                            <form action="{{ route('user.destroy', $user->id) }}" method="POST" 
                                  onsubmit="return cancellationConfirmation()">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    function cancellationConfirmation() {
        return confirm('Êtes-vous sûr de vouloir supprimer cet entraîneur ?');
    }
</script>
@endsection