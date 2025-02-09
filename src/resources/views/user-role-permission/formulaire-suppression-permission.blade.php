<div class="col-md-12">
    {{-- <div class="card bg-danger text-white mb-3">
        <div class="card-header">Header</div>
            <div class="card-body">
                <h5 class="card-title text-white">Danger card title</h5>
                <p class="card-text">Some quick example text to build on the card title and make up.</p>
            </div>
        </div> --}}
    <div class="card card-danger">
        <div class="card-header bg-danger d-flex align-items-center">
            <h3 class="card-title flex-grow-1" style="color:#fff; font-weight: bold">SUPPRESSION DE PERMISSION</h3>
        </div>

        <div class="card-body">
            <form method="post" action="{{ route('supprimer.permission', $permission->id) }}">
                <p><h3>Voulez-vous vraiment supprimer la permission <b>{{ $permission->name }} ?</b></h3></p>
                @csrf
                <input type="hidden" id="idPermission" name="idPermission" value="{{ $permission->id }}">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <input id="permission" name="permission" type="text" class="form-control" placeholder="Permission" value="{{ $permission->name }}" readonly>

                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <a href="{{ route('liste.permissions') }}" class="btn btn-secondary" id="btnNo">Non</a>
                                <button type="submit" class="btn btn-danger float-right">OUI</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
