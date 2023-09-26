@props(['actions', 'document', 'type'])

<tr class="filament-tables-row transition hover:bg-gray-50 dark:hover:bg-gray-500/10 cursor-pointer"
    style="height: 62px;">
    <td class="filament-tables-cell dark:text-white filament-table-cell" style="max-width: 500px">
        <div class="filament-tables-column-wrapper">
            <div class="filament-tables-text-column px-4 ">
                <div class="flex items-center space-x-2 rtl:space-x-reverse">
                    <div style="width: 70%; display: flex; justify-content: flex-start">
                        @if ($type == 'folder')
                            <img src="{{ asset('images/icons/folder.svg') }}" alt=""
                                style="width: 25px; margin-right: 7px;" />
                        @else
                            <img src="{{ asset("images/icons/{$this->getAssetIconImage($document)}") }}" alt=""
                                style="width: 25px; margin-right: 7px;" />
                        @endif
                        <span>{{ $document->name }}</span>
                    </div>
                </div>
            </div>
        </div>
    </td>
    <td class="filament-tables-cell dark:text-white filament-table-cell">
        <div class="filament-tables-column-wrapper">
            <div class="filament-tables-text-column px-4 ">
                <div class="inline-flex items-center space-x-1 rtl:space-x-reverse">
                    <span class="text-sm">
                        @if ($type == 'folder')
                            {{ $this->getFolderSize($document->id) }}
                        @else
                            {{ $this->convertedAssetSize($asset->size ?: 0) }}
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </td>
    <td class="filament-tables-cell dark:text-white filament-table-cell">
        <div class="filament-tables-column-wrapper">
            <div class="filament-tables-text-column px-4 ">
                <div class="inline-flex items-center space-x-1 rtl:space-x-reverse">
                    <span class="text-sm">
                        @if ($type == 'folder')
                            Folder
                        @else
                            {{ $document->file_type }}
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </td>
    <td class="filament-tables-cell dark:text-white filament-table-cell">
        <div class="filament-tables-column-wrapper">
            <div class="filament-tables-text-column px-4 ">
                <div class="inline-flex items-center space-x-1 rtl:space-x-reverse">
                    <span class="text-sm">
                        {{ $this->convertedDate($document->updated_at) }}
                    </span>
                </div>
            </div>
        </div>
    </td>
    <td class="filament-tables-cell dark:text-white filament-table-cell">
        <div class="filament-tables-column-wrapper">
            <div class="filament-tables-text-column px-4 ">
                <div class="inline-flex items-center space-x-1 rtl:space-x-reverse">
                    <span class="text-sm">
                        {{ $document->is_private ? 'Private' : 'Public' }}
                    </span>
                </div>
            </div>
        </div>
    </td>
    <td class="filament-tables-cell dark:text-white filament-table-cell">
        <div class="filament-tables-column-wrapper">
            <div class="filament-tables-text-column px-4 ">
                <div class="inline-flex items-center space-x-1 rtl:space-x-reverse">
                    <span class="">
                        {{-- ACTIONS --}}
                        <div class="filament-dropdown" x-data="{
                            toggle: function(event) {
                                $refs.panel.toggle(event)
                            },
                            open: function(event) {
                                $refs.panel.open(event)
                            },
                            close: function(event) {
                                $refs.panel.close(event)
                            },
                        }">
                            <div x-on:click="toggle" class="filament-dropdown-trigger cursor-pointer">
                                <button title="Actions" type="button"
                                    class="filament-icon-button flex items-center justify-center rounded-full relative outline-none hover:bg-gray-500/5 disabled:opacity-70 disabled:cursor-not-allowed disabled:pointer-events-none text-gray-500 focus:bg-gray-500/10 dark:hover:bg-gray-300/5 w-10 h-10">
                                    <span class="sr-only">
                                        Actions
                                    </span>

                                    <svg wire:loading.remove.delay="" wire:target=""
                                        class="filament-icon-button-icon w-5 h-5" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                        aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                                        </path>
                                    </svg>

                                </button>
                            </div>
                            <div x-ref="panel" x-float.placement.bottom-end.flip.teleport.offset="{ offset: 8 }"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:leave-end="opacity-0 scale-95"
                                class="filament-dropdown-panel absolute z-10 w-full divide-y divide-gray-100 overflow-y-auto rounded-lg bg-white shadow-lg ring-1 ring-black/5 transition dark:divide-gray-700 dark:bg-gray-800 dark:ring-white/10 max-w-[14rem]"
                                style="position: fixed; display: none;">
                                <div class="filament-dropdown-list p-1">
                                    @if ($type == 'folder')
                                        @foreach ($actions as $action)
                                            <button type="button" wire:loading.attr="disabled"
                                                wire:loading.class.delay="opacity-70 cursor-wait"
                                                wire:target="mountActionFolder('{{ $action['action'] }}', {{ $document['id'] }})"
                                                wire:click="mountActionFolder('{{ $action['action'] }}', {{ $document['id'] }})"
                                                class="filament-dropdown-list-item filament-dropdown-item group flex w-full items-center whitespace-nowrap rounded-md p-2 text-sm outline-none hover:text-white focus:text-white hover:bg-primary-500 focus:bg-primary-500 filament-grouped-action"
                                                dusk="filament.admin.action.open">

                                                <svg viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="animate-spin filament-dropdown-list-item-icon mr-2 h-5 w-5 rtl:ml-2 rtl:mr-0 group-hover:text-white group-focus:text-white text-primary-500"
                                                    wire:loading.delay="wire:loading.delay"
                                                    wire:target="mountActionFolder('{{ $action['action'] }}', {{ $document['id'] }})">
                                                    <path opacity="0.2" fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M12 19C15.866 19 19 15.866 19 12C19 8.13401 15.866 5 12 5C8.13401 5 5 8.13401 5 12C5 15.866 8.13401 19 12 19ZM12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z"
                                                        fill="currentColor"></path>
                                                    <path
                                                        d="M2 12C2 6.47715 6.47715 2 12 2V5C8.13401 5 5 8.13401 5 12H2Z"
                                                        fill="currentColor">
                                                    </path>
                                                </svg>

                                                <span
                                                    class="filament-dropdown-list-item-label truncate w-full text-start">
                                                    {{ $action['label'] }}
                                                </span>
                                            </button>
                                        @endforeach
                                    @else
                                        @foreach ($actions as $action)
                                            <button type="button" wire:loading.attr="disabled"
                                                wire:loading.class.delay="opacity-70 cursor-wait"
                                                wire:target="mountActionAsset('{{ $action['action'] }}', {{ $document['id'] }})"
                                                class="filament-dropdown-list-item filament-dropdown-item group flex w-full items-center whitespace-nowrap rounded-md p-2 text-sm outline-none hover:text-white focus:text-white hover:bg-primary-500 focus:bg-primary-500 filament-grouped-action"
                                                wire:click="mountActionAsset('{{ $action['action'] }}', {{ $document['id'] }})"
                                                dusk="filament.admin.action.open">

                                                <svg viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="animate-spin filament-dropdown-list-item-icon mr-2 h-5 w-5 rtl:ml-2 rtl:mr-0 group-hover:text-white group-focus:text-white text-primary-500"
                                                    wire:loading.delay="wire:loading.delay"
                                                    wire:target="mountActionAsset('{{ $action['action'] }}', {{ $document['id'] }})">
                                                    <path opacity="0.2" fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M12 19C15.866 19 19 15.866 19 12C19 8.13401 15.866 5 12 5C8.13401 5 5 8.13401 5 12C5 15.866 8.13401 19 12 19ZM12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z"
                                                        fill="currentColor"></path>
                                                    <path
                                                        d="M2 12C2 6.47715 6.47715 2 12 2V5C8.13401 5 5 8.13401 5 12H2Z"
                                                        fill="currentColor">
                                                    </path>
                                                </svg>

                                                <span
                                                    class="filament-dropdown-list-item-label truncate w-full text-start">
                                                    {{ $action['label'] }}
                                                </span>
                                            </button>
                                        @endforeach
                                    @endif

                                </div>
                            </div>
                        </div>
                    </span>
                </div>
            </div>
        </div>
    </td>
</tr>
