<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;



use Illuminate\Http\Request;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\Parametre;
use App\Http\Requests\{RoleRequest, PermissionRequest}; // Ajouter par OUATTARA
use DataTables;

class UserRolePermissionController extends Controller
{

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function creerUtilisateur(Request $request): RedirectResponse
    {
        $status = 'status';
        $message = 'Utilisateur enregistré avec succès';

        try {

            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            event(new Registered($user));
        } catch (\Throwable $ex) {
            // Gérez l'erreur ici, par exemple :
            $message = $ex->getMessage();
            $status = 'error';
        }


        //Auth::login($user);

        //return redirect(route('liste.utilisateurs', absolute: false));
        return redirect()->route('liste.utilisateurs')->with($status, $message);
    }


    public function listeUtilisateurs()
    {
        $listUser = User::get();
        $listUsers = Parametre::transformeEnCollectionEtRetouneLaPagination($listUser);
        return view('user-role-permission.liste_utilisateurs', compact('listUsers'));
    }


    public function creationUtilisateur()
    {

        return view('user-role-permission.creation_utilisateur');
    }

    public function modificationUtilisateur($idUtilisateur)
    {
        $user = User::find($idUtilisateur);

        //Roles d'un utilisateur
        $roles = $user->roles;
        $mapForCB = function ($value) {// recupère un tableau puis en sortie liste les id
            return $value["id"];
        };
        $roleIds = array_map($mapForCB, $roles->select('name', 'id')->toArray()); // [1, 2, 4]
        $rolesAll = Role::paginate(5);
        foreach ($rolesAll as $roleAll) {
            if (in_array($roleAll->id, $roleIds)) {
                $roleAll->active = true;
            } else {
                $roleAll->active = false;
            }
        }
        ///Permissions direct d'un user
        $permissions = $user->getDirectPermissions();//$user->permissions
        $permissionIds = array_map($mapForCB, $permissions->select('name', 'id')->toArray());
        $permissionsAll = Permission::paginate(10);
        foreach ($permissionsAll as $permissionAll) {
            if (in_array($permissionAll->id, $permissionIds)) {
                $permissionAll->active = true;
            } else {
                $permissionAll->active = false;
            }
        }


        return view('user-role-permission.modification_utilisateur', compact(
            'user',
            'rolesAll',
            'permissionsAll'
        ));
    }

    public function supprimerUtilisateur($idUtilisateur)
    {

        //Suppression des relations entre l'utilisateur et les roles
        $users = User::find($idUtilisateur);
        $users->delete();

        return redirect()->route('liste.utilisateurs')->with('status', "Utilisateur supprimé avec succès !");

    }

    public function modifierUtilisateur(Request $request)
    {

        $utilisateur = User::find($request->id);
        $utilisateur->name = $request->name;
        $utilisateur->email = $request->email;
        $utilisateur->save();

        return redirect()->route('liste.utilisateurs')->with('status', "Utilisateur modifié avec succès !");
        // return redirect()->route('modification.utilisateur', $request->id);
    }

    public function reinitialiserMotDePasseUtilisateur(Request $request)
    {

        $utilisateur = User::find($request->id);

        $utilisateur->password = Hash::make('password');
        $utilisateur->save();
        return redirect()->route('modification.utilisateur', $request->id);
    }

    public function modifierRolesPermissionsUtilisateur(Request $request)
    {
        $utilisateur = User::find($request->id);
        $rolesId = array();

        // Je supprime tous roles existant de l'utilisateur
        $utilisateur->syncRoles([]);
        //Si au moins un role a été activé
        if ($request->customSwitchRoles) {

            // Je cree ensuite un tableau de roles
            foreach ($request->customSwitchRoles as $key => $customSwitchRole) {

                $rolesId[] = $key;
            }
            //Je cree ensuite ses nouveaux roles
            $roles = Role::whereIn('id', $rolesId)->get();
            $utilisateur->syncRoles($roles);
        }

        $permissionId = array();
        // Je supprime toutes les permissions directes  de l'utilisateur
        $utilisateur->syncPermissions([]);
        if ($request->customSwitchPermissions) {
            // Je cree ensuite ses nouvelles permissions
            foreach ($request->customSwitchPermissions as $key => $customSwitchPermission) {
                $permissionId[] = $key;
            }

            //Je cree ensuite ses nouveaux permissions
            $permissions = Permission::whereIn('id', $permissionId)->get();
            $utilisateur->syncPermissions($permissions);
        }
        // return redirect()->route('modification.utilisateur', $request->id)->with('status', "Roles et Permissions mise à jour !");
        return redirect()->route('modification.utilisateur', $request->id)->with('status', "Roles et Permissions mise à jour !");
    }

