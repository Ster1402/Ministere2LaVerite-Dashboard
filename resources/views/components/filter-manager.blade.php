<form action="{{ $action }}" method="{{ $method }}" class="filter-form">
    <div class="filters-container mb-4">
        <h5 class="filters-title">{{ __('Filtres') }}</h5>

        @if ($isFilterable())
            <div id="filters-list">
                @if (empty($filters))
                    <x-dynamic-filter :model-name="$modelName" :attributes="$attributes" :operators="$operators" :index="0" />
                @else
                    @foreach ($filters as $index => $filter)
                        <x-dynamic-filter :model-name="$modelName" :attributes="$attributes" :operators="$operators" :filters="$filters"
                            :index="$index" />
                    @endforeach
                @endif
            </div>

            <div class="filters-actions mt-3">
                <button type="button" class="btn btn-sm btn-primary add-filter">
                    <i class="fas fa-plus"></i> {{ __('Ajouter un filtre') }}
                </button>

                <button type="button" class="btn btn-sm btn-danger clear-filters">
                    <i class="fas fa-times"></i> {{ __('Effacer les filtres') }}
                </button>

                <button type="submit" class="btn btn-sm btn-success apply-filters">
                    <i class="fas fa-filter"></i> {{ __('Appliquer les filtres') }}
                </button>
            </div>
        @else
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> {{ __('Ce modèle ne prend pas en charge le filtrage dynamique.') }}
            </div>
        @endif
    </div>
</form>

