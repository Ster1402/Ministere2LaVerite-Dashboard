<?php

namespace App\Http\Controllers;

use App\Actions\Ministere2LaVerite\StoreMediaAction;
use App\Http\Requests\DeleteMediaRequest;
use App\Http\Requests\StoreMediaRequest;
use App\Http\Requests\UpdateDocumentRequest;
use App\Models\Assembly;
use App\Models\Media;
use App\Models\User;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('medias.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('medias.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        StoreMediaRequest $request,
        StoreMediaAction $action
    )
    {
        $action->execute($request->toDTO());
        return redirect()->route('medias.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Media $media)
    {
        return view('medias.show', compact('media'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Media $media)
    {
        return view('medias.edit', compact('media'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDocumentRequest $request, Media $media)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        DeleteMediaRequest $request,
        Media              $media)
    {
        $media->delete();
        return redirect()->route('medias.index');
    }
}
