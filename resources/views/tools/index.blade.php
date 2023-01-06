<x-app-layout>
    <x-slot name="header">
        {{ __('Tools') }}
    </x-slot>

    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
        <div class="max-w-xl">
            @include('tools.partials.import')
        </div>
    </div>
    <div class="p-4 mt-10 sm:p-8 bg-white shadow sm:rounded-lg">
        <div class="max-w-xl">
            @include('tools.partials.delete-all')
        </div>
    </div>
</x-app-layout>
