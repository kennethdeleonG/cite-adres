<div>
    <div class="m-auto">
        <x-filament::header.heading>
            {{ $this->getHeading() }}
        </x-filament::header.heading>
    </div>

    <div class="items-end justify-between md:flex sm:space-x-4 ">
        <div></div>
        <x-filament::pages.actions :actions="$this->getCachedActions()" class="shrink-0" />
    </div>

    <div class="">
        @if (count($this->getBreadcrumbsMenu()) > 1)
            <x-filament::layouts.app.topbar.breadcrumbs :breadcrumbs="$this->getBreadcrumbsMenu()" />
        @endif
    </div>

</div>
