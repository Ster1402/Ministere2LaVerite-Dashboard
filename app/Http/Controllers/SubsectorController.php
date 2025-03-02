<?php

namespace App\Http\Controllers;

use App\Actions\Ministere2LaVerite\StoreSectorAction;
use App\Actions\Ministere2LaVerite\UpdateSectorAction;
use App\Http\Requests\DeleteSectorRequest;
use App\Http\Requests\StoreSectorRequest;
use App\Http\Requests\UpdateSectorRequest;
use App\Models\Sector;

class SubsectorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('subsectors.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('subsectors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        StoreSectorRequest $request,
        StoreSectorAction $storeSectorAction
    ) {
        $storeSectorAction->execute($request->toDTO());
        return redirect()->route('subsectors.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Sector $sector)
    {
        return view('subsectors.show', compact('sector'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sector $sector)
    {
        return view('subsectors.show', compact('sector'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        UpdateSectorRequest $request,
        Sector $sector,
        UpdateSectorAction $updateSectorAction
    ) {
        $updateSectorAction->execute($request->toDTO(), $sector);
        return redirect()->route('subsectors.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        DeleteSectorRequest $request,
        Sector $sector
    ) {
        $sector->delete();
        return redirect()->route('subsectors.index');
    }
}
