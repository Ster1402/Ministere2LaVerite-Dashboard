<div class="filter-row mb-3">
    <div class="row g-2 align-items-center">
        <div class="col-md-4">
            <select name="filters[{{ $index }}][field]" class="form-control filter-field"
                data-index="{{ $index }}">
                <option value="">{{ __('Sélectionner un champ') }}</option>
                @foreach ($attributes as $key => $attribute)
                    <option value="{{ $key }}" data-type="{{ $attribute['type'] }}"
                        {{ $selectedField == $key ? 'selected' : '' }}>
                        {{ $attribute['display_name'] }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <select name="filters[{{ $index }}][operator]" class="form-control filter-operator"
                data-index="{{ $index }}" data-selected="{{ $selectedOperator }}">
                <option value="">{{ __('Sélectionner un opérateur') }}</option>
                @if ($selectedField && isset($attributes[$selectedField]))
                    @php
                        $fieldType = $attributes[$selectedField]['type'];
                        $availableOperators = isset($operators[$fieldType]) ? $operators[$fieldType] : [];
                    @endphp

                    @foreach ($availableOperators as $key => $operator)
                        <option value="{{ $key }}" {{ $selectedOperator == $key ? 'selected' : '' }}>
                            {{ $operator['display'] }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>

        <div class="col-md-4">
            <input type="text" name="filters[{{ $index }}][value]" class="form-control filter-value"
                data-index="{{ $index }}" value="{{ $filterValue }}" placeholder="{{ __('Valeur') }}"
                {{ in_array($selectedOperator, ['is_null', 'is_not_null']) ? 'readonly' : '' }}>
        </div>

        <div class="col-md-1">
            @if ($index > 0)
                <button type="button" class="btn btn-danger btn-sm remove-filter" data-index="{{ $index }}">
                    <i class="fas fa-times"></i>
                </button>
            @else
                <button type="button" class="btn btn-secondary btn-sm" disabled style="visibility: hidden;">
                    <i class="fas fa-times"></i>
                </button>
            @endif
        </div>
    </div>
</div>
