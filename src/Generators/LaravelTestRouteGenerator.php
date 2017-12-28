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

class LaravelTestRouteGenerator extends DURCGenerator {


	//so that we target the same file the whole time..
	public static function getFile(){
		$file_name = base_path()."/routes/durc_test.php";
		return($file_name);
	}

	public static function start(){
		$file = LaravelTestRouteGenerator::getFile();

		$header = "
/*
	This test route can be added to the web.php route file..
	IF you would like to quickly see and test all of the index routes that are generated by DURC
	to use:

	cat routes/durc_test.routes >> routes/web.php

*/

//This closure lists all of the index routes that DURC knows about...
Route::get('durctest', function () {
    \$route_list = [ 



";
		//note that this function ignores the squash for now...
		file_put_contents($file,$header); //empty the current file..

	}

	public static function finish(){
	
		$file = LaravelTestRouteGenerator::getFile();


		$trailer = "


	]; //end route_list

	\$html = '<html><head><title>DURC Test Page</title><body><h1>DURC Test Page</h1><h3>DO NOT USE IN PRODUCTION!!!</h3>';

	\$html .= '<ul>';

	foreach(\$route_list as \$this_relative_link){
		\$html  .= \"<li><a href='\$this_relative_link'>\$this_relative_link </a> </li> \";
	}

	\$html .= '</ul></body></html>';
	return \$html;
}); //end DURC test route closure

Route::get('/',function () {
	\$test_data = ['content' => '<h1>This is test content</h1>'];
	return view('DURC.durc_html',\$test_data);
});
";

	
		file_put_contents($file, $trailer, FILE_APPEND | LOCK_EX);

	} 


        public static function run_generator($class_name,$database,$table,$fields,$has_many = null,$belongs_to = null, $many_many = null, $many_through = null, $squash = false){


		//we just need to add a little snippet to the route file..
		$file = LaravelTestRouteGenerator::getFile();


		$snippet = "\n 			'/DURC/$class_name', //from: $database.$table ";
		$snippet = "\n 			'/DURC/$class_name/create', //from: $database.$table ";
		$snippet = "\n 			'/DURC/$class_name/1', //from: $database.$table ";
		$snippet = "\n 			'/DURC/$class_name/1/edit', //from: $database.$table ";

		file_put_contents($file, $snippet, FILE_APPEND | LOCK_EX);

		return(true);
		

	}//end generate function








}//end class