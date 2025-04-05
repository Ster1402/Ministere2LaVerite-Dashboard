<x-table-layout>
    <x-slot name="header">
        @if (!Auth::user()?->is($data->first()?->sender))
            <h4>
                <form method="GET" action="">
                    <input type="hidden" name="category" value="" />
                    <button type="submit" class="btn btn-outline-info ">
                        Afficher toutes les catégories
                    </button>
                </form>
            </h4>
            <h4>
                <form method="GET" action="">
                    <input type="hidden" name="author" value="" />
                    <button type="submit" class="btn btn-outline-info ">
                        Afficher touts les auteurs
                    </button>
                </form>
            </h4>
        @else
            <h4>Messagerie</h4>
        @endif
    </x-slot>

    <x-slot name="body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        @if (!Auth::user()?->is($data->first()?->sender))
                            <th>Auteur</th>
                        @endif
                        <th>Suject</th>
                        <th>Categorie</th>
                        <th>Envoyer le</th>
                        @if (Auth::user()?->is($data->first()?->sender))
                            <th>Destinateur</th>
                        @endif
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $message)
                        <tr id="message-{{ $message->id }}">
                            @if (!Auth::user()?->is($message->sender))
                                <td>
                                    <a href="{{ route('users.show', ['user' => $message->sender->id]) }}">
                                        {{ $message->sender->name }}
                                    </a>
                                    <form method="GET" action="">
                                        <input type="hidden" name="author" value="{{ $message->sender->id }}" />
                                        <button type="submit" class="btn btn-link">{{ __('Messages') }}</button>
                                    </form>
                                </td>
                            @endif
                            <td>{!! $message->subject !!}
                                <div>
                                    @if (Auth::user()?->is($data->first()?->sender))
                                        <a href="{{ route('messages.edit', ['message' => $message->id]) }}">ouvrir</a>
                                    @else
                                        <a href="{{ route('messages.show', ['message' => $message->id]) }}">ouvrir</a>
                                    @endif
                                    <div class="bullet"></div>
                                    <a href="#delete-message-{{ $message->id }}" data-toggle="modal"
                                        class="text-danger">supprimer</a>
                                </div>
                                <x-modal-delete id="delete-message-{{ $message->id }}"
                                    action="{{ route('messages.destroy', ['message' => $message->id]) }}" />
                            </td>
                            <td>
                                <form method="GET" action="">
                                    <input type="hidden" name="category" value="{{ $message->category }}" />
                                    <button type="submit"
                                        class="btn btn-outline-info ">{{ $message->category }}</button>
                                </form>
                            </td>
                            <td>{{ $message->updated_at?->diffForHumans() }}
                                {{ $message->created_at != $message->updated_at ? __(' - modifié') : '' }}</td>
                            @if (Auth::user()?->is($data->first()?->sender))
                                <td>{{ $message->receiver->name ?? $message->assemblies->reduce(fn($acc, $ass) => $ass->name . ', ' . $acc, '') }}
                                </td>
                            @endif
                            <td>
                                @if ($message->deleted)
                                    Message supprimé
                                @else
                                    <div class="badge badge-primary">

                                        {{ $message->received ? 'Lu' : 'Non Lu' }}
                                    </div>
                                @endif
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
