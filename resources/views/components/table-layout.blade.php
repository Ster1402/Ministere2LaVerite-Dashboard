@props(['searchable' => true])

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                {{ $header }}
                @if($searchable)
                    <div class="card-header-action">
                        <x-search-input />
                    </div>
                @endif
            </div>
            <div class="card-body p-0">
                {{ $body }}
            </div>
        </div>
    </div>
</div>
