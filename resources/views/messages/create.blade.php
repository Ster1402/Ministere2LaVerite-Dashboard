<x-app-layout>
    <x-slot name="header">
        <x-banner>
            <x-slot name="title">
                <div class="section-header-back">
                    <a href="{{ route('messages.index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
                </div>
                <h1>Creé un Post</h1>
            </x-slot>
            <x-slot name="breadcrumb">
                <div class="breadcrumb-item"><a href="{{ route('messages.index') }}">{{ __('Messageries') }}</a></div>
                <div class="breadcrumb-item">{{ __('ChapelMail') }}</div>
            </x-slot>
        </x-banner>
    </x-slot>

    <x-slot name="subtitle">
        {{ __('Envoyer un nouveau message') }}
    </x-slot>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Rédigez votre post</h4>
                </div>
                <form method="POST" action="{{ route('messages.store') }}" class="card-body"
                      enctype="multipart/form-data">
                    @csrf
                    <x-validation-errors class="mb-4"/>
                    <div class="form-group row mb-4">
                        <label for="subject" class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Titre</label>
                        <div class="col-sm-12 col-md-7">
                            <input name="subject" required value="{{ old('subject') }}" id="subject" type="text"
                                   class="form-control">
                        </div>
                    </div>

                    <div class="form-group row mb-4">
                        <label for="category"
                               class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Catégorie</label>
                        <div class="col-sm-12 col-md-7">
                            <select id="category" name="category" class="form-control selectric">
                                <?php $categories = ["Annonce Générale", "Annonce Ciblée", "Plainte", "Information"] ?>
                                @foreach($categories as $category)
                                    <option
                                        value="{{ $category }}">{{ $category }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row mb-4" id="assembly-select-group-user">
                        <label for="receiver" class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Envoyé
                            à</label>
                        <div class="col-sm-12 col-md-7">
                            <select id="receiver" class="form-control selectric" name="receiver">
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row mb-4" id="assembly-select-group" style="display: none;">
                        <label for="assemblies"
                               class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Assemblées</label>
                        <div class="col-sm-12 col-md-7 input-group">
                            <div class="input-group-prepend"></div>
                            <select id="assemblies" name="assemblies[]" class="form-control select2" multiple=""
                                    style="width: 500px">
                                @foreach($assemblies as $assembly)
                                    <option
                                        value="{{ $assembly->id }}">{{ $assembly->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Envoyé à une Assemblée
                            ?</label>
                        <div class="col-sm-12 col-md-7">
                            <label class="custom-switch mt-2">
                                <input type="checkbox" name="sendToAssembly" id="assemblycheck"
                                       class="custom-switch-input">
                                <span class="custom-switch-indicator"></span>
                                <span class="custom-switch-description"> Oui ou Non ?</span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group row mb-4">
                        <label for="content" class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Votre
                            message</label>
                        <div class="col-sm-12 col-md-7">
                            <textarea required name="content" id="content"
                                      class="summernote-simple">{{ old('content') }}</textarea>
                        </div>
                    </div>

                    <div class="form-group row mb-4">
                        <label for="image-upload"
                               class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Vignette</label>
                        <div class="col-sm-12 col-md-7">
                            <input type="file" name="picture" id="image-upload"/>
                        </div>
                    </div>

                    <div class="form-group row mb-4">
                        <label for="tags" class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Tags</label>
                        <div class="col-sm-12 col-md-7">
                            <input id="tags" type="text" name="tags" class="form-control inputtags">
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                        <div class="col-sm-12 col-md-7">
                            <button type="submit" class="btn btn-primary">Envoyé</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

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

</x-app-layout>
