<x-app-layout>
    <x-slot name="header">
        {!! __("Code: <em>{$code->name}</em>") !!}
    </x-slot>

    <x-button-bar>
        <form method="POST" action="{{route('codes.destroy', ["code" => $code->uuid])}}">
            @csrf
            @method('DELETE')
            @php
                $confirm = __("Are you sure you want to delete this code? This action cannot be undone.");
            @endphp
            <x-button
                type="submit" class="bg-red-900 text-white cursor-pointer"
                onclick="
                    event.preventDefault();
                    var proceed = confirm('{{$confirm}}');
                    if (proceed) {
                        this.closest('form').submit();
                    }
                    ">
                    {{__('Delete code')}}
            </x-button>
        </form>
        <x-button href="{{route('codes.edit', ['code' => $code->uuid])}}" class="bg-indigo-900 text-white">{{__('Edit code')}}</x-button>
    </x-button-bar>

    <div class="flex flex-wrap gap-y-6">
        <div class="qr-img w-full lg:w-1/3 lg:pr-4">
            <div class="w-full max-w-xs">
                <div id="{{str_replace(" ", "-", strtolower($code->name)) . '-' . $code->uuid}}">
                    <div class="qrcode-img" style="--bg-color: {{$code->bg_color}}">
                        {{$qr}}
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap mt-4 gap-2 download-qr cursor-pointer" data-filename="{{str_replace(" ", "-", strtolower($code->name)) . '-' . $code->uuid}}">
                <x-button class="block bg-indigo-900 text-white w-fit" data-type="svg" data-mine="image/svg+xml">{{_("Download SVG")}}</x-button>
                <x-button class="block bg-indigo-200 text-indigo-900 w-fit" data-type="png" data-mine="image/png">{{_("Download PNG")}}</x-button>
                <x-button class="block bg-indigo-900 text-white w-fit" data-type="jpeg" data-mine="image/jpeg">{{_("Download JPEG")}}</x-button>
            </div>
        </div>
        <div class="qr-details w-full lg:w-2/3 lg:pl-4">
            <table>
                <tr>
                    <td class="font-bold">{{__("Name")}}</td>
                    <td>{{$code->name}}</td>
                </tr>
                <tr>
                    <td class="font-bold">{{__("Description")}}</td>
                    <td>{{$code->description}}</td>
                </tr>
                <tr>
                    <td class="font-bold">{{__("Link")}}</td>
                    <td>{{$code->link}}</td>
                </tr>
                <tr>
                    <td class="font-bold">{{__("Scans")}}</td>
                    <td>{{$code->scans}}</td>
                </tr>
                <tr>
                    <td class="font-bold">{{__("Unique Scans")}}</td>
                    <td>{{$code->u_scans}}</td>
                </tr>
                <tr>
                    <td class="font-bold">{{__("Copy Link:")}}</td>
                    <td><a href="{{env("APP_URL") . env("APP_REDIRECT_BASE", "/r/") . $code->uuid}}" id="copy-link" class="text-indigo-600 underline">{{env("APP_URL") . env("APP_REDIRECT_BASE", "/r/") . $code->uuid}}</a></td>
                </tr>
            </table>
        </div>
    </div>

</x-app-layout>

<style>
    svg {
        width: 100%;
        height: 100%;
    }

    table {
        width: 100%;
    }
    table td {
        padding: 0.5rem 0.3rem;
        width: 50%;
    }

    table tr:nth-child(odd) {
        background-color: #DFE7FD;
    }

    .qrcode-img {
        border: 1rem solid var(--bg-color);
        background: var(--bg-color);
    }
</style>