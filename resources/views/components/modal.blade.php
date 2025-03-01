@props(['id', 'action' => ''])

<div class="modal fade" id="{{ $id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" enctype="multipart/form-data" method="POST" action="{{ $action }}">
                    @csrf
                    <x-validation-errors class="mb-4"/>
                    {{ $slot }}
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal">
                            <i class="fa fa-close"></i> Fermer
                        </button>
                        <button type="submit" class="btn btn-primary btn-flat">
                            <i class="fa fa-save"></i> Confirmer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
