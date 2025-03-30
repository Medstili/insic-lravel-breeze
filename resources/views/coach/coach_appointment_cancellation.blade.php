@extends('layouts.coach_app')
@section('content')
    <div class="cancellation-container">
        <div class="max-w-2xl mx-auto px-4">
            <!-- Cancellation Card -->
            <div class="glass-card cancellation-card">
                <!-- Header -->
                <div class="header text-center mb-8">
                    <i class="fas fa-calendar-times text-4xl text-red-400 mb-4"></i>
                    <h1 class="title text-3xl font-bold mb-2">Annuler le Rendez-vous</h1>
                    <p class="subtitle">Êtes-vous sûr de vouloir annuler ce rendez-vous ?</p>
                </div>
                <!-- Cancellation Form -->
                <form action="{{ route('appointment_update', $appointment->id) }}" onsubmit="return confirmCancellation()" method="POST">
                    @csrf
                    @method("PATCH")

                    <!-- Canceller Selection -->
                    <div class="form-group mb-6">
                        <label class="form-label">Annulé Par</label>
                        <div class="relative">
                            <select name="cancelled_by" class="form-select" required>
                                <option value="" disabled selected>Sélectionnez qui annule</option>
                                <option value="patient">Patient</option>
                                <option value="coach">Coach</option>
                            </select>
                        </div>
                    </div>

                    <!-- Reason Input -->
                    <div class="form-group mb-8">
                        <label class="form-label">Raison de l'Annulation</label>
                        <textarea name="description" class="form-textarea" rows="4" placeholder="Veuillez décrire la raison de l'annulation..." required></textarea>
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons flex justify-between space-x-4">
                        <a href="{{ url()->previous() }}" class="btn btn-back">
                            <i class="fas fa-arrow-left mr-2"></i> Retour
                        </a>
                        <button type="submit" class="btn btn-cancel">
                            <i class="fas fa-ban mr-2"></i> Confirmer l'Annulation
                        </button>
                    </div>

                    <!-- Warning Message -->
                    <div class="warning mt-6 text-center">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Cette action est irréversible
                    </div>
                </form>
            </div>
        </div>
    </div>
<style>
    /* Container adjusts for the sidebar and navbar */
    .cancellation-container {
        background-color: #ecf0f1;
        min-height: calc(100vh - 60px);
        padding: 40px;
    }

    /* Glass Card styling */
    .glass-card {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.85);
        border-radius: 12px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 2rem;
    }

    /* Header & Text */
    .header .title {
        color: #2c3e50;
    }
    .header .subtitle {
        color: #7f8c8d;
    }
    .form-label {
        display: block;
        color: #2c3e50;
        margin-bottom: 0.5rem;
        font-weight: 500;
    }

    /* Form Elements */
    .form-select {
        width: 100%;
        background-color: rgba(255, 255, 255, 0.95);
        border: 1px solid #ccc;
        border-radius: 4px;
        padding: 0.75rem 2.5rem 0.75rem 1rem;
        color: #2c3e50;
        transition: background-color 0.3s ease;
    }
    .form-select:focus {
        border-color: #4f46e5;
        outline: none;
        background-color: #fff;
    }
    .form-textarea {
        width: 100%;
        background-color: rgba(255, 255, 255, 0.95);
        border: 1px solid #ccc;
        border-radius: 4px;
        padding: 0.75rem 1rem;
        color: #2c3e50;
        transition: background-color 0.3s ease;
    }
    .form-textarea:focus {
        border-color: #4f46e5;
        outline: none;
        background-color: #fff;
    }
    .select-icon {
        position: absolute;
        top: 50%;
        right: 1rem;
        transform: translateY(-50%);
        pointer-events: none;
        color: #8e44ad;
    }

    /* Buttons */
    .btn {
        flex: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.75rem;
        border-radius: 4px;
        font-size: 1rem;
        transition: background-color 0.3s ease;
        text-decoration: none;
    }
    .btn-back {
        background-color: rgba(128, 128, 128, 0.2);
        color: #2c3e50;
    }
    .btn-back:hover {
        background-color: rgba(128, 128, 128, 0.3);
    }
    .btn-cancel {
        background-color: rgba(231, 76, 60, 0.2);
        color: #e74c3c;
        margin-left: 20px;
    }
    .btn-cancel:hover {
        background-color: rgba(231, 76, 60, 0.3);
    }

    /* Warning Message */
    .warning {
        color: #e74c3c;
        font-size: 0.875rem;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .cancellation-container {
            margin-left: 0;
            padding: 20px;
        }
    }
    
    /* Hide the dropdown arrow in IE */
    select::-ms-expand {
        display: none;
    }
</style>

<script>
    function confirmCancellation() {
        return confirm("Êtes-vous sûr de vouloir annuler ce rendez-vous ?");
    }
</script>

@endsection
