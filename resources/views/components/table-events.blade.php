<x-table-layout>
    <x-slot name="header">
        <h4>
            <a href="#add-event" data-toggle="modal" class="btn btn-primary">
                <i class="fas fa-plus"></i> Ajouter un Évènement
            </a>
        </h4>
        <h4>
            <a href="#" class="btn btn-info" data-toggle="modal" data-target="#reportModal">
                <i class="fas fa-print"></i> Exporter la liste des Évènements
            </a>
        </h4>
    </x-slot>

    <x-slot name="body">
        <x-modal-event-info id="add-event" action="{{ route('events.store') }}" />

        <x-report-modal model-name="events" title="Exporter la liste des Évènements" />

        <div class="table-responsive">
            <table class="table table-striped" id="sortable-table">
                <thead>
                    <tr>
                        <th class="sort-handler">
                            <i class="fas fa-th"></i>
                        </th>
                        <th>Nom</th>
                        <th>Période</th>
                        <th>Assemblées</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $event)
                        <tr>
                            <td>
                                <div class="sort-handler">
                                    <i class="fas fa-th"></i>
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('events.show', ['event' => $event->id]) }}">
                                    {!! $event->title !!}
                                </a>
                            </td>
                            <td class="text-center">Du {{ $event->from?->format('d/m/Y') ?? __('Non spécifié') }}
                                au {{ $event->to?->format('d/m/Y') ?? __('Non spécifié') }}</td>
                            <td>
                                @foreach ($event->assemblies as $assembly)
                                    <div class="badge badge-info">{{ $assembly->name }}</div>
                                @endforeach
                            </td>
                            <td>{!! $event->description !!}</td>
                            <td>
                                <a href="#edit-event-{{ $event->id }}" data-toggle="modal" class="btn btn-warning">
                                    <i class="fas fa-edit"></i>
                                    Editer</a>
                                <!-- Button to trigger modal -->
                                <a class="btn btn-danger" data-toggle="modal" href="#destroy-event-{{ $event->id }}">
                                    <i class="fas fa-trash"></i>
                                    Detruire</a>
                            </td>

                            <x-modal-event-info :event="$event" id="edit-event-{{ $event->id }}"
                                action="{{ route('events.update', ['event' => $event->id]) }}">
                                @method('PATCH')
                            </x-modal-event-info>
                            <x-modal-delete id="destroy-event-{{ $event->id }}"
                                action="{{ route('events.destroy', ['event' => $event->id]) }}" />
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
