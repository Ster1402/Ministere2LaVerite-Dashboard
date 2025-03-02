<x-table-layout>
    <x-slot name="header">
        <h4>
            <a href="#add-user" data-toggle="modal" class="btn btn-primary">
                <i class="fas fa-plus"></i> Ajouter un utilisateur
            </a>
        </h4>
        <h4>
            <a href="#" class="btn btn-info" data-toggle="modal" data-target="#reportModal">
                <i class="fas fa-print"></i> Exporter les utilisateurs
            </a>
        </h4>
    </x-slot>
    <x-slot name="body">
        <x-modal-user-info id="add-user" action="{{ route('users.store') }}" />

        <x-report-modal model-name="users" title="Exporter les utilisateurs" />

        <div class="table-responsive">
            <table class="table table-striped" id="sortable-table">
                <thead>
                    <tr>
                        <th class="sort-handler">
                            <i class="fas fa-th"></i>
                        </th>
                        <th>Nom et prénoms</th>
                        <th>Profile</th>
                        <th>Assemblée</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $user)
                        <tr>
                            <td>
                                <div class="sort-handler">
                                    <i class="fas fa-th"></i>
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('users.show', ['user' => $user->id]) }}">
                                    {{ $user->name }} {{ $user->surname }}
                                </a>
                            </td>
                            <td>
                                <img alt="image" src="{{ $user->profile_photo_url }}" class="rounded-circle"
                                    width="35" data-toggle="tooltip"
                                    title="{{ $user->name }} {{ $user->surname }}">
                            </td>
                            <td>
                                {{ $user->assembly?->name ?? 'Aucune' }}
                            </td>
                            <td>
                                {{ $user->email }}
                            </td>
                            <td>
                                <a href="#edit-user-{{ $user->id }}" data-toggle="modal" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Éditer
                                </a>
                                <a class="btn btn-danger" href="#destroy-user-{{ $user->id }}" data-toggle="modal">
                                    <i class="fas fa-trash"></i> Supprimer
                                </a>
                            </td>
                            <!-- Modals -->
                            <x-modal-user-info :user="$user" id="edit-user-{{ $user->id }}"
                                action="{{ route('users.update', ['user' => $user->id]) }}">
                                @method('PATCH')
                            </x-modal-user-info>
                            <x-modal-delete id="destroy-user-{{ $user->id }}"
                                action="{{ route('users.destroy', ['user' => $user->id]) }}" />
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($data->hasPages())
            <div class="pagination-wrapper">
                {{ $data->links() }}
            </div>
        @endif
    </x-slot>
</x-table-layout>
