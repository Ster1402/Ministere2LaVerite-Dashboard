<x-table-layout :searchable="false">
    <x-slot name="header">
        <h4>Dons récents</h4>
        <div class="card-header-action">
            <button class="btn btn-danger dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                Plus
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item has-icon" href="{{ route('donations.index') }}">
                    <i class="fas fa-list"></i> Voir tous les dons
                </a>
                <a href="#" class="dropdown-item has-icon" data-toggle="modal" data-target="#reportModal">
                    <i class="fas fa-print"></i> Exporter la liste des dons
                </a>
            </div>
        </div>
    </x-slot>

    <x-slot name="body">
        <x-report-modal model-name="donations" title="Exporter la liste des admins" />

        <div class="table-responsive table-invoice">
            <table class="table table-striped">
                <tr>
                    <th>Référence</th>
                    <th>Donateur</th>
                    <th>Montant</th>
                    <th>Statut</th>
                    <th>Date</th>
                </tr>
                <tbody>
                    @forelse($donations as $donation)
                        <tr>
                            <td><a href="{{ route('donations.confirm', $donation) }}">{{ $donation->reference }}</a>
                            </td>
                            <td>
                                @if ($donation->is_anonymous)
                                    <span class="font-italic">Donateur anonyme</span>
                                @else
                                    {{ $donation->donor_name ?? ($donation->user ? $donation->user->name : 'N/A') }}
                                @endif
                                @if ($donation->message)
                                    <br><small
                                        class="text-muted font-italic">"{{ Str::limit($donation->message, 30) }}"</small>
                                @endif
                            </td>
                            <td>
                                <div class="badge badge-success"><b>{{ number_format($donation->amount, 0, ',', ' ') }}
                                        {{ $donation->currency }}</b></div>
                            </td>
                            <td>
                                @if ($donation->status === 'pending')
                                    <div class="badge badge-warning">En attente</div>
                                @elseif($donation->status === 'completed')
                                    <div class="badge badge-success">Complété</div>
                                @elseif($donation->status === 'failed')
                                    <div class="badge badge-danger">Échoué</div>
                                @else
                                    <div class="badge badge-info">{{ ucfirst($donation->status) }}</div>
                                @endif
                            </td>
                            <td>{{ $donation->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Aucun don récent.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if (isset($donations) && method_exists($donations, 'hasPages') && $donations->hasPages())
            <div class="pagination-wrapper">
                {{ $donations->links() }}
            </div>
        @endif

    </x-slot>
</x-table-layout>
