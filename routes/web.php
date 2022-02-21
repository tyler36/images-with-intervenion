<?php

use App\Models\Photo;
use Illuminate\Http\Request;
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

Route::get('/', function() {
    return view('welcome', [
        'photos' => Photo::pluck('name')->toArray()
    ]);
});

Route::post('/save', function(Request $request){
    $name = basename($request->photo->store('public'));

    Photo::create([
        'name' => $name
    ]);

    return redirect()->back();
});
