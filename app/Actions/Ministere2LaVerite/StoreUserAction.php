<?php


namespace App\Actions\Ministere2LaVerite;


use App\DTOs\commons\AssignRolesToUserDTO;
use App\DTOs\users\StoreUserDTO;
use App\Models\Baptism;
use App\Models\Roles;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class StoreUserAction
{
    public function execute(
        StoreUserDTO $storeUserDTO,
        AssignRolesToUserAction $assignRolesToUserAction
    ): void
    {
        $user = User::create(
            [
                'name' => $storeUserDTO->name,
                'surname' => $storeUserDTO->surname,
                'email' => $storeUserDTO->email,
                'profession' => $storeUserDTO->profession,
                'profession_details' => $storeUserDTO->profession_details,
                'password' => Hash::make($storeUserDTO->password),
                'dateOfBirth' => $storeUserDTO->dateOfBirth,
                'arrivalDate' => $storeUserDTO->arrivalDate,
                'gender' => $storeUserDTO->gender,
                'phoneNumber' => $storeUserDTO->phoneNumber,
                'antecedent' => $storeUserDTO->antecedent,
                'isActive' => $storeUserDTO->isActive,
                'isDisciplined' => $storeUserDTO->isDisciplined,
                'maritalStatus' => $storeUserDTO->maritalStatus,
                'numberOfChildren' => $storeUserDTO->numberOfChildren,
                'residence' => $storeUserDTO->residence,
                'sterileWoman' => $storeUserDTO->sterileWoman ?? false,
                'seriousIllnesses' => $storeUserDTO->seriousIllnesses,
                'profile_photo_path' => $storeUserDTO->profile_photo_path,
                'comment' => $storeUserDTO->comment
            ]
        );

        // Create Baptism information
        $baptism = Baptism::create([
            'date_water' => $storeUserDTO->baptism->dateWater,
            'date_holy_spirit' => $storeUserDTO->baptism->dateHolySpirit,
            'date_latest' => $storeUserDTO->baptism->dateLatest,
            'user_id' => $user->id,
            'type' => $storeUserDTO->baptism->type,
            'hasHolySpirit' => $storeUserDTO->baptism->hasHolySpirit ?? false,
            'ministerialLevel' => $storeUserDTO->baptism->ministerialLevel,
            'nominalMaker' => $storeUserDTO->baptism->nominalMaker,
            'spiritualLevel' => $storeUserDTO->baptism->spiritualLevel
        ]);

        $assignRolesToUserAction->execute(
            new AssignRolesToUserDTO(
                user: $user,
                rolesNames: [Roles::$END_USER]
            )
        );
    }
}
