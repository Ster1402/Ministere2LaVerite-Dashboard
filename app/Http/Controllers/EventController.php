<?php

namespace App\Http\Controllers;

use App\Actions\Ministere2LaVerite\StoreEventAction;
use App\Actions\Ministere2LaVerite\UpdateEventAction;
use App\Http\Requests\DeleteEventRequest;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('events.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('events.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        StoreEventRequest $request,
        StoreEventAction $storeEventAction
    )
    {
        $storeEventAction->execute(
            $request->toDTO()
        );

        return redirect(route('events.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        return view('events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        return view('events.edit', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        UpdateEventRequest $request,
        Event $event,
        UpdateEventAction $updateEventAction
    )
    {
        $updateEventAction->execute(
            $request->toDTO(),
            $event
        );

        return redirect(route('events.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleteEventRequest $request, Event $event): Application|Redirector|RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $event->delete();

        return redirect(route('events.index'));
    }
}
