@props(['id' => null, 'maxWidth' => null])

<x-modal
    :id="$id"
    :maxWidth="$maxWidth"
    {{ $attributes }}
>
    <div class="px-6 py-4">
        <div class="text-lg text-slate-900 dark:text-slate-200">
            {{ $title }}
        </div>

        <div class="mt-4">
            {{ $content }}
        </div>
    </div>

    <div class="flex flex-row justify-end px-6 py-4 bg-slate-100 text-right dark:bg-slate-700">
        {{ $footer }}
    </div>
</x-modal>
