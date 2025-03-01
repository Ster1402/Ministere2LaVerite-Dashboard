<?php

namespace App\Actions\Ministere2LaVerite;

use App\DTOs\resources\ResourceDTO;
use App\Models\Resource;

class UpdateResourceAction
{
    public function execute(ResourceDTO $resourceDTO, Resource $resource)
    {
        // Update the resource
        $resource->update([
            'name' => $resourceDTO->name,
            'group_id' => $resourceDTO->groupId,
            'quantity' => $resourceDTO->quantity,
            'description' => $resourceDTO->description,
        ]);

        session()->flash('success', 'Resource updated successfully');
    }
}
