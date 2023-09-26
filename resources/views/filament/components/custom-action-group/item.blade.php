@props(['actions', 'color' => null, 'darkMode' => config('filament.dark_mode'), 'icon' => 'heroicon-o-dots-vertical', 'label' => __('filament-support::actions/group.trigger.label'), 'size' => null, 'tooltip' => null])

<x-filament-support::dropdown :dark-mode="$darkMode" placement="bottom-end" teleport {{ $attributes }}>
    <x-slot name="trigger">
        <x-filament-support::button :color="$color" :dark-mode="$darkMode" :size="$size" :tooltip="$tooltip">
            {{ $label }}
        </x-filament-support::button>
    </x-slot>

    <x-filament-support::dropdown.list>
        @foreach ($actions as $action)
            @if (!$action->isHidden())
                {{ $action }}
            @endif
        @endforeach
    </x-filament-support::dropdown.list>
</x-filament-support::dropdown>
