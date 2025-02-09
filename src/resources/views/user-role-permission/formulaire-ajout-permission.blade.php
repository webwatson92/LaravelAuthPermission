<div class="col-md-12">
    <div class="card card-primary">
        <div class="card-header bg-primary d-flex align-items-center">
            <h3 class="card-title flex-grow-1" style="color:#fff; font-weight: bold">AJOUT DE PERMISSION</h3>
        </div>
        @include('layouts.flash')
        <div class="card-body">
            <form method="post" action="{{ route('ajouter.permission') }}">
                @csrf
                <input type="hidden" id="idPermission" name="id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <input id="permission" name="permission" type="text" class="form-control"
                                    placeholder="Entrer la Permission" required>
                            </div>
                            @error('permission')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary float-right">Enregistrer</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
