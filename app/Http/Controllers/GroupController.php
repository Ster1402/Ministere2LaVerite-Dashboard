<?php

namespace App\Http\Controllers;

use App\Actions\Ministere2LaVerite\StoreGroupAction;
use App\Actions\Ministere2LaVerite\UpdateGroupAction;
use App\Http\Requests\DeleteGroupRequest;
use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use App\Models\Group;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('groups.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('groups.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        StoreGroupRequest $request,
        StoreGroupAction $storeGroupAction,
    ) : RedirectResponse
    {
        $storeGroupAction->execute($request->toDTO());
        return redirect(route('groups.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Group $group)
    {
        return view('groups.show', compact('group'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Group $group)
    {
        return view('groups.edit', compact('group'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        UpdateGroupRequest $request,
        Group $group,
        UpdateGroupAction $action
    )
    {
        $action->execute($request->toDTO(), $group);
        return redirect()->route('groups.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        DeleteGroupRequest $request,
        Group $group)
    {
        $group->delete();
        return redirect()->route('groups.index');
    }
}
