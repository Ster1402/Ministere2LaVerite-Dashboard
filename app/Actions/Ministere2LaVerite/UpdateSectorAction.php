<?php

namespace App\Actions\Ministere2LaVerite;

use App\DTOs\sectors\SectorDTO;
use App\Models\Sector;

class UpdateSectorAction
{
    public function execute(SectorDTO $sectorDTO, Sector $sector): void
    {
        $sector->update([
            'name' => $sectorDTO->name,
            'description' => $sectorDTO->description,
            'master_id' => $sectorDTO->masterId
        ]);

        session()->flash('success', 'Sector updated successfully');
    }
}
