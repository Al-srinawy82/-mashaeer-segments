<?php

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


    $data = DB::table('mashaeer_nodes')->where('gid',29)->orderBy('node_sequence', 'asc')->get();

    $streets = DB::table('mashaeer_street')->Select('gid', 'name_ar')->orderBy('gid', 'asc')->get()->unique('name_ar');
     return view('welcome',compact('data', 'streets'));
});

Route::get('seqment/{id}', function ($gid) {

 
    
    $nodes = DB::table('mashaeer_nodes')->where('gid', $gid)->orderBy('node_sequence', 'asc')->get();
     return compact('nodes');
});


// Route::get('node/{seqment_id}', function ($gid) {

 
    
//     $nodes = DB::table('mashaeer_nodes')->where('gid', $gid)->orderBy('node_sequence', 'asc')->get();
//      return compact('nodes');
// });



Route::get('street/{id}', function ($gid) {

    // $streets = DB::table('mashaeer_nodes')->where('gid', $gid)->orderBy('node_sequence', 'asc')->get();
    $street_name = DB::table('mashaeer_street')->Select('gid', 'name_ar')->where('gid', $gid)->orderBy('gid', 'asc')->first();
    $streets_full = DB::table('mashaeer_street')->Select('gid', 'name_ar')->where('name_ar', $street_name->name_ar)->orderBy('gid', 'asc')->get();
    $streets_full_ids = $streets_full->pluck('gid');
    $streets = DB::table('mashaeer_nodes')->whereIn('gid', $streets_full_ids)->orderBy('gid', 'asc')->orderBy('node_sequence', 'asc')->get();
    $segments = DB::table('mashaeer_nodes')->whereIn('gid', $streets_full_ids)->orderBy('gid', 'asc')->orderBy('node_sequence', 'asc')->get()->unique('gid');

     return compact('streets', 'segments');
});


Route::get('node/{gid}/{fid}/{sid}', function ($gid, $fid, $sid) {

    $f_node = DB::table('mashaeer_nodes')->where('node_id', $fid)->orderBy('node_sequence', 'asc')->first();
    $s_node = DB::table('mashaeer_nodes')->where('node_id', $sid)->orderBy('node_sequence', 'asc')->first();
    $all_nodes = DB::table('mashaeer_nodes')->where('gid', $f_node->gid)->whereBetween('node_sequence', [$f_node->node_sequence, $s_node->node_sequence])->orderBy('node_sequence', 'asc')->get();
    return compact('all_nodes');
});


Route::get('segment-nodes/{gid}', function ($gid) {

    $nodes = DB::table('mashaeer_nodes')->where('gid', $gid)->orderBy('node_sequence', 'asc')->get();
     return compact('nodes');
});





Route::get('arafat-camp', 'App\Http\Controllers\DataController@arafat_camp');