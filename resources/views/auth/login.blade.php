<x-guest-layout>
    <x-authentication-card>
        <x-slot name="title">
            {{ __('Bienvenue 🖐️ !') }}
        </x-slot>

        <x-validation-errors class="mb-4"/>

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate="">
            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" value="{{ old('email') }}" class="form-control" name="email" tabindex="1"
                       required autofocus>
                <div class="invalid-feedback" id="required-mail">
                    Veuillez remplir votre adresse e-mail
                </div>
            </div>

            <div class="form-group">
                <div class="d-block">
                    <label for="password" class="control-label">
                        <div id="password_label">Mot de passe</div>
                    </label>
                </div>
                <input id="password" type="password" class="form-control" name="password" tabindex="2" required>
                <div class="invalid-feedback" id="required-password">
                    Veuillez remplir votre mot de passe
                </div>
            </div>

            <div class="form-group block mt-4">
                <label for="remember_me" class="flex items-center">
                    <x-checkbox id="remember_me" name="remember"/>
                    <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Se souvenir de moi') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                       href="{{ route('password.request') }}">
                        {{ __('Mot de passe oublié?') }}
                    </a>
                @endif

                <x-button type="submit" id="login" class="btn btn-primary btn-lg btn-block ms-4" tabindex="4">
                    {{ __('Connexion') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
