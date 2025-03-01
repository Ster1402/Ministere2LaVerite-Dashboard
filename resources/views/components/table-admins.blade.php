<x-table-layout>
    <x-slot name="header">
        <h4>
            <a href="#add-admin" data-toggle="modal" class="btn btn-primary">
                <i class="fas fa-plus"></i> Ajouter un admin
            </a>
        </h4>
        <h4>
            <a href="#print-admins" data-toggle="modal" class="btn btn-info">
                <i class="fas fa-print"></i> Télécharger la liste des admins
            </a>
        </h4>
    </x-slot>
    <x-slot name="body">
        <!-- Modal -->
        <x-modal id="print-admins" action="{{ route('report.admins') }}">
            <h2>Voulez-vous lancer le téléchargement de la liste des admins ?</h2>
        </x-modal>
        <x-modal id="add-admin" action="{{ route('admins.store') }}">
            <div class="form-group">
                <x-select-end-users />
                <x-select-roles />
            </div>
        </x-modal>
        <!-- Modal end -->
        <div class="table-responsive">
            <table class="table table-striped"
                   id="sortable-table">
                <thead>
                <tr>
                    <th class="sort-handler">
                        <i class="fas fa-th"></i>
                    </th>
                    <th>Nom</th>
                    <th>Profile</th>
                    <th>Crée le</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($data as $admin)
                    <tr>
                        <td>
                            <div class="sort-handler">
                                <i class="fas fa-th"></i>
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('admins.show', ['admin' => $admin->id]) }}">
                                {{ $admin->name }} {{ $admin->surname }}
                            </a>
                        </td>
                        <td>
                            <img alt="image"
                                 src="{{ $admin->profile_photo_url }}"
                                 class="rounded-circle"
                                 width="35" data-toggle="tooltip"
                                 title="{{ $admin->name }} {{ $admin->surname }}">
                        </td>
                        <td>{{ $admin->updated_at->format('d M Y') }}</td>
                        <td>
                            @foreach ($admin->roles as $role)
                                <div class="badge badge-info">
                                    {{ $role->displayName }}
                                </div>
                            @endforeach
                        </td>
                        <td>
                            <a href="#edit-admin-{{ $admin->id }}" data-toggle="modal" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Editer
                            </a>
                            <a class="btn btn-danger" href="#delete-admin-{{ $admin->id }}" data-toggle="modal">
                                <i class="fas fa-trash"></i>
                                Detruire
                            </a>
                            <!-- modals-content -->
                            <x-modal id="edit-admin-{{ $admin->id }}" action="{{ route('admins.update', ['admin' => $admin->id]) }}">
                                @method('PATCH')
                                <div class="form-group">
                                    <x-select-one-user :user="$admin" title="{{ __('Administrateur') }}" name="admin" />
                                    <x-select-roles :defaults="$admin->roles" />
                                </div>
                            </x-modal>
                            <x-modal-delete id="delete-admin-{{ $admin->id }}"
                                            :action="route('admins.destroy', ['admin' => $admin->id])">
                                <input type="hidden" name="admin" value="{{ $admin->id }}}}"/>
                            </x-modal-delete>
                        </td>
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
