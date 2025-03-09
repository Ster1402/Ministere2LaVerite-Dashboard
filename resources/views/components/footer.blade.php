<footer class="main-footer enhanced-footer">
    <div class="footer-container">
        <div class="footer-content">
            <div class="footer-branding">
                <div class="ministry-logo">
                    <i class="fas fa-church"></i>
                </div>
                <div class="ministry-info">
                    <h3 class="ministry-name">Ministère de La Vérité</h3>
                    <p class="ministry-tagline">Ensemble pour une communauté plus forte</p>
                </div>
            </div>

            <div class="footer-action">
                <p class="action-text">Votre générosité transforme des vies</p>
                <a href="{{ route('donations.form') }}" class="btn-donate">
                    <i class="fas fa-heart"></i> Faire un don
                </a>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="copyright">
                &copy; {{ date('Y') }} Ministère de La Vérité. Tous droits réservés.
            </div>
        </div>
    </div>
</footer>
