<x-app-layout>

    <div class="row">
        <x-stats-users-panel />

        <x-stats-resources-panel />

        <x-stats-events-panel />
    </div>

    <div class="row">
        <div class="col-md-8">
            <x-recent-donations />
        </div>
        <x-account-card />

        <div class="col-md-8">
            <x-donation-stats />
        </div>
    </div>

</x-app-layout>
