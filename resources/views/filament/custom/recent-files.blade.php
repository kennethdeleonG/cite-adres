<x-filament::page class="filament-dashboard-page">
    <x-filament::widgets :widgets="$this->getWidgets()" :columns="$this->getColumns()" />

    <x-filament::modal.heading>
        Recent Files
    </x-filament::modal.heading>

    <div
        class="filament-tables-table-container overflow-x-auto relative dark:border-gray-700 border-t p-2 space-y-2 bg-white rounded-xl shadow dark:bg-gray-800">
        <table class="filament-tables-table w-full text-start divide-y table-auto dark:divide-gray-700">
            <thead>
                <tr class="">
                    <th class="filament-tables-header-cell p-0 filament-table-header-cell-subject-type">
                        <div
                            class="flex items-center gap-x-1 w-full px-4 py-2 whitespace-nowrap font-medium text-sm text-gray-600
                                dark:text-gray-300 cursor-default ">
                            <span>
                                Name
                            </span>
                        </div>
                    </th>
                    <th class="filament-tables-header-cell p-0 filament-table-header-cell-subject-type">
                        <div
                            class="flex items-center gap-x-1 w-full px-4 py-2 whitespace-nowrap font-medium text-sm text-gray-600
                                dark:text-gray-300 cursor-default ">
                            <span>
                                Size
                            </span>
                        </div>
                    </th>
                    <th class="filament-tables-header-cell p-0 filament-table-header-cell-subject-type">
                        <div
                            class="flex items-center gap-x-1 w-full px-4 py-2 whitespace-nowrap font-medium text-sm text-gray-600
                                dark:text-gray-300 cursor-default ">
                            <span>
                                Type
                            </span>
                        </div>
                    </th>
                    <th class="filament-tables-header-cell p-0 filament-table-header-cell-subject-type">
                        <div
                            class="flex items-center gap-x-1 w-full px-4 py-2 whitespace-nowrap font-medium text-sm text-gray-600
                                dark:text-gray-300 cursor-default ">
                            <span>
                                Date Modified
                            </span>
                        </div>
                    </th>
                    <th class="filament-tables-header-cell p-0 filament-table-header-cell-subject-type">
                        <div
                            class="flex items-center gap-x-1 w-full px-4 py-2 whitespace-nowrap font-medium text-sm text-gray-600
                                dark:text-gray-300 cursor-default ">
                            <span>
                                Status
                            </span>
                        </div>
                    </th>
                    <th class="w-5"></th>
                </tr>
            </thead>
            <tbody class="divide-y whitespace-nowrap dark:divide-gray-700">
                @foreach ($this->assetList as $asset)
                    @include('filament.components.document-table-body', [
                        'document' => $asset,
                        'type' => 'asset',
                        'actions' => $this->getAssetActions(),
                    ])
                @endforeach
            </tbody>
        </table>
    </div>
</x-filament::page>
