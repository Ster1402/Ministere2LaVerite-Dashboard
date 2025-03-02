<x-guest-layout>
    <div class="login-container">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="login-tab" data-bs-toggle="tab" href="#login-section"
                    role="tab">Connexion</a>
            </li>
            @if (Route::has('register'))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('register') }}" role="tab">Inscription</a>
                </li>
            @endif
        </ul>

        <div id="login-section" class="active">
            <form id="login-form" class="needs-validation" method="POST" action="{{ route('login') }}" novalidate>
                @csrf

                @if (session('status'))
                    <div class="alert alert-success mb-3">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger mb-3">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="form-group">
                    <label>Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" class="form-control" name="email" value="{{ old('email') }}" required
                            autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label>Mot de passe</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" name="password" required>
                        <button type="button" class="btn btn-outline-secondary toggle-password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                        <label class="form-check-label" for="remember_me">
                            Se souvenir de moi
                        </label>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-decoration-none">
                            Mot de passe oublié?
                        </a>
                    @endif
                    <button type="submit" class="btn btn-primary">
                        Se connecter
                    </button>
                </div>
            </form>
        </div>

    </div>
</x-guest-layout>
