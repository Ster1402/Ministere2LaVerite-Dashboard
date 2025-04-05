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
                <!-- Admin Tools Section -->
                @if (Auth::user()->isAdmin() || Auth::user()->isSuperAdmin())
                    <div class="mt-4 flex text-lg-end">
                        <a href="{{ route('admin.deploy-update') }}" class="date-display">
                            Deploy System Updates
                        </a>
                        <a href="{{ route('admin.storage-link') }}" class="date-display">
                            Recreate Storage Links
                        </a>
                    </div>
                @endif
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
                                <x-stats-users-panel class="users-card" />

                                <!-- Resources Stats -->
                                <x-stats-resources-panel class="resources-card" />

                                <!-- Events Stats -->
                                <x-stats-events-panel class="events-card" />

                            </div>
                        </div>

                        <div class="col-lg-4">
                            <!-- Donation Stats Cards -->
                            <x-donation-stats />
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
