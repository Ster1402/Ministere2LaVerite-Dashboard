<?php

namespace App\View\Components;

use App\Models\Donation;
use Illuminate\View\Component;

class RecentDonations extends Component
{
    public $donations;

    public function __construct()
    {
        $this->donations = Donation::where('status', 'completed')
            ->latest()
            ->take(5)
            ->get();
    }

    public function render()
    {
        return view('components.recent-donations');
    }
}
