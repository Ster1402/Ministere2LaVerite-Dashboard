<div class="filter-row mb-3">
    <div class="row">
        <div class="col-md-4">
            <select name="filters[{{ $index }}][field]" class="form-control filter-field"
                data-index="{{ $index }}">
                <option value="">{{ __('Select Field') }}</option>
                @foreach ($attributes as $key => $attribute)
                    <option value="{{ $key }}" data-type="{{ $attribute['type'] }}"
                        {{ isset($filters[$index]['field']) && $filters[$index]['field'] == $key ? 'selected' : '' }}>
                        {{ $attribute['display_name'] }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <select name="filters[{{ $index }}][operator]" class="form-control filter-operator"
                data-index="{{ $index }}">
                <option value="">{{ __('Select Operator') }}</option>
                <!-- Operators will be populated dynamically via JavaScript -->
            </select>
        </div>

        <div class="col-md-4">
            <input type="text" name="filters[{{ $index }}][value]" class="form-control filter-value"
                data-index="{{ $index }}" value="{{ $filters[$index]['value'] ?? '' }}"
                placeholder="{{ __('Value') }}">
        </div>

        <div class="col-md-1">
            @if ($index > 0)
                <button type="button" class="btn btn-danger btn-sm remove-filter" data-index="{{ $index }}">
                    <i class="fas fa-times"></i>
                </button>
            @endif
        </div>
    </div>
</div>
