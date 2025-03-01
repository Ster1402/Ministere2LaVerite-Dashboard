<?php

namespace App\Actions\Ministere2LaVerite;

use App\DTOs\resources\ResourceDTO;
use App\Models\Resource;

class StoreResourceAction
{
    public function execute(ResourceDTO $resourceDTO)
    {
        // Store the resource
        Resource::create([
            'name' => $resourceDTO->name,
            'group_id' => $resourceDTO->groupId,
            'quantity' => $resourceDTO->quantity,
            'description' => $resourceDTO->description,
        ]);

        session()->flash('success', 'Resource created successfully');
    }
}
