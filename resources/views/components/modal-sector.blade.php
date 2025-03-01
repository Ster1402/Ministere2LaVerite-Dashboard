@props(['id', 'action', 'name'])

<x-modal id="{{ $id }}" method="POST" action="{{ $action }}">
    <div class="form-group">
        <label for="name">Nom du secteur</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <div class="input-group-text">
                    <i class="fas fa-user"></i>
                </div>
            </div>
            <input required id="name" name="name" value="{{ old('name') ?? $sector?->name }}" type="text"
                   class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label for="master_id">Secteur maître</label>
        <select id="master_id" name="master_id"
                class="form-control select2" style="width:300px">
            <option value="">Aucun</option>
            @foreach($sectors as $sec)
                @if($sec->id !== $sector?->id && !$sec->master )
                    <option
                        value="{{ $sec?->id }}"
                        {{ (old('master_id') === $sec?->id || $sector?->master?->id === $sec?->id) ? 'selected' : '' }}>
                        {{ $sec->name }}
                    </option>
                @endif
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="description">Description</label>
        <div class="form-group row mb-4">
            <div class="col-sm-12 col-md-7">
                <textarea id="description" name="description"
                          style="width:300px"
                          class="summernote-simple">{{ old('description') ?? $sector?->description }}</textarea>
            </div>
        </div>
    </div>
    {{ $slot }}
</x-modal>
