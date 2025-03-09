<x-app-layout>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Donation Confirmation') }}</div>

                <div class="card-body">
                    <div id="donation-status" data-donation-id="{{ $donation['id'] }}">
                        <div class="text-center mb-4">
                            @if($donation['is_completed'])
                                <div class="alert alert-success">
                                    <i class="fa fa-check-circle fa-3x mb-3"></i>
                                    <h4>{{ __('Thank you for your donation!') }}</h4>
                                    <p>{{ __('Your payment has been successfully processed.') }}</p>
                                </div>
                            @elseif($donation['is_failed'])
                                <div class="alert alert-danger">
                                    <i class="fa fa-times-circle fa-3x mb-3"></i>
                                    <h4>{{ __('Payment Failed') }}</h4>
                                    <p>{{ __('Sorry, your payment could not be processed.') }}</p>
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <div class="spinner-border text-warning mb-3" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <h4>{{ __('Payment Pending') }}</h4>
                                    <p>{{ __('Your payment is being processed. Please check your phone for payment confirmation.') }}</p>
                                </div>
                            @endif
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5>{{ __('Donation Details') }}</h5>
                                <table class="table table-borderless">
                                    <tr>
                                        <th>{{ __('Amount') }}</th>
                                        <td>{{ number_format($donation['amount'], 2) }} XAF</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Date') }}</th>
                                        <td>{{ $donation['donation_date'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Status') }}</th>
                                        <td>
                                            <span id="status-badge" class="badge
                                                @if($donation['is_completed']) bg-success
                                                @elseif($donation['is_failed']) bg-danger
                                                @else bg-warning @endif">
                                                {{ ucfirst($donation['status']) }}
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h5>{{ __('Payment Information') }}</h5>
                                <table class="table table-borderless">
                                    <tr>
                                        <th>{{ __('Method') }}</th>
                                        <td>{{ $donation['payment_method'] }}</td>
                                    </tr>
                                    @if($donation['reference'])
                                    <tr>
                                        <th>{{ __('Reference') }}</th>
                                        <td><small>{{ $donation['reference'] }}</small></td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <th>{{ __('Donor') }}</th>
                                        <td>{{ $donation['donor_name'] }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        @if($donation['is_pending'])
                            <div class="row mt-4">
                                <div class="col-12 text-center">
                                    <button id="check-status-btn" class="btn btn-primary">
                                        {{ __('Check Payment Status') }}
                                    </button>

                                    <button id="cancel-donation-btn" class="btn btn-outline-danger ms-2">
                                        {{ __('Cancel Payment') }}
                                    </button>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <h5>{{ __('Payment Instructions') }}</h5>
                                        <p>{{ __('To complete your payment:') }}</p>
                                        <ol>
                                            <li>{{ __('Check your phone for a payment confirmation message') }}</li>
                                            <li>{{ __('Approve the transaction on your phone') }}</li>
                                            <li>{{ __('Once approved, click "Check Payment Status" to update this page') }}</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($donation['is_completed'])
                            <div class="row mt-4">
                                <div class="col-12 text-center">
                                    <a href="{{ route('donations.form') }}" class="btn btn-primary">
                                        {{ __('Make Another Donation') }}
                                    </a>

                                    @if(Auth::check())
                                        <a href="{{ route('donations.my') }}" class="btn btn-outline-secondary ms-2">
                                            {{ __('View My Donations') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if($donation['is_failed'])
                            <div class="row mt-4">
                                <div class="col-12 text-center">
                                    <a href="{{ route('donations.form') }}" class="btn btn-primary">
                                        {{ __('Try Again') }}
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const donationId = document.getElementById('donation-status').dataset.donationId;
        const checkStatusBtn = document.getElementById('check-status-btn');
        const cancelDonationBtn = document.getElementById('cancel-donation-btn');

        // Auto-refresh for pending donations
        @if($donation['is_pending'])
            // Set up polling to check status every 30 seconds
            const statusInterval = setInterval(checkStatus, 30000);

            // Clear interval when page is unloaded
            window.addEventListener('beforeunload', function() {
                clearInterval(statusInterval);
            });
        @endif

        // Check status button handler
        if (checkStatusBtn) {
            checkStatusBtn.addEventListener('click', function() {
                this.disabled = true;
                this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Checking...';
                checkStatus();
            });
        }

        // Cancel donation button handler
        if (cancelDonationBtn) {
            cancelDonationBtn.addEventListener('click', function() {
                if (confirm('{{ __("Are you sure you want to cancel this donation?") }}')) {
                    this.disabled = true;
                    this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Cancelling...';
                    cancelDonation();
                }
            });
        }

        // Function to check donation status
        function checkStatus() {
            fetch('{{ route("donations.check-status") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    donation_id: donationId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // If status has changed, reload the page to show updated state
                    if (data.donation.status !== '{{ $donation["status"] }}') {
                        window.location.reload();
                    } else if (checkStatusBtn) {
                        // Re-enable the button if status hasn't changed
                        checkStatusBtn.disabled = false;
                        checkStatusBtn.innerHTML = '{{ __("Check Payment Status") }}';
                    }
                } else {
                    alert('{{ __("Error checking donation status") }}');
                    if (checkStatusBtn) {
                        checkStatusBtn.disabled = false;
                        checkStatusBtn.innerHTML = '{{ __("Check Payment Status") }}';
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (checkStatusBtn) {
                    checkStatusBtn.disabled = false;
                    checkStatusBtn.innerHTML = '{{ __("Check Payment Status") }}';
                }
            });
        }

        // Function to cancel donation
        function cancelDonation() {
            fetch('{{ route("donations.cancel") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    donation_id: donationId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message || '{{ __("Error cancelling donation") }}');
                    if (cancelDonationBtn) {
                        cancelDonationBtn.disabled = false;
                        cancelDonationBtn.innerHTML = '{{ __("Cancel Payment") }}';
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (cancelDonationBtn) {
                    cancelDonationBtn.disabled = false;
                    cancelDonationBtn.innerHTML = '{{ __("Cancel Payment") }}';
                }
            });
        }
    });
</script>
@endpush
</x-app-layout>