<div class="filter-template d-none">
    <x-dynamic-filter :model-name="$modelName" :attributes="$attributes" :operators="$operators" :index="'__INDEX__'" />
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize filter counter
            let filterCount = document.querySelectorAll('#filters-list .filter-row').length;

            // Add Filter button
            document.querySelector('.add-filter').addEventListener('click', function() {
                addNewFilter();
            });

            // Clear Filters button
            document.querySelector('.clear-filters').addEventListener('click', function() {
                clearAllFilters();
            });

            // Remove filter button (delegated event)
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-filter') ||
                    e.target.closest('.remove-filter')) {
                    removeFilter(e.target.closest('.filter-row'));
                }
            });

            // Field change event (delegated)
            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('filter-field')) {
                    updateOperators(e.target);
                }
            });

            // Add a new filter row
            function addNewFilter() {
                const template = document.querySelector('.filter-template').innerHTML;
                const newFilter = template.replace(/__INDEX__/g, filterCount);

                const filtersContainer = document.getElementById('filters-list');
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = newFilter;

                // Extract the actual element
                const filterElement = tempDiv.firstElementChild;
                filtersContainer.appendChild(filterElement);

                // Update operators for the new filter
                const fieldSelect = filterElement.querySelector('.filter-field');
                updateOperators(fieldSelect);

                filterCount++;
            }

            // Remove a filter row
            function removeFilter(filterRow) {
                if (filterRow) {
                    filterRow.remove();

                    // Renumber remaining filters to ensure proper indexing
                    const filterRows = document.querySelectorAll('#filters-list .filter-row');
                    filterRows.forEach((row, index) => {
                        updateFilterIndex(row, index);
                    });

                    // Reset counter
                    filterCount = filterRows.length;
                }
            }

            // Clear all filters
            function clearAllFilters() {
                const filtersContainer = document.getElementById('filters-list');
                // Keep only the first filter and reset it
                const filterRows = document.querySelectorAll('#filters-list .filter-row');

                if (filterRows.length > 0) {
                    const firstFilter = filterRows[0];
                    // Reset the first filter values
                    const fieldSelect = firstFilter.querySelector('.filter-field');
                    fieldSelect.selectedIndex = 0;

                    const operatorSelect = firstFilter.querySelector('.filter-operator');
                    operatorSelect.innerHTML = '<option value="">{{ __('Select Operator') }}</option>';

                    const valueInput = firstFilter.querySelector('.filter-value');
                    valueInput.value = '';

                    // Remove all other filters
                    for (let i = 1; i < filterRows.length; i++) {
                        filterRows[i].remove();
                    }

                    filterCount = 1;
                } else {
                    // If no filters exist, add a blank one
                    addNewFilter();
                }
            }

            // Update filter index in field names
            function updateFilterIndex(filterRow, newIndex) {
                const fieldSelect = filterRow.querySelector('.filter-field');
                const operatorSelect = filterRow.querySelector('.filter-operator');
                const valueInput = filterRow.querySelector('.filter-value');

                if (fieldSelect) {
                    fieldSelect.name = `filters[${newIndex}][field]`;
                    fieldSelect.dataset.index = newIndex;
                }

                if (operatorSelect) {
                    operatorSelect.name = `filters[${newIndex}][operator]`;
                    operatorSelect.dataset.index = newIndex;
                }

                if (valueInput) {
                    valueInput.name = `filters[${newIndex}][value]`;
                    valueInput.dataset.index = newIndex;
                }

                // Update the remove button if necessary
                const removeButton = filterRow.querySelector('.remove-filter');
                if (removeButton) {
                    removeButton.dataset.index = newIndex;
                }
            }

            // Update operators dropdown based on selected field
            function updateOperators(fieldSelect) {
                if (!fieldSelect) return;

                const selectedOption = fieldSelect.options[fieldSelect.selectedIndex];
                const fieldType = selectedOption.dataset.type;
                const index = fieldSelect.dataset.index;

                const operatorSelect = document.querySelector(`.filter-operator[data-index="${index}"]`);
                if (!operatorSelect) return;

                // Clear current operators
                operatorSelect.innerHTML = '<option value="">{{ __('Select Operator') }}</option>';

                // Skip if no field is selected
                if (!fieldSelect.value) return;

                // Get operators for this field type
                const operators = getOperatorsForType(fieldType);

                // Populate operators dropdown
                for (const [key, operator] of Object.entries(operators)) {
                    const option = document.createElement('option');
                    option.value = key;
                    option.textContent = operator.display;
                    operatorSelect.appendChild(option);
                }
            }

            // Get operators based on field type
            function getOperatorsForType(type) {
                // Default operators
                const defaultOperators = {
                    'equals': {
                        display: 'Égal à'
                    },
                    'not_equals': {
                        display: 'Différent de'
                    }
                };

                // Type-specific operators
                const typeOperators = {
                    'string': {
                        'equals': {
                            display: 'Égal à'
                        },
                        'not_equals': {
                            display: 'Différent de'
                        },
                        'contains': {
                            display: 'Contient'
                        },
                        'starts_with': {
                            display: 'Commence par'
                        },
                        'ends_with': {
                            display: 'Finit par'
                        },
                        'is_null': {
                            display: 'Est vide'
                        },
                        'is_not_null': {
                            display: 'N\'est pas vide'
                        }
                    },
                    'integer': {
                        'equals': {
                            display: 'Égal à'
                        },
                        'not_equals': {
                            display: 'Différent de'
                        },
                        'greater_than': {
                            display: 'Supérieur à'
                        },
                        'greater_than_or_equal': {
                            display: 'Supérieur ou égal à'
                        },
                        'less_than': {
                            display: 'Inférieur à'
                        },
                        'less_than_or_equal': {
                            display: 'Inférieur ou égal à'
                        },
                        'is_null': {
                            display: 'Est vide'
                        },
                        'is_not_null': {
                            display: 'N\'est pas vide'
                        }
                    },
                    'boolean': {
                        'equals': {
                            display: 'Est vrai'
                        },
                        'not_equals': {
                            display: 'Est faux'
                        }
                    },
                    'date': {
                        'equals': {
                            display: 'Égal à'
                        },
                        'not_equals': {
                            display: 'Différent de'
                        },
                        'greater_than': {
                            display: 'Après'
                        },
                        'less_than': {
                            display: 'Avant'
                        },
                        'is_null': {
                            display: 'Est vide'
                        },
                        'is_not_null': {
                            display: 'N\'est pas vide'
                        }
                    },
                    'datetime': {
                        'equals': {
                            display: 'Égal à'
                        },
                        'not_equals': {
                            display: 'Différent de'
                        },
                        'greater_than': {
                            display: 'Après'
                        },
                        'less_than': {
                            display: 'Avant'
                        },
                        'is_null': {
                            display: 'Est vide'
                        },
                        'is_not_null': {
                            display: 'N\'est pas vide'
                        }
                    }
                };

                return typeOperators[type] || defaultOperators;
            }

            // Initialize operators for existing filters
            document.querySelectorAll('.filter-field').forEach(function(fieldSelect) {
                updateOperators(fieldSelect);

                // Pre-select operator if it exists in the filter data
                const index = fieldSelect.dataset.index;
                const operatorSelect = document.querySelector(`.filter-operator[data-index="${index}"]`);

                if (operatorSelect && operatorSelect.dataset.selected) {
                    const selectedOperator = operatorSelect.dataset.selected;

                    // Find and select the option
                    for (let i = 0; i < operatorSelect.options.length; i++) {
                        if (operatorSelect.options[i].value === selectedOperator) {
                            operatorSelect.options[i].selected = true;
                            break;
                        }
                    }
                }
            });
        });
    </script>
@endpush

<style>
    .filters-container {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .filters-title {
        margin-bottom: 15px;
        font-weight: 600;
        color: #333;
        display: flex;
        align-items: center;
    }

    .filters-title::before {
        content: '\f0b0';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        margin-right: 8px;
        color: #3490dc;
    }

    .filter-row {
        position: relative;
        transition: all 0.3s ease;
    }

    .filter-row:hover {
        background-color: #f0f4f8;
    }

    .filters-actions {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        border-top: 1px solid #e2e8f0;
        padding-top: 15px;
    }

    .add-filter {
        background-color: #4299e1;
        border-color: #4299e1;
    }

    .clear-filters {
        background-color: #f56565;
        border-color: #f56565;
    }

    .apply-filters {
        background-color: #48bb78;
        border-color: #48bb78;
    }

    .remove-filter {
        border-radius: 50%;
        width: 28px;
        height: 28px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .filters-actions {
            flex-direction: column;
        }

        .filters-actions .btn {
            width: 100%;
            margin-bottom: 5px;
        }
    }
</style>
