<div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title-wrapper">
                    <i class="fas fa-file-export modal-icon"></i>
                    <h5 class="modal-title" id="reportModalLabel">{{ $title }}</h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="reportForm" class="needs-validation" method="POST" action="{{ route('reports.generate') }}"
                    target="_blank" novalidate>
                    @csrf
                    <input type="hidden" name="model" value="{{ $modelName }}">

                    <div class="report-options">
                        <!-- Tab navigation for Format and Filters -->
                        <ul class="nav nav-tabs mb-3" id="reportTabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="format-tab" data-toggle="tab" href="#format-panel"
                                    role="tab" aria-controls="format-panel" aria-selected="true">
                                    <i class="fas fa-file-alt me-1"></i> Format
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="filters-tab" data-toggle="tab" href="#filters-panel"
                                    role="tab" aria-controls="filters-panel" aria-selected="false">
                                    <i class="fas fa-filter me-1"></i> Filtres
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="columns-tab" data-toggle="tab" href="#columns-panel"
                                    role="tab" aria-controls="columns-panel" aria-selected="false">
                                    <i class="fas fa-columns me-1"></i> Colonnes
                                </a>
                            </li>
                        </ul>

                        <!-- Tab content -->
                        <div class="tab-content" id="reportTabsContent">
                            <!-- Format Tab -->
                            <div class="tab-pane fade show active" id="format-panel" role="tabpanel"
                                aria-labelledby="format-tab">
                                <div class="form-section">
                                    <h6 class="section-title">Options d'export</h6>

                                    <div class="mb-3">
                                        <label for="format" class="form-label">Format d'export</label>
                                        <div class="format-selector">
                                            <div class="format-option">
                                                <input type="radio" class="btn-check d-contents" name="format"
                                                    id="format-pdf" value="pdf" checked>
                                                <label class="btn format-btn" for="format-pdf">
                                                    <i class="fas fa-file-pdf"></i>
                                                    <span>PDF</span>
                                                </label>
                                            </div>
                                            <div class="format-option">
                                                <input type="radio" class="btn-check d-contents" name="format"
                                                    id="format-excel" value="excel">
                                                <label class="btn format-btn" for="format-excel">
                                                    <i class="fas fa-file-excel"></i>
                                                    <span>Excel</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="paper-options" id="pdf-options">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="paper_size" class="form-label">Format de papier</label>
                                                <select id="paper_size" name="paper_size" class="form-select">
                                                    @foreach ($paperSizes as $value => $label)
                                                        <option value="{{ $value }}">{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="orientation" class="form-label">Orientation</label>
                                                <select id="orientation" name="orientation" class="form-select">
                                                    @foreach ($orientations as $value => $label)
                                                        <option value="{{ $value }}">{{ $label }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Filters Tab -->
                            <div class="tab-pane fade" id="filters-panel" role="tabpanel"
                                aria-labelledby="filters-tab">
                                <div class="form-section">
                                    <h6 class="section-title">Filtrer les données</h6>
                                    <div id="dynamic-filters-container">
                                        <!-- Dynamic filters will be loaded here via AJAX -->
                                        <div class="loading-filters text-center py-4">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                            <p class="mt-2">Chargement des filtres...</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Columns Tab -->
                            <div class="tab-pane fade" id="columns-panel" role="tabpanel"
                                aria-labelledby="columns-tab">
                                <div class="form-section">
                                    <h6 class="section-title">Données à inclure</h6>

                                    <div class="mb-3">
                                        <label for="reportColumns" class="form-label">Sélection des colonnes</label>
                                        <div class="column-actions mb-2">
                                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                                id="selectAllColumns">
                                                <i class="fas fa-check-square me-1"></i> Tout sélectionner
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                                id="deselectAllColumns">
                                                <i class="fas fa-square me-1"></i> Tout désélectionner
                                            </button>
                                        </div>
                                        <select name="columns[]" class="form-control select2" multiple
                                            id="reportColumns">
                                            @foreach ($columns as $key => $column)
                                                <option value="{{ $key }}">{{ $column['title'] }}</option>
                                            @endforeach
                                        </select>
                                        <div class="form-text">
                                            <i class="fas fa-info-circle me-1"></i>Sélectionnez les colonnes à inclure
                                            dans le rapport
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Annuler
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-file-export me-1"></i>Générer le rapport
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* Report Modal Styles */
    #reportModal .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    #reportModal .modal-header {
        background: linear-gradient(135deg, #1c2c5b, #1e3a8a);
        color: white;
        border-bottom: none;
        padding: 1.25rem 1.5rem;
    }

    .modal-title-wrapper {
        display: flex;
        align-items: center;
    }

    .modal-icon {
        font-size: 1.5rem;
        margin-right: 0.75rem;
        opacity: 0.9;
    }

    #reportModal .modal-title {
        font-weight: 600;
        font-size: 1.25rem;
    }

    #reportModal .close {
        color: white;
        opacity: 0.8;
        transition: opacity 0.2s;
    }

    #reportModal .close:hover {
        opacity: 1;
    }

    #reportModal .modal-body {
        padding: 1.5rem;
    }

    #reportTabs .nav-link {
        color: #64748b;
        font-weight: 500;
        padding: 0.75rem 1rem;
        border-radius: 0.375rem 0.375rem 0 0;
        transition: all 0.2s ease;
    }

    #reportTabs .nav-link:hover {
        color: #334155;
        background-color: #f8fafc;
    }

    #reportTabs .nav-link.active {
        color: #3b82f6;
        border-color: #e2e8f0 #e2e8f0 #ffffff;
        font-weight: 600;
    }

    #reportTabs .nav-link i {
        margin-right: 0.5rem;
    }

    .report-options {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .form-section {
        background-color: #f8fafc;
        border-radius: 10px;
        padding: 1.25rem;
    }

    .section-title {
        font-size: 1rem;
        font-weight: 600;
        color: #334155;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
    }

    .section-title::before {
        content: '';
        display: inline-block;
        width: 4px;
        height: 18px;
        background-color: #3b82f6;
        border-radius: 2px;
        margin-right: 0.5rem;
    }

    .form-label {
        font-weight: 500;
        color: #475569;
        margin-bottom: 0.5rem;
    }

    .format-selector {
        display: flex;
        gap: 1rem;
    }

    .format-option {
        flex: 1;
    }

    .format-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 100%;
        padding: 1rem 0.5rem;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background-color: white;
        transition: all 0.2s ease;
    }

    .format-btn i {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
        color: #64748b;
    }

    .format-btn span {
        font-weight: 500;
        color: #334155;
    }

    .btn-check:checked+.format-btn {
        background-color: #eff6ff;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
    }

    .btn-check:checked+.format-btn i {
        color: #3b82f6;
    }

    #format-pdf+.format-btn i {
        color: #ef4444;
    }

    #format-excel+.format-btn i {
        color: #22c55e;
    }

    .btn-check:checked+.format-btn#format-pdf+.format-btn i {
        color: #ef4444;
    }

    .btn-check:checked+.format-btn#format-excel+.format-btn i {
        color: #22c55e;
    }

    .paper-options {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #e2e8f0;
    }

    .form-select {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.5rem 0.75rem;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .form-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        outline: none;
    }

    /* Style particulier pour la sélection des colonnes */
    .select-columns {
        display: block;
        width: 100%;
        height: 120px !important;
    }

    /* Correction pour Select2 si utilisé */
    .select2-container {
        width: 100% !important;
    }

    .form-text {
        font-size: 0.85rem;
        color: #64748b;
        margin-top: 0.5rem;
    }

    .column-actions {
        display: flex;
        gap: 0.5rem;
        margin-top: 0.75rem;
    }

    .column-actions .btn-outline-secondary {
        font-size: 0.85rem;
        padding: 0.25rem 0.5rem;
        color: #64748b;
        border-color: #e2e8f0;
        background-color: white;
    }

    .column-actions .btn-outline-secondary:hover {
        background-color: #f1f5f9;
        color: #334155;
    }

    #reportModal .modal-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid #f1f5f9;
    }

    #reportModal .btn-outline-secondary {
        color: #64748b;
        border-color: #e2e8f0;
    }

    #reportModal .btn-outline-secondary:hover {
        background-color: #f1f5f9;
        color: #334155;
    }

    #reportModal .btn-primary {
        background: linear-gradient(to right, #3b82f6, #2563eb);
        border: none;
        padding: 0.5rem 1.25rem;
    }

    #reportModal .btn-primary:hover {
        background: linear-gradient(to right, #2563eb, #1d4ed8);
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    /* Filter Styles */
    .filter-row {
        background-color: white;
        border-radius: 6px;
        padding: 12px;
        margin-bottom: 10px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        border: 1px solid #e5e7eb;
        transition: all 0.2s ease;
    }

    .filter-row:hover {
        border-color: #cbd5e1;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.08);
    }

    .filters-container {
        max-height: 350px;
        overflow-y: auto;
        padding-right: 5px;
    }

    .filters-container::-webkit-scrollbar {
        width: 6px;
    }

    .filters-container::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }

    .filters-container::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    .filter-actions {
        display: flex;
        justify-content: space-between;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #e2e8f0;
    }

    .filter-actions-left,
    .filter-actions-right {
        display: flex;
        gap: 8px;
    }

    .remove-filter {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #fee2e2;
        color: #ef4444;
        border: none;
        transition: all 0.2s ease;
    }

    .remove-filter:hover {
        background-color: #fecaca;
        color: #dc2626;
    }

    /* Responsive Adjustments */
    @media (max-width: 767px) {
        .format-selector {
            flex-direction: column;
            gap: 0.5rem;
        }

        .filter-actions {
            flex-direction: column;
            gap: 10px;
        }

        .filter-actions-left,
        .filter-actions-right {
            width: 100%;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle PDF-specific options when format changes
        const formatPdf = document.getElementById('format-pdf');
        const formatExcel = document.getElementById('format-excel');
        const pdfOptions = document.getElementById('pdf-options');

        function togglePdfOptions() {
            if (formatPdf.checked) {
                pdfOptions.style.display = 'block';
            } else {
                pdfOptions.style.display = 'none';
            }
        }

        formatPdf.addEventListener('change', togglePdfOptions);
        formatExcel.addEventListener('change', togglePdfOptions);

        // Initial state
        togglePdfOptions();

        // Column selection helpers
        const selectAllBtn = document.getElementById('selectAllColumns');
        const deselectAllBtn = document.getElementById('deselectAllColumns');
        const columnsSelect = document.getElementById('reportColumns');

        if (selectAllBtn && deselectAllBtn && columnsSelect) {
            selectAllBtn.addEventListener('click', function() {
                Array.from(columnsSelect.options).forEach(option => {
                    option.selected = true;
                });
                // Trigger change event for Select2 if used
                $(columnsSelect).trigger('change');
            });

            deselectAllBtn.addEventListener('click', function() {
                Array.from(columnsSelect.options).forEach(option => {
                    option.selected = false;
                });
                // Trigger change event for Select2 if used
                $(columnsSelect).trigger('change');
            });
        }

        // Initialize Select2 if available
        if (typeof $.fn.select2 !== 'undefined') {
            $('#reportColumns').select2({
                placeholder: 'Sélectionnez les colonnes',
                allowClear: true,
                width: '100%'
            });
        }

        // Load filters when filters tab is clicked
        $('#filters-tab').on('click', function() {
            loadDynamicFilters();
        });

        // When modal is shown, initialize dynamic content
        $('#reportModal').on('shown.bs.modal', function() {
            // Initialize filters if filters tab is active
            if ($('#filters-tab').hasClass('active')) {
                loadDynamicFilters();
            }
        });

        // Function to load dynamic filters via AJAX
        function loadDynamicFilters() {
            const filtersContainer = document.getElementById('dynamic-filters-container');
            const modelName = document.querySelector('input[name="model"]').value;

            if (!filtersContainer || !modelName) return;

            // Show loading indicator
            filtersContainer.innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <p class="mt-2">Chargement des filtres...</p>
            </div>
        `;

            // Fetch filters HTML
            fetch(`/reports/model-filters?model=${modelName}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text();
                })
                .then(html => {
                    filtersContainer.innerHTML = html;
                    initializeFilterHandlers();
                })
                .catch(error => {
                    filtersContainer.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        Erreur lors du chargement des filtres: ${error.message}
                    </div>
                `;
                });
        }

        // Initialize handlers for dynamic filter controls
        function initializeFilterHandlers() {
            // Add filter button
            const addFilterBtn = document.querySelector('.add-filter');
            if (addFilterBtn) {
                addFilterBtn.addEventListener('click', function() {
                    const filtersContainer = document.getElementById('filters-list');
                    const filterCount = filtersContainer.querySelectorAll('.filter-row').length;

                    // Clone the filter template
                    const template = document.querySelector('.filter-template');
                    if (template) {
                        const newFilter = template.cloneNode(true);
                        newFilter.classList.remove('filter-template', 'd-none');

                        // Update IDs and names
                        updateFilterIndices(newFilter, filterCount);

                        filtersContainer.appendChild(newFilter);
                    }
                });
            }

            // Remove filter button (delegated)
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-filter') || e.target.closest(
                    '.remove-filter')) {
                    const filterRow = e.target.closest('.filter-row');
                    if (filterRow) {
                        filterRow.remove();

                        // Renumber remaining filters
                        const filterRows = document.querySelectorAll('#filters-list .filter-row');
                        filterRows.forEach((row, index) => {
                            updateFilterIndices(row, index);
                        });
                    }
                }
            });

            // Field change event (delegated)
            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('filter-field')) {
                    const fieldSelect = e.target;
                    const rowIndex = fieldSelect.dataset.index;
                    const operatorSelect = document.querySelector(
                        `.filter-operator[data-index="${rowIndex}"]`);
                    const selectedOption = fieldSelect.options[fieldSelect.selectedIndex];
                    const fieldType = selectedOption.dataset.type;

                    if (operatorSelect) {
                        populateOperators(operatorSelect, fieldType);
                    }
                }
            });

            // Apply initial operators
            document.querySelectorAll('.filter-field').forEach(function(field) {
                const selectedOption = field.options[field.selectedIndex];
                if (selectedOption && selectedOption.value) {
                    const fieldType = selectedOption.dataset.type;
                    const rowIndex = field.dataset.index;
                    const operatorSelect = document.querySelector(
                        `.filter-operator[data-index="${rowIndex}"]`);

                    if (operatorSelect) {
                        populateOperators(operatorSelect, fieldType);

                        // Select previously selected operator if any
                        const selectedOperator = operatorSelect.dataset.selected;
                        if (selectedOperator) {
                            for (let i = 0; i < operatorSelect.options.length; i++) {
                                if (operatorSelect.options[i].value === selectedOperator) {
                                    operatorSelect.options[i].selected = true;
                                    break;
                                }
                            }
                        }
                    }
                }
            });

            // Clear filters button
            const clearFiltersBtn = document.querySelector('.clear-filters');
            if (clearFiltersBtn) {
                clearFiltersBtn.addEventListener('click', function() {
                    const filtersContainer = document.getElementById('filters-list');
                    const filterRows = filtersContainer.querySelectorAll('.filter-row');

                    if (filterRows.length > 0) {
                        // Keep only first row and reset it
                        filterRows.forEach((row, index) => {
                            if (index === 0) {
                                // Reset first row values
                                const fieldSelect = row.querySelector('.filter-field');
                                if (fieldSelect) fieldSelect.selectedIndex = 0;

                                const operatorSelect = row.querySelector('.filter-operator');
                                if (operatorSelect) {
                                    operatorSelect.innerHTML =
                                        '<option value="">Sélectionner un opérateur</option>';
                                }

                                const valueInput = row.querySelector('.filter-value');
                                if (valueInput) valueInput.value = '';
                            } else {
                                // Remove additional rows
                                row.remove();
                            }
                        });
                    }
                });
            }
        }

        // Helper function to update filter indices
        function updateFilterIndices(filterElement, index) {
            const fieldSelect = filterElement.querySelector('.filter-field');
            const operatorSelect = filterElement.querySelector('.filter-operator');
            const valueInput = filterElement.querySelector('.filter-value');

            if (fieldSelect) {
                fieldSelect.name = `filters[${index}][field]`;
                fieldSelect.dataset.index = index;
            }

            if (operatorSelect) {
                operatorSelect.name = `filters[${index}][operator]`;
                operatorSelect.dataset.index = index;
            }

            if (valueInput) {
                valueInput.name = `filters[${index}][value]`;
                valueInput.dataset.index = index;
            }
        }

        // Populate operators based on field type
        function populateOperators(operatorSelect, fieldType) {
            // Clear current options
            operatorSelect.innerHTML = '<option value="">Sélectionner un opérateur</option>';

            // Default operators
            const operators = {
                'string': {
                    'equals': 'Égal à',
                    'not_equals': 'Différent de',
                    'contains': 'Contient',
                    'starts_with': 'Commence par',
                    'ends_with': 'Finit par',
                    'is_null': 'Est vide',
                    'is_not_null': 'N\'est pas vide'
                },
                'integer': {
                    'equals': 'Égal à',
                    'not_equals': 'Différent de',
                    'greater_than': 'Supérieur à',
                    'greater_than_or_equal': 'Supérieur ou égal à',
                    'less_than': 'Inférieur à',
                    'less_than_or_equal': 'Inférieur ou égal à',
                    'is_null': 'Est vide',
                    'is_not_null': 'N\'est pas vide'
                },
                'boolean': {
                    'equals': 'Est vrai',
                    'not_equals': 'Est faux'
                },
                'date': {
                    'equals': 'Égal à',
                    'not_equals': 'Différent de',
                    'greater_than': 'Après',
                    'less_than': 'Avant',
                    'is_null': 'Est vide',
                    'is_not_null': 'N\'est pas vide'
                },
                'datetime': {
                    'equals': 'Égal à',
                    'not_equals': 'Différent de',
                    'greater_than': 'Après',
                    'less_than': 'Avant',
                    'is_null': 'Est vide',
                    'is_not_null': 'N\'est pas vide'
                }
            };

            // Get operators for this field type
            const typeOperators = operators[fieldType] || {
                'equals': 'Égal à',
                'not_equals': 'Différent de'
            };

            // Add operators to select
            Object.entries(typeOperators).forEach(([value, label]) => {
                const option = document.createElement('option');
                option.value = value;
                option.textContent = label;
                operatorSelect.appendChild(option);
            });
        }

        // Form submission handling
        document.getElementById('reportForm').addEventListener('submit', function(e) {
            // Ensure at least one column is selected
            const columnsSelect = document.getElementById('reportColumns');
            if (columnsSelect && columnsSelect.selectedOptions.length === 0) {
                e.preventDefault();
                alert('Veuillez sélectionner au moins une colonne');

                // Activate columns tab
                document.getElementById('columns-tab').click();
                return false;
            }

            // Pre-submit processing for filters
            const filtersContainer = document.getElementById('filters-list');
            if (filtersContainer) {
                const filterRows = filtersContainer.querySelectorAll('.filter-row');
                let hasValidFilter = false;

                filterRows.forEach((row, index) => {
                    const fieldSelect = row.querySelector('.filter-field');
                    const operatorSelect = row.querySelector('.filter-operator');
                    const valueInput = row.querySelector('.filter-value');

                    if (fieldSelect && fieldSelect.value &&
                        operatorSelect && operatorSelect.value) {
                        hasValidFilter = true;

                        // For null operators, we don't need a value
                        if (['is_null', 'is_not_null'].includes(operatorSelect.value)) {
                            if (valueInput) valueInput.value = '';
                        }
                    }
                });

                // If there are incomplete filters, we still submit but might want to warn the user
                if (filterRows.length > 0 && !hasValidFilter) {
                    const proceed = confirm(
                        'Certains filtres sont incomplets et seront ignorés. Voulez-vous continuer ?'
                        );
                    if (!proceed) {
                        e.preventDefault();
                        // Activate filters tab
                        document.getElementById('filters-tab').click();
                        return false;
                    }
                }
            }
        });
    });
</script>
