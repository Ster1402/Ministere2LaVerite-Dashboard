<div class="modal fade" id="reportModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $title }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="reportForm" class="form-horizontal" method="POST" action="{{ route('reports.generate') }}"
                    target="_blank">
                    @csrf
                    <input type="hidden" name="model" value="{{ $modelName }}">

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Format d'export</label>
                        <div class="col-sm-9">
                            <select id="format" name="format" class="form-control select2" style="width:100%">
                                <option value="pdf" selected>PDF</option>
                                <option value="excel">Excel</option>
                            </select>
                        </div>
                    </div>

                    <div id="paper_size" class="form-group row">
                        <label class="col-sm-3 col-form-label">Format de papier</label>
                        <div class="col-sm-9">
                            <select name="paper_size" class="form-control select2" style="width:100%">
                                @foreach ($paperSizes as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div id="orientation" class="form-group row">
                        <label class="col-sm-3 col-form-label">Orientation</label>
                        <div class="col-sm-9">
                            <select name="orientation" class="form-control select2" style="width:100%">
                                @foreach ($orientations as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Colonnes à exporter</label>
                        <div class="col-sm-9">
                            <select name="columns[]" class="form-control select2" multiple style="width:100%"
                                id="reportColumns">
                                @foreach ($columns as $key => $column)
                                    <option value="{{ $key }}">{{ $column['title'] }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Sélectionnez les colonnes que vous souhaitez inclure dans le
                                rapport.</small>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal">
                            <i class="fa fa-close"></i> Fermer
                        </button>
                        <button type="submit" class="btn btn-primary btn-flat" name="generate">
                            <i class="fa fa-file-pdf"></i> Générer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
