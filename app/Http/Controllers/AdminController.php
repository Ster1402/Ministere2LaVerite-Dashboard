<?php

namespace App\Http\Controllers;

use App\Actions\Ministere2LaVerite\CreateAdminAction;
use App\Actions\Ministere2LaVerite\RevokeAdminAction;
use App\Actions\Ministere2LaVerite\AssignRolesToUserAction;
use App\DTOs\admins\RevokeAdminDTO;
use App\Http\Requests\AddAdminRequest;
use App\Http\Requests\RevokeAdminRequest;
use App\Http\Requests\UpdateAdminRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Roles;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Laravel\Jetstream\Role;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admins.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admins.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        AddAdminRequest $request,
        AssignRolesToUserAction $createAdminAction
    ): RedirectResponse
    {
        $createAdminAction->execute(
            $request->toDTO()
        );

        return redirect(route('admins.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $admin)
    {
        return view('admins.show', compact('admin'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $admin)
    {
        return view('admins.edit', compact('admin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        UpdateAdminRequest $request,
        AssignRolesToUserAction $updateAdminAction): Application|Redirector|RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $updateAdminAction->execute(
            $request->toDTO()
        );

        return redirect(route('admins.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        RevokeAdminRequest $request,
        RevokeAdminAction $revokeAdminAction
    ): Application|Redirector|RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $revokeAdminAction->execute(
            $request->toDTO()
        );

        return redirect(route('admins.index'));
    }
}
