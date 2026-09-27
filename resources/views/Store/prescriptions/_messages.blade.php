{{-- Rendered standalone by Store\PrescriptionController::messages() for the
     Refresh/auto-update fetch, and included directly on first page load. --}}
@forelse ($messages as $message)
    @php $isMine = $message->user_id === auth()->id(); @endphp
    <div class="chat-message-row {{ $isMine ? 'is-mine' : '' }}">
        <div class="chat-bubble">
            {{ $message->body }}
        </div>
        <small class="chat-meta">{{ $isMine ? 'You' : $message->sender->first_name }} &middot; {{ $message->created_at->format('d M, h:i A') }}</small>
    </div>
@empty
    <p class="text-muted text-center mb-0">No messages yet — say hello to the customer.</p>
@endforelse