    public function listePermissions()
    {
        $listPermission = Permission::get();
        $listPermissions = Parametre::transformeEnCollectionEtRetouneLaPagination($listPermission);
        // dd($listPermissions);
        return view('user-role-permission.liste_permissions', compact('listPermissions'));
    }


    /**
     * Cette méthode class permet l'affichage des soldes et de la synthèse
     * @author OUATTARA EL HADJ YOUSSOUF <youssouf.ouattara@fpmnet.ci>
     * Date de mise à jour : 05/02/20225
     */
    public function ajouterPermission(AjouterPermissionRequest $request)
    {
        Permission::create([
            'name' => $request->permission,
        ]);
        return redirect()->route('liste.permissions')->with('status', "Permission ajoutée avec succès");
    }

    /**
     * Cette méthode class permet l'affichage des soldes et de la synthèse
     * @author OUATTARA EL HADJ YOUSSOUF <youssouf.ouattara@fpmnet.ci>
     * Date de mise à jour : 05/02/20225
     */
    public function modifierPermission(ModificationPermissionRequest $request)
    {
        $permission = Permission::find($request->idPermission);
        if (!$permission) {
            return redirect()->route('liste.permissions')->with('error', "Permission introuvable");
        }
        $permission->update(['name' => $request->permission]);

        return redirect()->route('liste.permissions')->with('status', "Permission modifiée avec succès");
    }

    public function supprimerPermission($idPermission)
    {
        $message = "Permission supprimée avec succès";
        $status = 'status';
        $permission = Permission::find($idPermission);
        // Je vérifie si au moins une permission a été attribuée au role
        // je vérifie le role a été attribué à au moins un utilisateur
        if ($permission->roles()->count() == 0 && $permission->users()->count() == 0) {

            try {
                $permission->delete();

            } catch (\Throwable $ex) {
                // Gérez l'erreur ici, par exemple :
                $message = "Une erreur s'est produite lors du traitement";
                $status = 'error';
            }
        } else {
            $message = "Il existe au moins un role ou un utilisateur qui a cette permission";
            $status = 'info';
        }



        return redirect()->route('liste.permissions')->with($status, $message);
    }

    public function listeRoles()
    {
        $listRoles = Role::paginate(5);
        // dd($listRoles);
        return view('user-role-permission.liste_roles', compact('listRoles'));
    }



    public function ajouterModifierRole(RoleRequest $request)
    {
        $message = "Role créé avec succès";
        if ($request->id) {

            $role = Role::find($request->id);
            $role->name = $request->name;
            $role->save();
            $message = "Role modifié avec succès";
            dd("role modifié");
        } else {

            $role = Role::create([
                'name' => $request->name,
            ]);

        }

        return redirect()->route('liste.roles')->with('status', $message);
    }

    public function modificationRole($idRole)
    {

        $role = Role::find($idRole);

        $mapForCB = function ($value) {// recupère un tableau puis en sortie liste les id
            return $value["id"];
        };

        ///Permissions liees à un role
        $permissions = $role->permissions;
        $permissionIds = array_map($mapForCB, $permissions->select('name', 'id')->toArray());
        $permissionsAll = Permission::paginate(25);
        foreach ($permissionsAll as $permissionAll) {
            if (in_array($permissionAll->id, $permissionIds)) {
                $permissionAll->active = true;
            } else {
                $permissionAll->active = false;
            }
        }


        return view('user-role-permission.modification_role', compact('permissionsAll', 'role'));
    }

    public function modifierRolePermissionOLD(Request $request)
    {

        $role = Role::find($request->id);
        $permissionId = array();
        $role->syncPermissions([]);
        if ($request->customSwitchPermissions) {
            foreach ($request->customSwitchPermissions as $key => $customSwitchPermission) {
                $permissionId[] = $key;
            }
            $permissions = Permission::whereIn('id', $permissionId)->get();

            $role->syncPermissions($permissions);
        }
        $message = "Permissions et roles modifiées avec succès";
        return redirect()->route('liste.roles')->with('status', $message);
    }

