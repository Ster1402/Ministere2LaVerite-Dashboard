@props(['label' => 'Sélectionner un/des Utilisateur(s)', 'name' => 'users[]', 'id' => 'users'])

<div class="form-group row">
    <label for="{{ $id }}" class="col-sm-3 col-form-label">{{ $label }}</label>
    <div class="col-sm-9">
        <select id="{{ $id }}" name="{{ $name }}" class="form-control select2" multiple="" style="width:300px">
            @foreach($users as $user)
                <option
                    {{ $defaults?->contains('id', '=', $user->id) ? 'selected' : '' }}
                    value="{{ $user->id }}">{{ $user->name }} {{ $user->surname }}</option>
            @endforeach
        </select>
    </div>
</div>
