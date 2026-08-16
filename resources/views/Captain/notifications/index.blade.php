<x-layouts.captain-layout title="Notifications">

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @include('common.notifications-list', ['rolePrefix' => 'captain'])

</x-layouts.captain-layout>
