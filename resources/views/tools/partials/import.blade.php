<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Import Codes') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {!! __("Import Codes through a CSV File. <a href='/media/import-template.csv' class='underline'>Use this template file.</a>") !!}
        </p>
    </header>
    @if (session('status') === 'codes-imported')
        <p
            x-data="{ show: true }"
            x-show="show"
            x-transition
            x-init="setTimeout(() => show = false, 2000)"
            class="text-green-600 mt-8 font-bold"
        >{{ __('Codes Imported.') }}</p>
    @endif
    <form method="post" action="{{ route('tools.import') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        <div>
            <x-input-label for="import_csv" :value="__('Import File')" />
            <x-text-input id="import_csv" name="import_csv" type="file" class="mt-1 block w-full" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>
        <button type="submit" class="p-2 bg-indigo-900 text-white rounded-sm w-1/4 ml-auto font-bold">{{__(("Import Codes"))}}</button>
    </form>
</section>
