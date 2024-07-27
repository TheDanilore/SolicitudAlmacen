<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        
        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- rol -->
        <div>
            <x-input-label for="rol" :value="__('Rol')" />
            <x-text-input id="rol" class="block mt-1 w-full" type="text" name="rol" :value="old('rol')" required autofocus autocomplete="rol" />
            <x-input-error :messages="$errors->get('rol')" class="mt-2" />
        </div>

        <!-- estado -->
        <div>
            <x-input-label for="estado_usuario" :value="__('Estado Usuario')" />
            <x-text-input id="estado_usuario" class="block mt-1 w-full" type="text" name="estado_usuario" :value="old('estado_usuario')" required autofocus autocomplete="estado_usuario" />
            <x-input-error :messages="$errors->get('estado_usuario')" class="mt-2" />
        </div>

        <!-- persona -->
        <div>
            <x-input-label for="persona" :value="__('Persona')" />
            <x-text-input id="persona" class="block mt-1 w-full" type="text" name="persona" :value="old('persona')" required autofocus autocomplete="persona" />
            <x-input-error :messages="$errors->get('persona')" class="mt-2" />
        </div>




        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
