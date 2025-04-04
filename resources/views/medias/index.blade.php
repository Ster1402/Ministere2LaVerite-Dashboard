<x-app-layout>
    <x-slot name="header">
        <div class="page-header-content">
            <h1 class="page-title">{{ __('Publications de Médias') }}</h1>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">{{ __('Médias') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('Import médias') }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </x-slot>

    <div class="container-fluid">
        <!-- Formulaire d'upload de médias -->
        <form method="POST" action="{{ route('medias.store') }}" enctype="multipart/form-data" id="mediaUploadForm">
            @csrf

            <x-validation-errors />

            <div class="row">
                <div class="col-lg-8">
                    <!-- Zone de dépôt des fichiers -->
                    <div class="card upload-card mb-4">
                        <div class="card-header">
                            <h4 class="card-title"><i
                                    class="fas fa-cloud-upload-alt me-2"></i>{{ __('Importer des fichiers') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="dropzone-container">
                                <input class="dropzone" id="mydropzone" name="medias[]" type="file" multiple />
                                <div class="dropzone-info">
                                    <p class="text-muted">Déposez vos fichiers ici ou cliquez pour parcourir</p>
                                    <ul class="file-types">
                                        <li><i class="fas fa-file-image"></i> Images</li>
                                        <li><i class="fas fa-file-video"></i> Vidéos</li>
                                        <li><i class="fas fa-file-audio"></i> Audio</li>
                                        <li><i class="fas fa-file-pdf"></i> Documents</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="card-body border-top">
                            <h5 class="section-title"><i
                                    class="fas fa-comment-alt me-2"></i>{{ __('Ajouter un message') }}</h5>
                            <div class="form-group mb-0">
                                <textarea id="comment" name="comment" class="summernote-simple form-control" rows="5"
                                    placeholder="Entrez un message pour accompagner vos médias...">{!! old('comment') !!}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Configuration de distribution -->
                    <div class="card distribution-card mb-4">
                        <div class="card-header">
                            <h4 class="card-title"><i
                                    class="fas fa-share-alt me-2"></i>{{ __('Options de distribution') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="distribution-toggle mb-4">
                                <h5 class="option-title">{{ __('Mode de distribution') }}</h5>
                                <div class="distribution-selector">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="sendToAssemblies"
                                            id="assemblycheck">
                                        <label class="form-check-label" for="assemblycheck">
                                            {{ __('Envoyer aux assemblées') }}
                                        </label>
                                    </div>
                                    <p class="distribution-hint">
                                        <i class="fas fa-info-circle"></i>
                                        <span
                                            id="distributionHintText">{{ __('Actuellement configuré pour envoyer aux utilisateurs sélectionnés.') }}</span>
                                    </p>
                                </div>
                            </div>

                            <div class="recipient-selection">
                                <h5 class="option-title">{{ __('Sélection des destinataires') }}</h5>

                                <div id="assembly-select-group-user" class="form-group mb-3">
                                    <label class="form-label">{{ __('Sélectionnez les utilisateurs') }}</label>
                                    <x-select-users name="receiver" class="form-select recipients-dropdown" />
                                </div>

                                <div id="assembly-select-group" class="form-group mb-3" style="display: none;">
                                    <label class="form-label">{{ __('Sélectionnez les assemblées') }}</label>
                                    <x-select-assembly name="assemblies[]" class="form-select recipients-dropdown" />
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-paper-plane me-2"></i>{{ __('Publier les médias') }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Prévisualisation (optionnelle) -->
                    <div class="card preview-card">
                        <div class="card-header">
                            <h4 class="card-title"><i class="fas fa-eye me-2"></i>{{ __('Prévisualisation') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="preview-placeholder text-center py-4">
                                <i class="fas fa-images fa-3x text-muted mb-3"></i>
                                <p class="text-muted">{{ __('Les fichiers sélectionnés apparaîtront ici') }}</p>
                            </div>
                            <div id="mediaPreviewContainer" class="media-preview-container" style="display: none;">
                                <!-- Les prévisualisations seront ajoutées ici par JavaScript -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Liste des médias existants -->
        <div class="media-listing-section mt-4">
            <h2 class="section-heading"><i class="fas fa-photo-video me-2"></i>{{ __('Médias publiés') }}</h2>
            <x-table-medias />
        </div>
    </div>

    @push('styles')
        <style>
            /* Styles pour le formulaire d'upload de médias */
            .upload-card,
            .distribution-card,
            .preview-card {
                border: none;
                border-radius: 12px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
                overflow: hidden;
            }

            .card-header {
                background-color: #fff;
                padding: 1.25rem 1.5rem;
                border-bottom: 1px solid #f0f0f0;
            }

            .card-title {
                font-size: 1.1rem;
                font-weight: 600;
                color: #333;
                margin: 0;
            }

            .card-footer {
                border-top: 1px solid #f0f0f0;
                padding: 1.25rem 1.5rem;
                background-color: #fff;
            }

            .dropzone-container {
                position: relative;
                min-height: 200px;
                border: 2px dashed #e0e0e0;
                border-radius: 8px;
                background-color: #f8fafc;
                padding: 2rem;
                transition: all 0.3s ease;
            }

            .dropzone-container:hover,
            .dropzone-container.dz-drag-hover {
                background-color: #f1f5f9;
                border-color: #3b82f6;
            }

            .dropzone-info {
                text-align: center;
                padding: 1rem 0;
            }

            .file-types {
                display: flex;
                justify-content: center;
                margin: 1rem 0 0;
                padding: 0;
                list-style: none;
                flex-wrap: wrap;
                gap: 1rem;
            }

            .file-types li {
                display: flex;
                align-items: center;
                font-size: 0.85rem;
                color: #64748b;
                background-color: #fff;
                padding: 0.5rem 0.75rem;
                border-radius: 50px;
                box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            }

            .file-types li i {
                margin-right: 0.5rem;
                color: #3b82f6;
            }

            .section-title {
                font-size: 1rem;
                font-weight: 600;
                color: #374151;
                margin-bottom: 1rem;
            }

            /* Distribution Options */
            .option-title {
                font-size: 0.95rem;
                font-weight: 600;
                color: #374151;
                margin-bottom: 0.75rem;
            }

            .distribution-toggle {
                border-bottom: 1px solid #f0f0f0;
                padding-bottom: 1rem;
            }

            .form-check-input {
                width: 2.5em;
                height: 1.25em;
            }

            .distribution-hint {
                font-size: 0.85rem;
                color: #64748b;
                margin-top: 0.75rem;
                display: flex;
                align-items: flex-start;
            }

            .distribution-hint i {
                margin-right: 0.5rem;
                color: #3b82f6;
                margin-top: 0.25rem;
            }

            .recipients-dropdown {
                border-radius: 8px;
                border: 1px solid #e2e8f0;
                padding: 0.5rem 0.75rem;
            }

            /* Preview Card */
            .preview-placeholder {
                color: #94a3b8;
            }

            .media-preview-container {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 0.5rem;
            }

            .preview-item {
                border-radius: 8px;
                overflow: hidden;
                position: relative;
            }

            .preview-item img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .preview-remove {
                position: absolute;
                top: 0.25rem;
                right: 0.25rem;
                background: rgba(239, 68, 68, 0.9);
                color: white;
                border: none;
                border-radius: 50%;
                width: 24px;
                height: 24px;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                font-size: 0.75rem;
            }

            /* Section d'introduction */
            .page-header-content {
                position: relative;
            }

            .page-title {
                font-size: 1.75rem;
                font-weight: 700;
                margin-bottom: 0.5rem;
                color: #fff;
            }

            .breadcrumb {
                background-color: transparent;
                padding: 0;
                margin: 0;
            }

            .breadcrumb-item {
                font-size: 0.9rem;
                color: rgba(255, 255, 255, 0.7);
            }

            .breadcrumb-item a {
                color: rgba(255, 255, 255, 0.7);
                text-decoration: none;
            }

            .breadcrumb-item a:hover {
                color: #fff;
            }

            .breadcrumb-item.active {
                color: #fff;
            }

            .breadcrumb-item+.breadcrumb-item::before {
                color: rgba(255, 255, 255, 0.5);
            }

            /* Section des médias publiés */
            .section-heading {
                font-size: 1.5rem;
                font-weight: 600;
                margin-bottom: 1.25rem;
                color: #333;
            }

            .btn-primary {
                background: linear-gradient(to right, #3b82f6, #2563eb);
                border: none;
                box-shadow: 0 4px 6px rgba(37, 99, 235, 0.2);
                transition: all 0.3s ease;
            }

            .btn-primary:hover {
                background: linear-gradient(to right, #2563eb, #1d4ed8);
                transform: translateY(-2px);
                box-shadow: 0 6px 12px rgba(37, 99, 235, 0.25);
            }

            /* Animations */
            @keyframes fadeIn {
                from {
                    opacity: 0;
                }

                to {
                    opacity: 1;
                }
            }

            .fadeIn {
                animation: fadeIn 0.5s ease;
            }

            /* Responsive Adjustments */
            @media (max-width: 992px) {
                .row {
                    flex-direction: column;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Configuration pour l'alternance entre utilisateurs et assemblées
                const assemblySelectGroup = document.getElementById("assembly-select-group");
                const assemblySelectGroupUser = document.getElementById("assembly-select-group-user");
                const assemblyCheck = document.getElementById("assemblycheck");
                const distributionHintText = document.getElementById("distributionHintText");

                // Écouteur d'événement pour détecter les changements de case à cocher
                assemblyCheck.addEventListener("change", function() {
                    // Si la case est cochée, afficher le champ de sélection assemblées, sinon afficher utilisateurs
                    if (this.checked) {
                        assemblySelectGroup.style.display = "block";
                        assemblySelectGroupUser.style.display = "none";
                        distributionHintText.textContent =
                            "Actuellement configuré pour envoyer aux assemblées sélectionnées.";
                    } else {
                        assemblySelectGroupUser.style.display = "block";
                        assemblySelectGroup.style.display = "none";
                        distributionHintText.textContent =
                            "Actuellement configuré pour envoyer aux utilisateurs sélectionnés.";
                    }
                });

                // Configuration améliorée de Dropzone
                if (typeof Dropzone !== 'undefined') {
                    Dropzone.autoDiscover = false;

                    new Dropzone("#mydropzone", {
                        url: "{{ route('medias.store') }}",
                        autoProcessQueue: false, // Désactiver le traitement automatique
                        parallelUploads: 10,
                        maxFilesize: 30, // 30 MB max
                        acceptedFiles: "image/*,video/*,audio/*,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx",
                        addRemoveLinks: true,
                        dictDefaultMessage: "Déposez vos fichiers ici ou cliquez pour parcourir",
                        dictRemoveFile: "Supprimer",
                        dictCancelUpload: "Annuler",
                        dictFallbackMessage: "Votre navigateur ne supporte pas les téléchargements par glisser-déposer.",

                        init: function() {
                            const dropzone = this;
                            const form = document.getElementById('mediaUploadForm');
                            const previewContainer = document.getElementById('mediaPreviewContainer');
                            const previewPlaceholder = document.querySelector('.preview-placeholder');

                            // Gestionnaires d'événements pour modifier l'apparence de la zone de dépôt
                            this.on("dragenter", function() {
                                document.querySelector('.dropzone-container').classList.add(
                                    'dz-drag-hover');
                            });

                            this.on("dragleave", function() {
                                document.querySelector('.dropzone-container').classList.remove(
                                    'dz-drag-hover');
                            });

                            this.on("drop", function() {
                                document.querySelector('.dropzone-container').classList.remove(
                                    'dz-drag-hover');
                            });

                            // Gérer l'ajout de fichier pour la prévisualisation
                            this.on("addedfile", function(file) {
                                // Cacher le placeholder et afficher le conteneur
                                previewPlaceholder.style.display = 'none';
                                previewContainer.style.display = 'grid';

                                // Créer une prévisualisation
                                const preview = document.createElement('div');
                                preview.className = 'preview-item fadeIn';
                                preview.setAttribute('data-uuid', file.upload.uuid);

                                if (file.type.startsWith('image/')) {
                                    // Si c'est une image
                                    const img = document.createElement('img');
                                    img.src = URL.createObjectURL(file);
                                    preview.appendChild(img);
                                } else {
                                    // Si c'est un autre type de fichier
                                    const iconElement = document.createElement('div');
                                    iconElement.className = 'file-preview-icon';

                                    let iconClass = 'fas fa-file';
                                    if (file.type.startsWith('video/')) iconClass =
                                        'fas fa-file-video';
                                    else if (file.type.startsWith('audio/')) iconClass =
                                        'fas fa-file-audio';
                                    else if (file.type.endsWith('pdf')) iconClass =
                                        'fas fa-file-pdf';
                                    else if (file.type.includes('document')) iconClass =
                                        'fas fa-file-word';

                                    iconElement.innerHTML =
                                        `<i class="${iconClass}"></i><span>${file.name}</span>`;
                                    preview.appendChild(iconElement);
                                }

                                // Ajouter un bouton de suppression
                                const removeBtn = document.createElement('button');
                                removeBtn.className = 'preview-remove';
                                removeBtn.innerHTML = '<i class="fas fa-times"></i>';
                                removeBtn.onclick = function() {
                                    dropzone.removeFile(file);
                                };
                                preview.appendChild(removeBtn);

                                previewContainer.appendChild(preview);
                            });

                            // Gérer la suppression de fichier
                            this.on("removedfile", function(file) {
                                // Supprimer la prévisualisation
                                const preview = document.querySelector(
                                    `.preview-item[data-uuid="${file.upload.uuid}"]`);
                                if (preview) preview.remove();

                                // Si aucun fichier, remettre le placeholder
                                if (dropzone.files.length === 0) {
                                    previewPlaceholder.style.display = 'block';
                                    previewContainer.style.display = 'none';
                                }
                            });

                            // Gérer la soumission du formulaire
                            form.addEventListener('submit', function(e) {
                                e.preventDefault();
                                e.stopPropagation();

                                if (dropzone.files.length === 0) {
                                    alert('Veuillez ajouter au moins un fichier.');
                                    return;
                                }

                                // Si la file d'attente est vide (premier envoi)
                                if (dropzone.getQueuedFiles().length === 0) {
                                    dropzone.processQueue();
                                }
                            });
                        }
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
