<x-app-layout>
    <x-slot name="header">
        <div class="page-header-content">
            <h1 class="page-title">Journal du Système</h1>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Journal du système</li>
                    </ol>
                </nav>
            </div>
        </div>
    </x-slot>

    <div class="container-fluid">
        <!-- Statistiques des logs -->
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card stat-card mb-4">
                    <div class="card-body">
                        <div class="stat-icon info">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div class="stat-details">
                            <h3 class="stat-value" id="info-count">{{ $logCounts['info'] ?? 0 }}</h3>
                            <p class="stat-label">Informations</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card stat-card mb-4">
                    <div class="card-body">
                        <div class="stat-icon warning">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="stat-details">
                            <h3 class="stat-value" id="warning-count">{{ $logCounts['warning'] ?? 0 }}</h3>
                            <p class="stat-label">Avertissements</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card stat-card mb-4">
                    <div class="card-body">
                        <div class="stat-icon error">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                        <div class="stat-details">
                            <h3 class="stat-value" id="error-count">{{ $logCounts['error'] ?? 0 }}</h3>
                            <p class="stat-label">Erreurs</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card stat-card mb-4">
                    <div class="card-body">
                        <div class="stat-icon debug">
                            <i class="fas fa-bug"></i>
                        </div>
                        <div class="stat-details">
                            <h3 class="stat-value" id="debug-count">{{ $logCounts['debug'] ?? 0 }}</h3>
                            <p class="stat-label">Débogage</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres et options -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Filtres</h4>
                <button type="button" class="btn btn-sm btn-outline-secondary" id="toggle-filters">
                    <i class="fas fa-filter"></i> Options de filtrage
                </button>
            </div>
            <div class="card-body filter-section" id="filters-section">
                <form id="log-filter-form" method="GET">
                    <div class="row">
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="date-range" class="form-label">Période</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                <input type="text" id="date-range" name="date_range"
                                    class="form-control date-range-picker" placeholder="Sélectionner une période"
                                    value="{{ request('date_range') }}">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="log-level" class="form-label">Niveau de log</label>
                            <select id="log-level" name="level" class="form-select">
                                <option value="">Tous les niveaux</option>
                                <option value="debug" {{ request('level') == 'debug' ? 'selected' : '' }}>Débogage
                                </option>
                                <option value="info" {{ request('level') == 'info' ? 'selected' : '' }}>Information
                                </option>
                                <option value="notice" {{ request('level') == 'notice' ? 'selected' : '' }}>Notice
                                </option>
                                <option value="warning" {{ request('level') == 'warning' ? 'selected' : '' }}>
                                    Avertissement</option>
                                <option value="error" {{ request('level') == 'error' ? 'selected' : '' }}>Erreur
                                </option>
                                <option value="critical" {{ request('level') == 'critical' ? 'selected' : '' }}>
                                    Critique</option>
                                <option value="alert" {{ request('level') == 'alert' ? 'selected' : '' }}>Alerte
                                </option>
                                <option value="emergency" {{ request('level') == 'emergency' ? 'selected' : '' }}>
                                    Urgence</option>
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="search-term" class="form-label">Rechercher</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" id="search-term" name="search" class="form-control"
                                    placeholder="Rechercher dans les logs..." value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="channel" class="form-label">Canal</label>
                            <select id="channel" name="channel" class="form-select">
                                <option value="">Tous les canaux</option>
                                @foreach ($channels ?? [] as $channelName)
                                    <option value="{{ $channelName }}"
                                        {{ request('channel') == $channelName ? 'selected' : '' }}>{{ $channelName }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="context" class="form-label">Contexte</label>
                            <select id="context" name="context" class="form-select">
                                <option value="">Tous les contextes</option>
                                @foreach ($contexts ?? [] as $contextName)
                                    <option value="{{ $contextName }}"
                                        {{ request('context') == $contextName ? 'selected' : '' }}>{{ $contextName }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-6 mb-3 d-flex align-items-end">
                            <div class="btn-group w-100">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter me-1"></i> Filtrer
                                </button>
                                <a href="{{ route('journal') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i> Réinitialiser
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tableau des logs -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Journal d'activité</h4>
                <div class="header-actions">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                            id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-download me-1"></i> Exporter
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="exportDropdown">
                            <li><a class="dropdown-item"
                                    href="{{ route('journal.export', array_merge(request()->all(), ['format' => 'csv'])) }}"><i
                                        class="fas fa-file-csv me-2"></i>CSV</a></li>
                            <li><a class="dropdown-item"
                                    href="{{ route('journal.export', array_merge(request()->all(), ['format' => 'excel'])) }}"><i
                                        class="fas fa-file-excel me-2"></i>Excel</a></li>
                            <li><a class="dropdown-item"
                                    href="{{ route('journal.export', array_merge(request()->all(), ['format' => 'pdf'])) }}"><i
                                        class="fas fa-file-pdf me-2"></i>PDF</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover logs-table mb-0">
                        <thead>
                            <tr>
                                <th>Niveau</th>
                                <th>Horodatage</th>
                                <th>Canal</th>
                                <th>Message</th>
                                <th>Utilisateur</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs ?? [] as $log)
                                <tr class="log-row" data-level="{{ $log->level }}">
                                    <td>
                                        <span class="log-level-badge level-{{ $log->level }}">
                                            @if ($log->level == 'info')
                                                <i class="fas fa-info-circle"></i>
                                            @elseif($log->level == 'warning')
                                                <i class="fas fa-exclamation-triangle"></i>
                                            @elseif($log->level == 'error' || $log->level == 'critical' || $log->level == 'alert' || $log->level == 'emergency')
                                                <i class="fas fa-exclamation-circle"></i>
                                            @elseif($log->level == 'debug')
                                                <i class="fas fa-bug"></i>
                                            @else
                                                <i class="fas fa-bell"></i>
                                            @endif
                                            {{ ucfirst($log->level) }}
                                        </span>
                                    </td>
                                    <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                                    <td>{{ $log->channel }}</td>
                                    <td class="log-message-cell">
                                        <div class="log-message text-truncate">{{ $log->message }}</div>
                                    </td>
                                    <td>
                                        @if ($log->user_id)
                                            <span class="user-info">
                                                <a
                                                    href="{{ route('users.show', $log->user_id) }}">{{ $log->user->name ?? 'Utilisateur #' . $log->user_id }}</a>
                                            </span>
                                        @else
                                            <span class="text-muted">Système</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-icon view-log-details"
                                            data-log-id="{{ $log->id }}" data-bs-toggle="modal"
                                            data-bs-target="#logDetailsModal">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="empty-state">
                                            <i class="fas fa-search mb-3"></i>
                                            <p>Aucun log trouvé correspondant à vos critères.</p>
                                            @if (request()->has('level') ||
                                                    request()->has('search') ||
                                                    request()->has('date_range') ||
                                                    request()->has('channel') ||
                                                    request()->has('context'))
                                                <a href="{{ route('journal') }}"
                                                    class="btn btn-sm btn-outline-primary mt-2">
                                                    Réinitialiser les filtres
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if (isset($logs) && method_exists($logs, 'links'))
                <div class="card-footer">
                    <div class="d-flex justify-content-center">
                        {{ $logs->appends(request()->except('page'))->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal pour les détails du log -->
    <div class="modal fade" id="logDetailsModal" tabindex="-1" aria-labelledby="logDetailsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logDetailsModalLabel">Détails du log</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <div class="log-detail-header">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="log-level-large">
                                    <span class="log-level-badge level-info" id="log-detail-level-badge">
                                        <i class="fas fa-info-circle" id="log-detail-level-icon"></i>
                                        <span id="log-detail-level">Information</span>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <div class="log-timestamp">
                                    <i class="fas fa-clock me-1"></i>
                                    <span id="log-detail-timestamp">01/01/2023 12:00:00</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="log-detail-message mb-4">
                        <label class="form-label text-muted mb-1">Message</label>
                        <div class="log-message-box" id="log-detail-message">
                            Le message du log apparaîtra ici.
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Canal</label>
                            <div class="field-value" id="log-detail-channel">laravel</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Utilisateur</label>
                            <div class="field-value" id="log-detail-user">Système</div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Adresse IP</label>
                            <div class="field-value" id="log-detail-ip">127.0.0.1</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">URI</label>
                            <div class="field-value" id="log-detail-uri">/api/endpoint</div>
                        </div>
                    </div>

                    <div class="log-detail-context">
                        <label class="form-label text-muted mb-1">Contexte</label>
                        <div class="code-container">
                            <pre id="log-detail-context">{ "key": "value" }</pre>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            /* Styles pour la page des logs */
            .stat-card {
                transition: all 0.3s ease;
            }

            .stat-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            }

            .stat-card .card-body {
                display: flex;
                align-items: center;
                padding: 1.5rem;
            }

            .stat-icon {
                width: 60px;
                height: 60px;
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.75rem;
                margin-right: 1rem;
            }

            .stat-icon.info {
                background-color: rgba(59, 130, 246, 0.15);
                color: #3b82f6;
            }

            .stat-icon.warning {
                background-color: rgba(245, 158, 11, 0.15);
                color: #f59e0b;
            }

            .stat-icon.error {
                background-color: rgba(239, 68, 68, 0.15);
                color: #ef4444;
            }

            .stat-icon.debug {
                background-color: rgba(139, 92, 246, 0.15);
                color: #8b5cf6;
            }

            .stat-details {
                flex-grow: 1;
            }

            .stat-value {
                font-size: 1.75rem;
                font-weight: 700;
                margin-bottom: 0.25rem;
                line-height: 1;
            }

            .stat-label {
                color: #6b7280;
                margin-bottom: 0;
                font-size: 0.9rem;
            }

            .filter-section {
                transition: all 0.3s ease;
            }

            /* Niveau de Log */
            .log-level-badge {
                display: inline-flex;
                align-items: center;
                padding: 0.35rem 0.75rem;
                border-radius: 50px;
                font-size: 0.775rem;
                font-weight: 600;
                white-space: nowrap;
            }

            .log-level-badge i {
                margin-right: 0.35rem;
            }

            .level-debug {
                background-color: #f5f3ff;
                color: #8b5cf6;
            }

            .level-info {
                background-color: #eff6ff;
                color: #3b82f6;
            }

            .level-notice {
                background-color: #ecfdf5;
                color: #10b981;
            }

            .level-warning {
                background-color: #fffbeb;
                color: #f59e0b;
            }

            .level-error {
                background-color: #fee2e2;
                color: #ef4444;
            }

            .level-critical,
            .level-alert,
            .level-emergency {
                background-color: #fef2f2;
                color: #dc2626;
            }

            /* Tableau des logs */
            .logs-table {
                font-size: 0.95rem;
            }

            .logs-table th {
                font-weight: 600;
                text-transform: uppercase;
                font-size: 0.8rem;
                color: #6b7280;
                letter-spacing: 0.5px;
            }

            .logs-table th,
            .logs-table td {
                padding: 0.75rem 1rem;
                vertical-align: middle;
            }

            .log-row {
                transition: background-color 0.15s ease;
            }

            .log-row:hover {
                background-color: #f9fafb;
            }

            .log-message-cell {
                max-width: 350px;
            }

            .log-message {
                max-width: 100%;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .btn-icon {
                width: 32px;
                height: 32px;
                padding: 0;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border-radius: 50%;
                color: #6b7280;
                background-color: #f3f4f6;
                border: none;
                transition: all 0.2s ease;
            }

            .btn-icon:hover {
                background-color: #e5e7eb;
                color: #374151;
            }

            /* État vide */
            .empty-state {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                color: #9ca3af;
                padding: 2rem 0;
            }

            .empty-state i {
                font-size: 2.5rem;
                margin-bottom: 1rem;
            }

            /* Modal des détails */
            .log-level-large .log-level-badge {
                padding: 0.5rem 1rem;
                font-size: 1rem;
            }

            .log-message-box {
                padding: 1rem;
                background-color: #f9fafb;
                border-radius: 0.5rem;
                border: 1px solid #e5e7eb;
                white-space: pre-wrap;
                word-break: break-word;
            }

            .field-value {
                font-weight: 500;
                color: #111827;
            }

            .code-container {
                background-color: #f9fafb;
                border-radius: 0.5rem;
                border: 1px solid #e5e7eb;
            }

            .code-container pre {
                margin: 0;
                padding: 1rem;
                font-size: 0.875rem;
                white-space: pre-wrap;
                word-break: break-word;
                color: #334155;
            }

            /* Date Picker */
            .date-range-picker {
                cursor: pointer;
                background-color: #fff !important;
            }

            /* Responsive */
            @media (max-width: 768px) {
                .log-message-cell {
                    max-width: 200px;
                }
            }

            @media (max-width: 576px) {

                .logs-table th:nth-child(3),
                .logs-table td:nth-child(3),
                .logs-table th:nth-child(5),
                .logs-table td:nth-child(5) {
                    display: none;
                }

                .log-message-cell {
                    max-width: 150px;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialisation de la date picker
                if (typeof $ !== 'undefined' && typeof moment !== 'undefined' && $.fn.daterangepicker) {
                    $('.date-range-picker').daterangepicker({
                        autoUpdateInput: false,
                        locale: {
                            format: 'DD/MM/YYYY',
                            applyLabel: 'Appliquer',
                            cancelLabel: 'Annuler',
                            fromLabel: 'Du',
                            toLabel: 'Au',
                            customRangeLabel: 'Personnalisé',
                            daysOfWeek: ['Di', 'Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa'],
                            monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet',
                                'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
                            ],
                            firstDay: 1
                        },
                        ranges: {
                            'Aujourd\'hui': [moment(), moment()],
                            'Hier': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                            '7 derniers jours': [moment().subtract(6, 'days'), moment()],
                            '30 derniers jours': [moment().subtract(29, 'days'), moment()],
                            'Ce mois-ci': [moment().startOf('month'), moment().endOf('month')],
                            'Mois précédent': [moment().subtract(1, 'month').startOf('month'), moment()
                                .subtract(1, 'month').endOf('month')
                            ]
                        }
                    });

                    $('.date-range-picker').on('apply.daterangepicker', function(ev, picker) {
                        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format(
                            'DD/MM/YYYY'));
                    });

                    $('.date-range-picker').on('cancel.daterangepicker', function(ev, picker) {
                        $(this).val('');
                    });
                }

                // Toggle des filtres
                const toggleFiltersBtn = document.getElementById('toggle-filters');
                const filtersSection = document.getElementById('filters-section');

                if (toggleFiltersBtn && filtersSection) {
                    toggleFiltersBtn.addEventListener('click', function() {
                        const isExpanded = filtersSection.style.display !== 'none';

                        if (isExpanded) {
                            filtersSection.style.display = 'none';
                            toggleFiltersBtn.innerHTML = '<i class="fas fa-filter"></i> Afficher les filtres';
                        } else {
                            filtersSection.style.display = 'block';
                            toggleFiltersBtn.innerHTML = '<i class="fas fa-filter"></i> Masquer les filtres';
                        }
                    });
                }

                // Gestion de l'affichage des détails du log
                const viewLogDetailsBtns = document.querySelectorAll('.view-log-details');

                viewLogDetailsBtns.forEach(btn => {
                    btn.addEventListener('click', function() {
                        const logId = this.getAttribute('data-log-id');

                        // Simuler un chargement des données
                        document.getElementById('log-detail-level').textContent = 'Chargement...';
                        document.getElementById('log-detail-message').textContent =
                            'Chargement des détails du log...';

                        // Faire une requête AJAX pour obtenir les détails du log
                        fetch(`/api/logs/${logId}`)
                            .then(response => response.json())
                            .then(data => {
                                // Mise à jour des détails du log dans la modal
                                document.getElementById('log-detail-level').textContent = data.level
                                    .charAt(0).toUpperCase() + data.level.slice(1);
                                document.getElementById('log-detail-level-badge').className =
                                    `log-level-badge level-${data.level}`;

                                // Mettre à jour l'icône selon le niveau
                                const iconElement = document.getElementById(
                                'log-detail-level-icon');
                                if (data.level === 'info') {
                                    iconElement.className = 'fas fa-info-circle';
                                } else if (data.level === 'warning') {
                                    iconElement.className = 'fas fa-exclamation-triangle';
                                } else if (['error', 'critical', 'alert', 'emergency'].includes(data
                                        .level)) {
                                    iconElement.className = 'fas fa-exclamation-circle';
                                } else if (data.level === 'debug') {
                                    iconElement.className = 'fas fa-bug';
                                } else {
                                    iconElement.className = 'fas fa-bell';
                                }

                                document.getElementById('log-detail-timestamp').textContent = data
                                    .formatted_created_at;
                                document.getElementById('log-detail-message').textContent = data
                                    .message;
                                document.getElementById('log-detail-channel').textContent = data
                                    .channel;

                                // Mise à jour des informations utilisateur
                                if (data.user_id) {
                                    document.getElementById('log-detail-user').innerHTML =
                                        `<a href="/users/${data.user_id}">${data.user_name || 'Utilisateur #' + data.user_id}</a>`;
                                } else {
                                    document.getElementById('log-detail-user').textContent =
                                        'Système';
                                }

                                document.getElementById('log-detail-ip').textContent = data
                                    .ip_address || 'N/A';
                                document.getElementById('log-detail-uri').textContent = data.uri ||
                                    'N/A';

                                // Mise à jour du contexte (formaté)
                                try {
                                    const contextObj = typeof data.context === 'object' ? data
                                        .context : JSON.parse(data.context || '{}');
                                    document.getElementById('log-detail-context').textContent = JSON
                                        .stringify(contextObj, null, 2);
                                } catch (e) {
                                    document.getElementById('log-detail-context').textContent = data
                                        .context || '{}';
                                }
                            })
                            .catch(error => {
                                console.error('Erreur lors de la récupération des détails du log:',
                                    error);
                                document.getElementById('log-detail-message').textContent =
                                    'Erreur lors de la récupération des détails du log.';
                            });
                    });
                });

                // Fonction pour mettre à jour les compteurs en temps réel
                function updateLogCounters() {
                    fetch('/api/logs/counts')
                        .then(response => response.json())
                        .then(data => {
                            document.getElementById('info-count').textContent = data.info || 0;
                            document.getElementById('warning-count').textContent = data.warning || 0;
                            document.getElementById('error-count').textContent = data.error || 0;
                            document.getElementById('debug-count').textContent = data.debug || 0;
                        })
                        .catch(error => console.error('Erreur de mise à jour des compteurs:', error));
                }

                // Mettre à jour les compteurs toutes les 60 secondes
                setInterval(updateLogCounters, 60000);

                // Ajout de la fonctionnalité de recherche en temps réel
                const searchInput = document.getElementById('search-term');
                if (searchInput) {
                    let timeout = null;

                    searchInput.addEventListener('input', function() {
                        clearTimeout(timeout);

                        timeout = setTimeout(() => {
                            const filterForm = document.getElementById('log-filter-form');
                            if (filterForm) {
                                filterForm.submit();
                            }
                        }, 500);
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
