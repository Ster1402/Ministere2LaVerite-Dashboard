<x-app-layout>
    <x-slot name="header">
        <x-banner title="Confirmation de don">
            <x-slot name="breadcrumb">
                <div class="breadcrumb-item"><a href="{{ route('donations.index') }}">Donations</a></div>
                <div class="breadcrumb-item">Confirmation</div>
            </x-slot>
        </x-banner>
    </x-slot>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Détails du don</h4>
                    </div>
                    <div class="card-body">
                        <div class="empty-state" data-height="400">
                            @if ($donation->status === 'completed')
                                <div class="empty-state-icon bg-success">
                                    <i class="fas fa-check"></i>
                                </div>
                                <h2>Merci pour votre don!</h2>
                                <p class="lead">
                                    Votre don de {{ number_format($donation->amount, 0, ',', ' ') }}
                                    {{ $donation->currency }} a été traité avec succès.
                                </p>
                            @elseif($donation->status === 'pending')
                                <div class="empty-state-icon bg-warning">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <h2>Paiement en cours</h2>
                                <p class="lead">
                                    Votre don de {{ number_format($donation->amount, 0, ',', ' ') }}
                                    {{ $donation->currency }} est en attente de confirmation.
                                </p>
                                <p>
                                    Veuillez vérifier votre téléphone pour confirmer la transaction via
                                    {{ $donation->paymentMethod->display_name }}.
                                </p>
                                <div class="mt-4">
                                    <button class="btn btn-warning btn-lg" id="check-status">
                                        <i class="fas fa-sync-alt"></i> Vérifier le statut
                                    </button>
                                </div>
                            @else
                                <div class="empty-state-icon bg-danger">
                                    <i class="fas fa-times"></i>
                                </div>
                                <h2>Échec du paiement</h2>
                                <p class="lead">
                                    Nous n'avons pas pu traiter votre don de
                                    {{ number_format($donation->amount, 0, ',', ' ') }} {{ $donation->currency }}.
                                </p>
                                <p>
                                    Raison: {{ $donation->payment_data['reason'] ?? 'Erreur de paiement' }}
                                </p>
                                <div class="mt-4">
                                    <a href="{{ route('donations.create') }}" class="btn btn-outline-primary btn-lg">
                                        <i class="fas fa-redo"></i> Réessayer
                                    </a>
                                </div>
                            @endif
                        </div>

                        <div class="donation-details mt-5">
                            <h5>Détails de la transaction</h5>
                            <table class="table table-striped">
                                <tr>
                                    <th>Référence</th>
                                    <td>{{ $donation->reference }}</td>
                                </tr>
                                <tr>
                                    <th>Date</th>
                                    <td>{{ $donation->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Montant</th>
                                    <td>{{ number_format($donation->amount, 0, ',', ' ') }} {{ $donation->currency }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Méthode de paiement</th>
                                    <td>{{ $donation->paymentMethod->display_name }}</td>
                                </tr>
                                <tr>
                                    <th>Statut</th>
                                    <td>
                                        @if ($donation->status === 'completed')
                                            <span class="badge badge-success">Complété</span>
                                        @elseif($donation->status === 'pending')
                                            <span class="badge badge-warning">En attente</span>
                                        @else
                                            <span class="badge badge-danger">Échoué</span>
                                        @endif
                                    </td>
                                </tr>
                                @if ($donation->message)
                                    <tr>
                                        <th>Message</th>
                                        <td>{{ $donation->message }}</td>
                                    </tr>
                                @endif
                            </table>
                        </div>

                        <div class="text-center mt-4">
                            <a href="{{ route('donations.index') }}" class="btn btn-primary">
                                <i class="fas fa-arrow-left"></i> Retour aux dons
                            </a>

                            @if ($donation->status === 'completed')
                                <a href="#" class="btn btn-info" onclick="window.print()">
                                    <i class="fas fa-print"></i> Imprimer reçu
                                </a>
                            @endif

                            @can('delete', $donation)
                                <form action="{{ route('donations.destroy', $donation) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce don ?')">
                                        <i class="fas fa-trash"></i> Supprimer
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($donation->status === 'pending')
        @push('scripts')
            <script>
                $(document).ready(function() {
                    $('#check-status').click(function() {
                        $(this).prop('disabled', true).html(
                            '<i class="fas fa-spinner fa-spin"></i> Vérification...');

                        // Reload the page to check the status
                        window.location.reload();
                    });

                    // Auto-refresh the page every 10 seconds for pending donations
                    setTimeout(function() {
                        window.location.reload();
                    }, 10000);
                });
            </script>
        @endpush
    @endif
</x-app-layout>
