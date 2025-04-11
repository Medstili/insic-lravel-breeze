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

    /* Responsive container */
    .coaches-container {
        padding: 1rem;
        background: var(--light-bg);
        max-width: 100%;
        overflow-x: hidden;
    }

    .header-card {
        background: white;
        border-radius: 12px;
        padding: 1.25rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        margin-bottom: 1.5rem;
    }

    .header-card h1 {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--dark-text);
        margin-bottom: 1rem;
    }

    /* Flex container for header */
    .header-flex {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .search-card {
        background: white;
        border-radius: 12px;
        padding: 1.25rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        margin-bottom: 1.5rem;
    }

    /* Responsive search form */
    .search-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1rem;
    }

    .coaches-table {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        overflow-x: auto; /* Allow horizontal scrolling on small screens */
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
        white-space: nowrap;
    }

    .coaches-table td {
        padding: 1rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .coaches-table tr:hover {
        background: rgba(99, 102, 241, 0.05);
    }

    /* Coach info flex container */
    .coach-info-flex {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .coach-info-text {
        display: flex;
        flex-direction: column;
    }

    .status-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 500;
        white-space: nowrap;
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
        justify-content: center;
        gap: 0.5rem;
        text-decoration: none;
        cursor: pointer;
        min-height: 44px; /* Better touch target */
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
        padding: 0.75rem 1rem;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        width: 100%;
        transition: all 0.3s ease;
        min-height: 44px; /* Better touch target */
        font-size: 16px; /* Prevent zoom on iOS */
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
        margin-bottom: 1.5rem;
    }

    .coach-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .coach-img-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--primary);
        flex-shrink: 0;
    }

    /* Bootstrap display utilities */
    .d-none {
        display: none !important;
    }
    
    .d-block {
        display: block !important;
    }
    
    @media (min-width: 768px) {
        .d-md-none {
            display: none !important;
        }
        
        .d-md-block {
            display: block !important;
        }
    }

    /* Mobile-specific table transformations */
    @media (max-width: 767px) {
        .coaches-table-mobile {
            display: block;
            width: 100%;
        }
        
        .coaches-table-mobile thead {
            display: none; /* Hide table header on mobile */
        }
        
        .coaches-table-mobile tbody {
            display: block;
            width: 100%;
        }
        
        .coaches-table-mobile tr {
            display: block;
            margin-bottom: 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 1rem;
            background-color: white;
        }
        
        .coaches-table-mobile td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .coaches-table-mobile td:last-child {
            border-bottom: none;
        }
        
        .coaches-table-mobile td:before {
            content: attr(data-label);
            font-weight: 600;
            margin-right: 1rem;
            flex: 0 0 40%;
        }
        
        /* Special handling for coach info cell */
        .coaches-table-mobile td.coach-info-cell {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .coaches-table-mobile td.coach-info-cell:before {
            margin-bottom: 0.5rem;
        }
        
        /* Center action buttons on mobile */
        .coaches-table-mobile .action-buttons {
            margin-left: auto;
        }
        
        /* Improve touch targets on mobile */
        .coaches-table-mobile .btn {
            min-width: 44px;
        }
    }

    /* Extra small devices (phones, less than 576px) */
    @media (max-width: 575.98px) {
        .coaches-container {
            padding: 0.75rem;
        }
        
        .header-card, .search-card {
            padding: 1rem;
            margin-bottom: 1rem;
        }
        
        .header-card h1 {
            font-size: 1.3rem;
        }
        
        .btn {
            padding: 0.5rem 0.75rem;
            font-size: 0.9rem;
        }
        
        .coach-avatar, .coach-img-avatar {
            width: 36px;
            height: 36px;
        }
        
        .coach-avatar {
            font-size: 1.2rem;
        }
        
        .status-badge {
            padding: 0.4rem 0.75rem;
            font-size: 0.8rem;
        }
    }

    /* Small devices (landscape phones, 576px and up) */
    @media (min-width: 576px) {
        .coaches-container {
            padding: 1.5rem;
        }
        
        .header-card, .search-card {
            padding: 1.5rem;
        }
        
        .coach-avatar, .coach-img-avatar {
            width: 45px;
            height: 45px;
        }
    }

    /* Medium devices (tablets, 768px and up) */
    @media (min-width: 768px) {
        .header-flex {
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
        }
        
        .header-card h1 {
            margin-bottom: 0;
        }
        
        .search-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .coach-avatar, .coach-img-avatar {
            width: 50px;
            height: 50px;
        }
    }

    /* Large devices (desktops, 992px and up) */
    @media (min-width: 992px) {
        .coaches-container {
            padding: 2rem;
        }
        
        .header-card {
            padding: 1.5rem;
        }
        
        .search-grid {
            grid-template-columns: repeat(4, 1fr);
        }
        
        .header-card h1 {
            font-size: 1.75rem;
        }
    }
    
  
    
    /* Print styles */
    @media print {
        .coaches-container {
            background: white;
            padding: 0;
        }
        
        .header-card, .search-card, .coaches-table {
            box-shadow: none;
            margin-bottom: 1rem;
        }
        
        .btn-primary, .btn-secondary, .btn-danger {
            display: none;
        }
        
        .coaches-table thead {
            background: #f8fafc;
            color: black;
        }
        
        .coaches-table-mobile tr {
            page-break-inside: avoid;
        }
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
        <div class="header-flex">
            <h1>Gestion des entraîneurs</h1>
            <a href="{{ route('user.create') }}" class="btn btn-primary">
                <i class="fas fa-plus mr-2"></i>Ajouter un nouvel entraîneur
            </a>
        </div>
    </div>

    <!-- Search Form -->
    <div class="search-card">
        <form action="{{ route('user.index') }}" method="GET">
            <div class="search-grid">
                <input type="text" name="q" value="{{ request('q') }}" 
                       class="form-control" placeholder="Rechercher des entraîneurs...">
                
                <select name="availability" class="form-control">
                    <option value="">Toutes les disponibilités</option>
                    <option value="1" {{ request('availability') == '1' ? 'selected' : '' }}>Disponible</option>
                    <option value="0" {{ request('availability') == '0' ? 'selected' : '' }}>Occupé</option>
                </select>

                <select name="specialities" class="form-control">
                    <option value="">Toutes les spécialités</option>
                    @foreach($specialities as $speciality)
                    <option value="{{ $speciality->id }}" {{ request('specialities') == $speciality->id ? 'selected' : '' }}>
                        {{ $speciality->name }}
                    </option>
                    @endforeach
                </select>

                <button type="submit" class="btn btn-secondary">
                    <i class="fas fa-search mr-2"></i>Rechercher
                </button>
            </div>
        </form>
    </div>

    <!-- Coaches Table - Desktop Version -->
    <div class="coaches-table d-none d-md-block">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Entraîneur</th>
                    <th>Spécialité</th>
                    <th>Disponibilité</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>
                        <div class="coach-info-flex">
                            @if ($user->image_path)
                                <img src="{{ asset('storage/' . $user->image_path) }}" alt="Coach Avatar" class="coach-img-avatar">
                            @else
                                <div class="coach-avatar">
                                    {{ strtoupper(substr($user->full_name, 0, 1)) }}
                                </div>
                            @endif
                            <div class="coach-info-text">
                                <div class="font-medium">{{ $user->full_name }}</div>
                                <div class="text-sm text-gray-600">{{ $user->email }}</div>
                            </div>   
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-primary-light text-black fs-6">
                            {{ $user->speciality->name }}
                        </span>
                    </td>
                    <td>
                        <span class="status-badge {{ $user->is_available ? 'available' : 'unavailable' }}">
                            {{ $user->is_available ? 'Disponible' : 'Occupé' }}
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('user.show', $user->id) }}" 
                               class="btn btn-secondary">
                               <i class="fas fa-eye"></i>
                            </a>
                            <form action="{{ route('user.destroy', $user->id) }}" method="POST" 
                                  onsubmit="return deleteConfirmation()">
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

    <!-- Coaches Table - Mobile Version -->
    <div class="coaches-table-mobile d-block d-md-none">
        <table>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td data-label="ID">{{ $user->id }}</td>
                    <td data-label="Entraîneur" class="coach-info-cell">
                        <div class="coach-info-flex">
                            @if ($user->image_path)
                                <img src="{{ asset('storage/' . $user->image_path) }}" alt="Coach Avatar" class="coach-img-avatar">
                            @else
                                <div class="coach-avatar">
                                    {{ strtoupper(substr($user->full_name, 0, 1)) }}
                                </div>
                            @endif
                            <div class="coach-info-text">
                                <div class="font-medium">{{ $user->full_name }}</div>
                                <div class="text-sm text-gray-600">{{ $user->email }}</div>
                            </div>   
                        </div>
                    </td>
                    <td data-label="Spécialité">
                        <span class="badge bg-primary-light text-black fs-6">
                            {{ $user->speciality->name }}
                        </span>
                    </td>
                    <td data-label="Disponibilité">
                        <span class="status-badge {{ $user->is_available ? 'available' : 'unavailable' }}">
                            {{ $user->is_available ? 'Disponible' : 'Occupé' }}
                        </span>
                    </td>
                    <td data-label="Actions">
                        <div class="action-buttons">
                            <a href="{{ route('user.show', $user->id) }}" 
                               class="btn btn-secondary">
                               <i class="fas fa-eye"></i>
                            </a>
                            <form action="{{ route('user.destroy', $user->id) }}" method="POST" 
                                  onsubmit="return deleteConfirmation()">
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
    function deleteConfirmation() {
        return confirm('Êtes-vous sûr de vouloir supprimer cet entraîneur ?');
    }
</script>
@endsection
