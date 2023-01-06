<section>
    <header>
        <h2 class="text-lg font-medium text-red-600">
            {{ __('Delete All Codes') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {!! __("By clicking the button below, you can delete all your QR-Codes. Please make sure you know what you are doing.") !!}
        </p>
    </header>
    @if (session('status') === 'codes-deleted')
        <p
            x-data="{ show: true }"
            x-show="show"
            x-transition
            x-init="setTimeout(() => show = false, 2000)"
            class="text-red-600 mt-8 font-bold"
        >{{ __('All codes deleted successfully.') }}</p>
    @endif
    <form method="post" action="{{ route('tools.delete') }}" class="mt-2 space-y-6">
        @csrf
        @method('delete')
        <button type="submit" class="p-2 bg-red-600 text-white rounded-sm w-1/4 ml-auto font-bold"
            onclick="return confirm('{{ __('Are you sure you want to delete all your codes? This action can not be undone') }}')"
        >{{__(("Delete all codes"))}}</button>
    </form>
</section>
