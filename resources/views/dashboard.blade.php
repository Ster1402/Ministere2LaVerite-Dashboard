<x-app-layout>
    <div class="dashboard-wrapper">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h1 class="welcome-title">Bienvenue, {{ Auth::user()->name }}</h1>
                        <p class="welcome-subtitle">Voici un aperçu de votre activité et des statistiques récentes.</p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <div class="date-display">
                            <i class="far fa-calendar-alt"></i> {{ now()->format('d F Y') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Dashboard Content -->
        <div class="dashboard-content">
            <div class="container-fluid">
                <!-- Main Stats Section -->
                <div class="dashboard-section">
                    <div class="row">
                        <div class="col-lg-8">
                            <!-- Key Metrics Cards -->
                            <div class="metrics-grid">
                                <!-- Users Stats -->
                                <div class="metric-card users-card">
                                    <div class="metric-icon">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div class="metric-content">
                                        <h3 class="metric-title">Utilisateurs</h3>
                                        <div class="metric-value">{{ $total ?? '0' }}</div>
                                        <div class="metric-subtitle">comptes actifs</div>
                                    </div>
                                    <div class="metric-chart">
                                        <!-- Small line chart would go here -->
                                        <div class="sparkline-chart"></div>
                                    </div>
                                </div>

                                <!-- Resources Stats -->
                                <div class="metric-card resources-card">
                                    <div class="metric-icon">
                                        <i class="fas fa-box-open"></i>
                                    </div>
                                    <div class="metric-content">
                                        <h3 class="metric-title">Ressources</h3>
                                        <div class="metric-value">{{ $stats['ressources']['total'] ?? '0' }}</div>
                                        <div class="metric-subtitle">ressources disponibles</div>
                                    </div>
                                    <div class="metric-chart">
                                        <div class="sparkline-chart"></div>
                                    </div>
                                </div>

                                <!-- Events Stats -->
                                <div class="metric-card events-card">
                                    <div class="metric-icon">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    <div class="metric-content">
                                        <h3 class="metric-title">Événements</h3>
                                        <div class="metric-value">{{ $stats['events']['total'] ?? '0' }}</div>
                                        <div class="metric-subtitle">événements planifiés</div>
                                    </div>
                                    <div class="metric-chart">
                                        <div class="sparkline-chart"></div>
                                    </div>
                                </div>

                                <!-- Account Balance -->
                                <div class="metric-card balance-card">
                                    <div class="metric-icon">
                                        <i class="fas fa-wallet"></i>
                                    </div>
                                    <div class="metric-content">
                                        <h3 class="metric-title">Solde courant</h3>
                                        <div class="metric-value">{{ number_format($total ?? 0, 0, ',', ' ') }} XAF
                                        </div>
                                        <div class="metric-subtitle">disponible maintenant</div>
                                    </div>
                                    <a href="#" class="metric-action">
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <!-- Donation Stats Cards -->
                            <div class="donation-stats-container">
                                <h2 class="section-title"><i class="fas fa-chart-line me-2"></i>Statistiques des Dons
                                </h2>

                                <div class="donation-stat-card total-donations">
                                    <div class="stat-icon">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </div>
                                    <div class="stat-content">
                                        <div class="stat-value">{{ number_format($totalAmount ?? 0, 0, ',', ' ') }} XAF
                                        </div>
                                        <div class="stat-label">Total des dons</div>
                                    </div>
                                    <div class="stat-trend positive">
                                        <i class="fas fa-arrow-up"></i> 5.2%
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
                                        <i class="fas fa-arrow-up"></i> 3.1%
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
                                        <i class="fas fa-minus"></i> 0%
                                    </div>
                                </div>

                                <div class="call-to-action">
                                    <a href="{{ route('donations.form') }}" class="btn-donate">
                                        <i class="fas fa-heart me-2"></i> Faire un don maintenant
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <x-recent-donations />

            </div>
        </div>
    </div>

    <!-- Report Modal -->
    <x-report-modal model-name="donations" title="Exporter la liste des dons" />

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // Simulate simple charts
            const chartElements = document.querySelectorAll('.sparkline-chart');
            chartElements.forEach(el => {
                // This would be replaced with actual chart library code
                // For now, just add a placeholder
                el.innerHTML = `<svg viewBox="0 0 100 30" class="sparkline">
                <path d="M0,15 L10,10 L20,20 L30,5 L40,15 L50,10 L60,20 L70,15 L80,5 L90,10 L100,15" fill="none" stroke="rgba(59, 130, 246, 0.5)" stroke-width="2" />
            </svg>`;
            });
        });
    </script>
</x-app-layout>
