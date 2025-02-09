@extends('layouts.base')
@push('headTitle')
    GESTION DES PERMISSIONS
@endpush
{{-- <div class="row">
    @push('titlePage')  GESTION DES PERMISSIONS @endpush
    @push('breadcrumb1') <a href="{{ route('dashboard')}}">ACCUEIL</a> @endpush
    @push('breadcrumb2') PERMISSIONS @endpush
</div> --}}
@section('content')
    <div class="container-fluid flex-grow-1 container-p-y" style="padding-top:2em;padding-bottom:2em">
        <div class="row">
            <div class="col-sm-3"></div>

            <div class="row col-sm-3">
                <a href="#" id="ajouter_permission" type="button" class="btn btn-primary btnAdd" data-bs-toggle="modal"
                    data-bs-target="#edit_permission">
                    AJOUTER UNE PERMISSION
                </a>
            </div>
            <div class="col-sm-5"></div>
        </div>
        <br/>
        <div class="row">
            {{-- <div class="col-sm-12"> --}}
            {{-- <div class="row"> --}}
            @csrf
            <div class="row">
                <div class="col-md-7">
                    <div class="table-responsive">
                        <div class="card">
                            <!-- <h5 class="card-header"></h5> -->
                            <nav aria-label="breadcrumb" class="card-header">
                                <ol class="breadcrumb breadcrumb-style2 mb-0">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('dashboard') }}">ACCUEIL</a>
                                    </li>
                                    <li class="breadcrumb-item active">LISTE DES PERMISSIONS</li>
                                </ol>
                            </nav>
                            <div class="table-responsive text-nowrap">
                                @php
                                    use Carbon\Carbon;
                                @endphp
                                @if (isset($listPermissions) && !empty($listPermissions))
                                    <table id="liste_permission" class="table">
                                        <thead>
                                            <tr class="active">
                                                <th>PERMISSION</th>
                                                <th>GUARD</th>
                                                <th>ACTION</th>
                                            </tr>
                                        </thead>
                                        <tbody class="table-border-bottom-0">
                                            @php $i=1; @endphp
                                            @foreach ($listPermissions as $permissions)
                                                @php
                                                    $action = '<a class="btn btn-primary btn-sm modification-permission" href="#"  id="'.$permissions->id.'"><i class="fa-solid fa-pencil fa-fw"></i></a> ';
                                                    $action .= '<a class="btn btn-danger btn-sm suppression-permission" href="#" id="'.$permissions->id.'"><i class="fa-solid fa-trash fa-fw"></i></a> ';

                                                @endphp
                                                <tr class="table-default">
                                                    {{-- <td><i class="fab fa-sketch fa-lg text-warning me-3"></i>
                                                                    <strong>{{ $i++ }}</td> --}}
                                                    <td>{{ $permissions->name }}</td>
                                                    <td>{{ $permissions->guard_name }}</td>
                                                    <td>
                                                        {!! $action !!}
                                                    </td>
                                                    <td>

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
                                    <div class="demo-inline-spacing">
                                        <!-- Basic Pagination -->
                                        <nav aria-label="Page navigation">
                                            <ul class="pagination justify-content-end">
                                                {{ $listPermissions->links('pagination::bootstrap-5') }}
                                            </ul>
                                        </nav>
                                        <!--/ Basic Pagination -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-5">

                    <div class="row" id="traitement-permission"></div>

                </div>
            </div>

        </div>
    </div>


@stop
@push('scripts')
    <script>
        $(document).ready(function(e) {
            //$("#btnNo").on("click", function() {
               // window.location = "{{ route('liste.permissions') }}";
           // });

            //GESTION DES ERREURS

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
                            title: '{{ session('status') }}'
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




            //$(document).on('click', '.btnEdit', function() {
               // var id = $(this).data('id');
                //var name = $(this).data('name');
                // var guard = $(this).data('guard');

                //$('#idPermission').val(id);
                //$('#permission').val(name);
                //$('#guard').val(guard);

               // $('#edit_permission').modal('show');
            //});



           // $('.btnAdd').on('click', function() {
           //     $('#idPermission').val('');
            //    $('#permission').val('');
           //     $('#titre_modal').html('Ajouter une Permission');
           // });


            function showErrors(container, errors) {
                $(container).find('.alert-danger').remove();
                let errorHtml = '<div class="alert alert-danger"><ul>';
                $.each(errors, function(key, value) {
                    errorHtml += '<li>' + value[0] + '</li>';
                });
                errorHtml += '</ul></div>';
                $(container).prepend(errorHtml);
            }

            // Gestion de la soumission du formulaire d'ajout
            $(document).on('submit', '#form-ajouter-permission', function(e) {
                e.preventDefault();
                let form = $(this);
                let url = form.attr('action');
                let data = form.serialize();

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: data,
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Succès', response.message, 'success');
                            location.reload();
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            showErrors('#traitement-permission', xhr.responseJSON.errors);
                        }
                    }
                });
            });

            // Gestion de la soumission du formulaire de modification
            $(document).on('submit', '#form-modifier-permission', function(e) {
                e.preventDefault();
                let form = $(this);
                let url = form.attr('action');
                let data = form.serialize();

                $.ajax({
                    url: url,
                    type: 'PUT',
                    data: data,
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Succès', response.message, 'success');
                            location.reload();
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            showErrors('#traitement-permission', xhr.responseJSON.errors);
                        }
                    }
                });
            });


            // Gestion de la soumission du formulaire de suppression
            $(document).on('submit', '#form-supprimer-permission', function(e) {
                e.preventDefault();
                let form = $(this);
                let url = form.attr('action');

                $.ajax({
                    url: url,
                    type: 'DELETE',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Succès', response.message, 'success');
                            location.reload();
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            showErrors('#traitement-permission', xhr.responseJSON.errors);
                        }
                    }
                });
            });


            //Chargement des formulaire
            $("#ajouter_permission").on("click", function(){
                $("#traitement-permission").load("{{ route('vue.creation.permission')}}");
            });

            $(".modification-permission").on("click", function(){
                let idPermission = $(this).attr('id');
                let url = "{{route( 'vue.modification.permission',[':idPermission'])}}";
                url = url.replace(':idPermission', idPermission);
                $("#traitement-permission").load(url);
            });

            $(".suppression-permission").on("click", function(){
                let idPermission = $(this).attr('id');
                let url = "{{route( 'vue.suppression.permission',[':idPermission'])}}";
                url = url.replace(':idPermission', idPermission);
                $("#traitement-permission").load(url);
            });


        });
    </script>
@endpush
