<?php

use App\Http\Controllers\RedirectController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});

// Short link redirect. Kept last so it does not shadow other routes (e.g. /admin).
Route::get('/{code}', RedirectController::class)
    ->where('code', '[A-Za-z0-9]+');
