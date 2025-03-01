@props(['name' => 'assemblies[]', 'id' => 'assemblies'])

<div {{ $attributes->merge(['class' => 'form-group ']) }} id="{{ $id }}">
    <label for="{{ $name }}">Assemblées</label>
    <div class="input-group">
        <div class="input-group-prepend"></div>
        <select id="{{ $name }}" name="{{ $name }}" class="form-control select2" multiple="" style="width: 500px">
            @foreach($data as $assembly)
                <option
                    {{ $defaults?->contains('id', '=', $assembly->id) ? 'selected' : '' }}
                    value="{{ $assembly->id }}">{{ $assembly->name }}</option>
            @endforeach
        </select>
    </div>
</div>