    /**
     * Modifie un rôle et ses permissions.
     *
     * Cette fonction permet de créer ou modifier un rôle, puis de mettre à jour ses permissions.
     * Elle prend en charge les rôles existants et s'assure que seules les permissions sélectionnées sont appliquées.
     *
     * @param Request $request La requête contenant les données du rôle et des permissions.
     * @return \Illuminate\Http\RedirectResponse Redirection avec un message de confirmation.
     * Author : OUATTARA EL HADJ YOUSSOUF <youssouf.ouattara@fpmnet.ci>
     * Mise à jour de la methode : 04/02/2025
     */
    public function modifierRolePermission(Request $request)
    {

        $role = Role::find($request->id);
        $role->name = $request->name;
        $permissionId = array();
        $role->syncPermissions([]);
        if ($request->customSwitchPermissions) {
            foreach ($request->customSwitchPermissions as $key => $customSwitchPermission) {
                $permissionId[] = $key;
            }
            $permissions = Permission::whereIn('id', $permissionId)->get();

            $role->syncPermissions($permissions);
        }
        $role->save();
        $message = "Permissions et roles modifiées avec succès";
        return redirect()->route('liste.roles')->with('status', $message);
    }

    public function supprimerRoleOLD($idRole)
    {
        $message = "Role supprimé avec succès";
        $status = 'status';
        $role = Role::find($idRole);
        if ($role->permissions->count() == 0 && $role->users()->count() == 0) {
            try {
                $role->delete();

            } catch (\Throwable $ex) {
                $message = "Une erreur s'est produite lors du traitement";
                $status = 'error';
            }
        } else {
            $message = "Il existe au moins une permission ou un utilisateur qui a ce role";
            $status = 'info';
        }


        return redirect()->route('liste.roles')->with($status, $message);
    }

    public function vueCreationPermission(){
        return view('user-role-permission.formulaire-ajout-permission');
    }

    public function vueModificationPermission($idPermission){
        $permission = Permission::find($idPermission);
        if(!$permission){
            return back()->with('error', "Permission introuvable.");//Trouver le moyen de gérer cette erreur
        }
        return view('user-role-permission.formulaire-modification-permission', compact('permission'));
    }

    public function vueSuppressionPermission($idPermission){
        $permission = Permission::find($idPermission);
        if(!$permission){
            return back()->with('error', "Permission introuvable.");//Trouver le moyen de gérer cette erreur
        }
        return view('user-role-permission.formulaire-suppression-permission', compact('permission'));
    }


    public function vueCreationRole(){
        return view('user-role-permission.formulaire-ajout-role');
    }

    public function vueSuppressionRole($idRole){
        $role = Role::find($idRole);
        if(!$role){
            return back()->with('error', "Role introuvable.");//Trouver le moyen de gérer cette erreur
        }
        return view('user-role-permission.formulaire-suppression-role', compact('role'));
    }

    /**
     * Suppression de rôle.
     *
     *
     * @param $idRole La requête contenant l'id du rôle.
     * @return \Illuminate\Http\RedirectResponse Redirection avec un message de confirmation.
     * Author : OUATTARA EL HADJ YOUSSOUF <youssouf.ouattara@fpmnet.ci>
     * Mise à jour de la methode : 04/02/2025
     */
    public function supprimerRole($idRole)
    {
        $role = Role::find($idRole);

        if (!$role) {
            return back()->with('error', "Rôle introuvable.");
        }

        if ($role->permissions()->exists() || $role->users()->exists()) {
            return back()->with('info', "Ce rôle est attribué à au moins un utilisateur ou possède des permissions.");
        }

        return $role->delete()
            ? back()->with('status', "Rôle supprimé avec succès.")
            : back()->with('error', "Une erreur s'est produite lors de la suppression.");
    }


    public function vueCreationUtilisateur(){
        return view('user-role-permission.formulaire-ajout-utilisateur');
    }

    public function vueSuppressionUtilisateur($idUtilisateur){
        $utilisateur = User::find($idUtilisateur);
        if(!$utilisateur){
            return back()->with('error', "Utilisateur introuvable.");//Trouver le moyen de gérer cette erreur
        }
        return view('user-role-permission.formulaire-suppression-utilisateur', compact('utilisateur'));
    }



}
