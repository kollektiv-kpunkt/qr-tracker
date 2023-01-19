<x-app-layout>
    <x-slot name="header">
        {{ __('Create new code') }}
    </x-slot>
    <div class="bg-white shadow-md overflow-hidden sm:rounded-lg p-8 ">
        <div class="max-w-xl">
            <form method="POST" action="{{ route('codes.update', ["code" => $code]) }}">
                @csrf
                @method('PUT')
                <div class="w-full flex flex-col gap-y-5 gap-x-3 flex-wrap">
                    @php
                        $fields = [
                            "uuid" => [
                                "label" => "UUID",
                                "required" => true,
                            ],
                            "name" => [
                                "label" => "Name",
                                "required" => true,
                            ],
                            "description" => [
                                "label" => "Description"
                            ],
                            "link" => [
                                "label" => "Link",
                                "required" => true,
                            ],
                            "fg_color" => [
                                "label" => "Foreground Color",
                                "width" => "w-1/4",
                                "type" => "color",
                                "required" => true,
                            ],
                            "bg_color" => [
                                "label" => "Background Color",
                                "width" => "w-1/4",
                                "type" => "color",
                                "required" => true,
                            ]
                        ]
                    @endphp
                    @foreach ($fields as $field => $options)
                        <div class="{{(isset($options["width"])) ? $options["width"] : "w-full"}} ">
                            <x-input-label for="{{$field}}" :value="__($options['label'])" />
                            <x-text-input
                                id="{{$field}}"
                                :readonly="(isset($options['readonly'])) ? $options['readonly'] : false"
                                class="block mt-1 w-full"
                                type="{{(isset($options['type'])) ? $options['type'] : 'text'}}"
                                name="{{$field}}"
                                :required="(isset($options['required'])) ? $options['required'] : false"
                                autofocus
                                :value="old($field, $code->$field)"
                            />
                            <x-input-error :messages="$errors->get($field)" class="mt-2" />
                        </div>
                    @endforeach
                    <input type="hidden" name="user_id" value="{{auth()->user()->id}}">
                    <div class="w-full flex">
                        <button type="submit" class="p-2 bg-indigo-900 text-white rounded-sm w-1/4 ml-auto font-bold">{{__(("Update Code"))}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

<style>
    input[readonly] {
        background-color: #e0e0e0;
    }
</style>