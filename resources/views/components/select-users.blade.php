@props(['title' => 'Utilisateur', 'name' => 'user', 'id' => ''])

<div id="{{ $id }}" class="form-group row mb-4">
    <label for="{{ $name }}" class="col-sm-3 col-form-label">{{ $title }}</label>
    <div class="col-sm-9">
        <select id="{{ $name }}" name="{{ $name }}" class="form-control select2" style="width:300px">
            @foreach($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>
    </div>
</div>
