<div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title-wrapper">
                    <i class="fas fa-file-export modal-icon"></i>
                    <h5 class="modal-title" id="reportModalLabel">{{ $title }}</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <form id="reportForm" class="needs-validation" method="POST" action="{{ route('reports.generate') }}"
                    target="_blank" novalidate>
                    @csrf
                    <input type="hidden" name="model" value="{{ $modelName }}">

                    <div class="report-options">
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
                                                <option value="{{ $value }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <h6 class="section-title">Données à inclure</h6>

                            <div class="mb-3">
                                <label for="reportColumns" class="form-label">Sélection des colonnes</label>
                                <select name="columns[]" class="form-control w-full select2" multiple
                                    id="reportColumns">
                                    @foreach ($columns as $key => $column)
                                        <option value="{{ $key }}">{{ $column['title'] }}</option>
                                    @endforeach
                                </select>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>Sélectionnez les colonnes à inclure dans le
                                    rapport
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
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

    #reportModal .btn-close {
        color: white;
        background: transparent url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23fff'%3E%3Cpath d='M.293.293a1 1 0 011.414 0L8 6.586 14.293.293a1 1 0 111.414 1.414L9.414 8l6.293 6.293a1 1 0 01-1.414 1.414L8 9.414l-6.293 6.293a1 1 0 01-1.414-1.414L6.586 8 .293 1.707a1 1 0 010-1.414z'/%3E%3C/svg%3E") center/1em auto no-repeat;
        opacity: 0.8;
        transition: opacity 0.2s;
    }

    #reportModal .btn-close:hover {
        opacity: 1;
    }

    #reportModal .modal-body {
        padding: 1.5rem;
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

    /* Responsive Adjustments */
    @media (max-width: 767px) {
        .format-selector {
            flex-direction: column;
            gap: 0.5rem;
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

        selectAllBtn.addEventListener('click', function() {
            Array.from(columnsSelect.options).forEach(option => {
                option.selected = true;
            });
        });

        deselectAllBtn.addEventListener('click', function() {
            Array.from(columnsSelect.options).forEach(option => {
                option.selected = false;
            });
        });

        // Compatibility with both Bootstrap 5 and 4
        // For Bootstrap 4 modals
        const closeBtn = document.querySelector('#reportModal .btn-close');
        closeBtn.addEventListener('click', function() {
            // Try Bootstrap 5 method first
            const bsModal = bootstrap.Modal.getInstance(document.getElementById('reportModal'));
            if (bsModal) {
                bsModal.hide();
            } else {
                // Fallback to jQuery for Bootstrap 4
                if (typeof $ !== 'undefined') {
                    $('#reportModal').modal('hide');
                }
            }
        });

        // Set cancel button to work with both Bootstrap versions
        const cancelBtn = document.querySelector('#reportModal .btn-outline-secondary');
        cancelBtn.addEventListener('click', function() {
            // Try Bootstrap 5 method first
            const bsModal = bootstrap.Modal.getInstance(document.getElementById('reportModal'));
            if (bsModal) {
                bsModal.hide();
            } else {
                // Fallback to jQuery for Bootstrap 4
                if (typeof $ !== 'undefined') {
                    $('#reportModal').modal('hide');
                }
            }
        });
    });
</script>
