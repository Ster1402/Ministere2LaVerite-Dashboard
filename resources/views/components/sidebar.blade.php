@php use App\Models\Assembly;use App\Models\Media;use App\Models\Resource;use App\Models\Sector;use App\Models\User; @endphp
<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="#">Menu</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('dashboard') }}">TF</a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Ouverture</li>
            <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}">
                    <i class="fas fa-fire"></i><span>Accueil</span>
                </a>
            </li>

            @can('admin.viewAny')
                <li class="menu-header">ROLES & ACCÈS</li>

                <li style="margin-right: 100%;" class="{{ request()->routeIs('admins.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admins.index') }}"><i class="fas fa-database"></i>Administrateurs
                    </a>
                </li>
            @endcan

            @can('viewAny', User::class)
                <li class="menu-header">GESTION UTILISATEURS</li>
                <li style="margin-right: 100%;" class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('users.index') }}"><i class="fas fa-users"></i>Utilisateurs
                    </a>
                </li>
            @endcan

            @can('viewAny', Event::class)
                <li class="menu-header">GESTION DES ÉVÈNEMENTS</li>
                <li style="margin-right: 100%;" class="{{ request()->routeIs('events.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('events.index') }}"><i class="fas fa-calendar-alt"></i>Évènements
                    </a>
                </li>
            @endcan

            <li class="menu-header">MESSAGERIE</li>
            <li class="{{ request()->routeIs('messages.*') ? 'active' : '' }}">
                <a href="{{ route('messages.index') }}" class="nav-link"><i class="fas fa-envelope"></i>
                    <span>ChapelMail</span></a>
            </li>
            @can('viewAny', Media::class)

                <li class="menu-header">MEDIAS</li>
                <li class="{{ request()->routeIs('medias.*') ? 'active' : '' }}">
                    <a href="{{ route('medias.index') }}" class="nav-link"><i class="fas fa-file-export"></i>
                        <span>Publications</span></a>
                </li>
            @endcan

            @canany('viewAny', Assembly::class)
                <li class="menu-header">GESTION ASSEMBLÉES/SECTEURS</li>
                <li class="dropdown {{ (request()->routeIs('assemblies.*') or request()->routeIs('sectors.*')) ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-industry"></i>
                        <span>Assemblées & Secteurs</span></a>
                    <ul class="dropdown-menu">
                        @can('viewAny', Assembly::class)
                            <li style="margin-right: 100%;"
                                class="{{ request()->routeIs('assemblies.*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('assemblies.index') }}">
                                    <i class="fas fa-share"></i>Assemblées
                                </a>
                            </li>
                        @endcan
                        @can('viewAny', Sector::class)
                            <li style="margin-right: 100%;"
                                class="{{ request()->routeIs('sectors.*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('sectors.index') }}">
                                    <i class="fas fa-share"></i>Secteurs
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcanany

            @can('viewAny', Resource::class)
                <li class="menu-header">GESTION DES RESSOURCES</li>
                <li class="dropdown {{ (request()->routeIs('resources.*') or request()->routeIs('groups.*')) ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-cogs"></i>
                        <span>Ressources & Groupes</span></a>
                    <ul class="dropdown-menu">
                        <li style="margin-right: 100%;" class="{{ request()->routeIs('resources.*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('resources.index') }}">
                                <i class="fas fa-share"></i>Ressources
                            </a>
                        </li>
                        <li style="margin-right: 100%;" class="{{ request()->routeIs('groups.*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('groups.index') }}">
                                <i class="fas fa-share"></i>Groupes
                            </a>
                        </li>
                    </ul>
                </li>
        </ul>
        @endcan

        <form method="POST" action="{{ route('logout') }}" class="mt-4 mb-4 p-3 hide-sidebar-mini">
            @csrf
            <x-button type="submit" class="btn btn-primary btn-lg btn-block btn-icon-split">
                <i class="fas fa-sign-out-alt"></i> Déconnexion
            </x-button>
        </form>
    </aside>
</div>
