@extends('layouts.app')
@section('content')

<div class="min-h-screen bg-gradient-to-br from-cyan-50 via-sky-50 to-blue-50 p-6 mt-24">
    
    <!-- Hero Header -->
    <div class="relative overflow-hidden bg-gradient-to-r from-cyan-500 via-sky-500 to-blue-500 rounded-3xl shadow-2xl mb-8">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="relative p-8 md:p-12">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold text-white mb-2">
                        Gestion des Patients
                    </h1>
                    <p class="text-cyan-100 text-lg md:text-xl">
                        Gérez et suivez tous vos patients en temps réel
                    </p>
                </div>
                <div class="hidden md:block">
                    <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-user-injured text-3xl text-white"></i>
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
                    <p class="text-gray-600 text-sm font-medium">Total Patients</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $patientCount }}</p>
                </div>
                <div class="w-12 h-12 bg-cyan-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-users text-cyan-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Patients Adultes</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $adultCount }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-user text-blue-600 text-xl"></i>
                </div>
        </div>
    </div>

        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Patients Enfants</p>
                    <p class="text-3xl font-bold text-orange-600">{{ $kidCount }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-baby text-orange-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Patients Jeunes</p>
                    <p class="text-3xl font-bold text-purple-600">{{ $youngCount }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-user-graduate text-purple-600 text-xl"></i>
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
                <a href="{{ route('patient.create') }}" 
                   class="bg-white text-cyan-600 px-6 py-3 rounded-xl font-semibold hover:bg-cyan-50 transition-all duration-200 flex items-center gap-3 shadow-lg hover:shadow-xl">
                    <i class="fas fa-plus"></i>
                    <span class="hidden sm:inline">Ajouter un Patient</span>
                </a>
            </div>
        </div>
        <div class="p-8">
            <form action="{{ route('patient.index') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 items-end">
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">Rechercher</label>
                        <input type="text" name="q" value="{{ request('q') }}" 
                               placeholder="Nom, email ou téléphone..." 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200 bg-white/50 backdrop-blur-sm">
                    </div>
                    
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">Type de Patient</label>
                        <select name="patient_type" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200 bg-white/50 backdrop-blur-sm">
                            <option value="">Tous les types</option>
                            <option value="adult" {{ request('patient_type') == 'adult' ? 'selected' : '' }}>Adulte</option>
                    <option value="kid" {{ request('patient_type') == 'kid' ? 'selected' : '' }}>Enfant</option>
                    <option value="young" {{ request('patient_type') == 'young' ? 'selected' : '' }}>Jeune</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">Genre</label>
                        <select name="gender" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200 bg-white/50 backdrop-blur-sm">
                            <option value="">Tous les genres</option>
                            <option value="M" {{ request('gender') == 'M' ? 'selected' : '' }}>Masculin</option>
                            <option value="F" {{ request('gender') == 'F' ? 'selected' : '' }}>Féminin</option>
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

    <!-- Patients Table Section -->
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 overflow-hidden">
        <div class="bg-gradient-to-r from-cyan-500 to-sky-600 px-8 py-6">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-white flex items-center gap-3">
                    <i class="fas fa-list"></i>
                    Liste des Patients
                </h3>
                <div class="text-white text-sm">
                    {{ $patients->total() }} patients trouvés
                </div>
            </div>
        </div>
        
        <!-- Desktop Table -->
        <div class="hidden lg:block overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gradient-to-r from-cyan-500 to-sky-600 text-white">
                        <th class="px-6 py-4 text-left font-semibold text-sm uppercase tracking-wider">Patient</th>
                        <th class="px-6 py-4 text-left font-semibold text-sm uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-4 text-left font-semibold text-sm uppercase tracking-wider">Type</th>
                        <th class="px-6 py-4 text-left font-semibold text-sm uppercase tracking-wider">Entraîneur</th>
                        <th class="px-6 py-4 text-left font-semibold text-sm uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($patients as $patient)
                    <tr class="hover:bg-gray-50/80 transition-all duration-200 group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                @if($patient->image_path)
                                    <img src="{{ asset('storage/' . $patient->image_path) }}" 
                                         alt="Patient Avatar" 
                                         class="w-12 h-12 rounded-full object-cover border-2 border-cyan-200">
                                @else
                                    <div class="w-12 h-12 bg-gradient-to-br from-cyan-400 to-sky-500 rounded-full flex items-center justify-center text-white font-semibold text-lg">
                                        @if($patient->patient_type == 'adult')
                                            {{ strtoupper(substr($patient->parent_first_name, 0, 1)) }}
                                        @else
                                            {{ strtoupper(substr($patient->first_name, 0, 1)) }}
                                        @endif
                                    </div>
                                @endif
                                <div>
                                    <div class="font-medium text-gray-900">
                                        @if($patient->patient_type == 'adult')
                                            {{ $patient->parent_first_name }} {{ $patient->parent_last_name }}
                                        @else
                                            {{ $patient->first_name }} {{ $patient->last_name }}
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        @if($patient->patient_type == 'adult')
                                            Patient Adulte
                                        @else
                                            Patient {{ ucfirst($patient->patient_type) }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                <div class="text-gray-900">{{ $patient->email }}</div>
                                <div class="text-gray-500">{{ $patient->phone ?? 'Aucun téléphone' }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $typeClasses = [
                                    'adult' => 'bg-blue-100 text-blue-800',
                                    'kid' => 'bg-orange-100 text-orange-800',
                                    'young' => 'bg-purple-100 text-purple-800'
                                ];
                                $typeClass = $typeClasses[$patient->patient_type] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full {{ $typeClass }}">
                                @if($patient->patient_type == 'adult')
                                    Adulte
                                @elseif($patient->patient_type == 'kid')
                                    Enfant
                                @elseif($patient->patient_type == 'young')
                                    Jeune
                                @else
                                    Inconnu
                           @endif
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($patient->coaches->count() > 0)
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-cyan-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user-md text-cyan-600 text-xs"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $patient->coaches->first()->full_name }}</div>
                                        @if($patient->coaches->count() > 1)
                                            <div class="text-xs text-gray-500">+{{ $patient->coaches->count() - 1 }} autres</div>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <span class="text-gray-400 text-sm">Aucun entraîneur assigné</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                            <a href="{{ route('patient.show', $patient->id) }}" 
                                   class="inline-flex items-center justify-center w-9 h-9 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition-all duration-200 hover:scale-110" 
                                   title="Voir les détails">
                                    <i class="fas fa-eye text-sm"></i>
                                </a>
                                <a href="{{ route('patient.edit', $patient->id) }}" 
                                   class="inline-flex items-center justify-center w-9 h-9 bg-yellow-100 text-yellow-600 rounded-lg hover:bg-yellow-200 transition-all duration-200 hover:scale-110" 
                                   title="Modifier">
                                    <i class="fas fa-edit text-sm"></i>
                                </a>
                                <button onclick="deletePatient({{ $patient->id }})" 
                                        class="inline-flex items-center justify-center w-9 h-9 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-all duration-200 hover:scale-110" 
                                        title="Supprimer">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-users text-2xl text-gray-400"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun patient trouvé</h3>
                                <p class="text-gray-500">Essayez d'ajuster vos critères de recherche</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile Cards View -->
    <div class="lg:hidden space-y-6 mt-8">
        @forelse($patients as $patient)
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 overflow-hidden hover:shadow-3xl transition-all duration-300">
            <div class="bg-gradient-to-r from-cyan-500 to-sky-600 px-6 py-4 text-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        @if($patient->image_path)
                            <img src="{{ asset('storage/' . $patient->image_path) }}" 
                                 alt="Patient Avatar" 
                                 class="w-12 h-12 rounded-full object-cover border-2 border-white/20">
                        @else
                            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                                <span class="text-white font-semibold text-lg">
                                    @if($patient->patient_type == 'adult')
                                        {{ strtoupper(substr($patient->parent_first_name, 0, 1)) }}
                                    @else
                                        {{ strtoupper(substr($patient->first_name, 0, 1)) }}
                                    @endif
                                </span>
                            </div>
                        @endif
                        <div>
                            <div class="font-semibold text-lg">
                                @if($patient->patient_type == 'adult')
                                    {{ $patient->parent_first_name }} {{ $patient->parent_last_name }}
                                @else
                                    {{ $patient->first_name }} {{ $patient->last_name }}
                                @endif
                            </div>
                            <div class="text-cyan-100 text-sm">
                                @if($patient->patient_type == 'adult')
                                    Patient Adulte
                                @else
                                    Patient {{ ucfirst($patient->patient_type) }}
                                @endif
                            </div>
                        </div>
                    </div>
                    @php
                        $typeClasses = [
                            'adult' => 'bg-blue-100 text-blue-800',
                            'kid' => 'bg-orange-100 text-orange-800',
                            'young' => 'bg-purple-100 text-purple-800'
                        ];
                        $typeClass = $typeClasses[$patient->patient_type] ?? 'bg-gray-100 text-gray-800';
                    @endphp
                    <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full {{ $typeClass }}">
                        @if($patient->patient_type == 'adult')
                            Adulte
                        @elseif($patient->patient_type == 'kid')
                            Enfant
                        @elseif($patient->patient_type == 'young')
                            Jeune
                        @else
                            Inconnu
                        @endif
                    </span>
                </div>
            </div>
            
            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-600">Email:</div>
                    <div class="text-gray-900 font-medium">{{ $patient->email }}</div>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-600">Téléphone:</div>
                    <div class="text-gray-900 font-medium">{{ $patient->phone ?? 'Aucun téléphone' }}</div>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-600">Entraîneur:</div>
                    <div class="text-gray-900 font-medium">
                        @if($patient->coaches->count() > 0)
                            {{ $patient->coaches->first()->full_name }}
                            @if($patient->coaches->count() > 1)
                                <span class="text-xs text-gray-500">+{{ $patient->coaches->count() - 1 }} autres</span>
                            @endif
                        @else
                            <span class="text-gray-400">Aucun entraîneur assigné</span>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-200/50">
                <div class="flex items-center justify-end gap-2">
                    <a href="{{ route('patient.show', $patient->id) }}" 
                       class="inline-flex items-center justify-center w-9 h-9 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition-all duration-200 hover:scale-110" 
                       title="Voir les détails">
                        <i class="fas fa-eye text-sm"></i>
                    </a>
                    <a href="{{ route('patient.edit', $patient->id) }}" 
                       class="inline-flex items-center justify-center w-9 h-9 bg-yellow-100 text-yellow-600 rounded-lg hover:bg-yellow-200 transition-all duration-200 hover:scale-110" 
                       title="Modifier">
                        <i class="fas fa-edit text-sm"></i>
                    </a>
                    <button onclick="deletePatient({{ $patient->id }})" 
                            class="inline-flex items-center justify-center w-9 h-9 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-all duration-200 hover:scale-110" 
                            title="Supprimer">
                        <i class="fas fa-trash text-sm"></i>
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 p-12 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-users text-2xl text-gray-400"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun patient trouvé</h3>
            <p class="text-gray-500">Essayez d'ajuster vos critères de recherche</p>
        </div>
        @endforelse
    </div>

    <!-- Modern Pagination Section -->
    @if($patients->hasPages())
    <div class="mt-12">
        <!-- Compact Pagination Info -->
        <div class="flex items-center justify-center mb-6">
            <div class="bg-white/90 backdrop-blur-xl rounded-2xl shadow-lg border border-white/30 px-6 py-3">
                <div class="flex items-center gap-6 text-sm">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-users text-cyan-600"></i>
                        <span class="text-gray-600">
                            <span class="font-semibold text-gray-900">{{ $patients->firstItem() }}-{{ $patients->lastItem() }}</span>
                            sur <span class="font-semibold text-gray-900">{{ $patients->total() }}</span> patients
                        </span>
                    </div>
                    <div class="w-px h-4 bg-gray-300"></div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-file-alt text-cyan-600"></i>
                        <span class="text-gray-600">
                            Page <span class="font-semibold text-gray-900">{{ $patients->currentPage() }}</span>
                            sur <span class="font-semibold text-gray-900">{{ $patients->lastPage() }}</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Pagination Controls -->
        <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/30 p-8">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-6">
                
                <!-- Left Side - Previous & First -->
                <div class="flex items-center gap-3">
                    @if($patients->currentPage() > 1)
                        <a href="{{ $patients->url(1) }}" 
                           class="group flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-cyan-50 to-sky-50 text-cyan-700 rounded-xl hover:from-cyan-100 hover:to-sky-100 transition-all duration-200 border border-cyan-200 hover:border-cyan-300">
                            <i class="fas fa-angle-double-left text-sm group-hover:scale-110 transition-transform"></i>
                            <span class="hidden sm:inline font-medium">Première</span>
                        </a>
                    @endif
                    
                    @if($patients->onFirstPage())
                        <button disabled class="flex items-center gap-2 px-4 py-2 text-gray-400 bg-gray-100 rounded-xl cursor-not-allowed border border-gray-200">
                            <i class="fas fa-angle-left"></i>
                            <span class="hidden sm:inline">Précédent</span>
                        </button>
                    @else
                        <a href="{{ $patients->previousPageUrl() }}" 
                           class="group flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-cyan-50 to-sky-50 text-cyan-700 rounded-xl hover:from-cyan-100 hover:to-sky-100 transition-all duration-200 border border-cyan-200 hover:border-cyan-300">
                            <i class="fas fa-angle-left text-sm group-hover:scale-110 transition-transform"></i>
                            <span class="hidden sm:inline font-medium">Précédent</span>
                        </a>
                    @endif
                </div>

                <!-- Center - Page Numbers -->
                <div class="flex items-center gap-1">
                    @php
                        $start = max(1, $patients->currentPage() - 2);
                        $end = min($patients->lastPage(), $patients->currentPage() + 2);
                    @endphp

                    @if($start > 1)
                        <a href="{{ $patients->url(1) }}" 
                           class="w-12 h-12 flex items-center justify-center text-cyan-600 bg-cyan-50 rounded-xl hover:bg-cyan-100 transition-all duration-200 border border-cyan-200 hover:border-cyan-300 font-medium hover:scale-105">
                            1
                        </a>
                        @if($start > 2)
                            <div class="w-12 h-12 flex items-center justify-center text-gray-400">
                                <i class="fas fa-ellipsis-h"></i>
                            </div>
                        @endif
                    @endif

                    @for($i = $start; $i <= $end; $i++)
                        @if($i == $patients->currentPage())
                            <div class="w-12 h-12 flex items-center justify-center text-white bg-gradient-to-r from-cyan-500 to-sky-600 rounded-xl font-bold shadow-lg border-2 border-cyan-400">
                                {{ $i }}
                            </div>
                        @else
                            <a href="{{ $patients->url($i) }}" 
                               class="w-12 h-12 flex items-center justify-center text-cyan-600 bg-cyan-50 rounded-xl hover:bg-cyan-100 transition-all duration-200 border border-cyan-200 hover:border-cyan-300 font-medium hover:scale-105">
                                {{ $i }}
                            </a>
                        @endif
                    @endfor

                    @if($end < $patients->lastPage())
                        @if($end < $patients->lastPage() - 1)
                            <div class="w-12 h-12 flex items-center justify-center text-gray-400">
                                <i class="fas fa-ellipsis-h"></i>
                            </div>
                        @endif
                        <a href="{{ $patients->url($patients->lastPage()) }}" 
                           class="w-12 h-12 flex items-center justify-center text-cyan-600 bg-cyan-50 rounded-xl hover:bg-cyan-100 transition-all duration-200 border border-cyan-200 hover:border-cyan-300 font-medium hover:scale-105">
                            {{ $patients->lastPage() }}
                        </a>
                    @endif
                </div>

                <!-- Right Side - Next & Last -->
                <div class="flex items-center gap-3">
                    @if($patients->hasMorePages())
                        <a href="{{ $patients->nextPageUrl() }}" 
                           class="group flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-cyan-50 to-sky-50 text-cyan-700 rounded-xl hover:from-cyan-100 hover:to-sky-100 transition-all duration-200 border border-cyan-200 hover:border-cyan-300">
                            <span class="hidden sm:inline font-medium">Suivant</span>
                            <i class="fas fa-angle-right text-sm group-hover:scale-110 transition-transform"></i>
                        </a>
                    @else
                        <button disabled class="flex items-center gap-2 px-4 py-2 text-gray-400 bg-gray-100 rounded-xl cursor-not-allowed border border-gray-200">
                            <span class="hidden sm:inline">Suivant</span>
                            <i class="fas fa-angle-right"></i>
                        </button>
                    @endif
                    
                    @if($patients->currentPage() < $patients->lastPage())
                        <a href="{{ $patients->url($patients->lastPage()) }}" 
                           class="group flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-cyan-50 to-sky-50 text-cyan-700 rounded-xl hover:from-cyan-100 hover:to-sky-100 transition-all duration-200 border border-cyan-200 hover:border-cyan-300">
                            <span class="hidden sm:inline font-medium">Dernière</span>
                            <i class="fas fa-angle-double-right text-sm group-hover:scale-110 transition-transform"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Load More Button -->
        @if($patients->hasMorePages())
        <div class="mt-6 text-center">
            <button onclick="loadMorePatients()" 
                    class="group bg-gradient-to-r from-cyan-500 via-sky-500 to-blue-500 text-white px-8 py-4 rounded-2xl font-semibold hover:from-cyan-600 hover:via-sky-600 hover:to-blue-600 transform hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-3 shadow-lg hover:shadow-2xl mx-auto border border-cyan-400/30">
                <i class="fas fa-plus-circle text-lg group-hover:scale-110 transition-transform"></i>
                <span>Charger {{ $patients->perPage() }} Patients Supplémentaires</span>
                <div class="flex items-center gap-1 text-sm opacity-75">
                    <i class="fas fa-arrow-down"></i>
                    <span class="hidden sm:inline">Page {{ $patients->currentPage() + 1 }}</span>
                </div>
            </button>
        </div>
        @endif

        <!-- Mobile Optimized Pagination -->
        <div class="lg:hidden mt-6">
            <div class="bg-white/90 backdrop-blur-xl rounded-2xl shadow-lg border border-white/30 p-4">
                <div class="flex items-center justify-between">
                    <div class="text-center">
                        <div class="text-sm text-gray-600">Page actuelle</div>
                        <div class="text-2xl font-bold text-cyan-600">{{ $patients->currentPage() }}</div>
                    </div>
                    <div class="text-center">
                        <div class="text-sm text-gray-600">Sur</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $patients->lastPage() }}</div>
                    </div>
                    <div class="text-center">
                        <div class="text-sm text-gray-600">Patients</div>
                        <div class="text-2xl font-bold text-blue-600">{{ $patients->total() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>

<!-- Delete Patient Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full mx-4">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Supprimer le Patient</h3>
                    <button onclick="closeDeleteModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <p class="text-gray-600 mb-6">
                    Êtes-vous sûr de vouloir supprimer ce patient ? Cette action ne peut pas être annulée et supprimera toutes les données associées.
                </p>
                <div class="flex items-center gap-3">
                    <button onclick="closeDeleteModal()" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors">
                        Annuler
                    </button>
                    <form id="deleteForm" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded-xl hover:bg-red-700 transition-colors flex items-center justify-center gap-2">
                            <i class="fas fa-trash"></i>
                            Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function deletePatient(patientId) {
        const modal = document.getElementById('deleteModal');
        const form = document.getElementById('deleteForm');
        
        form.action = `/patient/${patientId}`;
        modal.classList.remove('hidden');
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.add('hidden');
    }

    function loadMorePatients() {
        // Get current URL and parameters
        const currentUrl = new URL(window.location);
        const currentPage = parseInt(currentUrl.searchParams.get('page')) || 1;
        
        // Set next page
        currentUrl.searchParams.set('page', currentPage + 1);
        
        // Navigate to next page
        window.location.href = currentUrl.toString();
    }

    // Close modal when clicking outside
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });

    // Auto-submit form when pressing Enter in search
    document.querySelector('input[name="q"]').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            this.closest('form').submit();
        }
    });

    // Real-time search (optional)
    let searchTimeout;
    document.querySelector('input[name="q"]').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            if (this.value.length >= 3 || this.value.length === 0) {
                this.closest('form').submit();
            }
        }, 500);
    });

    // Keyboard navigation for pagination
    document.addEventListener('keydown', function(e) {
        // Only handle if not in input fields
        if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA' || e.target.tagName === 'SELECT') {
            return;
        }
        
        // Left arrow for previous page
        if (e.key === 'ArrowLeft') {
            const prevButton = document.querySelector('a[href*="page="][href*="' + ({{ $patients->currentPage() }} - 1) + '"]');
            if (prevButton) {
                window.location.href = prevButton.href;
            }
        }
        
        // Right arrow for next page
        if (e.key === 'ArrowRight') {
            const nextButton = document.querySelector('a[href*="page="][href*="' + ({{ $patients->currentPage() }} + 1) + '"]');
            if (nextButton) {
                window.location.href = nextButton.href;
            }
        }
        
        // Home key for first page
        if (e.key === 'Home') {
            const firstPageButton = document.querySelector('a[href*="page=1"]');
            if (firstPageButton) {
                window.location.href = firstPageButton.href;
            }
        }
        
        // End key for last page
        if (e.key === 'End') {
            const lastPageButton = document.querySelector('a[href*="page={{ $patients->lastPage() }}"]');
            if (lastPageButton) {
                window.location.href = lastPageButton.href;
            }
        }
    });
</script>

@endsection