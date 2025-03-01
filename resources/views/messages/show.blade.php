<x-app-layout>
    <x-slot name="header">
        <x-banner>
            <x-slot name="title">
                <div class="section-header-back">
                    <a href="{{ route('messages.index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
                </div>
                <h1>Ticket</h1>
            </x-slot>
            <x-slot name="breadcrumb">
                <div class="breadcrumb-item"><a href="{{ route('messages.index') }}">{{ __('Messageries') }}</a></div>
                <div class="breadcrumb-item">{{ __('Ticket') }}</div>
            </x-slot>
        </x-banner>
    </x-slot>

    <x-slot name="subtitle">
        {{ __('Consultez les mails de vos utilisateurs') }}
    </x-slot>

    <div class="card-body">
        <div class="tickets">
            <div class="ticket-items" id="ticket-items">
                @foreach($message->sender->messagesSent()->where('receiverId', Auth::id())->get() as $msg)
                    <a href="{{ route('messages.show', ['message' => $msg->id]) }}"
                       class="block ticket-item {{ $message->is($msg) ? 'active' : ''}}">
                        <div class="ticket-title">
                            <h4>{{ $msg->subject }}</h4>
                        </div>
                        <div class="ticket-desc">
                            <div>{{ $msg->sender->name }}</div>
                            <div class="bullet"></div>
                            <div>{{ $msg->updated_at->diffForHumans() }}</div>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="ticket-content">
                <div class="ticket-header">
                    <div class="ticket-sender-picture img-shadow">
                        <img src="{{ $message->picture_path }}" alt="image">
                    </div>
                    <div class="ticket-detail">
                        <div class="ticket-title">
                            <h4>{{ $message->subject }}</h4>
                        </div>
                        <div class="ticket-info">
                            <div class="font-weight-600">{{ $message->sender->name }}</div>
                            <div class="bullet"></div>
                            <div class="text-primary font-weight-600">{{ $message->updated_at->diffForHumans() }}</div>
                        </div>
                    </div>
                </div>
                <div class="ticket-description">
                    {!! $message->content !!}

                    <div class="gallery">
                        <div class="gallery-item" data-image="{{ $message->picture_path }}"
                             data-title="{{ $message->subject }}"></div>
                    </div>

                    @foreach($message->replies as $msg)
                        <div class="ticket-divider"></div>
                        <div class="ticket-header">
                            <div class="ticket-sender-picture img-shadow">
                                <img src="{{ $msg->sender->profile_photo_url }}" alt="image">
                            </div>
                            <div class="ticket-detail">
                                <div class="ticket-title ticket-info">
                                    <div class="font-weight-600">{{ $msg->sender->name }}</div>
                                    <div class="bullet"></div>
                                    <div
                                        class="text-primary font-weight-600">{{ $msg->updated_at->diffForHumans() }}</div>
                                </div>
                                <div class="ticket-description">
                                    {!! $msg->content !!}
                                </div>
                            </div>
                        </div>

                    @endforeach

                    <div class="ticket-form">
                        <form method="POST" action="{{ route('messages.store') }}">
                            @csrf
                            <x-validation-errors class="mb-4"/>

                            <input type="hidden" name="replyTo" value="{{ $message->id }}"/>
                            <input type="hidden" name="subject" value="{{ $message->subject }}"/>
                            <input type="hidden" name="category" value="{{ $message->category }}"/>
                            <input type="hidden" name="receiver" value="{{ $message->sender->id }}"/>
                            <input type="hidden" name="receiver" value="{{ $message->sender->id }}"/>
                            <div class="form-group">
                                <label>
                                    <textarea required name="content" class="summernote form-control"
                                              placeholder="Votre réponse..."></textarea>
                                </label>
                            </div>
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    {{ __('Répondre') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
