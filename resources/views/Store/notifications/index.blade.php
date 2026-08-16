<x-layouts.store-layout title="Notifications">

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @include('common.notifications-list', ['rolePrefix' => 'store'])

</x-layouts.store-layout>
