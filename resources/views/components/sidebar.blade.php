<div class="modern-sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <div class="ministry-logo">
                <i class="fas fa-church"></i>
            </div>
        </div>

    </div>

    <div class="user-profile">
        <div class="user-avatar">
            <img src="{{ Auth::user()?->profile_photo_url }}" alt="{{ Auth::user()?->name }}">
        </div>
        <div class="user-info">
            <h4>{{ Auth::user()?->name }}</h4>
            <p>{{ Auth::user()?->roles->pluck('displayName')->implode(', ') }}</p>
        </div>
    </div>

    <div class="sidebar-search">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
            </div>
            <input type="text" class="form-control" placeholder="Rechercher..." id="sidebar-search-input">
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section">
            <div class="nav-section-header">
                <span><i class="fas fa-home"></i>Menu Principal</span>
            </div>

            <ul class="nav-items">
                <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Tableau de bord</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('journal') ? 'active' : '' }}">
                    <a href="{{ route('journal') }}">
                        <i class="fas fa-book"></i>
                        <span>Journal</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Donations Section -->
        <div class="nav-section">
            <div class="nav-section-header">
                <span><i class="fas fa-hand-holding-heart"></i>Dons</span>
            </div>

            <ul class="nav-items">
                <li class="nav-item {{ request()->routeIs('donations.index') ? 'active' : '' }}">
                    <a href="{{ route('donations.index') }}">
                        <i class="fas fa-list"></i>
                        <span>Liste des dons</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('donations.form') ? 'active' : '' }} highlight-item">
                    <a href="{{ route('donations.form') }}">
                        <i class="fas fa-hand-holding-heart"></i>
                        <span>Faire un don</span>
                        <span class="item-badge">Soutenir</span>
                    </a>
                </li>

                @auth
                    <li class="nav-item {{ request()->routeIs('donations.my') ? 'active' : '' }}">
                        <a href="{{ route('donations.my') }}">
                            <i class="fas fa-user-circle"></i>
                            <span>Mes dons</span>
                        </a>
                    </li>
                @endauth
            </ul>
        </div>

        <!-- Admin Section -->
        @can('admin.viewAny')
            <div class="nav-section">
                <div class="nav-section-header">
                    <span><i class="fas fa-shield-alt"></i>Administration</span>
                </div>

                <ul class="nav-items">
                    <li class="nav-item {{ request()->routeIs('admins.*') ? 'active' : '' }}">
                        <a href="{{ route('admins.index') }}">
                            <i class="fas fa-user-shield"></i>
                            <span>Administrateurs</span>
                        </a>
                    </li>
                </ul>
            </div>
        @endcan

        <!-- Users Section -->
        @can('viewAny', App\Models\User::class)
            <div class="nav-section">
                <div class="nav-section-header">
                    <span><i class="fas fa-users"></i>Utilisateurs</span>
                </div>

                <ul class="nav-items">
                    <li class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <a href="{{ route('users.index') }}">
                            <i class="fas fa-user-friends"></i>
                            <span>Gestion utilisateurs</span>
                        </a>
                    </li>
                </ul>
            </div>
        @endcan

        <!-- Events Section -->
        @can('viewAny', App\Models\Event::class)
            <div class="nav-section">
                <div class="nav-section-header">
                    <span><i class="fas fa-calendar-alt"></i>Événements</span>
                </div>

                <ul class="nav-items">
                    <li class="nav-item {{ request()->routeIs('events.*') ? 'active' : '' }}">
                        <a href="{{ route('events.index') }}">
                            <i class="fas fa-calendar-check"></i>
                            <span>Gestion événements</span>
                        </a>
                    </li>
                </ul>
            </div>
        @endcan

        <!-- Messaging Section -->
        <div class="nav-section">
            <div class="nav-section-header">
                <span><i class="fas fa-envelope"></i>Messagerie</span>
            </div>

            <ul class="nav-items">
                <li class="nav-item {{ request()->routeIs('messages.*') ? 'active' : '' }}">
                    <a href="{{ route('messages.index') }}">
                        <i class="fas fa-inbox"></i>
                        <span>ChapelMail</span>
                        <span class="item-notification">3</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Media Section -->
        @can('viewAny', App\Models\Media::class)
            <div class="nav-section">
                <div class="nav-section-header">
                    <span><i class="fas fa-photo-video"></i>Médias</span>
                </div>

                <ul class="nav-items">
                    <li class="nav-item {{ request()->routeIs('medias.*') ? 'active' : '' }}">
                        <a href="{{ route('medias.index') }}">
                            <i class="fas fa-file-upload"></i>
                            <span>Publications</span>
                        </a>
                    </li>
                </ul>
            </div>
        @endcan

        <!-- Assemblies/Sectors Section -->
        @canany(['assembly.viewAny', 'sector.viewAny', 'subsector.viewAny'])
            <div class="nav-section">
                <div class="nav-section-header">
                    <span><i class="fas fa-building"></i>Organisation</span>
                </div>

                <ul class="nav-items">
                    @can('assembly.viewAny')
                        <li class="nav-item {{ request()->routeIs('assemblies.*') ? 'active' : '' }}">
                            <a href="{{ route('assemblies.index') }}">
                                <i class="fas fa-church"></i>
                                <span>Assemblées</span>
                            </a>
                        </li>
                    @endcan

                    @can('sector.viewAny')
                        <li class="nav-item {{ request()->routeIs('sectors.*') ? 'active' : '' }}">
                            <a href="{{ route('sectors.index') }}">
                                <i class="fas fa-sitemap"></i>
                                <span>Secteurs</span>
                            </a>
                        </li>
                    @endcan

                    @can('subsector.viewAny')
                        <li class="nav-item {{ request()->routeIs('subsectors.*') ? 'active' : '' }}">
                            <a href="{{ route('subsectors.index') }}">
                                <i class="fas fa-project-diagram"></i>
                                <span>Sous-Secteurs</span>
                            </a>
                        </li>
                    @endcan
                </ul>
            </div>
        @endcanany

        <!-- Resources Section -->
        @can('viewAny', App\Models\Resource::class)
            <div class="nav-section">
                <div class="nav-section-header">
                    <span><i class="fas fa-cogs"></i>Ressources</span>
                </div>

                <ul class="nav-items">
                    <li class="nav-item {{ request()->routeIs('resources.*') ? 'active' : '' }}">
                        <a href="{{ route('resources.index') }}">
                            <i class="fas fa-box-open"></i>
                            <span>Gestion ressources</span>
                        </a>
                    </li>

                    <li class="nav-item {{ request()->routeIs('groups.*') ? 'active' : '' }}">
                        <a href="{{ route('groups.index') }}">
                            <i class="fas fa-layer-group"></i>
                            <span>Gestion groupes</span>
                        </a>
                    </li>
                </ul>
            </div>
        @endcan
    </nav>

    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">
                <i class="fas fa-sign-out-alt"></i>
                <span>Déconnexion</span>
            </button>
        </form>
    </div>
