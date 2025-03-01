<x-app-layout>
    <x-slot name="header">
        <x-banner title="{{ __('Onglets Utilisateurs') }}">
            <x-slot name="breadcrumb">
                <div class="breadcrumb-item"><a
                        href="{{ route('users.index') }}">{{ __('Gestion des utilisateurs') }}</a></div>
                <div class="breadcrumb-item">{{ __('Profile') }}</div>
            </x-slot>
        </x-banner>
    </x-slot>

    <div class="section-body">
        <h2 class="section-title">Salutation, {{ $user->name }} {{ $user->surname }}</h2>

        <div class="row mt-sm-4">
            <div class="col-12 col-md-12 col-lg-5">
                <div class="card profile-widget">
                    <div class="profile-widget-header">
                        <img alt="image" src="{{ $user->profile_photo_url }}"
                             style="height: 100px"
                             class="rounded-circle profile-widget-picture">
                        <a href="#edit-user-{{ $user->id }}" data-toggle="modal" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Modifier les informations
                        </a>
                    </div>
                    <div class="profile-widget-description">
                        <div class="profile-widget-name">
                            Roles:
                            @foreach($user->roles as $role)
                                <div class="text-muted d-inline font-weight-normal">
                                    <div class="slash"></div>
                                    {{ $role->displayName }}
                                </div>
                            @endforeach
                        </div>
                        {!! $user->comment ?? __('Aucun commentaire sur l\'utilisateur.') !!}.
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-12 col-lg-7">
                <div class="card">
                    <div class="needs-validation">
                        <div class="card-header">
                            <h4>Informations de bases</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-6 col-12">
                                    <label for="name">Nom</label>
                                    <input id="name" disabled name="name" type="text" class="form-control"
                                           value="{{ $user->name }}" required="">
                                </div>
                                <div class="form-group col-md-6 col-12">
                                    <label for="surname">Prénom</label>
                                    <input id="surname" disabled name="surname" type="text" class="form-control"
                                           value="{{ $user->surname }}" required="">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-7 col-12">
                                    <label for="email">Email</label>
                                    <input id="email" disabled type="email" class="form-control"
                                           value="{{ $user->email }}" required="">
                                </div>
                                <div class="form-group col-md-5 col-12">
                                    <label for="phoneNumber">Phone</label>
                                    <input id="phoneNumber" disabled name="phoneNumber" type="tel" class="form-control"
                                           value="{{ $user->phoneNumber }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-12">
                                    <label for="antecedent">Antécedent</label>
                                    <textarea id="antecedent" readonly
                                              class="form-control summernote-simple">{!! $user->antecedent !!}</textarea>
                                </div>
                            </div>
                        </div>
                        <!-- Modals -->
                        <x-modal-user-info :user="$user" id="edit-user-{{ $user->id }}"
                                           action="{{ route('users.update', ['user' => $user->id]) }}">
                            @method('PATCH')
                        </x-modal-user-info>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
