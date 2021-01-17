<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Arafat1440Camp;
use DB;

class DataController extends Controller
{
  
    public function arafat_camp(){

      set_time_limit(0);

      $gid='';
      $counter = 1;
      $data = DB::table('mashaeer_nodes')->orderBy('node_sequence', 'asc')->get();

      foreach($data as $da){ 

        $path = $da->node_path;
        $rmfrist =  str_replace('{', '', $path);
        $rm2end =  str_replace('}', '', $rmfrist);
        $exploded =  explode(',', (string)$rm2end);
 
        echo "(".$counter++.")-"; 
        echo (int)$exploded[1];
        echo "--------------";
        echo $path;
        echo "<br>";

        DB::table('mashaeer_nodes')
        ->where('node_path', $da->node_path)
        ->where('gid', $da->gid)
        ->update(['node_sequence' => (int)$exploded[1]]);

      }

        
    }
}
