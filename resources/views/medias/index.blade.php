<x-app-layout>
    <x-slot name="header">
        <x-banner title="{{ __('Onglets des Publications') }}">
            <x-slot name="breadcrumb">
                <div class="breadcrumb-item"><a href="#">{{ __('Medias') }}</a></div>
                <div class="breadcrumb-item">{{ __('Import medias') }}</div>
            </x-slot>
        </x-banner>
    </x-slot>

    <x-slot name="subtitle">
        {{ __('Medias') }}
    </x-slot>

    <div class="row">
        <form method="POST" action="{{ route('medias.store') }}" enctype="multipart/form-data" class="col-12">
            @csrf

            <x-validation-errors/>

            <div class="card">
                <div class="card-body">
                    <input class="dropzone" id="mydropzone" style="width: 100%" name="medias[]" type="file" multiple/>
                </div>
                <div class="card-body">
                    <label for="comment">Message</label>
                    <div class="form-group row mb-4">
                        <div class="col-sm-12 col-md-7">
                <textarea id="comment" name="comment"
                          class="summernote-simple">{!! old('comment') !!}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group pr-4 row mb-4">
                                <x-select-users name="receiver" id="assembly-select-group-user"/>
                                <x-select-assembly name="assemblies[]" id="assembly-select-group"
                                                   style="display: none;"/>
                            </div>

                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Envoyer aux
                                    Assemblées ?</label>
                                <div class="col-sm-12 col-md-7">
                                    <label class="custom-switch mt-2">
                                        <input type="checkbox" name="sendToAssemblies" id="assemblycheck"
                                               class="custom-switch-input">
                                        <span class="custom-switch-indicator"></span>
                                        <span class="custom-switch-description"> Oui ou Non ??</span>
                                    </label>
                                </div>
                            </div>

                            <div style="text-align: center;">
                                <div class="form-group row mb-4">
                                    <div class="col-sm-12">
                                        <button type="submit" class="btn btn-lg btn-primary">Envoyer les medias</button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <x-table-medias/>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const assemblySelectGroup = document.getElementById("assembly-select-group");
            const assemblySelectGroupUser = document.getElementById("assembly-select-group-user");
            const assemblyCheck = document.getElementById("assemblycheck");
            const assemblyCheckUser = document.getElementById("assemblycheck-User");

            // Écouteur d'événement pour détecter les changements de case à cocher
            assemblyCheck.addEventListener("change", function () {
                // Si la case est cochée, afficher le champ de sélection, sinon le masquer
                if (this.checked) {
                    assemblySelectGroup.style.display = "";
                    assemblySelectGroupUser.style.display = "none";
                } else {
                    assemblySelectGroupUser.style.display = "";
                    assemblySelectGroup.style.display = "none";
                }
            });
        });
    </script>
    <!-- JS Libraies -->
    <script src="assets/modules/dropzonejs/min/dropzone.min.js"></script>
</x-app-layout>
