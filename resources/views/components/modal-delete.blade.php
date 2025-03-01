@props(['id', 'action'])

<x-modal :id="$id" :action="$action">
    @method('DELETE')
    <div class="form-group">
        <div style="text-align: center;">
            <h6 class="text-xl" style="color: red;">
                Voulez-vous supprimer cet élément ?
            </h6>
        </div>
    </div>
    {{ $slot }}
</x-modal>
