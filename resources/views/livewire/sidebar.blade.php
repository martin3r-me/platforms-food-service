<div>
    <div>
        <h4 x-show="!collapsed" class="p-3 text-sm italic text-secondary uppercase">General</h4>

        <a href="{{ route('foodservice.dashboard') }}"
           class="relative d-flex items-center p-2 my-1 rounded-md font-medium transition"
           :class="[
               window.location.pathname.endsWith('/food-service') || 
               window.location.pathname.endsWith('/food-service/')
                   ? 'bg-primary text-on-primary shadow-md'
                   : 'text-black hover:bg-primary-10 hover:text-primary hover:shadow-md',
               collapsed ? 'justify-center' : 'gap-3'
           ]"
           wire:navigate>
            <x-heroicon-o-chart-bar class="w-6 h-6 flex-shrink-0"/>
            <span x-show="!collapsed" class="truncate">Dashboard</span>
        </a>

        <a href="{{ route('foodservice.allergens.index') }}"
           class="relative d-flex items-center p-2 my-1 rounded-md font-medium transition"
           :class="[
               window.location.pathname.includes('/allergens')
                   ? 'bg-primary text-on-primary shadow-md'
                   : 'text-black hover:bg-primary-10 hover:text-primary hover:shadow-md',
               collapsed ? 'justify-center' : 'gap-3'
           ]"
           wire:navigate>
            <x-heroicon-o-exclamation-triangle class="w-6 h-6 flex-shrink-0"/>
            <span x-show="!collapsed" class="truncate">Allergens</span>
        </a>

        <a href="{{ route('foodservice.additives.index') }}"
           class="relative d-flex items-center p-2 my-1 rounded-md font-medium transition"
           :class="[
               window.location.pathname.includes('/additives')
                   ? 'bg-primary text-on-primary shadow-md'
                   : 'text-black hover:bg-primary-10 hover:text-primary hover:shadow-md',
               collapsed ? 'justify-center' : 'gap-3'
           ]"
           wire:navigate>
            <x-heroicon-o-beaker class="w-6 h-6 flex-shrink-0"/>
            <span x-show="!collapsed" class="truncate">Additives</span>
        </a>

        <a href="{{ route('foodservice.attributes.index') }}"
           class="relative d-flex items-center p-2 my-1 rounded-md font-medium transition"
           :class="[
               window.location.pathname.includes('/attributes')
                   ? 'bg-primary text-on-primary shadow-md'
                   : 'text-black hover:bg-primary-10 hover:text-primary hover:shadow-md',
               collapsed ? 'justify-center' : 'gap-3'
           ]"
           wire:navigate>
            <x-heroicon-o-adjustments-horizontal class="w-6 h-6 flex-shrink-0"/>
            <span x-show="!collapsed" class="truncate">Attributes</span>
        </a>

        <a href="{{ route('foodservice.vat-categories.index') }}"
           class="relative d-flex items-center p-2 my-1 rounded-md font-medium transition"
           :class="[
               window.location.pathname.includes('/vat-categories')
                   ? 'bg-primary text-on-primary shadow-md'
                   : 'text-black hover:bg-primary-10 hover:text-primary hover:shadow-md',
               collapsed ? 'justify-center' : 'gap-3'
           ]"
           wire:navigate>
            <x-heroicon-o-banknotes class="w-6 h-6 flex-shrink-0"/>
            <span x-show="!collapsed" class="truncate">VAT Categories</span>
        </a>
    </div>
</div>