{{-- Rendered standalone by Customer\PrescriptionController::messages() for the
     Refresh/auto-update fetch, and included directly on first page load. --}}
@forelse ($messages as $message)
    @php $isMine = $message->user_id === auth()->id(); @endphp
    <div style="display:flex; flex-direction:column; align-items:{{ $isMine ? 'flex-end' : 'flex-start' }};">
        <div style="max-width:80%; background:{{ $isMine ? 'var(--blue)' : '#fff' }}; color:{{ $isMine ? '#fff' : 'var(--ink)' }}; border:1px solid {{ $isMine ? 'var(--blue)' : 'var(--line)' }}; padding:8px 12px; border-radius:12px; font-size:13.5px; white-space:pre-wrap; word-break:break-word;">{{ $message->body }}</div>
        <div class="hint" style="margin-top:2px;">{{ $isMine ? 'You' : $message->sender->first_name }} &middot; {{ $message->created_at->format('h:i A') }}</div>
    </div>
@empty
    <p class="hint" style="text-align:center; margin:10px 0;">No messages yet — say hello to the store.</p>
@endforelse
