<?php

namespace App\Http\Controllers;

use App\Actions\Ministere2LaVerite\StoreAssemblyAction;
use App\Actions\Ministere2LaVerite\UpdateAssemblyAction;
use App\Http\Requests\DeleteAssemblyRequest;
use App\Http\Requests\StoreAssemblyRequest;
use App\Http\Requests\UpdateAssemblyRequest;
use App\Models\Assembly;
use Illuminate\Http\Request;

class AssemblyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('assemblies.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('assemblies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        StoreAssemblyRequest $request,
        StoreAssemblyAction $assemblyAction
    )
    {
        $assemblyAction->execute($request->toDTO());

        return view('assemblies.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Assembly $assembly)
    {
        return view('assemblies.show', compact('assembly'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Assembly $assembly)
    {
        return view('assemblies.edit', compact('assembly'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        UpdateAssemblyRequest $request,
        Assembly $assembly,
        UpdateAssemblyAction $updateAssemblyAction
    )
    {
        $updateAssemblyAction->execute($request->toDTO(), $assembly);
        return redirect(route('assemblies.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        DeleteAssemblyRequest $request,
        Assembly $assembly)
    {
        $assembly->delete();
        return redirect(route('assemblies.index'));
    }
}
