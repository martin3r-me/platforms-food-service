<x-ui-page>
    <x-slot name="navbar">
        <x-ui-page-navbar
            :title="$title"
            :icon="$icon"
            :description="$description"
            :breadcrumbs="$breadcrumbs"
        >
            @isset($actions)
                <x-slot name="actions">
                    {{ $actions }}
                </x-slot>
            @endisset
        </x-ui-page-navbar>
    </x-slot>

    <x-slot name="sidebar">
        @component('foodservice::livewire.includes.page-sidebar', ['title' => $sidebarTitle])
            {{ $sidebar ?? '' }}
        @endcomponent
    </x-slot>

    <x-slot name="activity">
        @component('foodservice::livewire.includes.activity-sidebar', ['title' => $activityTitle])
            {{ $activity ?? '' }}
        @endcomponent
    </x-slot>

    <x-ui-page-container>
        {{ $slot }}
    </x-ui-page-container>
</x-ui-page>

