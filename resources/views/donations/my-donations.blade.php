<x-app-layout>
    <div class="donations-history-page py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-11">
                    <!-- Header section with stats -->
                    <div class="donations-header mb-4">
                        <div class="row align-items-center">
                            <div class="col-md-7">
                                <h1 class="donations-title"><i
                                        class="fas fa-heart text-primary me-2"></i>{{ __('Historique de vos dons') }}
                                </h1>
                                <p class="text-muted">
                                    {{ __('Merci pour votre générosité et votre soutien continu à notre mission.') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Donation summary cards -->
                    @if (!$donations->isEmpty())
                        <div class="donation-stats mb-5">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="stat-card total-donations">
                                        <div class="stat-icon">
                                            <i class="fas fa-coins"></i>
                                        </div>
                                        <div class="stat-info">
                                            <h3 class="stat-value">
                                                {{ number_format($donations->sum('amount'), 0, ',', ' ') }} XAF</h3>
                                            <p class="stat-label">{{ __('Total des dons') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="stat-card donation-count">
                                        <div class="stat-icon">
                                            <i class="fas fa-gift"></i>
                                        </div>
                                        <div class="stat-info">
                                            <h3 class="stat-value">{{ $donations->count() }}</h3>
                                            <p class="stat-label">{{ __('Nombre de dons') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="stat-card completed-count">
                                        <div class="stat-icon">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                        <div class="stat-info">
                                            <h3 class="stat-value">
                                                {{ $donations->where('status', 'completed')->count() }}</h3>
                                            <p class="stat-label">{{ __('Dons complétés') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Main content card -->
                    <div class="donations-card">
                        <div class="donations-card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h2 class="mb-0"><i class="fas fa-history me-2"></i>{{ __('Vos Donations') }}</h2>

                                @if (!$donations->isEmpty())
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                            id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-filter me-1"></i>{{ __('Filtrer') }}
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end"
                                            aria-labelledby="dropdownMenuButton">
                                            <li><a class="dropdown-item" href="#">{{ __('Tous les dons') }}</a>
                                            </li>
                                            <li><a class="dropdown-item" href="#">{{ __('Dons complétés') }}</a>
                                            </li>
                                            <li><a class="dropdown-item" href="#">{{ __('Dons en attente') }}</a>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li><a class="dropdown-item"
                                                    href="#">{{ __('Trier par montant') }}</a></li>
                                            <li><a class="dropdown-item" href="#">{{ __('Trier par date') }}</a>
                                            </li>
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="donations-card-body">
                            @if ($donations->isEmpty())
                                <div class="empty-donations-container text-center py-5">
                                    <div class="empty-donations-icon mb-3">
                                        <i class="fas fa-hand-holding-heart"></i>
                                    </div>
                                    <h3 class="mb-3">{{ __('Aucun don effectué pour le moment') }}</h3>
                                    <p class="text-muted mb-4">
                                        {{ __('Votre premier don peut faire une grande différence dans notre communauté.') }}
                                    </p>
                                    <a href="{{ route('donations.form') }}" class="btn btn-primary btn-lg px-4">
                                        {{ __('Faire votre premier don') }}
                                    </a>
                                </div>
                            @else
                                <!-- Donations list -->
                                <div class="donations-list">
                                    @foreach ($donations as $donation)
                                        <div class="donation-item">
                                            <div class="donation-icon">
                                                @if ($donation->status == 'completed')
                                                    <i class="fas fa-check-circle text-success"></i>
                                                @elseif($donation->status == 'failed')
                                                    <i class="fas fa-times-circle text-danger"></i>
                                                @else
                                                    <i class="fas fa-clock text-warning"></i>
                                                @endif
                                            </div>
                                            <div class="donation-content">
                                                <div class="donation-details">
                                                    <div class="donation-amount">
                                                        {{ number_format($donation->amount, 0, ',', ' ') }} XAF</div>
                                                    <div class="donation-date"><i class="far fa-calendar-alt me-1"></i>
                                                        {{ $donation->donation_date->format('d/m/Y à H:i') }}</div>
                                                </div>
                                                <div class="donation-meta">
                                                    <div class="donation-method">
                                                        <span class="method-icon">
                                                            @if (optional($donation->paymentMethod)->display_name && strpos(strtolower($donation->paymentMethod->display_name), 'orange') !== false)
                                                                <i class="fas fa-mobile-alt"></i>
                                                            @elseif(optional($donation->paymentMethod)->display_name && strpos(strtolower($donation->paymentMethod->display_name), 'mtn') !== false)
                                                                <i class="fas fa-mobile-alt"></i>
                                                            @elseif(optional($donation->paymentMethod)->display_name && strpos(strtolower($donation->paymentMethod->display_name), 'carte') !== false)
                                                                <i class="far fa-credit-card"></i>
                                                            @elseif(optional($donation->paymentMethod)->display_name &&
                                                                    strpos(strtolower($donation->paymentMethod->display_name), 'virement') !== false)
                                                                <i class="fas fa-university"></i>
                                                            @else
                                                                <i class="fas fa-money-bill-wave"></i>
                                                            @endif
                                                        </span>
                                                        {{ optional($donation->paymentMethod)->display_name ?? 'Inconnu' }}
                                                    </div>
                                                    <div class="donation-status">
                                                        <span class="status-badge status-{{ $donation->status }}">
                                                            @if ($donation->status == 'completed')
                                                                {{ __('Complété') }}
                                                            @elseif($donation->status == 'failed')
                                                                {{ __('Échoué') }}
                                                            @elseif($donation->status == 'pending')
                                                                {{ __('En attente') }}
                                                            @else
                                                                {{ ucfirst($donation->status) }}
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="donation-actions">
                                                <a href="{{ route('donations.confirmation', ['id' => $donation->id]) }}"
                                                    class="btn btn-view-details">
                                                    <i class="fas fa-eye"></i> <span
                                                        class="d-none d-md-inline">{{ __('Détails') }}</span>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Pagination -->
                                <div class="donations-pagination">
                                    {{ $donations->links() }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Call to action banner -->
                    <div class="donation-cta-banner mt-5">
                        <div class="cta-content">
                            <h3>{{ __('Votre générosité fait la différence') }}</h3>
                            <p>{{ __('Continuez à soutenir notre mission avec un nouveau don dès aujourd\'hui.') }}</p>
                        </div>
                        <div class="cta-button">
                            <a href="{{ route('donations.form') }}"
                                class="btn btn-light btn-lg">{{ __('Faire un don') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            /* Styles for the donations history page */
            .donations-history-page {
                background-color: #f8f9fa;
                min-height: 100vh;
            }

            .donations-title {
                font-size: 2rem;
                font-weight: 700;
                color: #333;
                margin-bottom: 0.5rem;
            }

            /* Stats cards */
            .donation-stats {
                margin-top: 1.5rem;
            }

            .stat-card {
                display: flex;
                align-items: center;
                background: white;
                border-radius: 12px;
                padding: 1.5rem;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
                height: 100%;
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }

            .stat-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            }

            .total-donations {
                border-left: 4px solid #4CAF50;
            }

            .donation-count {
                border-left: 4px solid #2196F3;
            }

            .completed-count {
                border-left: 4px solid #FF9800;
            }

            .stat-icon {
                width: 60px;
                height: 60px;
                border-radius: 50%;
                background: #f8f9fa;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-right: 1rem;
                font-size: 1.5rem;
            }

            .total-donations .stat-icon {
                color: #4CAF50;
            }

            .donation-count .stat-icon {
                color: #2196F3;
            }

            .completed-count .stat-icon {
                color: #FF9800;
            }

            .stat-value {
                font-size: 1.5rem;
                font-weight: 700;
                margin-bottom: 0.25rem;
                color: #333;
            }

            .stat-label {
                font-size: 0.9rem;
                color: #6c757d;
                margin: 0;
            }

            /* Main card */
            .donations-card {
                background: white;
                border-radius: 12px;
                overflow: hidden;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            }

            .donations-card-header {
                background-color: #fff;
                padding: 1.5rem;
                border-bottom: 1px solid #f0f0f0;
            }

            .donations-card-header h2 {
                color: #333;
                font-size: 1.25rem;
                font-weight: 600;
            }

            .donations-card-body {
                padding: 0;
            }

            /* Empty state */
            .empty-donations-container {
                padding: 3rem 1.5rem;
            }

            .empty-donations-icon {
                font-size: 4rem;
                color: #e0e0e0;
            }

            /* Donation items */
            .donations-list {
                padding: 0.5rem 0;
            }

            .donation-item {
                display: flex;
                align-items: center;
                padding: 1.25rem 1.5rem;
                border-bottom: 1px solid #f0f0f0;
                transition: background-color 0.2s ease;
            }

            .donation-item:hover {
                background-color: #f8f9fa;
            }

            .donation-icon {
                flex-shrink: 0;
                font-size: 1.5rem;
                margin-right: 1.25rem;
                width: 40px;
                text-align: center;
            }

            .donation-content {
                flex-grow: 1;
                min-width: 0;
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
            }

            .donation-details {
                display: flex;
                flex-wrap: wrap;
                align-items: baseline;
                gap: 1rem;
            }

            .donation-amount {
                font-size: 1.25rem;
                font-weight: 700;
                color: #333;
            }

            .donation-date {
                color: #6c757d;
                font-size: 0.9rem;
            }

            .donation-meta {
                display: flex;
                flex-wrap: wrap;
                gap: 1rem;
            }

            .donation-method {
                display: flex;
                align-items: center;
                color: #6c757d;
                font-size: 0.9rem;
            }

            .method-icon {
                display: inline-flex;
                margin-right: 0.5rem;
            }

            .donation-status {
                display: flex;
                align-items: center;
            }

            .status-badge {
                display: inline-block;
                padding: 0.35rem 0.75rem;
                border-radius: 50px;
                font-size: 0.75rem;
                font-weight: 600;
            }

            .status-completed {
                background-color: #e8f5e9;
                color: #2e7d32;
            }

            .status-pending {
                background-color: #fff8e1;
                color: #f57f17;
            }

            .status-failed {
                background-color: #ffebee;
                color: #c62828;
            }

            .donation-actions {
                margin-left: 1rem;
            }

            .btn-view-details {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                color: #2196F3;
                background-color: #e3f2fd;
                border: none;
                padding: 0.5rem 0.75rem;
                border-radius: 8px;
                transition: all 0.2s ease;
            }

            .btn-view-details:hover {
                background-color: #2196F3;
                color: white;
            }

            .btn-view-details i {
                font-size: 0.9rem;
            }

            /* Pagination */
            .donations-pagination {
                padding: 1.5rem;
                display: flex;
                justify-content: center;
            }

            /* Call to action banner */
            .donation-cta-banner {
                display: flex;
                align-items: center;
                justify-content: space-between;
                background: linear-gradient(120deg, #3949ab, #1e88e5);
                color: white;
                border-radius: 12px;
                padding: 2rem;
                box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            }

            .cta-content h3 {
                font-size: 1.5rem;
                font-weight: 600;
                margin-bottom: 0.5rem;
            }

            .cta-content p {
                margin-bottom: 0;
                opacity: 0.9;
            }

            /* Responsive adjustments */
            @media (max-width: 767.98px) {
                .donation-cta-banner {
                    flex-direction: column;
                    text-align: center;
                    gap: 1.5rem;
                }

                .donation-details,
                .donation-meta {
                    flex-direction: column;
                    gap: 0.5rem;
                }

                .donation-item {
                    flex-wrap: wrap;
                }

                .donation-actions {
                    margin-left: auto;
                    margin-top: 1rem;
                }
            }
        </style>
    @endpush
</x-app-layout>
