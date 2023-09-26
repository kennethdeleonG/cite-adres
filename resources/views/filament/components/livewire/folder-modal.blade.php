<div>
    <style>
        /* Style the scrollbar only for this element */
        .space-y-0.overflow-y-auto::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .space-y-0.overflow-y-auto::-webkit-scrollbar-thumb {
            background-color: #4a5568;
            border-radius: 3px;
        }

        .space-y-0.overflow-y-auto::-webkit-scrollbar-track {
            background-color: #2d3748;
            border-radius: 3px;
        }
    </style>

    {{-- delete modal --}}
    <x-filament::modal id="delete-folder-modal-handle" width="sm" class="p-4 space-y-2 text-center dark:text-white"
        displayClasses="block">

        {{-- tricks to disable autofocus --}}
        <input type="text" hidden autofocus />

        <x-slot name="header">
            <x-filament::modal.heading class="mb-2">
                Delete Folder
            </x-filament::modal.heading>
            <x-filament::modal.subheading>
                Are you sure you would like to do this?
            </x-filament::modal.subheading>
        </x-slot>

        <div class="text-right flex gap-2 justify-end">
            <x-filament::button type="button" class="w-full" color="secondary"
                x-on:click="$dispatch('close-modal', { id: 'delete-folder-modal-handle' })">
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

    <script>
        window.addEventListener('close-modal', event => {
            if (event.detail.id === "move-folder-modal-handle") {
                Livewire.emit('closeMoveModal');
            }
        })
    </script>
</div>
