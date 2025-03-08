<?php

namespace App\View\Components;

use App\Services\messages\ApiMessageService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Twilio\Exceptions\TwilioException;

class InfoMessageAccount extends Component
{
    protected ApiMessageService $apiMessageService;
    public string $balance = 'Unknown';
    public mixed $account = 'Unknown';
    public mixed $name = 'Unknown';

    /**
     * Create a new component instance.
     *
     * @return void
     * @throws TwilioException
     */
    public function __construct(ApiMessageService $apiMessageService)
    {
        try {
            $this->apiMessageService = $apiMessageService;
            $accountInfo = $this->apiMessageService->getInfoMessageAccount();
            $this->balance = $accountInfo['balance'];
            $this->account = $accountInfo['account'];
            $this->name = $accountInfo['name'];
        } catch (\Exception $e) {
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return Application|Factory|View
     */
    public function render()
    {
        return view('components.info-message-account');
    }
}
