<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PaymentMethodController extends Controller
{
    /**
     * Get all active payment methods.
     */
    public function index(): JsonResponse
    {
        $paymentMethods = PaymentMethod::where('is_active', true)
            ->orderBy('order')
            ->get();

        return response()->json($paymentMethods);
    }

    /**
     * Validate a phone number against a payment method.
     */
    public function validatePhone(Request $request): JsonResponse
    {
        $request->validate([
            'phone' => 'required|string',
            'payment_method_id' => 'required|exists:payment_methods,id',
        ]);

        $phone = preg_replace('/\s+/', '', $request->phone);
        $paymentMethod = PaymentMethod::findOrFail($request->payment_method_id);

        $isValid = false;
        $message = 'Le numéro ne correspond pas à ce service.';

        if ($paymentMethod->phone_regex) {
            $isValid = preg_match('/' . $paymentMethod->phone_regex . '/', $phone);
            if ($isValid) {
                $message = 'Numéro valide pour ' . $paymentMethod->display_name;
            }
        }

        return response()->json([
            'valid' => $isValid,
            'message' => $message,
            'payment_method' => $paymentMethod
        ]);
    }
}
