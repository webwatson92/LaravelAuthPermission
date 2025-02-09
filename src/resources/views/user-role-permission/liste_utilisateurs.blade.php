@extends('layouts.base')
@push('headTitle')  GESTION DES UTILISATEURS @endpush
{{-- <div class="row">
    @push('titlePage')  GESTION DES UTILISATEURS @endpush
    @push("breadcrumb1") <a href="#">ACCUEIL</a> @endpush
    @push("breadcrumb2") Ajout du seuil @endpush
</div> --}}
@section('content')

<div class="container-fluid flex-grow-1 container-p-y" style="padding-top:2em;padding-bottom:2em">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">PARAMETRE DE COMPTE  /</span> LISTE DES UTILISATEURS</h4>
    <div class="row">
        <div class="col-sm-5"></div>
        <div class="col-sm-4"></div>
        <div class="row col-sm-3">
                <a href="{{ route('creation.utilisateur') }}" type="button" class="btn btn-primary">
                    <i class="fa-solid fa-plus"></i>
                    AJOUTER UN UTILISATEUR
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
                            <li class="breadcrumb-item active">LISTE DES UTILISATEURS</li>
                        </ol>
                    </nav>
                    <div class="table-responsive text-nowrap">
                        @php
                            use Carbon\Carbon;
                        @endphp
                        @if(isset($listUsers) && !empty($listUsers))
                            <table id="liste_permission" class="table">
                                <thead>
                                    <tr class="active">
                                        <th>N°</th>
                                        <th>NOM & PRENOMS</th>
                                        <th>EMAIL</th>
                                        <th>ACTION</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    @php $i=1; @endphp
                                    @foreach($listUsers as $user)
                                        @php
                                            $editButton = '
                                                <a href="'.route('modification.role', $user->id).'" class="btn btn-primary btn-sm btnEdit"><i class="fa-solid fa-pencil fa-fw"></i></a>
                                                ';

                                            $deleteButton = '
                                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal'.$user->id.'"><i class="fa-solid fa-trash fa-fw"></i>
                                                </button>
                                                <div class="modal fade" id="deleteModal'.$user->id.'" tabindex="-1"
                                                    role="dialog" aria-labelledby="deleteModalLabel'.$user->id.'" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="deleteModalLabel'.$user->id.'">Confirmation de
                                                                    suppression</h5>
                                                                <button type="button" class="close" data-bs-dismiss="modal"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Êtes-vous sûr de vouloir supprimer ce role '.$user->name.'
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Annuler</button>
                                                                <form action="'.route('supprimer.role', $user->id).'"
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
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
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
                                        {{ $listUsers->links('pagination::bootstrap-5') }}
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

@endsection
@push('scripts')
{{-- <script src="{{ asset('plugins-bracket/js/jquery.datatables.min.js')}}"></script> --}}
<script>

    $(document).ready(function() {


        if ($.fn.DataTable.isDataTable('#liste_utilisateur')) {
            $('#liste_utilisateur').DataTable().destroy();
        }
        var oTable = $('#liste_utilisateur').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{!! route('liste.utilisateur.data') !!}",
            columns: [
                {data: 'numero',name: 'numero'},
                {data: 'name', name: 'name'},
                {data: 'email', name: 'email', searchable: true},
                {data: 'action', name: 'action'}
            ],


           "sPaginationType": "full_numbers"
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
                        title: '{{ session('warning') }}',
                        timer: 10000
                    })
                @endif
            });

            $('.swalDefaultSuccess').ready(function() {
                @if (session('status'))
                    Toast.fire({
                        icon: 'success',
                        title: "{{ session('status') }}"
                    })
                @endif
            });

            $('.swalDefaultSuccess').ready(function() {
                @if (session('error'))
                    Toast.fire({
                        icon: 'error',
                        title: "{{ session('error') }}",
                        timer: 10000
                    })
                @endif
            });

            $('.swalDefaultSuccess').ready(function() {
                @if (session('info'))
                    Toast.fire({
                        icon: 'info',
                        title: "{{ session('info') }}",
                        timer: 10000
                    })
                @endif
            });
        });

    });
</script>


@endpush





