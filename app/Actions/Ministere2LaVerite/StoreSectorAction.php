<?php

namespace App\Actions\Ministere2LaVerite;

use App\DTOs\sectors\SectorDTO;
use App\Models\Sector;

class StoreSectorAction
{
    public function execute(SectorDTO $sectorDTO): void
    {
        // Create a new sector
        Sector::create([
            'name' => $sectorDTO->name,
            'description' => $sectorDTO->description,
            'master_id' => $sectorDTO->masterId
        ]);

        session()->flash('success', 'Sector created successfully');
    }
}
