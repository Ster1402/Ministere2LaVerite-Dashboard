<?php


namespace App\Actions\Ministere2LaVerite;

use App\DTOs\groups\GroupDTO;
use App\Models\Group;

class StoreGroupAction
{
    public function execute(GroupDTO $groupDTO): Group
    {
        $group = Group::create([
            'name' => $groupDTO->name,
            'description' => $groupDTO->description
        ]);

        session()->flash('success', 'Group created successfully');

        return $group;
    }
}
