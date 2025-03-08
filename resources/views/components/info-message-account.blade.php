<div {{ $attributes->merge(['class' => "col-md-4 stretch-card grid-margin "]) }}>
    <div class="card">
        <div class="card-header">
            Information sur le compte API SMS
        </div>
        <div class="card-body">
            <p class="card-text">Nom du compte: {{ $name }}</p>
            <p class="card-text">Solde restant: {{ $balance }}</p>
        </div>
    </div>
</div>
