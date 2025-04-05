{{-- resources/views/donations/form.blade.php --}}
<x-app-layout>
    <div class="donation-page">
        <div class="container py-5">
            <!-- Hero Section -->
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold text-primary mb-3">Faites la Différence Aujourd'hui</h1>
                <p class="lead text-muted mb-4">Votre générosité transforme des vies et crée un impact durable dans notre
                    communauté.</p>
            </div>

            <div class="row justify-content-center">
                <!-- Form Column -->
                <div class="col-lg-12">
                    <div class="donation-form-card">
                        <div class="card-header bg-primary text-white text-center p-4">
                            <h2 class="m-0"><i class="fas fa-heart me-2"></i>{{ __('Votre Don') }}</h2>
                        </div>

                        <div class="card-body p-4">
                            @if (session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif

                            {{-- Show pending donations if any --}}
                            @if (!empty($pendingDonations))
                                <div class="alert alert-warning">
                                    <h5 class="alert-heading"><i
                                            class="fas fa-clock me-2"></i>{{ __('Vous avez des dons en attente') }}</h5>
                                    <p>{{ __('Les dons suivants sont toujours en cours de traitement:') }}</p>
                                    <ul class="list-group mb-0">
                                        @foreach ($pendingDonations as $pending)
                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong>{{ number_format($pending['amount'], 2) }} XAF</strong>
                                                    <small class="text-muted">{{ $pending['payment_method'] }}</small>
                                                    <br>
                                                    <small>{{ $pending['donation_date'] }}</small>
                                                </div>
                                                <div>
                                                    <a href="{{ route('donations.confirmation', ['id' => $pending['id']]) }}"
                                                        class="btn btn-sm btn-info"><i
                                                            class="fas fa-eye me-1"></i>{{ __('Voir Statut') }}</a>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- Donation Amount Selector -->
                            <div class="donation-amount-selector mb-4">
                                <h5 class="text-center mb-3">Choisissez votre montant de don</h5>
                                <div class="amount-presets d-flex flex-wrap justify-content-center gap-2 mb-3">
                                    <button type="button" class="btn btn-outline-primary amount-preset"
                                        data-amount="5000">5 000 XAF</button>
                                    <button type="button" class="btn btn-outline-primary amount-preset"
                                        data-amount="10000">10 000 XAF</button>
                                    <button type="button" class="btn btn-outline-primary amount-preset"
                                        data-amount="25000">25 000 XAF</button>
                                    <button type="button" class="btn btn-outline-primary amount-preset"
                                        data-amount="50000">50 000 XAF</button>
                                    <button type="button" class="btn btn-outline-primary amount-preset"
                                        data-amount="100000">100 000 XAF</button>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('donations.process') }}" class="needs-validation"
                                novalidate>
                                @csrf

                                <div class="mb-4">
                                    <label for="amount"
                                        class="form-label fw-bold">{{ __('Montant du don (XAF)') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span>
                                        <input id="amount" type="number"
                                            class="form-control form-control-lg @error('amount') is-invalid @enderror"
                                            name="amount" value="{{ old('amount', 0) }}" required min="5">
                                        <span class="input-group-text">XAF</span>

                                        @error('amount')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-text text-center mt-2">
                                        Chaque contribution fait la différence, quelle que soit sa taille.
                                    </div>
                                </div>

                                <div class="card mb-4">
                                    <div class="card-body">
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                name="is_anonymous" id="is_anonymous" value="1"
                                                {{ old('is_anonymous') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_anonymous">
                                                {{ __('Faire un don anonyme') }}
                                            </label>
                                        </div>

                                        <div id="donor-info" class="{{ old('is_anonymous') ? 'd-none' : '' }}">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label for="donor_name"
                                                        class="form-label">{{ __('Votre Nom') }}</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i
                                                                class="fas fa-user"></i></span>
                                                        <input id="donor_name" type="text"
                                                            class="form-control @error('donor_name') is-invalid @enderror"
                                                            name="donor_name"
                                                            value="{{ old('donor_name', Auth::user()?->name ?? '') }}">
                                                        @error('donor_name')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="donor_email"
                                                        class="form-label">{{ __('Votre Email') }}</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i
                                                                class="fas fa-envelope"></i></span>
                                                        <input id="donor_email" type="email"
                                                            class="form-control @error('donor_email') is-invalid @enderror"
                                                            name="donor_email"
                                                            value="{{ old('donor_email', Auth::user()?->email ?? '') }}">
                                                        @error('donor_email')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-3">
                                            <label for="donor_phone"
                                                class="form-label">{{ __('Numéro de Téléphone') }}</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                <input id="donor_phone" type="text"
                                                    class="form-control @error('donor_phone') is-invalid @enderror"
                                                    name="donor_phone"
                                                    value="{{ old('donor_phone', Auth::user()?->phoneNumber ?? '') }}"
                                                    required>
                                                @error('donor_phone')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="form-text">
                                                {{ __('Requis pour la confirmation du paiement mobile') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="message"
                                        class="form-label">{{ __('Laissez un Message (Optionnel)') }}</label>
                                    <textarea id="message" class="form-control @error('message') is-invalid @enderror" name="message" rows="3"
                                        placeholder="Partagez pourquoi vous faites un don ou ajoutez un message d'encouragement...">{{ old('message') }}</textarea>
                                    @error('message')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Nouvelle section des méthodes de paiement -->
                                <div class="mb-5">
                                    <h4 class="form-label fw-bold mb-4 text-center">
                                        {{ __('Choisissez votre méthode de paiement') }}</h4>

                                    <div class="payment-methods-section">
                                        @foreach ($paymentMethods as $index => $method)
                                            <div class="payment-option">
                                                <input type="radio" name="payment_method_id"
                                                    id="payment_method_{{ $method->id }}"
                                                    class="payment-radio-input" value="{{ $method->id }}"
                                                    {{ old('payment_method_id') == $method->id ? 'checked' : ($index === 0 ? 'checked' : '') }}
                                                    required>

                                                <label for="payment_method_{{ $method->id }}"
                                                    class="payment-option-card">
                                                    <div class="payment-option-inner">
                                                        <div class="payment-logo-container">
                                                            @if (strpos(strtolower($method->display_name), 'orange') !== false)
                                                                <div class="payment-logo orange-money">
                                                                    <i class="fas fa-mobile-alt"></i>
                                                                    <span class="logo-text">Orange<br>Money</span>
                                                                </div>
                                                            @elseif (strpos(strtolower($method->display_name), 'mtn') !== false)
                                                                <div class="payment-logo mtn-momo">
                                                                    <i class="fas fa-mobile-alt"></i>
                                                                    <span class="logo-text">MTN<br>MoMo</span>
                                                                </div>
                                                            @elseif (strpos(strtolower($method->display_name), 'carte') !== false)
                                                                <div class="payment-logo card-payment">
                                                                    <i class="far fa-credit-card"></i>
                                                                    <span class="logo-text">Carte<br>Bancaire</span>
                                                                </div>
                                                            @elseif (strpos(strtolower($method->display_name), 'virement') !== false)
                                                                <div class="payment-logo bank-transfer">
                                                                    <i class="fas fa-university"></i>
                                                                    <span class="logo-text">Virement<br>Bancaire</span>
                                                                </div>
                                                            @else
                                                                <div class="payment-logo default-payment">
                                                                    <i class="fas fa-money-bill-wave"></i>
                                                                    <span class="logo-text">Paiement<br>Mobile</span>
                                                                </div>
                                                            @endif
                                                        </div>

                                                        <div class="payment-details">
                                                            <div class="payment-title">{{ $method->display_name }}
                                                            </div>
                                                            <div class="payment-subtitle">
                                                                @if (strpos(strtolower($method->display_name), 'orange') !== false)
                                                                    Paiement simple et rapide via Orange Money
                                                                @elseif (strpos(strtolower($method->display_name), 'mtn') !== false)
                                                                    Paiement mobile pratique via MTN Mobile Money
                                                                @elseif (strpos(strtolower($method->display_name), 'carte') !== false)
                                                                    Paiement sécurisé par carte bancaire
                                                                @elseif (strpos(strtolower($method->display_name), 'virement') !== false)
                                                                    Virement bancaire direct vers notre compte
                                                                @else
                                                                    Paiement facile, sécurisé et instantané
                                                                @endif
                                                            </div>
                                                            <div class="payment-features">
                                                                <span class="feature-tag">
                                                                    <i class="fas fa-bolt"></i> Instantané
                                                                </span>
                                                                <span class="feature-tag">
                                                                    <i class="fas fa-lock"></i> Sécurisé
                                                                </span>
                                                                @if (strpos(strtolower($method->display_name), 'orange') !== false ||
                                                                        strpos(strtolower($method->display_name), 'mtn') !== false)
                                                                    <span class="feature-tag">
                                                                        <i class="fas fa-mobile-alt"></i> Mobile
                                                                    </span>
                                                                @endif
                                                                @if (strpos(strtolower($method->display_name), 'carte') !== false)
                                                                    <span class="feature-tag">
                                                                        <i class="fas fa-globe"></i> International
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="payment-select-indicator">
                                                            <div class="select-circle"></div>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>

                                    @error('payment_method_id')
                                        <div class="text-danger mt-2 text-center">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                    <div class="text-center mt-3">
                                        <span class="text-muted small">
                                            <i class="fas fa-shield-alt me-1"></i> Toutes les transactions sont
                                            sécurisées et chiffrées
                                        </span>
                                    </div>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary btn-lg px-5 py-3 fw-bold">
                                        <i class="fas fa-heart me-2"></i>{{ __('Faire un Don Maintenant') }}
                                    </button>
                                    <p class="mt-3 text-muted small">
                                        <i class="fas fa-lock me-1"></i> Don sécurisé traité par FreeMoPay
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Toggle donor info fields based on anonymous checkbox
                const anonymousCheckbox = document.getElementById('is_anonymous');
                const donorInfoSection = document.getElementById('donor-info');

                anonymousCheckbox.addEventListener('change', function() {
                    if (this.checked) {
                        donorInfoSection.classList.add('d-none');
                        // Clear required attribute when anonymous
                        document.getElementById('donor_name').removeAttribute('required');
                        document.getElementById('donor_email').removeAttribute('required');
                    } else {
                        donorInfoSection.classList.remove('d-none');
                        // Add required attribute when not anonymous
                        document.getElementById('donor_name').setAttribute('required', '');
                        document.getElementById('donor_email').setAttribute('required', '');
                    }
                });

                // Amount preset buttons
                const amountInput = document.getElementById('amount');
                const presetButtons = document.querySelectorAll('.amount-preset');

                presetButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const amount = this.dataset.amount;
                        amountInput.value = amount;

                        // Toggle active class
                        presetButtons.forEach(btn => btn.classList.remove('active'));
                        this.classList.add('active');
                    });
                });

                // Set initial active state based on amount input
                function updateActivePreset() {
                    const currentAmount = amountInput.value;
                    presetButtons.forEach(button => {
                        if (button.dataset.amount === currentAmount) {
                            button.classList.add('active');
                        } else {
                            button.classList.remove('active');
                        }
                    });
                }

                updateActivePreset();

                // Update when amount changes manually
                amountInput.addEventListener('change', updateActivePreset);
            });
        </script>
    @endpush
</x-app-layout>
