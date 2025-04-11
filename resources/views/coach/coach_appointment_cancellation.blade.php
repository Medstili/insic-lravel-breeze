@extends('layouts.coach_app')
@section('content')
    <div class="cancellation-container">
        <div class="form-wrapper">
            <!-- Cancellation Card -->
            <div class="glass-card cancellation-card">
                <!-- Header -->
                <div class="header">
                    <i class="fas fa-calendar-times header-icon"></i>
                    <h1 class="title">Annuler le Rendez-vous</h1>
                    <p class="subtitle">Êtes-vous sûr de vouloir annuler ce rendez-vous ?</p>
                </div>
                <!-- Cancellation Form -->
                <form action="{{ route('appointment_update', $appointment->id) }}" onsubmit="return confirmCancellation()" method="POST">
                    @csrf
                    @method("PATCH")

                    <!-- Canceller Selection -->
                    <div class="form-group">
                        <label class="form-label">Annulé Par</label>
                        <div class="select-wrapper">
                            <select name="cancelled_by" class="form-select" required>
                                <option value="" disabled selected>Sélectionnez qui annule</option>
                                <option value="patient">Patient</option>
                                <option value="coach">Coach</option>
                            </select>
                            <div class="select-icon">
                                <i class="fas fa-chevron-down"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Reason Input -->
                    <div class="form-group">
                        <label class="form-label">Raison de l'Annulation</label>
                        <textarea name="description" class="form-textarea" rows="4" placeholder="Veuillez décrire la raison de l'annulation..." required></textarea>
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <a href="{{ url()->previous() }}" class="btn btn-back">
                            <i class="fas fa-arrow-left"></i> <span class="btn-text">Retour</span>
                        </a>
                        <button type="submit" class="btn btn-cancel">
                            <i class="fas fa-ban"></i> <span class="btn-text">Confirmer l'Annulation</span>
                        </button>
                    </div>

                    <!-- Warning Message -->
                    <div class="warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        Cette action est irréversible
                    </div>
                </form>
            </div>
        </div>
    </div>
<style>
    :root {
        --background-color: #ecf0f1;
        --card-background: rgba(255, 255, 255, 0.85);
        --text-dark: #2c3e50;
        --text-muted: #7f8c8d;
        --border-color: #ccc;
        --primary-color: #4f46e5;
        --danger-color: #e74c3c;
        --danger-bg: rgba(231, 76, 60, 0.2);
        --danger-bg-hover: rgba(231, 76, 60, 0.3);
        --neutral-bg: rgba(128, 128, 128, 0.2);
        --neutral-bg-hover: rgba(128, 128, 128, 0.3);
        --shadow-sm: 0 4px 8px rgba(0, 0, 0, 0.1);
        --shadow-md: 0 6px 12px rgba(0, 0, 0, 0.15);
        --radius-sm: 4px;
        --radius-md: 8px;
        --radius-lg: 12px;
        --spacing-xs: 0.5rem;
        --spacing-sm: 1rem;
        --spacing-md: 1.5rem;
        --spacing-lg: 2rem;
    }

    /* Container styles */
    .cancellation-container {
        background-color: var(--background-color);
        min-height: calc(100vh - 60px);
        padding: var(--spacing-lg);
        display: flex;
        justify-content: center;
        align-items: flex-start;
    }

    .form-wrapper {
        width: 100%;
        max-width: 600px;
    }

    /* Glass Card styling */
    .glass-card {
        background: var(--card-background);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.85);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-sm);
        padding: var(--spacing-lg);
    }

    /* Header & Text */
    .header {
        text-align: center;
        margin-bottom: var(--spacing-lg);
    }

    .header-icon {
        font-size: 2.5rem;
        color: var(--danger-color);
        margin-bottom: var(--spacing-sm);
    }

    .title {
        font-size: 1.875rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
    }

    .subtitle {
        color: var(--text-muted);
        font-size: 1rem;
    }

    .form-label {
        display: block;
        color: var(--text-dark);
        margin-bottom: var(--spacing-xs);
        font-weight: 500;
    }

    /* Form Elements */
    .form-group {
        margin-bottom: var(--spacing-md);
    }

    .select-wrapper {
        position: relative;
    }

    .form-select {
        width: 100%;
        background-color: rgba(255, 255, 255, 0.95);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        padding: 0.75rem 2.5rem 0.75rem 1rem;
        color: var(--text-dark);
        transition: all 0.3s ease;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
    }

    .form-select:focus {
        border-color: var(--primary-color);
        outline: none;
        background-color: #fff;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    .select-icon {
        position: absolute;
        top: 50%;
        right: 1rem;
        transform: translateY(-50%);
        pointer-events: none;
        color: var(--text-muted);
    }

    .form-textarea {
        width: 100%;
        background-color: rgba(255, 255, 255, 0.95);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        padding: 0.75rem 1rem;
        color: var(--text-dark);
        transition: all 0.3s ease;
        resize: vertical;
        min-height: 100px;
    }

    .form-textarea:focus {
        border-color: var(--primary-color);
        outline: none;
        background-color: #fff;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    /* Buttons */
    .action-buttons {
        display: flex;
        gap: var(--spacing-sm);
        margin-bottom: var(--spacing-md);
    }

    .btn {
        flex: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.75rem 1rem;
        border-radius: var(--radius-sm);
        font-size: 1rem;
        transition: all 0.3s ease;
        text-decoration: none;
        border: none;
        cursor: pointer;
        gap: 0.5rem;
    }

    .btn-back {
        background-color: var(--neutral-bg);
        color: var(--text-dark);
    }

    .btn-back:hover {
        background-color: var(--neutral-bg-hover);
    }

    .btn-cancel {
        background-color: var(--danger-bg);
        color: var(--danger-color);
    }

    .btn-cancel:hover {
        background-color: var(--danger-bg-hover);
    }

    /* Warning Message */
    .warning {
        color: var(--danger-color);
        font-size: 0.875rem;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .cancellation-container {
            padding: var(--spacing-md);
        }
        
        .glass-card {
            padding: var(--spacing-md);
        }
        
        .title {
            font-size: 1.5rem;
        }
        
        .header-icon {
            font-size: 2rem;
        }
    }

    @media (max-width: 480px) {
        .cancellation-container {
            padding: var(--spacing-sm);
        }
        
        .glass-card {
            padding: var(--spacing-sm);
            border-radius: var(--radius-md);
        }
        
        .title {
            font-size: 1.25rem;
        }
        
        .subtitle {
            font-size: 0.875rem;
        }
        
        .header-icon {
            font-size: 1.75rem;
        }
        
        .action-buttons {
            flex-direction: column;
        }
        
        .btn {
            width: 100%;
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
