{{-- Rendered standalone by Store\PrescriptionController::messages() for the
     Refresh/auto-update fetch, and included directly on first page load. --}}
@forelse ($messages as $message)
    @php $isMine = $message->user_id === auth()->id(); @endphp
    <div class="d-flex flex-column {{ $isMine ? 'align-items-end' : 'align-items-start' }} mb-2">
        <div class="p-2 rounded {{ $isMine ? 'bg-primary text-white' : 'bg-light' }}" style="max-width:75%; white-space:pre-wrap; word-break:break-word;">
            {{ $message->body }}
        </div>
        <small class="text-muted mt-1">{{ $isMine ? 'You' : $message->sender->first_name }} &middot; {{ $message->created_at->format('d M, h:i A') }}</small>
    </div>
@empty
    <p class="text-muted text-center mb-0">No messages yet — say hello to the customer.</p>
@endforelse
