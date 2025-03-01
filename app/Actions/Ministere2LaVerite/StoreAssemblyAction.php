<?php

namespace App\Actions\Ministere2LaVerite;

use App\DTOs\assembly\AssemblyDTO;
use App\Models\Assembly;

class StoreAssemblyAction
{
    public function execute(AssemblyDTO $assemblyDTO): void
    {
        $assembly = Assembly::create([
            'name' => $assemblyDTO->name,
            'description' => $assemblyDTO->description,
            'sector_id' => $assemblyDTO->sectorId
        ]);

        session()->flash('success', 'Assembly created successfully');
    }
}
