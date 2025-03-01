<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserInAssemblyRequest;
use App\Models\Assembly;
use App\Models\User;
use Illuminate\Http\Request;

class AddUserToAssemblyController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(
        StoreUserInAssemblyRequest $request,
        Assembly                   $assembly
    )
    {
        $user_ids = $request->user_ids;

        foreach ($assembly->users as $user) {
            $user->update(['assembly_id' => null]);
        }

        foreach ($user_ids as $id) {
            $user = User::findOrFail($id);
            $user->update(['assembly_id' => $assembly->id]);
        }

        return redirect()->route('assemblies.index');
    }
}
