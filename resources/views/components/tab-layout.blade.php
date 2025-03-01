@props([
'title' => __('--- Gestions des actions CRUD et Tutoriel ---'),
'subtitle' => __('Liste'),
'tab2' => __('Tutoriel'),
]);

<div class="row">
    <div class="col-12">
        <div class="card h-100">
            <div class="card-header">
                <h4>{{ $title }}</h4>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home"
                           role="tab" aria-controls="home"
                           aria-selected="true"><b>{{ $subtitle }}</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile"
                           role="tab" aria-controls="profile"
                           aria-selected="false"><b>{{ $tab2 }}</b></a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel"
                         aria-labelledby="home-tab">
                        {{ $home }}
                    </div>
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <br>
                        {{ $tutorial }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
