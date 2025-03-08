<div class="modal fade" id="donation-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Faire un don</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="{{ route('donations.store') }}" id="donation-form">
                    @csrf
                    <x-validation-errors class="mb-4"/>

                    <div class="form-group">
                        <label for="amount">Montant *</label>
                        <div class="input-group">
                            <input type="number" name="amount" id="amount" class="form-control" required min="100" placeholder="Montant du don">
                            <div class="input-group-append">
                                <span class="input-group-text">XAF</span>
                            </div>
                        </div>
                        <input type="hidden" name="currency" value="XAF">
                    </div>

                    <div class="form-group">
                        <label for="name">Votre nom (optionnel)</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Votre nom" value="{{ auth()->user()->name ?? '' }}">
                    </div>

                    <div class="form-group">
                        <label for="email">Email (optionnel)</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Email pour le reçu" value="{{ auth()->user()->email ?? '' }}">
                    </div>

                    <div class="form-group">
                        <label for="payment_method_id">Méthode de paiement *</label>
                        <select name="payment_method_id" id="payment_method_id" class="form-control" required>
                            <option value="">Sélectionner une méthode de paiement</option>
                            @foreach($paymentMethods as $method)
                                <option value="{{ $method->id }}"
                                    data-color="{{ $method->color_code }}"
                                    data-regex="{{ $method->phone_regex }}"
                                    data-prefix="{{ $method->phone_prefix }}">
                                    {{ $method->display_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="phone">Numéro de téléphone mobile *</label>
                        <div class="input-group">
                            <input type="text" name="phone" id="phone" class="form-control" placeholder="Ex: 6XXXXXXXX" value="{{ auth()->user()->phoneNumber ?? '' }}" required>
                            <div class="input-group-append">
                                <span class="input-group-text operator-icon">
                                    <i class="fas fa-mobile-alt"></i>
                                </span>
                            </div>
                        </div>
                        <small class="form-text text-muted operator-hint">Veuillez sélectionner une méthode de paiement.</small>
                    </div>

                    <div class="form-group">
                        <label for="comment">Message (optionnel)</label>
                        <textarea name="comment" id="comment" class="form-control" rows="3" placeholder="Votre message"></textarea>
                    </div>

                    <div class="form-group">
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle"></i> Après avoir soumis ce formulaire, vous recevrez un SMS pour finaliser votre don via le service choisi.
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal">
                            <i class="fa fa-close"></i> Annuler
                        </button>
                        <button type="submit" class="btn btn-success btn-flat">
                            <i class="fa fa-hand-holding-heart"></i> Faire un don
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // When payment method changes
        $('#payment_method_id').change(function() {
            const selectedOption = $(this).find('option:selected');
            const phoneRegex = selectedOption.data('regex');
            const phonePrefix = selectedOption.data('prefix');
            const colorCode = selectedOption.data('color');
            const methodName = selectedOption.text();

            if (phonePrefix) {
                $('.operator-hint').text(`Préfixes valides: ${phonePrefix}`);
                $('.operator-hint').show();
            } else {
                $('.operator-hint').hide();
            }

            if (colorCode) {
                $('.operator-icon').css('background-color', colorCode);
            } else {
                $('.operator-icon').css('background-color', '');
            }

            // Validate the current phone number if there's one
            validatePhoneNumber();
        });

        // When phone number changes
        $('#phone').on('input', function() {
            validatePhoneNumber();
        });

        function validatePhoneNumber() {
            const phoneNumber = $('#phone').val().replace(/\s+/g, '');
            const paymentMethodId = $('#payment_method_id').val();

            if (!phoneNumber || !paymentMethodId) {
                return;
            }

            // Send ajax request to validate the phone number
            $.ajax({
                url: "{{ route('payment-methods.validate-phone') }}",
                method: 'POST',
                data: {
                    phone: phoneNumber,
                    payment_method_id: paymentMethodId,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.valid) {
                        $('#phone').removeClass('is-invalid').addClass('is-valid');
                        $('.operator-hint').removeClass('text-danger').addClass('text-success').text(response.message);
                    } else {
                        $('#phone').removeClass('is-valid').addClass('is-invalid');
                        $('.operator-hint').removeClass('text-success').addClass('text-danger').text(response.message);
                    }
                },
                error: function() {
                    $('.operator-hint').removeClass('text-success').addClass('text-danger').text('Erreur de validation du numéro.');
                }
            });
        }

        // Form validation before submit
        $('#donation-form').submit(function(e) {
            const phoneNumber = $('#phone').val().replace(/\s+/g, '');
            const paymentMethodId = $('#payment_method_id').val();
            const selectedOption = $('#payment_method_id').find('option:selected');
            const phoneRegex = selectedOption.data('regex');

            if (phoneRegex && phoneNumber) {
                const regexPattern = new RegExp(phoneRegex);
                if (!regexPattern.test(phoneNumber)) {
                    e.preventDefault();
                    alert('Le numéro de téléphone ne correspond pas au service sélectionné.');
                    return false;
                }
            }

            return true;
        });
    });
</script>
@endpush
