@extends('layouts.base')
@push('headTitle') Interface de Modification d'un utilisateur @endpush
@push('styles')

@endpush

@section('content')

    <div class="container" style="padding-top:1em">
        <div class="row">
            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fa fa-user-plus fa-2x"></i> Mise à jour un utilisateur</h3>
                    </div>
                    <form method="POST" action="{{ route('modifier.utilisateur') }}">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <input type="hidden" name="id" id="id" value="{{ $user->id }}">
                                    <div class="form-group">
                                        <label>Nom & Prénoms</label>
                                        <input id="name" class="form-control" type="text" name="name" value="{{ $user->name }}" required autofocus autocomplete="name" placeholder="Enter le nom & prénoms"/>
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>E-mail</label>
                                        <div class="input-group">
                                            <input id="email" class="form-control" type="email" name="email" value="{{ $user->email }}" required autocomplete="username" placeholder="Enter l'email'"/>
                                            {{-- <x-input-error :messages="$errors->get('email')" class="mt-2" /> --}}
                                        </div>
                                        @error('email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="card-footer">
                            <a style="margin-right:20px" href="{{ route('liste.utilisateurs') }}" type="button" style="float: right;" class="btn btn-danger">Liste des Utilisateurs</a>
                            <button type="submit" class="btn btn-primary float-right">Enregistrer les modifications</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fa fa-key fa-2x"></i> Authentification</h3>
                            </div>
                            <form method="POST" action="{{ route('reinitialiser.motdepasse.utilisateur') }}">
                                @csrf
                                <input type="hidden" name="id" id="id" value="{{ $user->id }}">
                                <div class="card-body">
                                    <ul>
                                        <li>
                                            <button type="submit" class="btn btn-link">Réinitialiser le mot de passe</button>
                                            {{-- <a href="{{ route('reinitialiser.motdepasse.utilisateur') }}" class="btn btn-link" wire:click.prevent="confirmPwdReset">Réinitialiser le mot de passe</a> --}}
                                            <span>(par défaut: "password")</span>
                                        </li>
                                    </ul>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mt-4">
                        <div class="card card-primary">
                            <form method="POST" action="{{ route('modifier.roles.permissions.utilisateur') }}">
                                @csrf
                                <input type="hidden" name="id" id="id" value="{{ $user->id }}">
                                <div class="card-header d-flex align-items-center">
                                    <h3 class="card-title flex-grow-1"><i class="fas fa-fingerprint fa-2x "></i> Rôles & Permissions</h3>
                                    <button type="submit" class="btn bg-gradient-success" ><i class="fas fa-check"></i> Appliquer les modifications</button>
                                </div>

                                <div class="card-body">
                                    <div id="accordion">
                                        @foreach($rolesAll as $role)

                                        <div class="card collapsed-card">

                                            <div class="card-header  d-flex justify-content-between">
                                                <h4 class="card-title  flex-grow-1">
                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                                <a data-parent="#accordion" href="#" aria-expanded="true">
                                                    {{ $role->name }}
                                                </a>
                                            </h4>
                                            <div
                                                class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">

                                                <input type="checkbox" class="custom-control-input custom-switch-roles"   {{
                                                    $role->active ? "checked" :"unchecked"}}
                                                    name="customSwitchRoles[{{$role->id}}]" id="customSwitchRoles{{$role->id}}">
                                                <label class="custom-control-label"  for="customSwitchRoles{{$role->id}}">{{ $role->active ? "Activé" :"Désactivé"}}</label>
                                            </div>
                                                <!-- /.card-tools -->
                                            </div>
                                            <!-- /.card-header -->
                                            <div class="card-body">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Permissions du Role</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($role->permissions as $permission )
                                                            <tr>

                                                                <td>{{ $permission->name }}</td>

                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            <!-- /.card-body -->
                                        </div>


                                        @endforeach
                                        {{-- @json($rolePermissions["roles"]) --}}
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Permissions Spécifiques</th>
                                                    <th></th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($permissionsAll as $permission )
                                                    <tr>
                                                        <td>{{ $permission->name }}</td>
                                                        <td>
                                                            <div class="custom-control custom-switch  custom-switch-off-danger custom-switch-on-success">

                                                                <input type="checkbox" class="custom-control-input custom-switch-permissions"  {{$permission->active ? "checked":"unchecked"}} id="customSwitchPermissions{{ $permission->id }}" name="customSwitchPermissions[{{ $permission->id }}]">
                                                                <label class="custom-control-label" for="customSwitchPermissions{{ $permission->id }}">{{$permission->active ? "Activé":"Désactivé"}}</label>
                                                            </div>
                                                        </td>

                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>



                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

@stop
@push("scripts")

<script>

    $(document).ready(function(){
        $('.custom-switch-roles').on('click',function(){

            var label = $(this).siblings('label'); // Sélectionne le label associé à ce switch

            if ($(this).is(':checked')) {
                label.text('Activé');
            } else {
                label.text('Désactivé');
            }
        });

        $('.custom-switch-permissions').on('click',function(){

            var label = $(this).siblings('label'); // Sélectionne le label associé à ce switch

            if ($(this).is(':checked')) {
                label.text('Activé');
            } else {
                label.text('Désactivé');
            }
        });

        $(function(){
            $(".select2").select2();

            $(".select2bs4").select2({
                theme: "bootstrap4",
            });

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
                @if (session('danger'))
                    Toast.fire({
                        icon: 'danger',
                        title: '{{ session('status') }}'
                    })
                @endif
            });

            $('.swalDefaultSuccess').ready(function() {
                @if (session('info'))
                    Toast.fire({
                        icon: 'info',
                        title: '{{ session('status') }}'
                    })
                @endif
            });
        });

    });

</script>
@endpush

