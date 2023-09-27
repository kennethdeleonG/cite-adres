<div>
    {{-- delete modal --}}
    <x-filament::modal id="delete-asset-modal-handle" width="sm" class="p-4 space-y-2 text-center dark:text-white"
        displayClasses="block">

        {{-- tricks to disable autofocus --}}
        <input type="text" hidden autofocus />

        <x-slot name="header">
            <x-filament::modal.heading class="mb-2">
                Delete Asset
            </x-filament::modal.heading>
            <x-filament::modal.subheading>
                Are you sure you would like to do this?
            </x-filament::modal.subheading>
        </x-slot>

        <div class="text-right flex gap-2 justify-end">
            <x-filament::button type="button" class="w-full" color="secondary"
                x-on:click="$dispatch('close-modal', { id: 'delete-asset-modal-handle' })">
                Cancel
            </x-filament::button>
            <x-filament::button type="submit" class="w-full" wire:click="deleteAction" color="danger"
                wire:loading.attr="disabled">
                <span wire:target="deleteAction" wire:loading.remove>
                    Confirm
                </span>
                <span wire:target="deleteAction" wire:loading>
                    Confirming...
                </span>
            </x-filament::button>
        </div>
    </x-filament::modal>

</div>
