<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Verite Apps') }}</title>

        <!-- General CSS Files -->
        <link rel="stylesheet" href="assets/modules/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/modules/fontawesome/css/all.min.css">

        <!-- CSS Libraries -->
        <link rel="stylesheet" href="assets/modules/bootstrap-social/bootstrap-social.css">

        <!-- Template CSS -->

        <link rel="stylesheet" href="assets/css/style.css">
        <link rel="stylesheet" href="assets/css/components.css">
        <link rel="icon" href="assets/img/admin.png">

        <!-- Démarrer GA -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-94034622-3"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }

            gtag('js', new Date());

            gtag('config', 'UA-94034622-3');
        </script>
        <!-- /FIN GA -->

        <style>
            body {
                /* Votre image de fond */
                background-image: url('bg.jpg');
                background-position: center;
                background-repeat: no-repeat;
                background-attachment: fixed;
                background-size: 40%;
                /* Autres styles pour le corps de la page */
                margin: 0; /* Pour éviter les marges par défaut */
            }

            /* Créer un pseudo-élément pour superposer une couleur */
            body::after {
                content: "";
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5); /* Couleur avec opacité */
                z-index: -1; /* Assurez-vous que la couleur est sous l'image de fond */
            }
        </style>
    </head>

    <body>
        <div id="app">
            {{ $slot }}
        </div>

        <!-- General JS Scripts -->
        <script src="assets/modules/jquery.min.js"></script>
        <script src="assets/modules/popper.js"></script>
        <script src="assets/modules/tooltip.js"></script>
        <script src="assets/modules/bootstrap/js/bootstrap.min.js"></script>
        <script src="assets/modules/nicescroll/jquery.nicescroll.min.js"></script>
        <script src="assets/modules/moment.min.js"></script>
        <script src="assets/js/stisla.js"></script>
        <script src="assets/js/scripts.js"></script>
        <script src="assets/js/custom.js"></script>
    </body>
</html>
