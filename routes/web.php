<?php

use App\Models\Photo;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Intervention\Image\Facades\Image;

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
    // $name = basename($request->photo->store('public'));

    // Generate filename using original file extention type
    $name = Str::random(32) . "." . $request->photo->getClientOriginalExtension();

    $watermark = Image::make(storage_path('app/public/watermark.png'))
        ->widen(400)
        ->opacity(50);

    Image::make($request->photo)
        // Resize image, but keep aspect ration
        ->resize(1000, 1000, function($constraint){
            $constraint->aspectRatio();
        })
        // Insert watermark
        ->insert($watermark, 'bottom-right', 20, 20 )
        ->save(storage_path('app/public/' . $name));

    Photo::create([
        'name' => $name
    ]);

    return redirect()->back();
});
