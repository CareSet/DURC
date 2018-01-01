<?php
/*
        This is the place where the actual command is orchestrated.
        it ends up being our "main()"


*/
namespace CareSet\DURC\Generators;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\DB;
use CareSet\DURC\DURC;
class LaravelRouteGenerator extends \CareSet\DURC\DURCGenerator {

        public static function finish(){

        }


	//so that we target the same file the whole time..
	public static function getFile(){
		$file_name = base_path()."/routes/web.durc.php";
		return($file_name);
	}


	public static function start(){
		$file = LaravelRouteGenerator::getFile();

		$gen_string = DURC::get_gen_string();
		$header = "<?php
/*
This is an auto generated route file from DURC
this will be automatically overwritten by future DURC runs.

$gen_string

*/

";
		//note that this function ignores the squash for now...
		file_put_contents($file,$header); //empty the current file..

	}

        public static function run_generator($class_name,$database,$table,$fields,$has_many = null,$belongs_to = null, $many_many = null, $many_through = null, $squash = false){


		//we just need to add a little snippet to the route file..
		$file = LaravelRouteGenerator::getFile();


		$snippet = " 
//DURC->	$database.$table
Route::resource('/DURC/$class_name', '$class_name"."Controller');
Route::get('/DURCjson/$class_name/{"."$class_name"."_id}', '$class_name"."Controller@jsonone');
Route::get('/DURCjson/$class_name/', '$class_name"."Controller@jsonall');
Route::get('/DURCsearchjson/$class_name/', '$class_name"."Controller@search');
";

		file_put_contents($file, $snippet, FILE_APPEND | LOCK_EX);

		return(true);
		

	}//end generate function








}//end class
