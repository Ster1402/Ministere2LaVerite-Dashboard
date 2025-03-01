<a href="{{ route('messages.index') }}#message-{{ $id }}" id="message-{{ $id }}" class="dropdown-item dropdown-item-unread">
    <div class="dropdown-item-avatar">
        <img alt="image" src="{{ $message->sender->profile_photo_url }}" class="rounded-circle">
        <div class="is-online"></div>
    </div>
    <div class="dropdown-item-desc">
        <b>{{ $sender }}</b>
        <p>{{ $msg }}</p>
        <div class="time">{{ $sendAt }}</div>
    </div>
</a>
