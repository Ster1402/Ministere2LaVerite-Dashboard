<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Ministère2LaVerité') }}</title>

    <!-- CSS Libraries -->
    <link rel="stylesheet" href="/assets/modules/codemirror/lib/codemirror.css">
    <link rel="stylesheet" href="/assets/modules/codemirror/theme/duotone-dark.css">

    <link rel="stylesheet" href="/assets/modules/bootstrap-daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="/assets/modules/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css">
    <link rel="stylesheet" href="/assets/modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.css">

    <!-- General CSS Files -->
    <link rel="stylesheet" href="/assets/modules/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/modules/fontawesome/css/all.min.css">

    <link rel="stylesheet" href="/assets/modules/select2/dist/css/select2.min.css">
    <link rel="stylesheet" href="/assets/modules/jquery-selectric/selectric.css">

    <!-- CSS Libraries -->
    <link rel="stylesheet" href="/assets/modules/jqvmap/dist/jqvmap.min.css">
    <link rel="stylesheet" href="/assets/modules/summernote/summernote-bs4.css">
    <link rel="stylesheet" href="/assets/modules/owlcarousel2/dist/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="/assets/modules/owlcarousel2/dist/assets/owl.theme.default.min.css">

    <!-- Template CSS -->
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/components.css">
    <link rel="stylesheet" href="/assets/css/custom.css">
    <link rel="icon" href="/assets/img/admin.png">
    <!-- Start GA -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-94034622-3"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }
        gtag('js', new Date());

        gtag('config', 'UA-94034622-3');
    </script>
    <!-- /END GA -->
</head>

<body class="antialiased">

<div id="app">

    <!-- Page Content -->
    <div class="main-wrapper main-wrapper-1">
        <x-nav-bar/>
        <x-sidebar/>

        <main class="main-content">
            <section class="section">
                @if(isset($header))
                    {{ $header }}
                @endif

                <div class="section-body">
                    @if(isset($subtitle))
                        <h2 class="section-title">{{ $subtitle }}</h2>
                    @endif
                    <x-validation-errors />

                    {{ $slot }}
                </div>
            </section>
        </main>

        <x-footer/>
    </div>

</div>

<!-- General JS Scripts -->
<script src="/assets/modules/jquery.min.js"></script>
<script src="/assets/modules/popper.js"></script>
<script src="/assets/modules/tooltip.js"></script>
<script src="/assets/modules/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/modules/nicescroll/jquery.nicescroll.min.js"></script>
<script src="/assets/modules/moment.min.js"></script>
<script src="/assets/js/stisla.js"></script>

<!-- JS Libraies -->
<script src="/assets/modules/jquery-ui/jquery-ui.min.js"></script>

<!-- Page Specific JS File -->
<script src="/assets/js/page/bootstrap-modal_admin_page.js"></script>
<script src="/assets/js/page/components-table.js"></script>
<script src="/assets/modules/jquery.sparkline.min.js"></script>
<script src="/assets/modules/chart.min.js"></script>
<script src="/assets/modules/owlcarousel2/dist/owl.carousel.min.js"></script>
<script src="/assets/modules/summernote/summernote-bs4.js"></script>
<script src="/assets/modules/chocolat/dist/js/jquery.chocolat.min.js"></script>
<script src="/assets/js/page/forms-advanced-forms.js"></script>
<script src="/assets/modules/select2/dist/js/select2.full.min.js"></script>
<script src="/assets/modules/jquery-selectric/jquery.selectric.min.js"></script>

<!-- Page Specific JS File -->
<script src="/assets/js/page/index.js"></script>

<!-- Template JS File -->
<script src="/assets/js/scripts.js"></script>
<script src="/assets/js/custom.js"></script>

</body>

</html>
