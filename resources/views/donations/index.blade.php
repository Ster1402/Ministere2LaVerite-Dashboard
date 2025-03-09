<x-app-layout>

    <x-slot name="header">
        <x-banner title="{{ __('Dons') }}">
            <x-slot name="breadcrumb">
                <div class="breadcrumb-item"><a href="#">Donations</a></div>
                <div class="breadcrumb-item">Gestion des dons</div>
            </x-slot>
        </x-banner>
    </x-slot>

    <x-slot name="subtitle">
        {{ __('Gestion des dons') }}
    </x-slot>

    <x-tab-layout subtitle="{{ __('Liste des donations') }}">
        <x-slot name="home">
            <section class="section">
                <div class="section-header">
                    <h1>Gestion des dons</h1>
                    <div class="section-header-button">
                        <a href="{{ route('donations.form') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-hand-holding-heart"></i> Faire un don
                        </a>
                    </div>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                        <div class="breadcrumb-item">Dons</div>
                    </div>
                </div>

                <div class="section-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Liste des dons</h4>
                                    <div class="card-header-action">
                                        <a href="#" data-toggle="modal" data-target="#reportModal"
                                            class="btn btn-icon btn-primary">
                                            <i class="fas fa-print"></i>
                                            <span>Exporter la liste des dons</span>
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <x-report-modal model-name="donations" title="Exporter la liste des dons" />

                                    <div class="table-responsive">
                                        <table class="table table-striped table-md">
                                            <tr>
                                                <th>Référence</th>
                                                <th>Donateur</th>
                                                <th>Montant</th>
                                                <th>Méthode</th>
                                                <th>Statut</th>
                                                <th>Date</th>
                                                <th>Action</th>
                                            </tr>
                                            <tbody>
                                                @forelse($donations as $donation)
                                                    <tr>
                                                        <td>{{ $donation->reference }}</td>
                                                        <td>
                                                            @if ($donation->is_anonymous)
                                                                <span class="font-italic">Anonyme</span>
                                                            @else
                                                                @if ($donation->user_id)
                                                                    <a
                                                                        href="{{ route('users.show', $donation->user_id) }}">
                                                                        {{ $donation->donor_name ?? $donation->user->name }}
                                                                    </a>
                                                                @else
                                                                    {{ $donation->donor_name ?? 'N/A' }}
                                                                @endif
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="badge badge-success">
                                                                {{ number_format($donation->amount, 0, ',', ' ') }}
                                                                {{ $donation->currency }}</div>
                                                        </td>
                                                        <td>{{ $donation->paymentMethod->display_name }}</td>
                                                        </td>
                                                        <td>
                                                            @if ($donation->status === 'pending')
                                                                <div class="badge badge-warning">En attente</div>
                                                            @elseif($donation->status === 'completed')
                                                                <div class="badge badge-success">Complété</div>
                                                            @elseif($donation->status === 'failed')
                                                                <div class="badge badge-danger">Échoué</div>
                                                            @else
                                                                <div class="badge badge-info">
                                                                    {{ ucfirst($donation->status) }}
                                                                </div>
                                                            @endif
                                                        </td>
                                                        <td>{{ $donation->created_at->format('d/m/Y H:i') }}</td>
                                                        <td>
                                                            @can('delete', $donation)
                                                                <form action="{{ route('donations.destroy', $donation) }}"
                                                                    method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce don ?')">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </form>
                                                            @endcan
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="7" class="text-center">Aucun don trouvé.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    {{ $donations->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </x-slot>
        <x-slot name="tutorial">
            <div class="container">
                <h3 class="title">🛠️ Gestion des dons</h3>
                <br>
                <div class="tip">
                    <h5>Création d'un don</h5>
                    <p>Pour créer un nouveau don, cliquez sur le bouton <span class="highlight">"Faire un don"</span>
                        situé dans la section de gestion des dons. Vous serez dirigé vers un formulaire où vous pourrez
                        saisir les informations nécessaires pour le nouveau don.</p>
                </div>
                <br>
                <div class="tip">
                    <h5>Dons anonymes</h5>
                    <p>Si vous souhaitez faire un don anonyme, cliquez sur le bouton <span class="highlight">"Faire un
                            don anonyme"</span> situé dans la section de gestion des dons. Vous serez dirigé vers un
                        formulaire où vous pourrez saisir les informations nécessaires pour le nouveau don anonyme.</p>
                </div>
            </div>
        </x-slot>
    </x-tab-layout>

</x-app-layout>
