<!-- This will be used as a template for creating new filter rows -->
<div class="filter-template d-none">
    <div class="filter-row">
        <div class="row g-2 align-items-center">
            <div class="col-md-4">
                <select name="filters[__INDEX__][field]" class="form-control filter-field" data-index="__INDEX__">
                    <option value="">{{ __('Sélectionner un champ') }}</option>
                    @foreach ($attributes as $key => $attribute)
                        <option value="{{ $key }}" data-type="{{ $attribute['type'] }}">
                            {{ $attribute['display_name'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <select name="filters[__INDEX__][operator]" class="form-control filter-operator" data-index="__INDEX__">
                    <option value="">{{ __('Sélectionner un opérateur') }}</option>
                </select>
            </div>

            <div class="col-md-4">
                <input type="text" name="filters[__INDEX__][value]" class="form-control filter-value"
                    data-index="__INDEX__" placeholder="{{ __('Valeur') }}">
            </div>

            <div class="col-md-1">
                <button type="button" class="btn btn-danger btn-sm remove-filter" data-index="__INDEX__">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>
</div>
