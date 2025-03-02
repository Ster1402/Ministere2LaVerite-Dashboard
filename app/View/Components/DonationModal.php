<?php

namespace App\View\Components;

use App\Models\PaymentMethod;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DonationModal extends Component
{
    public $paymentMethods;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->paymentMethods = PaymentMethod::where('is_active', true)
            ->orderBy('order')
            ->get();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.donation-modal', [
            'paymentMethods' => $this->paymentMethods
        ]);
    }
}
