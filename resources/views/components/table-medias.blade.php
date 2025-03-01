<x-table-layout>
    <x-slot name="header">
        <h4>
            <form method="GET" action="">
                <input type="hidden" name="type" value=""/>
                <button type="submit" class="btn btn-outline-info ">
                    Afficher toutes les types de medias
                </button>
            </form>
        </h4>
    </x-slot>
    <x-slot name="body">
        <div class="table-responsive">
            <table class="table table-striped"
                   id="sortable-table">
                <thead>
                <tr>
                    <th class="sort-handler">
                        <i class="fas fa-th"></i>
                    </th>
                    <th>Nom du fichier</th>
                    <th>Type</th>
                    <th>Destinataire</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($data as $media)
                    <tr>
                        <td>
                            <div class="sort-handler">
                                <i class="fas fa-th"></i>
                            </div>
                        </td>
                        <td>
                            <a href="{{ $media->uri }}">
                                {{ $media->name }}
                            </a>
                        </td>
                        <td>
                            <form method="GET" action="">
                                <input type="hidden" name="type" value="{{ $media->type }}"/>
                                <button type="submit"
                                        class="btn btn-outline-info rounded">{{ $media->type }}</button>
                            </form>
                        </td>
                        <td>
                            {{ $media->user?->name }}
                            @if($media->assemblies->count() > 0)
                                {!! ' <strong>' . $media->assemblies->reduce(fn($acc, $ass) => $ass->name . '</strong>, ' .
                                $acc, '') !!}
                            @endif
                        </td>
                        <td>
                            <a class="btn btn-danger" href="#delete-media-{{ $media->id }}"
                               data-toggle="modal"><i
                                        class="fas fa-trash"></i>
                                Supprimé</a>
                            <!-- Modal -->
                            <x-modal-delete
                                    id="delete-media-{{ $media->id }}"
                                    action="{{ route('medias.destroy', $media->id) }}"/>
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
