<x-table-layout>
    <x-slot name="header">
        <h4><a href="#add-group" data-toggle="modal" class="btn btn-primary">
                <i class="fas fa-plus"></i> Ajouter une categorie</a></h4>
        <h4>
            <a href="#" class="btn btn-info" data-toggle="modal" data-target="#reportModal">
                <i class="fas fa-print"></i> Exporter la liste des categories
            </a>
        </h4>
    </x-slot>

    <x-slot name="body">
        <x-modal-group id="add-group" action="{{ route('groups.store') }}" />
        <x-report-modal model-name="resources" title="Exporter la liste des categories" />

        <div class="table-responsive">
            <table class="table table-striped" id="sortable-table">
                <thead>
                    <tr>
                        <th class="sort-handler">
                            <i class="fas fa-th"></i>
                        </th>
                        <th>Nom</th>
                        <th class="w-75">Commentaire</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $group)
                        <tr>
                            <td>
                                <div class="sort-handler">
                                    <i class="fas fa-th"></i>
                                </div>
                            </td>
                            <td>{{ $group->name }}</td>
                            <td class="w-75">{!! $group->description ?: __('RAS') !!}</td>
                            <td>
                                <a href="#edit-group-{{ $group->id }}" data-toggle="modal" class="btn btn-warning">
                                    <i class="fas fa-edit"></i>
                                    Éditer</a>
                                <!-- Button to trigger modal -->
                                <a class="btn btn-danger" href="#delete-group-{{ $group->id }}" data-toggle="modal">
                                    <i class="fas fa-trash"></i>
                                    Supprimer
                                </a>
                            </td>

                            <!-- Modal to delete a group -->
                            <x-modal-delete id="delete-group-{{ $group->id }}"
                                action="{{ route('groups.destroy', $group->id) }}" />
                            <!-- Modal to edit a group -->
                            <x-modal-group id="edit-group-{{ $group->id }}"
                                action="{{ route('groups.update', $group->id) }}" :group="$group">
                                @method('PATCH')
                            </x-modal-group>
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
