<x-filament::page>

    <header
        class="filament-header space-y-2 items-start justify-between sm:flex sm:space-y-0 sm:space-x-4  sm:rtl:space-x-reverse sm:py-4">
        <x-filament::header.heading>
            {{ $this->headerTitle }}
        </x-filament::header.heading>
    </header>

    {{ $this->table }}
</x-filament::page>
