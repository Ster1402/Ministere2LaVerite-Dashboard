<?php

namespace App\Http\Controllers;

use App\Actions\Ministere2LaVerite\StoreResourceAction;
use App\Actions\Ministere2LaVerite\UpdateResourceAction;
use App\Http\Requests\DeleteResourceRequest;
use App\Http\Requests\StoreResourceRequest;
use App\Http\Requests\UpdateResourceRequest;
use App\Models\Resource;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('resources.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('resources.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        StoreResourceRequest $request,
        StoreResourceAction $storeResourceAction
    )
    {
        $storeResourceAction->execute($request->toDTO());
        return redirect()->route('resources.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Resource $resource)
    {
        return view('resources.show', compact('resource'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Resource $resource)
    {
        return view('resources.edit', compact('resource'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        UpdateResourceRequest $request,
        Resource $resource,
        UpdateResourceAction $updateResourceAction
    )
    {
        $updateResourceAction->execute($request->toDTO(), $resource);
        return redirect()->route('resources.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        DeleteResourceRequest $request,
        Resource $resource)
    {
        $resource->delete();
        return redirect()->route('resources.index');
    }
}
