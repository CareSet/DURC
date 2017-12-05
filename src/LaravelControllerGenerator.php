<?php
/*
        This is the place where the actual command is orchestrated.
        it ends up being our "main()"


*/
namespace CareSet\DURC;

use Illuminate\Support\Facades\Artisan;

class LaravelControllerGenerator {



	public static function run_generator($class_name,$database,$table,$fields){

		//artisan already understands how to make a restful controller 
		//out of a model...

		//trying to emulat the following command...
		//php artisan make:controller PhotoController --resource --model=Photo
    		$result = Artisan::call('make:controller', [
        			'name' => $class_name.'Controller', 
				'--resource' => 'true', 
				'--model' => $class_name
    		]);
	
		return($result);

	}//end generate function







}//end class
