<div class="col-lg-4 col-md-4 col-sm-12">
    <div class="card card-statistic-2">
        <div class="card-stats">
            <div class="card-stats-title">{{ $title }} -
            </div>
            <div class="card-stats-items">
                @foreach($statItems as $item)
                    @if($loop->iteration <= 3)
                    <div class="card-stats-item">
                        <div class="card-stats-item-count">{{ $item->count }}</div>
                        <div class="card-stats-item-label">{{ $item->name }}</div>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>

        <div id="modal-55" class="card-icon shadow-primary bg-info">
            <i class="fas fa-eye"></i>
        </div>
        <div class="card-wrap">
            <div class="card-header">
                <h4>Total des {{ $slug }}</h4>
            </div>
            <div class="card-body">
                {{ $total }}
            </div>
        </div>

    </div>
</div>
