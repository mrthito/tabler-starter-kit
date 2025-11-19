<div class="flex flex-col w-full mx-auto text-sm">
    @if (!$mfaHelper->confirmed)
        <div class="relative flex flex-col items-start rounded-xl justify-start space-y-5">
            <flux:badge color="orange">Disabled</flux:badge>
            <p class="-translate-y-1 text-stone-500 dark:text-stone-400">When you enable 2FA, you’ll be prompted
                for a secure code during login, which can be retrieved from your phone's Google Authenticator
                app.</p>
            <flux:modal.trigger name="edit-profile">
                <div class="w-auto">
                    <flux:button variant="primary" class="w-full" wire:click="enable()">{{ __('Enable') }}
                    </flux:button>
                </div>
            </flux:modal.trigger>
        </div>

        <flux:modal name="edit-profile" class="md:w-full md:max-w-md">
            <div class="relative w-full items-center justify-center flex flex-col space-y-5">
                <div
                    class="p-0.5 w-auto rounded-full border border-stone-100 dark:border-stone-600 bg-white dark:bg-stone-800 shadow-sm">
                    <div
                        class="p-2.5 rounded-full border border-stone-200 dark:border-stone-600 overflow-hidden bg-stone-100 dark:bg-stone-200 relative">
                        {{-- Checkerboard lines --}}
                        <div
                            class="flex items-stretch absolute inset-0 w-full h-full divide-x [&>div]:flex-1 divide-stone-200 dark:divide-stone-300 justify-around opacity-50">
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                        </div>
                        <div
                            class="flex flex-col items-stretch absolute w-full h-full divide-y [&>div]:flex-1 inset-0 divide-stone-200 dark:divide-stone-300 justify-around opacity-50">
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                        </div>
                        <flux:icon.scan-line class="size-6 relative z-20 dark:text-black" />
                    </div>
                </div>
                <div class="space-y-2 flex flex-col items-center justify-center">
                    <h2 class="text-xl font-medium text-stone-900 dark:text-stone-100">
                        @if (!$mfaHelper->verify)
                            {{ __('Turn on 2-step Verification') }}@else{{ __('Verify Authentication Code') }}
                        @endif
                    </h2>
                    <p class="text-stone-600 dark:text-stone-400">
                        @if (!$mfaHelper->verify)
                            {{ __('Open your authenticator app and choose Scan QR code') }}@else{{ __('Enter the 6-digit code from your authenticator app') }}
                        @endif
                    </p>
                </div>
                @if (!$mfaHelper->verify)
                    <div class="relative max-w-md mx-auto overflow-hidden flex items-center p-8 pt-0">
                        <div
                            class="border border-stone-200 dark:border-stone-700 rounded-lg relative overflow-hidden w-64 aspect-square mx-auto">
                            <div wire:loading.flex wire:target="enable"
                                class="bg-white dark:bg-stone-700 animate-pulse flex items-center justify-center absolute inset-0 w-full h-auto aspect-square z-10">
                                <flux:icon.loader-circle class="size-6 animate-spin" />
                            </div>
                            @if ($mfaHelper->enabled)
                                <img wire:loading.remove wire:target="enable"
                                    src="data:image/svg+xml;base64,{{ $qr }}" style="width:400px; height:auto"
                                    style="relative z-10" />
                            @endif
                        </div>
                        @if ($mfaHelper->enabled)
                            <div
                                class="h-1/2 z-20 w-full border-t border-blue-500 absolute bottom-0 left-0 -translate-y-4">
                                <div
                                    class="absolute inset-0 w-full h-full bg-gradient-to-b from-blue-500 to-transparent opacity-20">
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="flex items-center space-x-5 w-full">
                        <button class="btn btn-primary w-full" wire:click="verify2fa()">
                            {{ __('Continue') }}</button>
                    </div>
                    <div class="flex items-center relative w-full justify-center">
                        <div class="w-full absolute inset-0 top-1/2 bg-stone-200 dark:bg-stone-600 h-px"></div>
                        <span
                            class="px-2 py-1 bg-white dark:bg-stone-800 relative">{{ __('or, enter the code manually') }}</span>
                    </div>
                    <div class="flex items-center justify-center w-full space-x-2" x-data="{
                        copied: false,
                        copyToClipboard() {
                            if (navigator && navigator.clipboard) {
                                navigator.clipboard.writeText('{{ $mfaHelper->secret }}');
                            } else {
                                console.warn('Clipboard API is only available in secure contexts (HTTPS) or localhost.');
                            }
                            this.copied = true;
                            setTimeout(() => {
                                this.copied = false;
                            }, 1500);
                        }
                    }">
                        <div class="w-full rounded-xl flex items-stretch border dark:border-stone-700 overflow-hidden">
                            <div wire:loading.flex wire:target="enable"
                                class="w-full h-full flex items-center justify-center bg-stone-100 dark:bg-stone-700 p-3">
                                <flux:icon.loader-circle class="size-4 animate-spin" />
                            </div>
                            @if ($mfaHelper->enabled)
                                <input wire:loading.remove wire:target="mfaHelper->enable" type="text" readonly
                                    value="{{ $mfaHelper->secret }}"
                                    class="form-control" />
                                <button wire:loading.remove wire:target="enable" x-on:click="copyToClipboard()"
                                    class="btn btn-outline">
                                    <flux:icon x-show="!copied" icon="copy" class="w-4"></flux:icon>
                                    <flux:icon x-show="copied" icon="check" class="w-4 text-green-500">
                                    </flux:icon>
                                </button>
                            @endif
                        </div>
                    </div>
                @else
                    {{-- <x-input-otp :digits="6" id="two_factor_auth_code" eventCallback="submitCode" /> --}}
                    <div class="flex items-center space-x-5 w-full">
                        <flux:button variant="outline" class="w-full" wire:click="$set('mfaHelper->verify', false)"
                            wire:target="mfaHelper->cancelTwoFactor">{{ __('Back') }}</flux:button>
                        <flux:button variant="primary" class="w-full"
                            wire:click="mfaHelper->submitCode(document.getElementById('two_factor_auth_code').value)"
                            wire:target="submitCode">{{ __('Confirm') }}</flux:button>
                    </div>
                    {{-- <flux:input type="text" wire:model="auth_code" :label="__('Code')" /> --}}
                    @error('mfaHelper->auth_code')
                        <p class="my-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                @endif
            </div>
        </flux:modal>
    @else
        <div class="flex flex-col space-y-5">
            <div class="relative">
                <flux:badge color="green">Enabled</flux:badge>
            </div>
            <p>With two factor authentication enabled, you’ll be prompted for a secure, random token during
                login, which you can retrieve from your Google Authenticator app.</p>

            <div>
                <flux:callout icon="lock-keyhole-open" color="gray" inline class="rounded-b-none">
                    <flux:callout.heading>2FA Recovery Codes</flux:callout.heading>
                    <flux:callout.text>
                        Recovery codes let you regain access if you lose your 2FA device. Store them in a secure
                        password manager.
                    </flux:callout.text>
                </flux:callout>
                <div
                    class="bg-stone-100 dark:bg-stone-800 rounded-b-xl border-t-0 border border-stone-200 dark:border-stone-700 text-sm">
                    <div x-on:click="showRecoveryCodes = !showRecoveryCodes"
                        class="h-10 group cursor-pointer flex items-center select-none justify-between px-5 text-xs"
                        x-cloak>
                        <div :class="{
                            'opacity-40 group-hover:opacity-60': !
                                showRecoveryCodes,
                            'opacity-60': showRecoveryCodes
                        }"
                            class="relative">
                            <span x-show="!showRecoveryCodes" class="flex items-center space-x-1">
                                <flux:icon.eye class="size-4" /> <span>View My Recovery Codes</span>
                            </span>
                            <span x-show="showRecoveryCodes" class="flex items-center space-x-1"
                                x-cloak><flux:icon.eye-off class="size-4" /> <span>Hide Recovery
                                    Codes</span></span>
                        </div>
                        <flux:button x-show="showRecoveryCodes" size="xs" variant="filled" class="text-stone-600"
                            wire:click="regenerateCodes"
                            x-on:click="$event.preventDefault(); $event.stopPropagation();">
                            {{ __('Regenerate Codes') }}</flux:button>
                    </div>
                    <div x-show="showRecoveryCodes" class="relative" x-collapse x-cloak>
                        <div
                            class="grid max-w-xl gap-1 px-4 py-4 font-mono text-sm bg-stone-200 dark:bg-stone-900 dark:text-stone-100">
                            @foreach (json_decode(decrypt(auth()->user()->two_factor_recovery_codes), true) as $code)
                                <div>{{ $code }}</div>
                            @endforeach
                        </div>
                        <p class="px-4 py-3 text-xs select-none text-stone-500 dark:text-stone-400">
                            You have
                            {{ count(json_decode(decrypt(auth()->user()->two_factor_recovery_codes))) }}
                            recovery codes left. Each can be used once to access your account and will be
                            removed after use. If you need more, click <span class="font-bold">Regenerate
                                Codes</span> above.</p>
                    </div>
                </div>
            </div>
            <div class="inline relative">
                <flux:button variant="danger" type="submit" class="w-auto" wire:click="disable">
                    {{ __('Disable 2FA') }}</flux:button>
            </div>
        </div>

    @endif
</div>
