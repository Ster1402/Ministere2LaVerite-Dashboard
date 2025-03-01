<div class="navbar-bg"></div>
<nav class="navbar navbar-expand-lg main-navbar">
    <div class="form-inline mr-auto">
    </div>
    <ul class="navbar-nav navbar-right">
        <li class="dropdown dropdown-list-toggle"><a href="#" data-toggle="dropdown"
                                                     class="nav-link nav-link-lg message-toggle beep"><i
                    class="far fa-envelope"></i></a>
            <div class="dropdown-menu dropdown-list dropdown-menu-right">
                <div class="dropdown-header">Messages
                    <div class="float-right">
                        <a href="#">Tout Marquer Comme Lu</a>
                    </div>
                </div>
                <div class="dropdown-list-content dropdown-list-message">
                    @foreach ($messages as $message)
                        <x-message-card :message="$message"/>
                    @endforeach
                </div>
                <div class="dropdown-footer text-center">
                    <a href="{{ route('messages.index') }}">Voir Tout <i class="fas fa-chevron-right"></i></a>
                </div>
            </div>
        </li>

        <li class="dropdown"><a href="#" data-toggle="dropdown"
                                class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                <img alt="image" src="{{ Auth::user()->profile_photo_url }}" class="rounded-circle mr-1">
                <div class="d-sm-none d-lg-inline-block">{{ Auth::user()->name }}</div>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <style>
                    .online-dot {
                        display: inline-block;
                        width: 8px;
                        height: 8px;
                        background-color: #4CAF50;
                        /* Vert */
                        border-radius: 50%;
                        margin-left: 5px;
                        /* Ajustez la marge selon vos besoins */
                    }
                </style>
                <a href="{{ route('profile') }}" class="dropdown-item has-icon">
                    <i class="far fa-user"></i> Profil
                </a>
                <div class="dropdown-divider"></div>
                <form method="POST" action="{{ route('logout') }}"
                      class="dropdown-item has-icon text-danger hide-sidebar-mini">
                    @csrf
                    <button type="submit"
                            class="w-full mx-0 px-0 text-danger btn bg-transparent btn-outline- btn-lg btn-block btn-icon-split">
                        <i class="fas fa-sign-out-alt w-icon"></i> Déconnexion
                    </button>
                </form>
            </div>
        </li>
    </ul>
</nav>
