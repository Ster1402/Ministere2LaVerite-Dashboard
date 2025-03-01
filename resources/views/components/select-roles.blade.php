<div class="form-group row">
    <label class="col-sm-3 col-form-label">Rôle(s)</label>
    <div class="col-sm-9">
        <select class="form-control select2" name="rolesNames[]" multiple style="width:300px">
            @foreach($roles as $role)
                <option
                    {{ $defaults?->contains('name', 'LIKE', $role->name) ? 'selected' : '' }}
                    value="{{ $role->name }}">{{ $role->displayName }}</option>
            @endforeach
        </select>
    </div>
</div>
