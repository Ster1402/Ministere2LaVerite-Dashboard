<div class="donation-stats-container">
    <h2 class="section-title">Statistiques des Dons
    </h2>

    <div class="donation-stat-card total-donations">
        <div class="stat-icon">
            <i class="fas fa-money-bill-wave"></i>
        </div>
        <div class="stat-content">
            <div class="stat-value">{{ number_format($totalAmount, 0, ',', ' ') }} XAF
            </div>
            <div class="stat-label">Total des dons</div>
        </div>
    </div>

    <div class="donation-stat-card completed-donations">
        <div class="stat-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-content">
            <div class="stat-value">{{ $donationsCount ?? 0 }}</div>
            <div class="stat-label">Dons reçus</div>
        </div>
        <div class="stat-trend positive">
            <i class="fas fa-arrow-up"></i>
            {{ $donationsCount + $pendingCount > 0 ? number_format(($donationsCount / ($donationsCount + $pendingCount)) * 100, 0, ',', ' ') . '%' : '0%' }}
        </div>
    </div>

    <div class="donation-stat-card pending-donations">
        <div class="stat-icon">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-content">
            <div class="stat-value">{{ $pendingCount ?? 0 }}</div>
            <div class="stat-label">Dons en attente</div>
        </div>
        <div class="stat-trend neutral">
            <i class="fas fa-minus"></i>
            {{ $donationsCount + $pendingCount > 0 ? number_format(($pendingCount / ($donationsCount + $pendingCount)) * 100, 0, ',', ' ') . '%' : '0%' }}
        </div>
    </div>

    <div class="call-to-action">
        <a href="{{ route('donations.form') }}" class="btn-donate">
            <i class="fas fa-heart me-2"></i> Faire un don maintenant
        </a>
    </div>
</div>
