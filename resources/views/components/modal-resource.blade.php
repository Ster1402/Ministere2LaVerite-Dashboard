@props(['id', 'action', 'name'])

<x-modal id="{{ $id }}" method="POST" action="{{ $action }}">
    <div class="form-group">
        <label for="name">Nom de la resource</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <div class="input-group-text">
                    <i class="fas fa-user"></i>
                </div>
            </div>
            <input required id="name" name="name" value="{{ old('name') ?? $resource?->name }}" type="text"
                   class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label for="quantity">Quantité en stock</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <div class="input-group-text">
                    <i class="fas fa-user"></i>
                </div>
            </div>
            <input required id="quantity" name="quantity" type="number" min="0" value="{{ old('quantity') ?? $resource?->quantity }}"
                   class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label for="group_id">Groupes</label>
        <select id="group_id" name="group_id"
                class="form-control select2" style="width:300px">
            @foreach($groups as $group)
                <option
                    value="{{ $group->id }}"
                    {{ (old('group_id') === $group?->id || $resource?->group?->id === $group->id) ? 'selected' : '' }}>
                    {{ $group->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="description">Description</label>
        <div class="form-group row mb-4">
            <div class="col-sm-12 col-md-7">
                <textarea id="description" name="description"
                          style="width:300px"
                          class="summernote-simple">{{ old('description') ?? $resource?->description }}</textarea>
            </div>
        </div>
    </div>
    {{ $slot }}
</x-modal>
