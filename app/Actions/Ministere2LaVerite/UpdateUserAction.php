<?php


namespace App\Actions\Ministere2LaVerite;


use App\DTOs\users\UpdateUserDTO;
use App\Models\Baptism;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UpdateUserAction
{
    public function execute(UpdateUserDTO $updateUserDTO, User $user): void
    {
        DB::beginTransaction();
        
        // Update user information's
        $user->update([
            'name' => $updateUserDTO->name,
            'surname' => $updateUserDTO->surname,
            'email' => $updateUserDTO->email,
            'profession' => $updateUserDTO->profession,
            'profession_details' => $updateUserDTO->profession_details,
            'password' => $updateUserDTO->password ? Hash::make($updateUserDTO->password) : $user->password,
            'dateOfBirth' => $updateUserDTO->dateOfBirth,
            'arrivalDate' => $updateUserDTO->arrivalDate,
            'gender' => $updateUserDTO->gender,
            'phoneNumber' => $updateUserDTO->phoneNumber,
            'antecedent' => $updateUserDTO->antecedent,
            'isActive' => $updateUserDTO->isActive,
            'isDisciplined' => $updateUserDTO->isDisciplined,
            'maritalStatus' => $updateUserDTO->maritalStatus,
            'numberOfChildren' => $updateUserDTO->numberOfChildren,
            'residence' => $updateUserDTO->residence,
            'sterileWoman' => $updateUserDTO->sterileWoman,
            'seriousIllnesses' => $updateUserDTO->seriousIllnesses,
            'comment' => $updateUserDTO->comment,
            'profile_photo_path' => $updateUserDTO->profile_photo_path,
        ]);

        // Find if the date of the baptism has changed
        $baptism = Baptism::firstOrCreate(
            [
                'user_id' => $user->id,
            ],
            [
                'user_id' => $user->id,
                'date_water' => $updateUserDTO->baptism->dateWater,
                'date_holy_spirit' => $updateUserDTO->baptism->dateHolySpirit,
                'date_latest' => $updateUserDTO->baptism->dateLatest,
                'type' => $updateUserDTO->baptism->type,
                'hasHolySpirit' => $updateUserDTO->baptism->hasHolySpirit,
                'ministerialLevel' => $updateUserDTO->baptism->ministerialLevel,
                'nominalMaker' => $updateUserDTO->baptism->nominalMaker,
                'spiritualLevel' => $updateUserDTO->baptism->spiritualLevel,
            ]
        );

        Baptism::where('id', $baptism->id)->update([
            'date_water' => $updateUserDTO->baptism->dateWater,
            'date_holy_spirit' => $updateUserDTO->baptism->dateHolySpirit,
            'date_latest' => $updateUserDTO->baptism->dateLatest,
            'type' => $updateUserDTO->baptism->type,
            'hasHolySpirit' => $updateUserDTO->baptism->hasHolySpirit,
            'ministerialLevel' => $updateUserDTO->baptism->ministerialLevel,
            'nominalMaker' => $updateUserDTO->baptism->nominalMaker,
            'spiritualLevel' => $updateUserDTO->baptism->spiritualLevel,
        ]);

        $baptism = Baptism::firstWhere('id', $baptism->id);

        DB::commit();

        session()->flash('success', 'Information Updated Successfully!');
    }
}
