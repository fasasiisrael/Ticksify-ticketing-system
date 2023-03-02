<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:flex md:items-center md:justify-between lg:px-8">
        <h1 class="font-display text-3xl text-slate-900 dark:text-slate-200">
            {{ __('Products') }}
        </h1>
        <div class="mt-4 sm:mt-0">
            @if($this->envatoSettings->token_enabled)
                <div class="inline-flex rounded-md shadow-sm">
                    <button
                        wire:click="createProduct"
                        type="button"
                        class="relative inline-flex items-center rounded-l-md border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 focus:z-10 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:bg-slate-700 dark:border-slate-500 dark:text-slate-200 dark:focus:ring-blue-400 dark:focus:border-blue-400 dark:hover:border-slate-400 dark:focus:ring-offset-slate-800"
                    >
                        <x-heroicon-m-plus class="-ml-1 mr-2 w-4 h-4" />
                        {{ __('New product') }}
                    </button>
                    <div class="relative -ml-px block">
                        <x-dropdown>
                            <x-slot:trigger>
                                <button
                                    type="button"
                                    class="relative inline-flex items-center rounded-r-md border border-slate-300 bg-white px-2 py-2 text-sm font-medium text-slate-500 hover:bg-slate-50 focus:z-10 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:bg-slate-700 dark:border-slate-500 dark:text-slate-200 dark:focus:ring-blue-400 dark:focus:border-blue-400 dark:hover:border-slate-400 dark:focus:ring-offset-slate-800"
                                >
                                    <span class="sr-only">{{ __('Open options') }}</span>
                                    <x-heroicon-m-chevron-down class="h-5 w-5" />
                                </button>
                            </x-slot:trigger>
                            <x-slot:content>
                                <x-dropdown-link
                                    wire:click="$set('showEnvatoModal', true)"
                                    role="button"
                                >
                                    {{ __('Import from Envato') }}
                                </x-dropdown-link>
                            </x-slot:content>
                        </x-dropdown>
                    </div>
                </div>
            @else
                <x-button.primary
                    wire:click="createProduct"
                    type="button"
                >
                    <x-heroicon-m-plus class="-ml-1 mr-2 w-4 h-4" />
                    {{ __('New product') }}
                </x-button.primary>
            @endif
        </div>
    </div>

    <div
        x-data="{ showProductForm: @entangle('showProductForm') }"
        class="mt-4 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"
    >
        <div class="space-y-4">
            <x-card
                x-cloak
                x-show="showProductForm"
                x-trap="showProductForm"
                class="bg-slate-50 rounded-lg dark:bg-slate-700/50"
            >
                <x-slot:content>
                    @if ($errors->any())
                        <div class="mb-4 rounded-md bg-red-50 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <x-heroicon-s-x-circle class="w-5 h-5 text-red-400" />
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">
                                        {{ trans_choice('There were :count error with your submission|There were :count errors with your submission', $errors->count()) }}
                                    </h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <ul
                                            role="list"
                                            class="list-disc pl-5 space-y-1"
                                        >
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form wire:submit.prevent="saveProduct">
                        <fieldset
                            wire:loading.attr="disabled"
                            class="grid grid-cols-1 gap-6 sm:grid-cols-12 items-end"
                        >
                            <div class="sm:col-span-12 xl:col-span-4">
                                <x-label
                                    for="product.name"
                                    :value="__('Product name')"
                                />
                                <x-input
                                    wire:model.defer="product.name"
                                    type="text"
                                    class="mt-1"
                                    :placeholder="__('Product name')"
                                />
                            </div>
                            <div class="sm:col-span-6 xl:col-span-3">
                                <x-label
                                    for="product.provider"
                                    :value="__('Provider')"
                                />
                                <x-select
                                    wire:model.defer="product.provider"
                                    class="mt-1"
                                >
                                    @foreach(\App\Enums\ProductProvider::cases() as $provider)
                                        <option value="{{ $provider->name }}">{{ $provider->label() }}</option>
                                    @endforeach
                                </x-select>
                            </div>
                            <div class="sm:col-span-6 xl:col-span-3">
                                <x-label
                                    for="product.code"
                                    :value="__('Identifier')"
                                />
                                <x-input
                                    wire:model.defer="product.code"
                                    type="text"
                                    class="mt-1"
                                    :placeholder="__('Unique identifier')"
                                />
                            </div>
                            <div class="sm:col-span-12 xl:col-span-2">
                                <div class="flex items-center justify-end space-x-2">
                                    <x-button.text x-on:click="showProductForm = false">
                                        {{ __('Cancel') }}
                                    </x-button.text>
                                    <x-button.primary>
                                        {{ __('Save') }}
                                    </x-button.primary>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </x-slot:content>
            </x-card>

            <x-card class="relative overflow-hidden rounded-lg">
                <x-slot:header>
                    <div>
                        <x-input
                            wire:model.debounce.500ms="search"
                            type="search"
                            placeholder="{{ __('Search...') }}"
                        />
                    </div>
                </x-slot:header>
                <x-slot:content>
                    <div class="-mx-4 -my-6 overflow-x-auto sm:-mx-6">
                        <div class="inline-block min-w-full align-middle">
                            <div class="relative overflow-hidden ring-1 ring-black ring-opacity-5">
                                <div
                                    wire:loading.delay
                                    class="absolute inset-0 z-10 bg-slate-100/50 dark:bg-slate-700/50"
                                >
                                    <div
                                        wire:loading.flex
                                        class="h-full w-full items-center justify-center"
                                    >
                                        <div class="m-auto flex items-center space-x-2">
                                            <x-loading-spinner class="w-5 h-5 dark:text-slate-200" />
                                            <p class="text-sm dark:text-slate-200">{{ 'Loading products...' }}</p>
                                        </div>
                                    </div>
                                </div>
                                <table class="min-w-full divide-y divide-slate-300 dark:divide-slate-600">
                                    <thead class="bg-slate-50 dark:bg-slate-700">
                                        <tr>
                                            <th
                                                scope="col"
                                                class="pl-4 pr-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-slate-500 whitespace-nowrap sm:pl-6 dark:text-slate-200"
                                            >
                                                {{ __('Name') }}
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-3 py-3 text-center text-xs font-medium uppercase tracking-wide text-slate-500 whitespace-nowrap dark:text-slate-200"
                                            >
                                                {{ __('Tickets') }}
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-3 py-3 text-center text-xs font-medium uppercase tracking-wide text-slate-500 whitespace-nowrap dark:text-slate-200"
                                            >
                                                {{ __('Support') }}
                                            </th>
                                            <th
                                                scope="col"
                                                class="relative pl-3 pr-4 py-3 text-right text-xs font-medium uppercase tracking-wide text-slate-500 whitespace-nowrap sm:pr-6 dark:text-slate-200"
                                            >
                                                <span class="sr-only">
                                                    {{ __('Actions') }}
                                                </span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200 dark:divide-slate-600">
                                        @forelse($this->products as $product)
                                            <tr
                                                wire:target="search"
                                                wire:loading.class.delay="opacity-50"
                                                class="hover:bg-slate-50 dark:hover:bg-slate-700/25"
                                            >
                                                <td class="whitespace-nowrap pl-4 pr-3 py-4 font-medium text-sm text-slate-900 sm:pl-6 dark:text-slate-200">
                                                    <div class="flex items-center">
                                                        <div class="h-10 w-10 flex-shrink-0">
                                                            @if($product->hasMedia('logo'))
                                                                <img
                                                                    src="{{ $product->getFirstMediaUrl('logo', 'thumb') }}"
                                                                    alt="{{ $product->name }}"
                                                                    class="h-10 w-10 rounded-full"
                                                                >
                                                            @else
                                                                <x-heroicon-o-cube class="h-10 w-10 text-slate-400" />
                                                            @endif
                                                        </div>
                                                        <div class="ml-4">
                                                            <a
                                                                href="{{ route('agent.tickets.list', ['product' => $product->id]) }}"
                                                                class="hover:text-blue-500 hover:underline dark:hover:text-blue-400"
                                                            >
                                                                {{ $product->name }}
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="whitespace-nowrap px-3 py-4 text-center text-sm text-slate-500 dark:text-slate-400">
                                                    {{ $product->tickets_count }}
                                                </td>
                                                <td class="whitespace-nowrap px-3 py-4 text-center text-sm text-slate-500 dark:text-slate-400">
                                                    <button
                                                        wire:click="toggleSupport({{ $product->id }})"
                                                        type="button"
                                                        class="group relative inline-flex h-5 w-10 flex-shrink-0 cursor-pointer items-center justify-center rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-slate-700"
                                                    >
                                                        <span class="sr-only">{{ __('Toggle support status') }}</span>
                                                        <span
                                                            aria-hidden="true"
                                                            class="pointer-events-none absolute h-full w-full rounded-md"
                                                        ></span>
                                                        <span
                                                            aria-hidden="true"
                                                            @class(['pointer-events-none absolute mx-auto h-4 w-9 rounded-full transition-colors duration-200 ease-in-out', 'bg-slate-200' => $product->is_disabled, 'bg-blue-600' => ! $product->is_disabled])
                                                        ></span>
                                                        <span
                                                            aria-hidden="true"
                                                            @class(['pointer-events-none absolute left-0 inline-block h-5 w-5 transform rounded-full border border-slate-200 bg-white shadow ring-0 transition-transform duration-200 ease-in-out', 'translate-x-0' => $product->is_disabled, 'translate-x-5' => ! $product->is_disabled])
                                                        ></span>
                                                    </button>
                                                </td>
                                                <td class="whitespace-nowrap pl-3 pr-4 py-4 text-right text-sm text-slate-500 sm:pr-6 dark:text-slate-400">
                                                    <div
                                                        x-data="{ confirmProductDeletion: false }"
                                                        x-on:click.outside="confirmProductDeletion = false"
                                                        class="relative"
                                                    >
                                                        <div
                                                            x-show="!confirmProductDeletion"
                                                            class="space-x-1"
                                                        >
                                                            <button
                                                                wire:click="editProduct({{ $product->id }})"
                                                                type="button"
                                                                title="{{ __('Edit product') }}"
                                                                class="hover:text-blue-500"
                                                            >
                                                                <x-heroicon-m-pencil-square class="h-4 w-4" />
                                                                <span class="sr-only">{{ __('Edit') }}</span>
                                                            </button>
                                                            <button
                                                                x-on:click="confirmProductDeletion = true"
                                                                type="button"
                                                                title="{{ __('Delete product') }}"
                                                                class="hover:text-red-500"
                                                            >
                                                                <x-heroicon-m-trash class="h-4 w-4" />
                                                                <span class="sr-only">{{ __('Delete') }}</span>
                                                            </button>
                                                        </div>
                                                        <div
                                                            x-cloak
                                                            x-show="confirmProductDeletion"
                                                            class="space-x-1"
                                                        >
                                                            <button
                                                                x-on:click="confirmProductDeletion = false"
                                                                type="button"
                                                                title="{{ __('Cancel') }}"
                                                                class="text-blue-600 hover:text-blue-500"
                                                            >
                                                                <x-heroicon-m-x-circle class="h-4 w-4" />
                                                                <span class="sr-only">{{ __('Cancel') }}</span>
                                                            </button>
                                                            <button
                                                                wire:click="deleteProduct('{{ $product->id }}')"
                                                                x-on:click="confirmProductDeletion = false"
                                                                type="button"
                                                                title="{{ __('Confirm') }}"
                                                                class="text-red-600 hover:text-red-500"
                                                            >
                                                                <x-heroicon-m-check-circle class="h-4 w-4" />
                                                                <span class="sr-only">{{ __('Confirm') }}</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr wire:loading.class.delay="opacity-50">
                                                <td
                                                    colspan="3"
                                                    class="px-3 py-8 text-center text-slate-500 dark:text-slate-400"
                                                >
                                                    <div class="inline-flex items-center space-x-1">
                                                        <x-heroicon-o-inbox class="w-5 h-5 text-slate-400" />
                                                        <span>{{ __('No records found...') }}</span>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </x-slot:content>
                @if($this->products->hasPages())
                    <x-slot:footer>
                        {{ $this->products->links() }}
                    </x-slot:footer>
                @endif
            </x-card>
        </div>
    </div>

    <x-dialog-modal wire:model="showEnvatoModal">
        <x-slot:title>
            {{ __('Sync product from Envato') }}
        </x-slot:title>

        <x-slot:content>
            @if($errors->has('envato'))
                <div class="rounded-md bg-red-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <x-heroicon-m-x-circle class="h-5 w-5 text-red-400" />
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">
                                {{ __('There were error while processing your request') }}
                            </h3>
                            <div class="mt-2 text-sm text-red-700">
                                <span>{{ $errors->first('envato') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($this->envatoProducts)
                <ul
                    role="list"
                    class="divide-y divide-slate-200 dark:divide-slate-600"
                >
                    @foreach($this->envatoProducts as $envatoProducts)
                        <li class="px-4 py-4 sm:px-0">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <img
                                        src="{{ $envatoProducts['thumbnail'] }}"
                                        alt="{{ $envatoProducts['item'] }}"
                                        class="w-12 h-12 rounded-md"
                                    >
                                </div>
                                <div class="flex-1 min-w-0">
                                    <a
                                        href="{{ $envatoProducts['url'] }}"
                                        class="block text-sm font-medium text-slate-900 truncate hover:text-blue-500 hover:underline dark:text-slate-200 dark:hover:text-blue-400"
                                        target="_blank"
                                    >
                                        {{ $envatoProducts['item'] }}
                                    </a>
                                    <p class="text-sm text-slate-500 truncate dark:text-slate-400">
                                        {{ $envatoProducts['user'] }}
                                    </p>
                                </div>
                                <div>
                                    @if($this->products->contains('code', $envatoProducts['id']))
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-medium bg-slate-100 text-slate-800 dark:bg-slate-600 dark:text-slate-400">
                                            {{ __('Added') }}
                                        </span>
                                    @else
                                        <x-button.primary
                                            wire:click="addProduct('{{ $loop->index }}')"
                                            wire:target="addProduct('{{ $loop->index }}')"
                                            wire:loading.attr="disabled"
                                            size="sm"
                                        >
                                            <x-heroicon-m-plus class="-ml-0.5 mr-2 h-4 w-4" />
                                            {{ __('Add') }}
                                        </x-button.primary>
                                    @endif
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="text-center">
                    <x-heroicon-o-cube class="mx-auto h-12 w-12 text-slate-400" />
                    <h3 class="mt-2 text-sm font-medium text-slate-900 dark:text-slate-200">{{ __('No products') }}</h3>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('Get started by syncing from Envato.') }}</p>
                    <div class="mt-6">
                        <x-button.primary
                            wire:click="loadEnvatoProducts"
                            wire:loading.attr="disabled"
                        >
                            <x-heroicon-m-arrow-path
                                wire:target="loadEnvatoProducts"
                                wire:loading.class="animate-spin"
                                class="-ml-1 mr-2 h-5 w-5"
                            />
                            {{ __('Start syncing') }}
                        </x-button.primary>
                    </div>
                </div>
            @endif
        </x-slot:content>

        <x-slot:footer>
            <x-button.secondary wire:click="$set('showEnvatoModal', false)">
                {{ __('Close') }}
            </x-button.secondary>
        </x-slot:footer>
    </x-dialog-modal>
</div>