</div>

<style>
    /* Modern Sidebar Styles */
    .modern-sidebar {
        display: flex;
        flex-direction: column;
        width: 280px;
        height: 100vh;
        background: linear-gradient(to bottom, #1c2c5b, #1e3a8a);
        color: #e2e8f0;
        position: fixed;
        left: 0;
        top: 0;
        z-index: 1000;
        box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .modern-sidebar.collapsed {
        width: 70px;
    }

    /* Sidebar Header */
    .sidebar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem 1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .sidebar-logo {
        display: flex;
        align-items: center;
    }

    .logo-large {
        height: 40px;
        transition: opacity 0.3s ease;
    }

    .logo-small {
        height: 30px;
        display: none;
        transition: opacity 0.3s ease;
    }

    .modern-sidebar.collapsed .logo-large {
        display: none;
    }

    .modern-sidebar.collapsed .logo-small {
        display: block;
    }

    .sidebar-toggle {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    #sidebar-toggle-btn {
        background: transparent;
        border: none;
        color: #e2e8f0;
        font-size: 1.25rem;
        cursor: pointer;
        transition: color 0.2s ease;
    }

    #sidebar-toggle-btn:hover {
        color: #ffffff;
    }

    /* User Profile Section */
    .user-profile {
        display: flex;
        align-items: center;
        padding: 1.25rem 1rem;
        background: rgba(0, 0, 0, 0.1);
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }

    .user-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        overflow: hidden;
        margin-right: 1rem;
        border: 2px solid rgba(255, 255, 255, 0.2);
        flex-shrink: 0;
    }

    .user-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .user-info {
        overflow: hidden;
        transition: opacity 0.3s ease;
    }

    .user-info h4 {
        margin: 0;
        font-size: 1rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        color: #ffffff;
    }

    .user-info p {
        margin: 0;
        font-size: 0.85rem;
        opacity: 0.7;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .modern-sidebar.collapsed .user-info {
        opacity: 0;
        width: 0;
    }

    /* Search Box */
    .sidebar-search {
        padding: 0 1rem 1.25rem;
        transition: opacity 0.3s ease;
    }

    .modern-sidebar.collapsed .sidebar-search {
        opacity: 0;
        height: 0;
        padding: 0;
        overflow: hidden;
    }

    .sidebar-search .input-group {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 5px;
        overflow: hidden;
    }

    .sidebar-search .input-group-prepend {
        display: flex;
    }

    .sidebar-search .input-group-text {
        background: transparent;
        border: none;
        color: rgba(255, 255, 255, 0.7);
        padding: 0.5rem 0.75rem;
    }

    .sidebar-search .form-control {
        background: transparent;
        border: none;
        color: #ffffff;
        padding: 0.5rem 0.75rem;
    }

    .sidebar-search .form-control::placeholder {
        color: rgba(255, 255, 255, 0.5);
    }

    .sidebar-search .form-control:focus {
        box-shadow: none;
        outline: none;
    }

    /* Navigation */
    .sidebar-nav {
        flex: 1;
        overflow-y: auto;
        padding-bottom: 1rem;
    }

    .sidebar-nav::-webkit-scrollbar {
        width: 5px;
    }

    .sidebar-nav::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.1);
    }

    .sidebar-nav::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 3px;
    }

    .nav-section {
        margin-bottom: 1rem;
    }

    .nav-section-header {
        padding: 0.75rem 1.5rem 0.5rem;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: rgba(255, 255, 255, 0.4);
        transition: padding 0.3s ease;
    }

    .nav-section-header span {
        display: flex;
        align-items: center;
    }

    .nav-section-header span i {
        margin-right: 0.5rem;
        font-size: 0.9rem;
    }

    .modern-sidebar.collapsed .nav-section-header {
        padding: 0.75rem 0 0.5rem;
        display: flex;
        justify-content: center;
    }

    .modern-sidebar.collapsed .nav-section-header span {
        display: none;
    }

    .nav-items {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .nav-item {
        position: relative;
        transition: background-color 0.2s ease;
    }

    .nav-item:hover {
        background-color: rgba(255, 255, 255, 0.05);
    }

    .nav-item.active {
        background-color: rgba(59, 130, 246, 0.3);
    }

    .nav-item.active::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 3px;
        background: #3b82f6;
    }

    .nav-item a {
        display: flex;
        align-items: center;
        padding: 0.85rem 1.5rem;
        color: rgba(255, 255, 255, 0.7);
        text-decoration: none;
        transition: all 0.2s ease;
        position: relative;
    }

    .nav-item a:hover {
        color: #ffffff;
    }

    .nav-item.active a {
        color: #ffffff;
    }

    .nav-item a i {
        font-size: 1.1rem;
        min-width: 1.5rem;
        margin-right: 0.75rem;
        text-align: center;
        transition: margin 0.3s ease;
    }

    .nav-item a span {
        white-space: nowrap;
        transition: opacity 0.3s ease;
    }

    .modern-sidebar.collapsed .nav-item a {
        padding: 0.85rem 0;
        justify-content: center;
    }

    .modern-sidebar.collapsed .nav-item a i {
        margin-right: 0;
        font-size: 1.25rem;
    }

    .modern-sidebar.collapsed .nav-item a span {
        opacity: 0;
        width: 0;
        overflow: hidden;
    }

    /* Highlight Item (Make a Donation) */
    .highlight-item {
        margin: 0.5rem 0.75rem;
        border-radius: 8px;
        background: linear-gradient(to right, #3b82f6, #2563eb);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .highlight-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        background: linear-gradient(to right, #2563eb, #1d4ed8);
    }

    .highlight-item.active::before {
        display: none;
    }

    .highlight-item a {
        padding: 0.85rem 1rem;
    }

    .item-badge {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(255, 255, 255, 0.2);
        font-size: 0.7rem;
        padding: 0.15rem 0.5rem;
        border-radius: 50px;
        transition: opacity 0.3s ease;
    }

    .modern-sidebar.collapsed .item-badge {
        opacity: 0;
    }

    .item-notification {
        display: flex;
        align-items: center;
        justify-content: center;
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        background: #ef4444;
        color: white;
        font-size: 0.75rem;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        transition: right 0.3s ease;
    }

    .modern-sidebar.collapsed .item-notification {
        right: 0.5rem;
    }

    /* Footer */
    .sidebar-footer {
        padding: 1rem;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    .btn-logout {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        padding: 0.75rem;
        background: rgba(255, 255, 255, 0.1);
        border: none;
        border-radius: 5px;
        color: #e2e8f0;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-logout:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    .btn-logout i {
        margin-right: 0.5rem;
        font-size: 1rem;
        transition: margin 0.3s ease;
    }

    .btn-logout span {
        transition: opacity 0.3s ease;
    }

    .modern-sidebar.collapsed .btn-logout {
        padding: 0.75rem 0;
    }

    .modern-sidebar.collapsed .btn-logout i {
        margin-right: 0;
    }

    .modern-sidebar.collapsed .btn-logout span {
        opacity: 0;
        width: 0;
        overflow: hidden;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .modern-sidebar {
            transform: translateX(-100%);
            width: 280px;
        }

        .modern-sidebar.mobile-visible {
            transform: translateX(0);
        }

        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            display: none;
        }

        .sidebar-overlay.active {
            display: block;
        }
    }

    /* For very small screens */
    @media (max-width: 576px) {
        .modern-sidebar {
            width: 260px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.querySelector('.modern-sidebar');
        const toggleBtn = document.getElementById('sidebar-toggle-btn');
        const body = document.body;

        // Create overlay element for mobile
        const overlay = document.createElement('div');
        overlay.className = 'sidebar-overlay';
        body.appendChild(overlay);

        // Toggle sidebar collapse state
        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');

            // For mobile
            if (window.innerWidth <= 992) {
                sidebar.classList.toggle('mobile-visible');
                overlay.classList.toggle('active');
            }
        });

        // Hide sidebar when clicking on overlay (mobile)
        overlay.addEventListener('click', function() {
            sidebar.classList.remove('mobile-visible');
            overlay.classList.remove('active');
        });

        // Implement search functionality
        const searchInput = document.getElementById('sidebar-search-input');
        const navItems = document.querySelectorAll('.nav-item');

        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();

            navItems.forEach(item => {
                const text = item.textContent.toLowerCase();

                if (searchTerm === '' || text.includes(searchTerm)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });

        // Auto-collapse on small screens
        function handleResize() {
            if (window.innerWidth <= 992) {
                sidebar.classList.add('collapsed');
                sidebar.classList.remove('mobile-visible');
                overlay.classList.remove('active');
            }
        }

        window.addEventListener('resize', handleResize);
        handleResize(); // Initial check
    });
</script>
