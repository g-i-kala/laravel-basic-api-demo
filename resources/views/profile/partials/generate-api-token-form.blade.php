<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('API Token') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Remember to copy the generated token.') }}
        </p>
    </header>

    <form method="post" action="{{ route('apitoken.generate') }}">
        @csrf
        <x-primary-button>
            {{ __('Generate API token') }}
        </x-primary-button>
    </form>

    <x-modal name="confirm-token-generation" :show="session('showTokenModal') ?? false" focusable>

        <div
            x-data="{
                token: '{{ session('apiToken') }}',
                copied: false,
                timeout: null,
                copy() {
                    $clipboard(this.token)
                    this.copied = true
                    this.timeout = setTimeout(() => { this.copied = false }, 3000)
                }
            }"
            class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Your API token has been generated.') }}
            </h2>
            <p class="mt-1 text-sm text-gray-600">
                {{ __('Please copy it now, as it cannot be retrieved later. Keep it secure to ensure the safety of your account and data.') }}
            </p>
            <div class="flex justify-between mt-6">
                <p class="mt-1 text-sm text-gray-600">
                    {{ session('apiToken') }}
                </p>
                <x-secondary-button x-on:click="copy" x-text="copied ? `Copied!` : `Copy link`">
                    Copy
                </x-secondary-button>
            </div>
            <div class="mt-6 flex justify-end">
                <x-primary-button x-on:click="$dispatch('close')">
                    {{ __('Got it!') }}
                </x-primary-button>
            </div>
        </div>

    </x-modal>


    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-token-deletion')">{{ __('Delete API token') }}</x-danger-button>

    <x-modal name="confirm-token-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('apitoken.delete') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Are you sure you want to delete your token?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('Once your token id deleted, access to your account through API will be unavailable.') }}
            </p>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    {{ __('Delete Token') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
