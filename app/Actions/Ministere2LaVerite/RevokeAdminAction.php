<?php


namespace App\Actions\Ministere2LaVerite;


use App\DTOs\admins\RevokeAdminDTO;
use Spatie\LaravelData\Data;

class RevokeAdminAction
{
    public function execute(RevokeAdminDTO|Data $adminDTO): void
    {
        $adminDTO->user->roles()->detach();
        session()->flash('success', 'Admin authorization was revoke successfully');
    }
}
