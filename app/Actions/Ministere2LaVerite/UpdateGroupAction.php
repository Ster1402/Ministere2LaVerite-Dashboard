<?php

namespace App\Actions\Ministere2LaVerite;

use App\DTOs\groups\GroupDTO;
use App\Models\Group;

class UpdateGroupAction
{
    public function execute(GroupDTO $groupDTO, Group $group): void
    {
        $group->update([
            'name' => $groupDTO->name,
            'description' => $groupDTO->description
        ]);

        session()->flash('success', 'Group updated successfully');
    }
}
