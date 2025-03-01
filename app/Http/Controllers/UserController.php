<?php

namespace App\Http\Controllers;

use App\Actions\Ministere2LaVerite\AssignRolesToUserAction;
use App\Actions\Ministere2LaVerite\StoreUserAction;
use App\Actions\Ministere2LaVerite\UpdateUserAction;
use App\Http\Requests\DeleteUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        StoreUserRequest $request,
        StoreUserAction $storeUserAction,
        AssignRolesToUserAction $assignRolesToUserAction
    ): Application|Redirector|RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $storeUserAction->execute(
            $request->toDTO(),
            $assignRolesToUserAction
        );

        return redirect(route('users.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        UpdateUserRequest $request,
        User $user,
        UpdateUserAction $updateUserAction
    )
    {
        $request->validate([
            "email" => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($user->id)
            ],
        ]);

        $updateUserAction->execute($request->toDTO(), $user);

        return redirect(route('users.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        DeleteUserRequest $request, User $user
    ): Application|Redirector|RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $user->deleteOrFail();

        return redirect(route('users.index'));
    }
}
