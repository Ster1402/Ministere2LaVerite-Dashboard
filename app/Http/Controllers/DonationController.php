<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Transaction;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DonationController extends Controller
{
    /**
     * Store a new donation transaction.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:100',
            'currency' => 'required|string',
            'comment' => 'nullable|string',
            'name' => 'nullable|string',
            'email' => 'nullable|email',
            'phone' => 'required|string',
            'payment_method_id' => 'required|exists:payment_methods,id',
        ]);

        // Validate the phone number against the selected payment method
        $paymentMethod = PaymentMethod::findOrFail($validated['payment_method_id']);
        $phone = preg_replace('/\s+/', '', $validated['phone']);

        if ($paymentMethod->phone_regex && !preg_match('/' . $paymentMethod->phone_regex . '/', $phone)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['phone' => 'Le numéro de téléphone ne correspond pas au service de paiement sélectionné.']);
        }

        // Generate a reference number
        $referenceNumber = 'DON-' . strtoupper(Str::random(6));

        // Create the transaction record
        $transaction = Transaction::create([
            'amount' => $validated['amount'],
            'currency' => $validated['currency'],
            'comment' => $validated['comment'] ?? 'Don anonyme',
            'user_id' => auth()->id(), // Will be null for guest users
            'donor_name' => $validated['name'] ?? 'Anonyme',
            'donor_email' => $validated['email'] ?? null,
            'donor_phone' => $phone,
            'payment_method_id' => $validated['payment_method_id'],
            'is_processed' => true,
            'transaction_reference' => $referenceNumber,
        ]);

        // Create the donation record
        $donation = Donation::create([
            'transaction_id' => $transaction->id,
            'amount' => $validated['amount'],
            'donor_name' => $validated['name'] ?? 'Anonyme',
            'donor_email' => $validated['email'] ?? null,
            'donor_phone' => $phone,
            'donation_date' => now(),
            'message' => $validated['comment'] ?? null,
            'is_anonymous' => empty($validated['name']),
            'user_id' => auth()->id(),
            'payment_method_id' => $validated['payment_method_id'],
            'status' => 'completed'
        ]);

        // Log the donation for administrative purposes
        \Log::info('New donation received', [
            'donation_id' => $donation->id,
            'transaction_id' => $transaction->id,
            'amount' => $transaction->amount,
            'payment_method' => $paymentMethod->display_name,
            'phone' => $phone,
            'reference' => $referenceNumber
        ]);

        // Return with success message
        return redirect()->back()
            ->with('success', "Merci pour votre don de {$transaction->amount} {$transaction->currency}! Votre référence de transaction est: {$referenceNumber}");
    }
}
