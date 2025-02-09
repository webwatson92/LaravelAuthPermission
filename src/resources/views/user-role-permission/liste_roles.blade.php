@extends('layouts.base')
@push('headTitle')  GESTION DES ROLES @endpush
{{-- <div class="row">
    @push('titlePage')  GESTION DES ROLES @endpush
    @push("breadcrumb1") <a href="{{ route('dashboard')}}">ACCUEIL</a> @endpush
    @push("breadcrumb2") ROLES @endpush
</div> --}}
@section('content')

<div class="container-fluid flex-grow-1 container-p-y" style="padding-top:2em;padding-bottom:2em">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">PARAMETRE DE COMPTE  /</span> LISTE DES ROLES</h4>
    <div class="row">
        <div class="col-sm-5"></div>
        <div class="col-sm-4"></div>
        <div class="row col-sm-3">
                <a href="#" type="button" class="btn btn-primary btnAdd" data-bs-toggle="modal" data-bs-target="#edit_role">
                    <i class="fa-solid fa-plus"></i>
                    AJOUTER UN ROLE
                </a>
        </div>
    </div>
    <div class="row" style="background-color: white;margin-top:2em">
        <div class="col-md-12 col-sm-12">
            <div class="table-responsive">
                @include('layouts.flash')
                <div class="card">
                    <nav aria-label="breadcrumb" class="card-header">
                        <ol class="breadcrumb breadcrumb-style2 mb-0">
                            <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">ACCUEIL</a>
                            </li>
                            <li class="breadcrumb-item active">LISTE DES ROLES</li>
                        </ol>
                    </nav>
                    <div class="table-responsive text-nowrap">
                        @php
                            use Carbon\Carbon;
                        @endphp
                        @if(isset($listRoles) && !empty($listRoles))
                            <table id="liste_permission" class="table">
                                <thead>
                                    <tr class="active">
                                        <th>N°</th>
                                        <th>ROLE</th>
                                        <th>PERMISSION</th>
                                        <th>ACTION</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    @php $i=1; @endphp
                                    @foreach($listRoles as $role)
                                        @php
                                            $editButton = '
                                                <a href="'.route('modification.role', $role->id).'" class="btn btn-primary btn-sm btnEdit"><i class="fa-solid fa-pencil fa-fw"></i></a>
                                                ';

                                            $deleteButton = '
                                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal'.$role->id.'"><i class="fa-solid fa-trash fa-fw"></i>
                                                </button>
                                                <div class="modal fade" id="deleteModal'.$role->id.'" tabindex="-1"
                                                    role="dialog" aria-labelledby="deleteModalLabel'.$role->id.'" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="deleteModalLabel'.$role->id.'">Confirmation de
                                                                    suppression</h5>
                                                                <button type="button" class="close" data-bs-dismiss="modal"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Êtes-vous sûr de vouloir supprimer ce role '.$role->name.'
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Annuler</button>
                                                                <form action="'.route('supprimer.role', $role->id).'"
                                                                    method="GET">
                                                                    '.csrf_field().'
                                                                    '.method_field('DELETE').'
                                                                    <button type="submit" class="btn btn-danger">Supprimer</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            ';
                                        @endphp
                                        <tr class="table-default">
                                            <td><i class="fab fa-sketch fa-lg text-warning me-3"></i> <strong>{{ $i++ }}</strong></td>
                                            <td>{{ $role->name }}</td>
                                            <td>{{ $role->guard_name }}</td>
                                            <td>
                                                {!! $editButton !!}
                                                {!! $deleteButton !!}
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        @else
                            <p>Aucune information sur le solde disponible.</p>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col text-end">
                            {{-- <small class="text-light fw-semibold">pagination</small> --}}
                            <div class="demo-inline-spacing">
                                <!-- Basic Pagination -->
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-end">
                                        {{ $listRoles->links('pagination::bootstrap-5') }}
                                    </ul>
                                </nav>
                                <!--/ Basic Pagination -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="edit_role" tabindex="-1" role="dialog" aria-labelledby="editCategorieModalCenterTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header  btn-primary">
                <h3 id="titre_modal" class="card-title"><i class="fa fa-fingerprint fa-1x"></i>Ajouter un Role</h3>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{ route('ajouter.modifier.role') }}">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>Libelle Role</label>
                                <input id="name" name="name" type="text" class="form-control  " placeholder="Entrer le Role" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-bs-dismiss="modal" class="btn btn-secondary float-left">Fermer</button>
                    <button type="submit" class="btn btn-primary float-right">Enregistrer </button>
                </div>
            </form>
        </div>
    </div>
</div>

@stop
@push('scripts')

<script>

    $(document).ready(function() {
        if ($.fn.DataTable.isDataTable('#liste_role')) {
            $('#liste_role').DataTable().destroy();
        }
        var oTable = $('#liste_role').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{!! route('liste.roles.data') !!}",
            columns: [
                {data: 'numero',name: 'numero'},
                {data: 'name',name: 'name'},
                {data: 'guard_name', name: 'guard_name'},
                {data: 'action', name: 'action'}
            ]
         });

         //GESTION DES ERREURS
         $(function() {
            var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000
            });

            $('.swalDefaultWarning').ready(function() {
                @if (session('warning'))
                    Toast.fire({
                        icon: 'warning',
                        title: '{{ session('warning') }}'
                    })
                @endif
            });

            $('.swalDefaultSuccess').ready(function() {
                @if (session('status'))
                    Toast.fire({
                        icon: 'success',
                        title: '{{ session('status') }}'
                    })
                @endif
            });

            $('.swalDefaultSuccess').ready(function() {
                @if (session('error'))
                    Toast.fire({
                        icon: 'error',
                        title: '{{ session('error') }}'
                    })
                @endif
            });

            $('.swalDefaultSuccess').ready(function() {
                @if (session('info'))
                    Toast.fire({
                        icon: 'info',
                        title: '{{ session('info') }}'
                    })
                @endif
            });
        });


        $('.btnAdd').on('click', function(){


            $('#name').val('');
            //$('#titre_modal').html('Ajouter un Role');

        });
    });
    </script>
@endpush





