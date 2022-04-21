<?php

use App\Helpers\TypeChecker;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/debug', function () {
    $value = [
        'name' => 'Rokas',
        'surname' => 'Paulikas',
    ];

    $value = json_encode($value);

    $result = TypeChecker::isJSON($value);

    dd($result);
});
