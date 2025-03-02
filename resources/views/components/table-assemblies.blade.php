<x-table-layout>
    <x-slot name="header">
        <h4>
            <a href="#add-assembly" data-toggle="modal" class="btn btn-primary">
                <i class="fas fa-plus"></i> Ajouter une Assemblée
            </a>
        </h4>
        <h4>
            <a href="#" class="btn btn-info" data-toggle="modal" data-target="#reportModal">
                <i class="fas fa-print"></i> Exporter la liste des assemblées
            </a>
        </h4>
    </x-slot>

    <x-slot name="body">
        <x-modal-assembly id="add-assembly" action="{{ route('assemblies.store') }}" />
        <x-report-modal model-name="assemblies" title="Exporter la liste des admins" />

        <div class="table-responsive">
            <table class="table table-striped" id="sortable-table">
                <thead>
                    <tr>
                        <th class="sort-handler">
                            <i class="fas fa-th"></i>
                        </th>
                        <th>Nom</th>
                        <th>Secteur</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $assembly)
                        <tr>
                            <td>
                                <div class="sort-handler">
                                    <i class="fas fa-th"></i>
                                </div>
                            </td>
                            <td>{{ $assembly->name }}</td>
                            <td>{{ $assembly->sector->name }}</td>
                            <td>
                                <a href="#edit-assembly-{{ $assembly->id }}" data-toggle="modal"
                                    class="btn btn-warning">
                                    <i class="fas fa-edit"></i>
                                    Éditer</a>
                                <!-- Button to trigger modal -->
                                <a class="btn btn-danger" href="#destroy-assembly-{{ $assembly->id }}"
                                    data-toggle="modal"><i class="fas fa-trash"></i>
                                    Supprimé</a>

                                <a class="btn btn-success" href="#grantAssemblyTo-{{ $assembly->id }}"
                                    data-toggle="modal">
                                    <i class="fas fa-user-plus"></i>
                                    Ajouter un Utilisateur</a>

                                <!-- Modal -->
                                <x-modal-assembly :assembly="$assembly" id="edit-assembly-{{ $assembly->id }}"
                                    action="{{ route('assemblies.update', ['assembly' => $assembly->id]) }}">
                                    @method('PATCH')
                                </x-modal-assembly>
                                <x-modal id="grantAssemblyTo-{{ $assembly->id }}"
                                    action="{{ route('assemblies.users.store', ['assembly' => $assembly->id]) }}">
                                    <input type="hidden" name="assembly_id" value="{{ $assembly->id }}" />
                                    <x-select-multiple-users id="user_ids" name="user_ids[]" :defaults="$assembly?->users" />
                                </x-modal>
                                <x-modal-delete
                                    action="{{ route('assemblies.destroy', ['assembly' => $assembly->id]) }}"
                                    id="destroy-assembly-{{ $assembly->id }}" />
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
