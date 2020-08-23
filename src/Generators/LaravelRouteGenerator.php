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


        //Run only once at the end of generation
        public static function finish(
							$db_config,
                                                        $squash,
                                                        $URLroot){
                //does nothing need to comply with abstract class
        }        



	//so that we target the same file the whole time..
	public static function getFile(){
		$file_name = base_path()."/routes/web.durc.php";
		return($file_name);
	}


        public static function start(
							$db_config,
                                                        $squash,
                                                        $URLroot){
		$file = LaravelRouteGenerator::getFile();

		$gen_string = DURC::get_gen_string();
		$header = "<?php
/*
This is an auto generated route file from DURC
this will be automatically overwritten by future DURC runs.


*/

";
		//note that this function ignores the squash for now...
		file_put_contents($file,$header); //empty the current file..

	}//end start

/*
        This accepts the data for each table in the database that DURC is aware of..
        and generates a Laravel Route
*/
        public static function run_generator($data_for_gen){

                $data_for_gen = $this->_check_arguments($data_for_gen); //ensure reasonable defaults...
                extract($data_for_gen); //everything goes into the local scope... this includes all settings from the config json file...

            $soft_delete_route = null;
            if ( self::get_possible_deleted_at( $fields ) !== false ) {
                $soft_delete_route = "Route::get(\"$URLroot"."$class_name/restore/{id}\", '$class_name"."Controller@restore');";
            }

		//we just need to add a little snippet to the route file..
		$file = LaravelRouteGenerator::getFile();


		$snippet = " 
//DURC->	$database.$table
Route::resource(\"$URLroot$class_name\", '$class_name"."Controller');
Route::get(\"$URLroot"."json/$class_name/{"."$class_name"."_id}\", '$class_name"."Controller@jsonone');
Route::get(\"$URLroot"."json/$class_name/\", '$class_name"."Controller@jsonall');
Route::get(\"$URLroot"."searchjson/$class_name/\", '$class_name"."Controller@search');
$soft_delete_route
";

		file_put_contents($file, $snippet, FILE_APPEND | LOCK_EX);

		return(true);
		

	}//end generate function




}//end class
