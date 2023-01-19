<x-app-layout>
    <x-slot name="header">
        {{ __('Your QR Codes') }}
    </x-slot>

    <x-button-bar>
        <x-button href="{{route('codes.create')}}" class="bg-indigo-900 text-white">{{__('Create new code')}}</x-button>
        <x-button href="/codes/export" class="bg-indigo-100 text-indigo-900">{{__('Export stats')}}</x-button>
        <x-button href="/codes/export-svg" class="text-green-100 bg-green-900">{{__('Export SVG')}}</x-button>
    </x-button-bar>

    @if (count($codes) != 0)
        <table class="w-full mb-12 border-2 border-indigo-900">
        <thead>
            <tr>
                <th>{{__('Name')}}</th>
                <th>{{__('Scans')}}</th>
                <th>{{__('Actions')}}</th>
            </tr>
        </thead>
        @foreach ($codes as $code)
        <tr>
            <td><a href="/codes/{{$code->uuid}}" class="underline text-indigo-900">{{$code->name}}</a></td>
            <td>{{$code->scans}}</td>
            <td>...</td>
        </tr>
        @endforeach
    </table>
    @else
        <p>{{__('You have no codes yet.')}}</p>
    @endif

    @if ($codes->hasPages())
        <div class="pagination-wrapper">
            {{ $codes->links() }}
        </div>
    @endif
</x-app-layout>

<style>


    table {
        width: 100%;
    }
    table thead tr th {
        background: lightgrey;
        text-align: left;
    }

    table td, table th {
        padding: 1rem 0.6rem;
    }

    table tr:nth-child(even) {
        background-color: #DFE7FD;
    }

    table tr {
        border: 1px solid indigo;
    }
</style>