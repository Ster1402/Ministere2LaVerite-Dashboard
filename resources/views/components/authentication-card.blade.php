<section class="section">
    <div class="container mt-5">
        <div class="row">
            <div
                class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                <div class="login-brand">
                    <img src="assets/img/admin.png" alt="logo" width="100" class="shadow-light rounded-circle">
                </div>

                <div class="card card-primary" style="background-color: rgba(255, 255, 255, 0.774)">
                    <div class="card-header">
                        <h4 id="welcome-message">{{ $title ?? __('Welcome back,') }}</h4>
                    </div>

                    <div class="card-body">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
