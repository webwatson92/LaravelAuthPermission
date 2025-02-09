@extends('layouts.base')
@push('headTitle') CREATION D'UN UTILISATEUR @endpush
@push('styles')
<style>
    .hidden {
        display: none;
    }
</style>
@endpush

@section('content')

    <div class="container" style="padding-top:1em">
        <div class="row">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fa fa-user-plus fa-2x"></i> GESTION DES UTILISATEURS <span>CREATION D'UN UTILISATEUR</h3>
                    <span class="label">VOUS ETES ICI:</span>
                    <ol class="breadcrumb">
                        <li class="active">Crétion d'un utilisateur</li>
                    </ol>
                </div>

                <div class="contentpanel">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <form method="POST" action="{{ route('creer.utilisateur') }}">
                                    @csrf
                                    <div class="panel-heading">
                                        <h4 class="panel-title">INTERFACE DE CREATION DE L'UTILISATEUR</h4>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row"  style="margin-bottom:1em">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="control-label">NOM PRENOM</label>
                                                    <input id="name" class="form-control" type="text" name="name" value="{{ old('name') }}" autofocus autocomplete="name" />
                                                    @error('name')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div><!-- col-sm-6 -->
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="control-label">E-MAIL</label>
                                                    <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}"  autocomplete="username" />
                                                    @error('email')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div><!-- col-sm-6 -->
                                        </div><!-- row -->

                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="control-label">MOT DE PASSE</label>
                                                    <input id="password" class="form-control" type="password" name="password" autocomplete="new-password" />
                                                    @error('password')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div><!-- col-sm-6 -->
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="control-label">CONFIRMATION DE MOT DE PASSE</label>
                                                    <input id="password_confirmation" class="form-control" type="password" name="password_confirmation"  autocomplete="new-password" />
                                                    @error('password_confirmation')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div><!-- col-sm-6 -->
                                        </div><!-- row -->

                                    </div>
                                    <!-- panel-body -->
                                    <div class="panel-footer" style="padding-top:2em; padding-bottom:2em">
                                        <a href="{{ route('liste.utilisateurs') }}" type="button"  class="btn btn-danger">LISTE UTILISATEUR</a>
                                        <button type="submit" class="btn btn-primary pull-right">ENREGISTRER</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


@stop
@push("scripts")
<script>

    $(document).ready(function() {
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
        });
    });
</script>
@endpush

