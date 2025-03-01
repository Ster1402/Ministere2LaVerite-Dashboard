@props(['id', 'action'])

<x-modal :id="$id" :action="$action">
    <div class="form-group">
        <label for="title">Titre</label>
        <div class="input-group">
            <div class="input-group-prepend"></div>
            <input id="title" name="title" type="text"
                   value="{{ old('title') ?? $event?->title }}"
                   class="form-control" placeholder="{{ __('Nom de l\'évènement') }}"/>
        </div>
    </div>
    <br/>

    <div class="form-group">
        <label for="from">Date de Début</label>
        <input id="from" name="from" type="date" value="{{ old('from') ?? $event?->from?->format('Y-m-d') }}"
               class="form-control"/>
    </div>

    <div class="form-group">
        <label for="to">Date de Fin</label>
        <input id="to" name="to" type="date" value="{{ old('to') ?? $event?->to?->format('Y-m-d') }}"
               class="form-control datetimepicker"/>
    </div>
    <x-select-assembly name="assemblies[]" :defaults="$event?->assemblies"/>
    <br/>
    <div class="form-group">
        <label for="description">Description</label>
        <div class="form-group row mb-4">
            <div class="col-sm-12 col-md-7">
                <textarea id="description" name="description"
                          class="summernote-simple">{!! old('description') ?? $event?->description !!}</textarea>
            </div>
        </div>
    </div>
    {{ $slot }}
</x-modal>
