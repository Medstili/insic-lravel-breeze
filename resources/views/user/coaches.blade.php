@extends('layouts.app')
@section('content')

<div class="min-h-screen bg-gradient-to-br from-gray-50 via-cyan-50 to-sky-50 p-6 mt-24">
    
    <!-- Hero Header -->
    <div class="relative overflow-hidden bg-gradient-to-r from-cyan-500 via-sky-500 to-blue-500 rounded-3xl shadow-2xl mb-8">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="relative p-8 md:p-12">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold text-white mb-2">
                        Gestion des Entraîneurs
                    </h1>
                    <p class="text-cyan-100 text-lg md:text-xl">
                        Gérez et suivez tous vos entraîneurs en temps réel
                    </p>
                </div>
                <div class="hidden md:block">
                    <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-users text-3xl text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 rounded-2xl p-6 mb-8">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fas fa-check-circle text-green-600"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-green-800">Succès</h3>
                <p class="text-green-700">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Entraîneurs</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $users->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-cyan-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-users text-cyan-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Disponibles</p>
                    <p class="text-3xl font-bold text-green-600">{{ $users->where('is_available', true)->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Occupés</p>
                    <p class="text-3xl font-bold text-red-600">{{ $users->where('is_available', false)->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-times-circle text-red-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Spécialités</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $specialities->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-tags text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 overflow-hidden mb-8">
        <div class="bg-gradient-to-r from-cyan-500 to-sky-600 px-8 py-6">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-white flex items-center gap-3">
                    <i class="fas fa-search"></i>
                    Recherche et Filtres
                </h3>
                <a href="{{ route('user.create') }}" 
                   class="bg-white text-cyan-600 px-6 py-3 rounded-xl font-semibold hover:bg-cyan-50 transition-all duration-200 flex items-center gap-3 shadow-lg hover:shadow-xl">
                    <i class="fas fa-plus"></i>
                    <span class="hidden sm:inline">Ajouter un Entraîneur</span>
                </a>
            </div>
        </div>
        <div class="p-8">
        <form action="{{ route('user.index') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 items-end">
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">Rechercher</label>
                <input type="text" name="q" value="{{ request('q') }}" 
                               placeholder="Nom de l'entraîneur..." 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200 bg-white/50 backdrop-blur-sm">
                    </div>
                
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">Disponibilité</label>
                        <select name="availability" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200 bg-white/50 backdrop-blur-sm">
                    <option value="">Toutes les disponibilités</option>
                    <option value="1" {{ request('availability') == '1' ? 'selected' : '' }}>Disponible</option>
                    <option value="0" {{ request('availability') == '0' ? 'selected' : '' }}>Occupé</option>
                </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">Spécialité</label>
                        <select name="specialities" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200 bg-white/50 backdrop-blur-sm">
                    <option value="">Toutes les spécialités</option>
                    @foreach($specialities as $speciality)
                    <option value="{{ $speciality->id }}" {{ request('specialities') == $speciality->id ? 'selected' : '' }}>
                        {{ $speciality->name }}
                    </option>
                    @endforeach
                </select>
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-r from-cyan-500 to-sky-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-cyan-600 hover:to-sky-700 transform hover:-translate-y-1 transition-all duration-200 flex items-center justify-center gap-3 shadow-lg hover:shadow-xl">
                        <i class="fas fa-search"></i>
                        <span class="hidden sm:inline">Rechercher</span>
                </button>
            </div>
        </form>
    </div>
    </div>

    <!-- Coaches Table Section -->
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 overflow-hidden">
        <div class="bg-gradient-to-r from-cyan-500 to-sky-600 px-8 py-6">
            <h3 class="text-xl font-bold text-white flex items-center gap-3">
                <i class="fas fa-list"></i>
                Liste des Entraîneurs
            </h3>
    </div>

        <!-- Desktop Table -->
        <div class="hidden lg:block overflow-x-auto">
            <table class="w-full">
            <thead>
                    <tr class="bg-gradient-to-r from-cyan-500 to-sky-600 text-white">
                        <th class="px-6 py-4 text-left font-semibold text-sm uppercase tracking-wider">ID</th>
                        <th class="px-6 py-4 text-left font-semibold text-sm uppercase tracking-wider">Entraîneur</th>
                        <th class="px-6 py-4 text-left font-semibold text-sm uppercase tracking-wider">Spécialité</th>
                        <th class="px-6 py-4 text-left font-semibold text-sm uppercase tracking-wider">Disponibilité</th>
                        <th class="px-6 py-4 text-left font-semibold text-sm uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
                <tbody class="divide-y divide-gray-100">
                @foreach($users as $user)
                    <tr class="hover:bg-gray-50/80 transition-all duration-200 group">
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-cyan-100 text-cyan-800">
                                #{{ $user->id }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                            @if ($user->image_path)
                                    <img src="{{ asset('storage/' . $user->image_path) }}" 
                                         alt="Coach Avatar" 
                                         class="w-12 h-12 rounded-full object-cover border-2 border-cyan-200">
                            @else
                                    <div class="w-12 h-12 bg-gradient-to-br from-cyan-400 to-sky-500 rounded-full flex items-center justify-center text-white font-semibold text-lg">
                                    {{ strtoupper(substr($user->full_name, 0, 1)) }}
                                </div>
                            @endif
                                <div>
                                    <div class="font-medium text-gray-900">{{ $user->full_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                            </div>   
                        </div>
                    </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-3 py-1 text-sm font-medium bg-blue-100 text-blue-800 rounded-full">
                            {{ $user->speciality->name }}
                        </span>
                    </td>
                        <td class="px-6 py-4">
                            @php
                                $statusClasses = [
                                    true => 'bg-green-100 text-green-800 border-green-200',
                                    false => 'bg-red-100 text-red-800 border-red-200'
                                ];
                                $statusClass = $statusClasses[$user->is_available] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                            @endphp
                            <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full border {{ $statusClass }}">
                                <span class="w-2 h-2 rounded-full mr-2 {{ $user->is_available ? 'bg-green-500' : 'bg-red-500' }}"></span>
                            {{ $user->is_available ? 'Disponible' : 'Occupé' }}
                        </span>
                    </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                            <a href="{{ route('user.show', $user->id) }}" 
                                   class="inline-flex items-center justify-center w-9 h-9 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition-all duration-200 hover:scale-110" 
                                   title="Voir les détails">
                                    <i class="fas fa-eye text-sm"></i>
                            </a>
                            <form action="{{ route('user.destroy', $user->id) }}" method="POST" 
                                      onsubmit="return deleteConfirmation()" class="inline">
                                @csrf
                                @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center justify-center w-9 h-9 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-all duration-200 hover:scale-110" 
                                            title="Supprimer">
                                        <i class="fas fa-trash text-sm"></i>
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

    <!-- Mobile Cards View -->
    <div class="lg:hidden space-y-6 mt-8">
                @foreach($users as $user)
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 overflow-hidden hover:shadow-3xl transition-all duration-300">
            <div class="bg-gradient-to-r from-cyan-500 to-sky-600 px-6 py-4 text-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                            @if ($user->image_path)
                            <img src="{{ asset('storage/' . $user->image_path) }}" 
                                 alt="Coach Avatar" 
                                 class="w-12 h-12 rounded-full object-cover border-2 border-white/20">
                            @else
                            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                                <span class="text-white font-semibold text-lg">{{ strtoupper(substr($user->full_name, 0, 1)) }}</span>
                                </div>
                            @endif
                        <div>
                            <div class="font-semibold text-lg">{{ $user->full_name }}</div>
                            <div class="text-cyan-100 text-sm">ID: #{{ $user->id }}</div>
                        </div>
                    </div>
                    @php
                        $statusClasses = [
                            true => 'bg-green-100 text-green-800',
                            false => 'bg-red-100 text-red-800'
                        ];
                        $statusClass = $statusClasses[$user->is_available] ?? 'bg-gray-100 text-gray-800';
                    @endphp
                    <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full {{ $statusClass }}">
                        {{ $user->is_available ? 'Disponible' : 'Occupé' }}
                    </span>
                </div>
            </div>
            
            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-600">Email:</div>
                    <div class="text-gray-900 font-medium">{{ $user->email }}</div>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-600">Spécialité:</div>
                    <div>
                        <span class="inline-flex px-3 py-1 text-sm font-medium bg-blue-100 text-blue-800 rounded-full">
                            {{ $user->speciality->name }}
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-200/50">
                <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('user.show', $user->id) }}" 
                       class="inline-flex items-center justify-center w-9 h-9 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition-all duration-200 hover:scale-110" 
                       title="Voir les détails">
                        <i class="fas fa-eye text-sm"></i>
                            </a>
                            <form action="{{ route('user.destroy', $user->id) }}" method="POST" 
                          onsubmit="return deleteConfirmation()" class="inline">
                                @csrf
                                @method('DELETE')
                        <button type="submit" 
                                class="inline-flex items-center justify-center w-9 h-9 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-all duration-200 hover:scale-110" 
                                title="Supprimer">
                            <i class="fas fa-trash text-sm"></i>
                                </button>
                            </form>
                        </div>
            </div>
        </div>
                @endforeach
    </div>

</div>

<script>
    function deleteConfirmation() {
        return confirm('Êtes-vous sûr de vouloir supprimer cet entraîneur ?');
    }
</script>

@endsection
