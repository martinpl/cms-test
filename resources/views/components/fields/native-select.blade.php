<x-native-select :$attributes>
    <x-native-select.option value="">Select option</x-native-select.option>
    @foreach ($getOptions() as $value => $title)
        <x-native-select.option value="{{ $value }}">{{ $title }}</x-native-select.option>
    @endforeach
</x-native-select>
