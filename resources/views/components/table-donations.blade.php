<x-table-layout :searchable="false">
    <x-slot name="header">
        <h4>Le don le plus récent</h4>
        <div class="card-header-action">
            <!-- <div class="dropdown d-inline"> -->
            <button class="btn btn-danger dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                Plus
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item has-icon" href="{{ route('dashboard') }}">
                    <i class="fas fa-spinner"></i>Actualiser
                </a>

                <a href="#" class="btn btn-info" data-toggle="modal" data-target="#reportModal">
                    <i class="fas fa-print"></i> Exporter la liste
                </a>
            </div>
        </div>
    </x-slot>

    <x-slot name="body">
        <x-report-modal model-name="donations" title="Exporter la liste des dons" />

        <div class="table-responsive table-invoice">
            <table class="table table-striped">
                <tr>
                    <th>ID du don</th>
                    <th>Montant</th>
                    <th>Déposé le</th>
                    <th>Utilisateur</th>
                </tr>
                <tbody>
                    @foreach ($donations as $donation)
                        <tr>
                            <td><a href="#">TR-{{ $donation->id }}</a></td>
                            <td>
                                <div class="badge badge-success"><b>{{ $donation->amount }} XAF</b></div>
                            </td>
                            <td>{{ $donation->created_at }}</td>
                            <td>
                                <a href="{{ route('users.show', ['user' => $donation->user_id]) }}">
                                    {{ $donation->user->name }}
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    @if ($donations->isEmpty())
                        <tr>
                            <td colspan="5">Désolé 😢. Aucun dons trouvés.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        @if ($donations->hasPages())
            <div class="pagination-wrapper">
                {{ $donations->links() }}
            </div>
        @endif
    </x-slot>

</x-table-layout>
