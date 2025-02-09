<?php 

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/admin', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

});


foreach(File::allFiles(__DIR__.'/web') as $route_file){
    require $route_file->getPathname();
}