<div>
    <form wire:submit.prevent="submit">
        <fieldset wire:loading.attr="disabled">
            <div>
                <label
                    for="comment"
                    class="sr-only"
                >
                    {{ __('Comment') }}
                </label>
                <x-tiptap-comment wire:model="comment.content">
                    @if(auth()->user() instanceof \App\Models\Agent)
                        {{-- Admin Toolbar --}}
                        <div class="flex items-center border-t border-slate-300 pt-3 pb-1 px-2 -mx-2 space-x-2 dark:border-slate-600">
                            <div
                                x-data="{ checked: @entangle('comment.is_private') }"
                                class="flex items-center"
                            >
                                <button
                                    x-on:click="checked = !checked"
                                    type="button"
                                    class="group relative inline-flex h-5 w-10 flex-shrink-0 cursor-pointer items-center justify-center rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-slate-800"
                                >
                                    <span
                                        aria-hidden="true"
                                        class="pointer-events-none absolute h-full w-full rounded-md"
                                    ></span>
                                    <span
                                        aria-hidden="true"
                                        class="pointer-events-none absolute mx-auto h-4 w-9 rounded-full transition-colors duration-200 ease-in-out"
                                        :class="{ 'bg-blue-600': checked, 'bg-slate-200': !checked }"
                                    ></span>
                                    <span
                                        aria-hidden="true"
                                        class="pointer-events-none absolute left-0 inline-block h-5 w-5 transform rounded-full border border-slate-200 bg-white shadow ring-0 transition-transform duration-200 ease-in-out"
                                        :class="{ 'translate-x-5': checked, 'translate-x-0': !checked }"
                                    ></span>
                                </button>
                                <span class="ml-3 text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Private') }}</span>
                            </div>
                            <span class="text-slate-300 dark:text-slate-600">|</span>
                            <x-dropdown
                                align="left"
                                is-drop-up="true"
                                trigger-classes="flex"
                                width="80"
                            >
                                <x-slot:trigger>
                                    <button
                                        type="button"
                                        class="inline-flex items-center text-slate-500 hover:text-slate-600 dark:text-slate-400 dark:hover:text-slate-300"
                                    >
                                        <x-heroicon-m-hashtag class="h-4 w-4" />
                                    </button>
                                </x-slot:trigger>
                                <x-slot:content>
                                    <div class="px-4 py-2 border-b border-slate-200 font-medium text-xs text-slate-700 dark:border-slate-600 dark:text-slate-200">
                                        {{ __('Insert a canned response into this comment') }}
                                    </div>
                                    <div class="relative max-h-64 overflow-y-auto">
                                        @forelse($this->user->cannedResponses as $cannedResponse)
                                            <x-dropdown-link
                                                x-on:click="insertContent({{ json_encode($cannedResponse->content) }})"
                                                role="button"
                                                class="w-full"
                                            >
                                                <p class="font-medium text-sm truncate dark:text-slate-200">
                                                    {{ $cannedResponse->title }}
                                                </p>
                                                <p class="text-sm text-slate-500 truncate dark:text-slate-400">
                                                    {{ $cannedResponse->content }}
                                                </p>
                                            </x-dropdown-link>
                                        @empty
                                            <div class="px-4 py-2 text-sm text-slate-500 dark:text-slate-400">
                                                {{ __('No canned responses found!') }}
                                                <a
                                                    href="{{ route('agent.canned-responses') }}"
                                                    class="text-blue-600 hover:text-blue-500"
                                                >
                                                    {{ __('Create one') }}
                                                </a>
                                            </div>
                                        @endforelse
                                    </div>
                                </x-slot:content>
                            </x-dropdown>
                        </div>
                    @endif
                </x-tiptap-comment>
                <x-input-error
                    for="comment.content"
                    class="mt-2"
                />
            </div>
            @if($attachments)
                <ul class="mt-3 relative overflow-hidden bg-white border border-slate-200 rounded-md divide-y divide-slate-200 dark:border-slate-600 dark:divide-slate-600">
                    @foreach($attachments as $attachment)
                        <li class="pl-3 pr-4 py-3 bg-white flex items-center justify-between text-sm dark:bg-slate-700">
                            <div class="w-0 flex-1 flex items-center text-slate-900 dark:text-slate-200">
                                <x-heroicon-s-paper-clip class="flex-shrink-0 h-5 w-5 text-slate-400" />
                                <span class="ml-2 flex-1 w-0 truncate">
                                    {{ $attachment->getClientOriginalName() }}
                                </span>
                            </div>
                            <div class="ml-4 flex-shrink-0">
                                <button
                                    wire:click="removeAttachment({{ $loop->index }})"
                                    type="button"
                                    class="font-medium text-red-600 hover:text-red-500 dark:text-red-400 dark:hover:text-red-300"
                                >
                                    {{ __('Remove') }}
                                </button>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
            @error('attachments.*') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
            <div class="mt-3 flex items-center justify-between">
                <div x-data>
                    <input
                        x-ref="fileInput"
                        wire:model="attachments"
                        type="file"
                        class="sr-only"
                        multiple
                    >
                    <a
                        @click="$refs.fileInput.click()"
                        role="button"
                        class="inline-flex items-center text-sm text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300"
                    >
                        <x-heroicon-o-paper-clip class="mr-1 w-4 h-4" />
                        {{ __('Add attachment') }}
                    </a>
                </div>
                <x-button.primary>
                    {{ __('Post comment') }}
                </x-button.primary>
            </div>
        </fieldset>
    </form>
</div>
