<?php
/*
        This is the place where the actual command is orchestrated.
        it ends up being our "main()"


*/
namespace CareSet\DURC;


class LaravelControllerGenerator extends DURCGenerator {



	public static function run_generator($class_name,$database,$table,$fields){

		//possible created_at field names... 
		//in reverse order of priority. we pick the last one.
		$valid_created_at_fields = [
			'creation_date',
			'created_at_date',
			'createdAt', //Sequlize style
			'created_at',
			];

		//possible updated_at field names... 
		//in reverse order of priority. we pick the last one.
                $valid_updated_at_fields = [
                        'update_date',
                        'update_at_date',
                        'updated_date',
                        'updated_at_date',
			'updated_at',
			'updatedAt', //Sequlize style
                        'update_at',
                        ];


		$updated_at_code = null;	
		$created_at_code = null;	
		
		foreach($valid_created_at_fields as $this_possible_created_at){
			if(isset($fields[$this_possible_created_at])){
				//we found our created_at variable..
				$created_at_code = "const CREATED_AT = '$this_possible_created_at'"; 	
			}
		}	
		foreach($valid_updated_at_fields as $this_possible_updated_at){
			if(isset($fields[$this_possible_updated_at])){
				//we found our created_at variable..
				$updated_at_code = "const UPDATED_AT = '$this_possible_updated_at'"; 	
			}
		}	

		//we should do more logic to support dateformat laravel here...


		if(is_null($updated_at_code) || is_null($created_at_code)){ //we must have both...
			$timestamp_code = "public \$timestamps = false;";
			$updated_at_code = '//DURC NOTE: did not find updated_at and created_at fields for this model' ."\n";
			$created_at_code = '';
		}


		$parent_class_name = "DURC_$class_name";

		$parent_file_name = "$parent_class_name"."Controller.php";	
		$child_file_name = "$class_name"."Controller.php";	

		$parent_class_text = "<?php

namespace App\DURC\Controllers;

use App\\$class_name;
use Illuminate\Http\Request;
use CareSet\DURC\DURCController;

class DURC_$class_name"."Controller extends DURCController
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index(){
        
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Http\Response
     */
    public function create(){
        
    }

    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request  \$request
     * @return \Illuminate\Http\Response
     */
    public function store(Request \$request){
        
    }

    /**
     * Display the specified resource.
     * @param  \App\\$$class_name  \$$class_name
     * @return \Illuminate\Http\Response
     */
    public function show($class_name \$$class_name){
        
    }

    /**
     * Show the form for editing the specified resource.
     * @param  \App\\$class_name  \$$class_name
     * @return \Illuminate\Http\Response
     */
    public function edit($class_name \$$class_name){
        
    }

    /**
     * Update the specified resource in storage.
     * @param  \Illuminate\Http\Request  \$request
     * @param  \App\\$class_name  \$$class_name
     * @return \Illuminate\Http\Response
     */
    public function update(Request \$request, $class_name $\$class_name){
        
    }

    /**
     * Remove the specified resource from storage.
     * @param  \App\\$class_name  \$$class_name
     * @return \Illuminate\Http\Response
     */
    public function destroy($class_name \$$class_name){
        
    }
}
";

		

		$child_class_text = "<?php

namespace App\Http\Controllers;

use App\\$class_name;
use Illuminate\Http\Request;

class $class_name"."Controller extends DURC_$class_name"."Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index(){
        // enter your stuff here if you want...
	return(parent::index());
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Http\Response
     */
    public function create(){
        // enter your stuff here if you want...
	return(parent::create());
    }

    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request  \$request
     * @return \Illuminate\Http\Response
     */
    public function store(Request \$request){
        // enter your stuff here if you want...
	return(parent::store(\$request));
    }

    /**
     * Display the specified resource.
     * @param  \App\\$$class_name  \$$class_name
     * @return \Illuminate\Http\Response
     */
    public function show($class_name \$$class_name){
        // enter your stuff here if you want...
	return(parent::show(\$$class_name));
    }

    /**
     * Show the form for editing the specified resource.
     * @param  \App\\$class_name  \$$class_name
     * @return \Illuminate\Http\Response
     */
    public function edit($class_name \$$class_name){
        // enter your stuff here if you want...
	return(parent::edit(\$$class_name));
    }

    /**
     * Update the specified resource in storage.
     * @param  \Illuminate\Http\Request  \$request
     * @param  \App\\$class_name  \$$class_name
     * @return \Illuminate\Http\Response
     */
    public function update(Request \$request, $class_name \$$class_name){
        // enter your stuff here if you want...
	return(parent::update(\$request,\$$class_name));
    }

    /**
     * Remove the specified resource from storage.
     * @param  \App\\$class_name  \$$class_name
     * @return \Illuminate\Http\Response
     */
    public function destroy($class_name \$$class_name){
        // enter your stuff here if you want...
	return(parent::destroy(\$$class_name));
    }

}
";

		$app_path = base_path() . '/app/Http/Controllers/'; //we put the editable file in the main app directory where all of the others live..
		$durc_app_path = base_path() . '/app/DURC/Controllers/'; //we put the auto-genertated parent classes in a directory above that..

		if(!is_dir($durc_app_path)){
			if (!mkdir($durc_app_path, 0777, true)) {
    				die("DURC needs to create the $durc_app_path directory... but it could not.. what if it already existed? What then?");
			}
		}

		$child_file = $app_path.$child_file_name;
		$parent_file = $durc_app_path.$parent_file_name;

		if(!file_exists($child_file)){
			//we will only create this file the first time...
			//so getting here means that it does not exist, this is the first creation pass.
			file_put_contents($child_file,$child_class_text);
		}

		//but we always overwrite the parent class with the auto-generated stuff..
		file_put_contents($parent_file,$parent_class_text);

		return(true);


	}//end generate function







}//end class
