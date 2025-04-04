<div class="filters-not-available">
    <div class="alert alert-info">
        <div class="alert-icon">
            <i class="fas fa-info-circle fa-lg"></i>
        </div>
        <div class="alert-content">
            <h6 class="alert-title">Filtrage non disponible</h6>
            <p class="alert-message">{{ $message ?? 'Ce modèle ne prend pas en charge le filtrage dynamique.' }}</p>
        </div>
    </div>

    <div class="text-center mt-4">
        <i class="fas fa-filter fa-3x text-muted mb-3"></i>
        <p class="text-muted">Utilisez d'autres options pour personnaliser votre rapport.</p>
    </div>
</div>

<style>
    .filters-not-available {
        padding: 1.5rem;
    }

    .alert {
        display: flex;
        align-items: flex-start;
        background-color: #eff6ff;
        border-left: 4px solid #3b82f6;
        border-radius: 8px;
        padding: 1rem;
    }

    .alert-icon {
        color: #3b82f6;
        margin-right: 1rem;
        padding-top: 0.2rem;
    }

    .alert-content {
        flex: 1;
    }

    .alert-title {
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #1e40af;
    }

    .alert-message {
        margin: 0;
        color: #334155;
    }
</style>
