@props(['id', 'action', 'name'])

<x-modal id="{{ $id }}" method="POST" action="{{ $action }}">
    <div class="form-group">
        <label for="name">Nom du groupe</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <div class="input-group-text">
                    <i class="fas fa-user"></i>
                </div>
            </div>
            <input required id="name" name="name" value="{{ old('name') ?? $group?->name }}" type="text"
                   class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label for="description">Description</label>
        <div class="form-group row mb-4">
            <div class="col-sm-12 col-md-7">
                <textarea id="description" name="description"
                          style="width:300px"
                          class="summernote-simple">{{ old('description') ?? $group?->description }}</textarea>
            </div>
        </div>
    </div>
    {{ $slot }}
</x-modal>
