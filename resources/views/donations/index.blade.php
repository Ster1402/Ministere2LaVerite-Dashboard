@extends('layouts.app')

@section('title', 'Gestion des dons')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Gestion des dons</h1>
        <div class="section-header-button">
            <a href="{{ route('donations.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Faire un don
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
                            <a href="#print-donations" data-toggle="modal" class="btn btn-icon btn-primary">
                                <i class="fas fa-print"></i>
                            </a>
                            <a href="{{ route('donations.index') }}" class="btn btn-icon btn-secondary">
                                <i class="fas fa-sync"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <x-modal id="print-donations" action="{{ route('report.donations') }}">
                            <h2>Voulez-vous lancer le téléchargement de la liste des dons ?</h2>
                        </x-modal>

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
                                            @if($donation->is_anonymous)
                                                <span class="font-italic">Anonyme</span>
                                            @else
                                                @if($donation->user_id)
                                                    <a href="{{ route('users.show', $donation->user_id) }}">
                                                        {{ $donation->donor_name ?? $donation->user->name }}
                                                    </a>
                                                @else
                                                    {{ $donation->donor_name ?? 'N/A' }}
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            <div class="badge badge-success">{{ number_format($donation->amount, 0, ',', ' ') }} {{ $donation->currency }}</div>
                                        </td>
                                        <td>{{ ucfirst(str_replace('_', ' ', $donation->payment_method)) }}</td>
                                        <td>
                                            @if($donation->status === 'pending')
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
                                        <td>
                                            <a href="{{ route('donations.confirm', $donation) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
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
@endsection
