<?php
/*
        This is the place where the actual command is orchestrated.
        it ends up being our "main()"


*/
namespace CareSet\DURC;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\DB;

class LaravelRouteGenerator extends DURCGenerator {


	//so that we target the same file the whole time..
	public static function getFile(){
		$file_name = base_path()."/routes/durc.php";
		return($file_name);
	}

	public static function start(){
		$file = LaravelRouteGenerator::getFile();

		$header = "<?php
/*
This is an auto generated route file from DURC
this will be automatically overwritten by future DURC runs.

*/

";

		file_put_contents($file,$header); //empty the current file..

	}

	public static function run_generator($class_name,$database,$table,$fields){


		//we just need to add a little snippet to the route file..
		$file = LaravelRouteGenerator::getFile();


		$snippet = "
Route::resource('$class_name', '$class_name"."Controller');
";

		file_put_contents($file, $snippet, FILE_APPEND | LOCK_EX);

		return(true);
		

	}//end generate function








}//end class
