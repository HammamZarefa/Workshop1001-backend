@extends('layouts.main')

@section('content')

<div class="max-w-4xl mx-auto">

    <h1 class="text-3xl font-bold mb-6 text-gray-800">
        System Settings
    </h1>

   <form action="{{ route('admin.settings.update') }}" method="POST">
    @csrf
    @method('PUT')

        @foreach($settings as $setting)

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    {{ ucfirst(str_replace('_', ' ', $setting->key)) }}
                </label>

                @if($setting->type === 'string')
                    <input
                        type="text"
                        name="settings[{{ $setting->key }}]"
                        value="{{ old('settings.'.$setting->key, $setting->value) }}"
                        class="w-full border rounded-lg px-4 py-2 focus:ring focus:ring-blue-200"
                    />

                @elseif($setting->type === 'number')
                    <input
                        type="number"
                        step="any"
                        name="settings[{{ $setting->key }}]"
                        value="{{ old('settings.'.$setting->key, $setting->value) }}"
                        class="w-full border rounded-lg px-4 py-2 focus:ring focus:ring-blue-200"
                    />

                @elseif($setting->type === 'boolean')
                    <select
                        name="settings[{{ $setting->key }}]"
                        class="w-full border rounded-lg px-4 py-2"
                    >
                        <option value="1" @selected($setting->value)>Yes</option>
                        <option value="0" @selected(!$setting->value)>No</option>
                    </select>

                @elseif($setting->type === 'json')
                    <textarea
                        name="settings[{{ $setting->key }}]"
                        rows="4"
                        class="w-full border rounded-lg px-4 py-2 font-mono text-sm"
                    >{{ old('settings.'.$setting->key, json_encode($setting->value, JSON_PRETTY_PRINT)) }}</textarea>
                @endif

                @error('settings.'.$setting->key)
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

        @endforeach

        <div class="text-right">
            <button
                type="submit"
                class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition"
            >
                Save Settings
            </button>
        </div>

    </form>

</div>

@endsection
