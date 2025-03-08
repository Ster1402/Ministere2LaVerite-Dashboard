<?php

namespace App\View\Components;

use App\Models\Donation;
use Illuminate\View\Component;

class DonationStats extends Component
{
    public $totalAmount;
    public $donationsCount;
    public $pendingCount;

    public function __construct()
    {
        $this->totalAmount = Donation::where('status', 'completed')->sum('amount');
        $this->donationsCount = Donation::where('status', 'completed')->count();
        $this->pendingCount = Donation::where('status', 'pending')->count();
    }

    public function render()
    {
        return view('components.donation-stats');
    }
}
