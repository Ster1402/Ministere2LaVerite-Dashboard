<?php

namespace App\Actions\Ministere2LaVerite;

use App\DTOs\assembly\AssemblyDTO;
use App\Models\Assembly;

class UpdateAssemblyAction
{
    public function execute(AssemblyDTO $dto, Assembly $assembly)
    {
        $assembly->update([
            'name' => $dto->name,
            'sector_id' => $dto->sectorId,
            'description' => $dto->description,
        ]);

        session()->flash('success', 'Assembly updated successfully');
    }
}
