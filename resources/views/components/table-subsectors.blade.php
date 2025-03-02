<x-table-layout>
    <x-slot name="header">
        <h4>
            <a href="#add-sector" data-toggle="modal" class="btn btn-primary">
                <i class="fas fa-plus"></i> Ajouter un sous-secteur
            </a>
        </h4>
        <h4>
            <a href="#printSector" data-toggle="modal" class="btn btn-info">
                <i class="fas fa-print"></i> Télécharger la liste des sous-sous-secteurs
            </a>
        </h4>
    </x-slot>
    <x-slot name="body">
        <x-modal-sector id="add-sector" action="{{ route('sectors.store') }}" />
        <x-modal id="printSector" action="{{ route('report.sectors') }}">
            <h2>Voulez-vous télécharger la liste des sous-sous-secteurs ?</h2>
        </x-modal>
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
                    @foreach ($data as $sector)
                        <tr>
                            <td>
                                <div class="sort-handler">
                                    <i class="fas fa-th"></i>
                                </div>
                            </td>
                            <td>{{ $sector->name }}</td>
                            <td>
                                <div class="badge font-weight-bold badge-{{ $sector->master ? 'success' : 'danger' }}">
                                    {{ $sector->master ? $sector->master->name : 'Unknown' }}
                                </div>
                            </td>
                            <td>
                                <a href="#edit-sector-{{ $sector->id }}" data-toggle="modal" class="btn btn-warning">
                                    <i class="fas fa-edit"></i>
                                    Éditer</a>
                                <!-- Button to trigger modal -->
                                <a class="btn btn-danger" href="#delete-sector-{{ $sector->id }}"
                                    data-toggle="modal"><i class="fas fa-trash"></i>
                                    Supprimé</a>

                                <!-- Modal -->
                                <x-modal-sector id="edit-sector-{{ $sector->id }}" :sector="$sector"
                                    action="{{ route('sectors.update', $sector->id) }}">
                                    @method('PATCH')
                                </x-modal-sector>
                                <x-modal-delete id="delete-sector-{{ $sector->id }}"
                                    action="{{ route('sectors.destroy', $sector->id) }}" />
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
