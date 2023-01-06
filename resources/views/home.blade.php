<x-base-layout>

    <div class="qr-home-container h-screen flex justify-center items-center bg-gray-100">
        <div class="qr-home-inner text-center">
            <div class="qr-home-logo mx-auto w-fit mb-4">
                <x-application-logo class="block h-52 w-auto fill-current text-gray-800" />
            </div>
            <p>Welcome to your QR-Code Generator. Please either register or sign in:</p>
            <div class="qr-home-buttons flex mt-4 w-fit mx-auto gap-4">
                <x-button href="/login" class="bg-black text-white">
                    Login
                </x-button>
                <x-button href="/register" class="bg-black text-white">
                    Register
                </x-button>
            </div>
        </div>
    </div>
</x-base-layout>
