<?php

namespace App\Http\Controllers;

use App\Actions\Ministere2LaVerite\StoreMessageAction;
use App\Actions\Ministere2LaVerite\UpdateMessageAction;
use App\Http\Requests\DeleteMessageRequest;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\UpdateMessageRequest;
use App\Models\Assembly;
use App\Models\Message;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $messagesReceived = Message::orderBy('updated_at', 'desc')
            ->where('receiverId', auth()->id())
            ->where('senderId', '!=', auth()->id())
            ->orWhereHas('assemblies', fn ($q) => $q->where('assemblies.id', \Auth::user()->assembly_id))
            ->filter(request(['search', 'category', 'assembly', 'author']))
            ->paginate(15, ['*'], 'msg-received')
            ->withQueryString();

        $messagesSent = Message::orderBy('updated_at', 'desc')
            ->where('senderId', auth()->id())
            ->where('receiverId', '!=', auth()->id())
            ->filter(request(['search', 'category', 'assembly']))
            ->paginate(15, ['*'], 'msg-sent')
            ->withQueryString();

        return view('messages.index', compact('messagesSent', 'messagesReceived'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::orderBy('name')->get(['id', 'name']);
        $assemblies = Assembly::orderBy('name')->get(['id', 'name']);
        return view('messages.create', compact('users', 'assemblies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        StoreMessageRequest $request,
        StoreMessageAction  $action
    ): Application|Redirector|RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $action->execute($request->toDTO());

        return redirect()->route('messages.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Message $message)
    {
        return view('messages.show', compact('message'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Message $message)
    {
        $users = User::orderBy('name')->get(['id', 'name']);
        $assemblies = Assembly::orderBy('name')->get(['id', 'name']);
        return view('messages.edit', compact('message', 'users', 'assemblies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        UpdateMessageRequest $request,
        Message $message,
        UpdateMessageAction $updateMessageAction
    )
    {
        $updateMessageAction->execute( $request->toDTO(), $message );
        return redirect()->route('messages.index');
    }

    /**
     * Remove the specified resource from storage.
     * @throws \Throwable
     */
    public function destroy(
        DeleteMessageRequest $request,
        Message              $message): Application|Redirector|RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $message->deleteOrFail();

        return redirect(route('messages.index'));
    }
}
