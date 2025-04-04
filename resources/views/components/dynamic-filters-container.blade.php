<div class="filters-container">
    <div id="filters-list">
        @if (empty($filters))
            <div class="filter-row">
                <div class="row g-2 align-items-center">
                    <div class="col-md-4">
                        <select name="filters[0][field]" class="form-control filter-field" data-index="0">
                            <option value="">{{ __('Sélectionner un champ') }}</option>
                            @foreach ($attributes as $key => $attribute)
                                <option value="{{ $key }}" data-type="{{ $attribute['type'] }}">
                                    {{ $attribute['display_name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <select name="filters[0][operator]" class="form-control filter-operator" data-index="0">
                            <option value="">{{ __('Sélectionner un opérateur') }}</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <input type="text" name="filters[0][value]" class="form-control filter-value" data-index="0"
                            placeholder="{{ __('Valeur') }}">
                    </div>

                    <div class="col-md-1">
                        <button type="button" class="btn btn-secondary btn-sm" disabled style="visibility: hidden;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        @else
            @foreach ($filters as $index => $filter)
                <div class="filter-row">
                    <div class="row g-2 align-items-center">
                        <div class="col-md-4">
                            <select name="filters[{{ $index }}][field]" class="form-control filter-field"
                                data-index="{{ $index }}">
                                <option value="">{{ __('Sélectionner un champ') }}</option>
                                @foreach ($attributes as $key => $attribute)
                                    <option value="{{ $key }}" data-type="{{ $attribute['type'] }}"
                                        {{ $filter['field'] == $key ? 'selected' : '' }}>
                                        {{ $attribute['display_name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <select name="filters[{{ $index }}][operator]" class="form-control filter-operator"
                                data-index="{{ $index }}" data-selected="{{ $filter['operator'] ?? '' }}">
                                <option value="">{{ __('Sélectionner un opérateur') }}</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <input type="text" name="filters[{{ $index }}][value]"
                                class="form-control filter-value" data-index="{{ $index }}"
                                value="{{ $filter['value'] ?? '' }}" placeholder="{{ __('Valeur') }}">
                        </div>

                        <div class="col-md-1">
                            @if ($index > 0)
                                <button type="button" class="btn btn-danger btn-sm remove-filter"
                                    data-index="{{ $index }}">
                                    <i class="fas fa-times"></i>
                                </button>
                            @else
                                <button type="button" class="btn btn-secondary btn-sm" disabled
                                    style="visibility: hidden;">
                                    <i class="fas fa-times"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <!-- Include filter template -->
    @include('components.filter-template')

    <div class="filter-actions">
        <div class="filter-actions-left">
            <button type="button" class="btn btn-sm btn-primary add-filter">
                <i class="fas fa-plus me-1"></i> {{ __('Ajouter un filtre') }}
            </button>
            <button type="button" class="btn btn-sm btn-danger clear-filters">
                <i class="fas fa-times me-1"></i> {{ __('Effacer les filtres') }}
            </button>
        </div>
        <div class="filter-actions-right">
            <span class="text-sm text-muted">
                <i class="fas fa-info-circle me-1"></i> Filtre incomplet = ignoré
            </span>
        </div>
    </div>
</div>
