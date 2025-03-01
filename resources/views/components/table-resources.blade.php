<x-table-layout>
    <x-slot name="header">
        <h4><a href="#add-resource" data-toggle="modal" class="btn btn-primary">
                <i class="fas fa-plus"></i> Ajouter une ressource</a></h4>
        <h4><a href="#print-resources" data-toggle="modal" class="btn btn-info"> <i
                    class="fas fa-print"></i> Télécharger la liste des ressources</a></h4>
    </x-slot>

    <x-slot name="body">
        <x-modal-resource id="add-resource" action="{{ route('resources.store') }}"/>
        <x-modal id="print-resources" action="{{ route('report.resources') }}">
            <h2>Voulez-vous télécharger la liste des ressources ?</h2>
        </x-modal>

        <div class="table-responsive">
            <table class="table table-striped"
                   id="sortable-table">
                <thead>
                <tr>
                    <th class="sort-handler">
                        <i class="fas fa-th"></i>
                    </th>
                    <th>Nom</th>
                    <th>Quantité total</th>
                    <th>Groupe</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($data as $resource)
                    <tr>
                        <td>
                            <div class="sort-handler">
                                <i class="fas fa-th"></i>
                            </div>
                        </td>
                        <td>{{ $resource->name }}</td>
                        <td>{{ $resource->quantity }}</td>
                        <td>{{ $resource->group->name }}</td>
                        <td>
                            <a href="#edit-resource-{{ $resource->id }}"
                               data-toggle="modal"
                               class="btn btn-warning">
                                <i class="fas fa-edit"></i>
                                Éditer
                            </a>
                            <!-- Button to trigger modal -->
                            <a class="btn btn-danger"
                               href="#delete-resource-{{ $resource->id }}"
                               data-toggle="modal"
                            >
                                <i class="fas fa-trash"></i>
                                Supprimé
                            </a>
                            <a class="btn btn-success"
                               data-toggle="modal"
                               href="#grantRessourceTo-{{ $resource->id }}">
                                <i class="fas fa-user-plus"></i>
                                Assigner à un Utilisateur
                            </a>
                        </td>

                        <!-- Modal to delete a resource -->
                        <x-modal-delete id="delete-resource-{{ $resource->id }}"
                                        action="{{ route('resources.destroy', $resource->id) }}"/>
                        <!-- Modal to edit a resource -->
                        <x-modal-resource :resource="$resource" id="edit-resource-{{ $resource->id }}"
                                          action="{{ route('resources.update', $resource->id) }}">
                            @method('PATCH')
                        </x-modal-resource>
                        <!-- Modal to assign a resource to a user -->
                        <x-modal id="grantRessourceTo-{{ $resource->id }}"
                                 action="{{ route('resources.users.borrow', ['resource' => $resource->id]) }}">
                            <x-select-users />
                            <div class="form-group row mb-4">
                                <label for="quantity" class="col-sm-3 col-form-label">Quantité</label>
                                <input id="quantity" name="quantity" min="0" type="number" value="{{ old('quantity') }}"
                                       class="form-control" style="width:300px"/>
                            </div>
                        </x-modal>
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
