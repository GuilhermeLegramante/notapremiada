<x-filament-panels::page.simple>
    <x-filament::form
        method="POST"
        action="{{ route('password.update') }}"
    >
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email }}">

        <x-filament::input.wrapper>
            <x-filament::input
                type="password"
                name="password"
                label="Nova senha"
                required
            />
        </x-filament::input.wrapper>

        <x-filament::input.wrapper>
            <x-filament::input
                type="password"
                name="password_confirmation"
                label="Confirmar senha"
                required
            />
        </x-filament::input.wrapper>

        <x-filament::button type="submit" class="w-full">
            Redefinir senha
        </x-filament::button>
    </x-filament::form>
</x-filament-panels::page.simple>
