<?php

use App\Http\Controllers\UserRolePermissionController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['role:admin']], function () {
    Route::get('liste/utilisateurs', [UserRolePermissionController::class, 'listeUtilisateurs'])
        ->name('liste.utilisateurs');
    Route::get('creation/utilisateur', [UserRolePermissionController::class, 'creationUtilisateur'])
        ->name('creation.utilisateur');
    Route::post('creer/utilisateur', [UserRolePermissionController::class, 'creerUtilisateur'])
        ->name('creer.utilisateur');
    Route::get('modification/utilisateur/{idUtilisateur}', [UserRolePermissionController::class, 'modificationUtilisateur'])
        ->name('modification.utilisateur');
    Route::get('supprimer/utilisateur/{idUtilisateur}', [UserRolePermissionController::class, 'supprimerUtilisateur'])
        ->name('supprimer.utilisateur');
    Route::post('modifier/utilisateur', [UserRolePermissionController::class, 'modifierUtilisateur'])
        ->name('modifier.utilisateur');
    Route::post('reinitialiser/motdepasse/utilisateur', [UserRolePermissionController::class, 'reinitialiserMotDePasseUtilisateur'])
        ->name('reinitialiser.motdepasse.utilisateur');
    Route::post('modifier/roles/permissions/utilisateur', [UserRolePermissionController::class, 'modifierRolesPermissionsUtilisateur'])
        ->name('modifier.roles.permissions.utilisateur');

    Route::get('liste/utilisateur/data', [UserRolePermissionController::class, 'listeUtilisateurData'])
        ->name('liste.utilisateur.data');


    /************************************* */
    Route::get('liste/permissions', [UserRolePermissionController::class, 'listePermissions'])
        ->name('liste.permissions');
    Route::get('liste/permissions/data', [UserRolePermissionController::class, 'listePermissionsData'])
        ->name('liste.permissions.data');

    Route::post('ajouter/permission', [UserRolePermissionController2::class, 'ajouterPermission'])
        ->name('ajouter.permission');

    Route::post('modifier/permission', [UserRolePermissionController2::class, 'modifierPermission'])
        ->name('modifier.permission');

    Route::post('supprimer/permission/{idPermission}', [UserRolePermissionController2::class, 'supprimerPermission'])
        ->name('supprimer.permission');
    /************************************* */
    Route::get('liste/roles', [UserRolePermissionController::class, 'listeRoles'])
        ->name('liste.roles');
    Route::get('liste/roles/data', [UserRolePermissionController::class, 'listeRolesData'])
        ->name('liste.roles.data');
    Route::post('ajouter/modifier/role', [UserRolePermissionController::class, 'ajouterModifierRole'])
        ->name('ajouter.modifier.role');
    Route::get('modification/role/{idRole}', [UserRolePermissionController::class, 'modificationRole'])
        ->name('modification.role');
    Route::post('modifier/role/permission', [UserRolePermissionController::class, 'modifierRolePermission'])
        ->name('modifier.role.permission');
    Route::post('supprimer/roles/{idRole}', [UserRolePermissionController::class, 'supprimerRole'])
        ->name('supprimer.role');

    Route::get('vue/creation/role', [UserRolePermissionController::class, 'vueCreationRole'])
        ->name('vue.creation.role');

    Route::get('vue/suppression/role/{idRole}', [UserRolePermissionController::class, 'vueSuppressionRole'])
        ->name('vue.suppression.role');



    Route::get('vue/creation/permission', [UserRolePermissionController::class, 'vueCreationPermission'])
        ->name('vue.creation.permission');

    Route::get('vue/modfication/permission/{idPermission}', [UserRolePermissionController::class, 'vueModificationPermission'])
        ->name('vue.modification.permission');

    Route::get('vue/suppression/permission/{idPermission}', [UserRolePermissionController::class, 'vueSuppressionPermission'])
        ->name('vue.suppression.permission');


    Route::get('vue/creation/utilisateur', [UserRolePermissionController::class, 'vueCreationUtilisateur'])
        ->name('vue.creation.utilisateur');

    Route::get('vue/suppression/utilisateur/{idUtilisateur}', [UserRolePermissionController::class, 'vueSuppressionUtilisateur'])
        ->name('vue.suppression.utilisateur');
});
